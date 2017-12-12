<?php
/**
 * AuthShibboleth Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethAppController', 'AuthShibboleth.Controller');
App::uses('AuthShibbolethComponent', 'AuthShibboleth.Controller/Component');
App::uses('UserAttributeChoice', 'UserAttributes.Model');

/**
 * AuthShibboleth Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller
 */
class AuthShibbolethController extends AuthShibbolethAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'AuthShibboleth.IdpUser',
		'Users.User',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'AuthShibboleth.AuthShibboleth',
	);

/**
 * beforeFilter
 *
 * @return void
 **/
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('secure', 'discovery', 'mapping');
	}

/**
 * 学認Embedded DS表示（URLで直接開く）
 * https://example.com/auth_shibboleth/auth_shibboleth/discovery
 *
 * @return void
 **/
	public function discovery() {
		$this->view = 'AuthShibboleth.AuthShibboleth/ds';
	}

/**
 * 初期処理
 *
 * @return CakeResponse
 **/
	public function secure() {
		// ベースURL（認証後のURLを開いた後のリダイレクトに利用します）
		$baseUrl = SiteSettingUtil::read('AuthShibboleth.base_url');
		$redirect = $baseUrl . 'auth_shibboleth/auth_shibboleth/mapping';
		$this->Session->delete('AuthShibboleth.wayfAutoLogin');

		// IdPのユーザ情報 セット
		$this->AuthShibboleth->setIdpUserData();

		// IdPによる個人識別番号 or persistentId の存在チェック
		if ($this->AuthShibboleth->isIdpUserid()) {
			// リダイレクト
			return $this->redirect($redirect);
		}

		// 必要な属性情報が得られない時は、DSの自動ログインをOFFにする
		$this->Session->write('AuthShibboleth.wayfAutoLogin', false);

		$returnUrl = $baseUrl . 'auth/login';
		$redirect = $baseUrl . 'Shibboleth.sso/Logout?return=' . $returnUrl;

		// メッセージ表示
		$this->NetCommons->setFlashNotification(
			__d('auth_shibboleth', 'AuthShibboleth.login.failure'),
			array(
				'class' => 'danger',
				'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
			),
			400
		);

		// リダイレクト
		return $this->redirect($redirect);
	}

/**
 * ユーザ紐づけ
 *
 * @return void
 **/
	public function mapping() {
		//メールを送れるかどうか
		$this->set('isMailSend', $this->ForgotPass->isMailSendCommon('auth', 'auth'));

		if ($this->request->is('post')) {
			// ログイン
			$this->_login();

		} else {
			// --- ユーザ紐づけ済みならログイン
			// IdPによる個人識別番号 で取得
			$idpUser = $this->IdpUser->findByIdpUserid($this->AuthShibboleth->getIdpUserid());

			if ($idpUser) {
				// ユーザ検索
				$user = $this->User->findByIdAndStatus($idpUser['IdpUser']['user_id'],
					UserAttributeChoice::STATUS_CODE_ACTIVE);
				if ($user) {
					// $this->Auth->_user と同じ配列構成にする
					$user = Hash::merge($user, $user['User']);
					$user = Hash::remove($user, 'User');
					// ログイン
					$this->_login($user);
				}
			}

		}
	}

/**
 * ユーザ紐づけ画面のログイン
 *
 * @param array $user ユーザ情報
 * @throws BadRequestException
 * @return CakeResponse
 * @see AuthController::login() よりコピー
 **/
	protected function _login($user = null) {
		//Auth->login()を実行すると、$this->UserがUsers.UserからModelAppに置き換わってしまい、
		//エラーになるため、変数に保持しておく。
		$User = $this->User;

		$this->Auth->authenticate['all']['scope'] = array(
			'User.status' => UserAttributeChoice::STATUS_CODE_ACTIVE
		);

		if ($this->Auth->login($user)) {
			// ユーザ紐づけ
			$this->AuthShibboleth->saveUserMapping($this->Auth->user('id'));

			// user情報更新
			$User->updateLoginTime($this->Auth->user('id'));
			Current::write('User', $this->Auth->user());
			if ($this->Auth->user('language') !== UserAttributeChoice::LANGUAGE_KEY_AUTO) {
				$this->Session->write('Config.language', $this->Auth->user('language'));
			}

			// メッセージ表示
			$this->NetCommons->setFlashNotification(
				__d('auth_shibboleth', 'AuthShibboleth.login.success'),
				array(
					'class' => 'success',
					'interval' => 4000,
				)
			);

			// リダイレクト
			$this->Auth->loginRedirect = $this->_getDefaultStartPage();
			return $this->redirect($this->Auth->redirectUrl());
		}

		$this->NetCommons->setFlashNotification(
			__d('auth', 'Invalid username or password, try again'),
			array(
				'class' => 'danger',
				'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
			),
			400
		);
	}
}
