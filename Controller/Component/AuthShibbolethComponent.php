<?php
/**
 * AuthShibboleth Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * AuthShibboleth Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller\Component
 * @property SessionComponent $Session
 */
class AuthShibbolethComponent extends Component {
/**
 * @var string IdPによる個人識別番号。eppn=フェデレーション内のエンティティを一意に定めます
 * @see https://meatwiki.nii.ac.jp/confluence/pages/viewpage.action?pageId=12158166 属性リスト
 */
	const IDP_USERID = 'eppn';

/**
 * @var string フェデレーション内のエンティティを匿名で表す
 */
	const PERSISTENT_ID = 'persistent-id';

/**
 * @var string AuthType shibbolethのロケーション
 */
	const SHIBBOLETH_LOCATION = '/secure';

/**
 * @var string IdPによる個人識別番号
 */
	public $idpUserid = null;

/**
 * @var string フェデレーション内のエンティティを匿名で表す
 */
	public $persistentId = null;

/**
 * @var array プロフィール情報。shibbolethログインで取得できる情報の配列
 */
	public $profile = array();

/**
 * Other components
 *
 * @var array
 */
	public $components = array(
		'Session',
	);

/**
 * @var Controller コントローラ
 */
	protected $_controller = null;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		// どのファンクションでも $controller にアクセスできるようにクラス内変数に保持する
		$this->_controller = $controller;
	}

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::startup
 */
	public function startup(Controller $controller) {
		$controller->IdpUser = ClassRegistry::init('AuthShibboleth.IdpUser');
		$controller->IdpUserProfile = ClassRegistry::init('AuthShibboleth.IdpUserProfile');
	}

/**
 * IdPのユーザ情報 セット
 *
 * @return void
 */
	public function setIdpUserData() {
		// 登録途中でキャンセルやブラウザ閉じた後、再登録した場合を考え、セッション初期化
		$this->Session->delete('AuthShibboleth');

		// Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
		$prefix = '';
		for ($i = 0; $i < 5; $i++) {
			$prefix = str_repeat("REDIRECT_", $i);
			$this->__setSession($prefix, AuthShibbolethComponent::IDP_USERID);
			$this->__setSession($prefix, AuthShibbolethComponent::PERSISTENT_ID);

			//$idpUserid = $this->getProfileByItemKey(AuthShibbolethComponent::IDP_USERID);
			$idpUserid = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::IDP_USERID);
			if ($idpUserid) {
				break;
			}
		}
		//$session->removeParameter("login_wayf_not_auto_login");
		//$this->Session->delete('AuthShibboleth.loginWayfNotAutoLogin');
		//return true;

		if (! $this->isIdpUserid()) {
			return;
		}

		$this->__setSession($prefix, 'mail');			//メールアドレス
		$this->__setSession($prefix, 'jaDisplayName');	//日本語氏名（表示名）
		$this->__setSession($prefix, 'jasn');			//氏名（姓）の日本語
		$this->__setSession($prefix, 'jaGivenName');	//氏名（名）の日本語
		$this->__setSession($prefix, 'jao');			//所属(日本語)
		$this->__setSession($prefix, 'jaou');			//部署(日本語)
		$this->__setSession($prefix, 'displayName');	//英字氏名（表示名）
		$this->__setSession($prefix, 'sn');				//氏名(姓)の英字
		$this->__setSession($prefix, 'givenName');		//氏名(名)の英字
		$this->__setSession($prefix, 'o');				//所属(英語)
		$this->__setSession($prefix, 'ou');				//部署(英語)
	}

/**
 * セッション セット
 *
 * @param string $prefix Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
 * @param string $itemKey Sessionの配列キーの一部
 * @return void
 */
	private function __setSession($prefix, $itemKey) {
		$item = Hash::get($_SERVER, $prefix . $itemKey);
		if (! is_null($item)) {
			$this->Session->write('AuthShibboleth.' . $itemKey, $item);
		}
	}

/**
 * IdPによる個人識別番号 or persistentId の存在チェック
 *
 * @return bool true:存在する、false:存在しない
 */
	public function isIdpUserid() {
		$idpUserid = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::IDP_USERID);
		$persistentId = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::PERSISTENT_ID);
		if (is_null($idpUserid) && is_null($persistentId)) {
			return false;
		}
		return true;
	}

/**
 * IdPによる個人識別番号 or persistentId の取得
 *
 * @return string idpUserid or persistentId
 */
	public function getIdpUserid() {
		$idpUserid = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::IDP_USERID);
		$persistentId = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::PERSISTENT_ID);
		if (is_null($idpUserid) && is_null($persistentId)) {
			// idpUserid=空、persistentId=空
			return null;
		} elseif (is_null($idpUserid) && ! is_null($persistentId)) {
			// idpUserid=空、persistentId=あり
			return $persistentId;
		}
		// idpUserid=あり、persistentId=あり or なし
		return $idpUserid;
	}

/**
 * ePTID(eduPersonTargetedID)かどうか
 *
 * @return int null：Shibboleth以外, 0：ePPN(eduPersonPrincipalName), 1：ePTID(eduPersonTargetedID)
 */
	public function isShibEptid() {
		$idpUserid = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::IDP_USERID);
		$persistentId = $this->Session->read('AuthShibboleth.' . AuthShibbolethComponent::PERSISTENT_ID);
		if (is_null($idpUserid) && is_null($persistentId)) {
			// idpUserid=空、persistentId=空
			return null;
		} elseif (is_null($idpUserid) && ! is_null($persistentId)) {
			// idpUserid=空、persistentId=あり
			return '1';
		}
		// idpUserid=あり、persistentId=あり or なし
		return '0';
	}

/**
 * ユーザ紐づけ
 *
 * @param int $userId ユーザID
 * @return void
 * @throws BadRequestException
 */
	public function saveUserMapping($userId) {
		// IdPによる個人識別番号 で取得
		$idpUser = $this->_controller->IdpUser->findByIdpUserid($this->getIdpUserid());

		if (! $idpUser) {
			// 外部ID連携 保存
			$data = array(
				//'user_id' => $this->_controller->Auth->user('id'),
				'user_id' => $userId,
				'idp_userid' => $this->getIdpUserid(),		// IdPによる個人識別番号
				'is_shib_eptid' => $this->isShibEptid(),	// ePTID(eduPersonTargetedID)かどうか
				'status' => '2',			// 2:有効
				// nc3版はscope消した（shibboleth時は空なので）
				//'scope' => '',				// shibboleth時は空
			);
			$idpUser = $this->_controller->IdpUser->saveIdpUser($data);
			if (! $idpUser) {
				throw new BadRequestException(print_r($this->_controller->IdpUser->validationErrors, true));
			}
		}

		// 外部ID連携詳細 保存
		$data = array(
			'idp_user_id' => $idpUser['IdpUser']['id'],		// idp_user.id
			'email' => $this->Session->read('AuthShibboleth.mail'),
			'profile' => serialize($this->Session->read('AuthShibboleth')),
		);
		if (Hash::get($idpUser, 'IdpUserProfile.id')) {
			$data += array('id' => Hash::get($idpUser, 'IdpUserProfile.id'));
		}
		$IdpUserProfile = $this->_controller->IdpUserProfile->saveIdpUserProfile($data);
		if (! $IdpUserProfile) {
			throw new BadRequestException(print_r($this->_controller->IdpUserProfile->validationErrors, true));
		}

		// ユーザ紐づけ済みのため、セッション初期化
		$this->Session->delete('AuthShibboleth');
	}
}
