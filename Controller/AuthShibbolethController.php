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

/**
 * AuthShibboleth Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller
 */
class AuthShibbolethController extends AuthShibbolethAppController {

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

		// 外部認証のID関連付けログイン、仮登録画面、関連付け画面への遷移先別けはexloginで行う
		$redirect = $baseUrl . 'auth_shibboleth/auth_shibboleth/exlogin';
		$this->Session->delete('AuthShibbolethTmp.wayfAutoLogin');

		// IdPのユーザ情報 セット
		$this->AuthShibboleth->setIdpUserData();

		// IdPによる個人識別番号 or persistentId の存在チェック
		if ($this->AuthShibboleth->isIdpUserid()) {
			// リダイレクト
			return $this->redirect($redirect);
		}

		// 必要な属性情報が得られない時は、DSの自動ログインをOFFにする
		$this->Session->write('AuthShibbolethTmp.wayfAutoLogin', false);

		$returnUrl = $baseUrl . 'auth/login';
		$redirect = $baseUrl . 'Shibboleth.sso/Logout?return=' . $returnUrl;

		// メッセージ表示
		$this->NetCommons->setFlashNotification(
			__d('auth', 'Auth.exlogin.failure'),
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
 * 「他サービスを用いたログイン処理」後の処理・画面遷移先
 * (外部認証系プラグインのコントローラでオーバーライトして実装)
 *
 * @return string リダイレクト先
 **/
	protected function _exLoginRedirect() {
		return $this->AuthShibboleth->exLoginRedirect();
	}

/**
 * ID関連付け
 * (オーバーライト)
 *
 * @return void
 **/
	public function mapping() {
		// IdPによる個人識別番号 or persistentId の存在チェック
		if (! $this->AuthShibboleth->isIdpUserid()) {
			return $this->throwBadRequest();
		}
		parent::mapping();
	}

/**
 * IdPによる個人識別番号 取得
 * (オーバーライト)
 *
 * @return string idpUserid or persistentId
 **/
	protected function _getIdpUserid() {
		return $this->AuthShibboleth->getIdpUserid();
	}

/**
 * _exLogin()でログイン後の追加処理
 * (オーバーライト)
 *
 * @return void
 **/
	protected function _exLoggedin() {
		// ログイン関連付け
		$this->AuthShibboleth->saveUserMapping($this->Auth->user('id'));

		// shibbolethのセッション初期化
		$this->Session->delete('AuthShibboleth');
	}
}
