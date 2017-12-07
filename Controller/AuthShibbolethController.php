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
		'AuthShibboleth.IdpUserProfile',
		'Users.User',
	);

/**
 * Other components
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
		$this->Auth->allow('secure', 'ds', 'mapping');
	}

/**
 * 学認Embedded DS表示（URLで直接開く）
 * https://example.com/auth_shibboleth/auth_shibboleth/ds
 *
 * @return void
 **/
	public function ds() {
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

		// IdPのユーザ情報 セット
		$this->AuthShibboleth->setIdpUserData();

		// IdPによる個人識別番号 or persistentId の存在チェック
		if ($this->AuthShibboleth->isIdpUserid()) {
			// リダイレクト
			return $this->redirect($redirect);
		}

		// [まだ] DS login_wayf_not_auto_loginのON、OFF処理
		//$session->setParameter("login_wayf_not_auto_login", _ON);
		//$this->Session->write('AuthShibboleth.loginWayfNotAutoLogin', '1');

		$returnUrl = $baseUrl . 'auth/login';
		$redirect = $baseUrl . 'Shibboleth.sso/Logout?return=' . $returnUrl;

		// NC2メッセージ　login_error_externalId = "選択した所属機関認証システムから必要な属性情報が得られないため、<br />ログインすることができません。<br /><div style='margin-top:15px;'>選択した所属機関認証システムの管理者にお問い合わせください。</div>"
		// メッセージ表示
		$this->NetCommons->setFlashNotification(
			__d('auth_shibboleth', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'),
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
 * @return CakeResponse
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

		//$this->__setNc2Authenticate();

		if ($this->Auth->login($user)) {
			// ユーザ紐づけ
			$this->AuthShibboleth->saveUserMapping($this->Auth->user('id'));

			// user情報更新
			$User->updateLoginTime($this->Auth->user('id'));
			Current::write('User', $this->Auth->user());
			if ($this->Auth->user('language') !== UserAttributeChoice::LANGUAGE_KEY_AUTO) {
				$this->Session->write('Config.language', $this->Auth->user('language'));
			}

			// login_logging_announce = "すべてのブラウザを閉じるまで「ログイン認証済み」となります。<br /><br style='line-height:16px;' />他者が使用する可能性のあるパソコンで接続した場合は、<br />席を立つ前に<span style='text-decoration:underline;'>必ずすべてのブラウザを閉じてください。</span>"
			// メッセージ表示
			$this->NetCommons->setFlashNotification(
				__d('auth_shibboleth', 'すべてのブラウザを閉じるまで「ログイン認証済み」となります。他者が使用する可能性のあるパソコンで接続した場合は、席を立つ前に必ずすべてのブラウザを閉じてください。'),
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
