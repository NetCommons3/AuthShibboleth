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
	const IDP_USERID = "eppn";

/**
 * @var string フェデレーション内のエンティティを匿名で表す
 */
	const PERSISTENT_ID = "persistent-id";

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

	///**
	// * Called before the Controller::beforeRender(), and before
	// * the view class is loaded, and before Controller::render()
	// *
	// * コンテンツコメントの一覧データをPaginatorで取得する
	// *
	// * @param Controller $controller Controller with components to beforeRender
	// * @return void
	// * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::beforeRender
	// * @throws Exception Paginatorによる例外
	// */
	//	public function beforeRender(Controller $controller) {
	//		// 設定なし
	//		if (! isset($this->settings['viewVarsKey']['useComment'],
	//					$this->settings['viewVarsKey']['contentKey'],
	//					$this->settings['allow'])) {
	//			return;
	//		}
	//
	//		$useComment = Hash::get($controller->viewVars, $this->settings['viewVarsKey']['useComment']);
	//
	//		// コンテンツキー
	//		$contentKey = Hash::get($controller->viewVars, $this->settings['viewVarsKey']['contentKey']);
	//
	//		// 許可アクション
	//		$allow = $this->settings['allow'];
	//
	//		// コメントを利用しない
	//		if (! $useComment) {
	//			return;
	//		}
	//
	//		// コンテンツキーのDB項目名なし
	//		if (! isset($contentKey)) {
	//			return;
	//		}
	//
	//		// 許可アクションなし
	//		if (! in_array($controller->request->params['action'], $allow)) {
	//			return;
	//		}
	//
	//		// 条件
	//		/* @see ContentComment::getConditions() */
	//		$query['conditions'] = $controller->ContentComment->getConditions($contentKey);
	//
	//		//ソート
	//		$query['order'] = array('ContentComment.created' => 'desc');
	//
	//		//表示件数
	//		$query['limit'] = $this::START_LIMIT;
	//
	//		$this->Paginator->settings = $query;
	//		try {
	//			$contentComments = $this->Paginator->paginate('ContentComment');
	//		} catch (Exception $ex) {
	//			CakeLog::error($ex);
	//			throw $ex;
	//		}
	//
	//		$controller->request->data['ContentComments'] = $contentComments;
	//
	//		if (!in_array('ContentComments.ContentComment', $controller->helpers) &&
	//			!array_key_exists('ContentComments.ContentComment', $controller->helpers)
	//		) {
	//			$controller->helpers[] = 'ContentComments.ContentComment';
	//		}
	//	}

/**
 * IdPによる個人識別番号 and persistentId セット
 *
 * @return void
 */
	public function setIdpUserData() {
		// Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
		//$entityId = null;
		//$persistentId = null;
		//var_dump($_SERVER);

		// 登録途中でキャンセルやブラウザ閉じた後、再登録した場合を考え、セッション初期化
		$this->Session->delete('AuthShibboleth');

		$prefix = '';
		for ($i = 0; $i < 5; $i++) {
			$prefix = str_repeat("REDIRECT_", $i);
			//$entityId = !empty($_SERVER[$prefix . AuthShibbolethComponent::ENTITY_ID]) ?
			//	$_SERVER[$prefix . AuthShibbolethComponent::ENTITY_ID] : "";
			//if (!empty($_SERVER[$prefix . AuthShibbolethComponent::PERSISTENT_ID])) {
			//	$persistentId = $_SERVER[$prefix . AuthShibbolethComponent::PERSISTENT_ID];
			//}
			//$this->idpUserid = Hash::get($_SERVER, $prefix . AuthShibbolethComponent::IDP_USERID);
			//if (! is_null($this->idpUserid)) {
			//	$this->profile[AuthShibbolethComponent::IDP_USERID] = $this->idpUserid;
			//}
			//$this->idpUserid = $this->__setProfile($prefix, AuthShibbolethComponent::IDP_USERID);
			//$this->persistentId = $this->__setProfile($prefix, AuthShibbolethComponent::PERSISTENT_ID);
			$this->__setProfile($prefix, AuthShibbolethComponent::IDP_USERID);
			$this->__setProfile($prefix, AuthShibbolethComponent::PERSISTENT_ID);
			$idpUserid = $this->getProfileByItemKey(AuthShibbolethComponent::IDP_USERID);

			//if ($entityId != "") {
			//if ($this->idpUserid) {
			if ($idpUserid) {
				break;
			}
		}
		//if ($entityId == "" && $persistentId != "") {
		//if (is_null($this->entityId) && ! is_null($this->persistentId)) {
		//	$entityId = $persistentId;
		//	$this->isShibEptid = '1';
		//}

		//		//if (empty($entityId)) {
		//		if (is_null($this->entityId) && is_null($this->persistentId)) {
		//			// entityId=空、persistentId=空
		//			//$commonMain =& $container->getComponent("commonMain");
		//			//$session->setParameter("login_wayf_not_auto_login", _ON);
		//			//$this->Session->write('AuthShibboleth.loginWayfNotAutoLogin', '1');
		//			//$url = BASE_URL_HTTPS . "/Shibboleth.sso/Logout?return=" . rawurlencode(BASE_URL);
		//			//$commonMain->redirectHeader($url, 10, $errStr);
		//
		//			//return $errStr;
		//			return false;
		//			//		} elseif (is_null($this->entityId) && ! is_null($this->persistentId)) {
		//			//			// entityId=空、persistentId=あり
		//			//			$this->isShibEptid = '1';
		//			//		} else {
		//			//			// entityId=あり、persistentId=あり or なし
		//			//			$this->isShibEptid = '0';
		//		}
		//
		//		//$session->removeParameter("login_wayf_not_auto_login");
		//		//$this->Session->delete('AuthShibboleth.loginWayfNotAutoLogin');
		//		//return true;
		//
		//		// パーミッションがあるかチェック
		//		//		if (!$this->__checkPermission()) {
		//		//			return false;
		//		//		}
		//
		//		return true;
		if (! $this->isIdpUserid()) {
			return;
		}

		//		//メールアドレス
		//		if (isset($_SERVER[$prefix . 'mail'])) {
		//			$buf_name = $_SERVER[$prefix . 'mail'];
		//			$this->session->setParameter(array('login_external', 'email'), $buf_name);
		//		}
		//		//氏名(日本語)
		//		if (isset($_SERVER[$prefix . 'jaDisplayName']) || isset($_SERVER[$prefix . 'jasn']) && isset($_SERVER[$prefix . 'jaGivenName'])) {
		//			if (isset($_SERVER[$prefix . 'jaDisplayName'])) {
		//				$buf_name = $_SERVER[$prefix . 'jaDisplayName'];
		//			} else {
		//				$buf_name = $_SERVER[$prefix . 'jasn'];
		//				$buf_name .= " ". $_SERVER[$prefix . 'jaGivenName'];
		//			}
		//			$this->session->setParameter(array('login_external', 'user_name'), $buf_name);
		//		}
		//		//所属(日本語)
		//		if (isset($_SERVER[$prefix . 'jao'])) {
		//			$buf_name = $_SERVER[$prefix . 'jao'];
		//			$this->session->setParameter(array('login_external', 'affiliation'), $buf_name);
		//		}
		//		//部署(日本語)
		//		if (isset($_SERVER[$prefix . 'jaou'])) {
		//			$buf_name = $_SERVER[$prefix . 'jaou'];
		//			$this->session->setParameter(array('login_external', 'section'), $buf_name);
		//		}
		//		//氏名(英語)
		//		if (isset($_SERVER[$prefix . 'displayName']) || isset($_SERVER[$prefix . 'sn']) && isset($_SERVER[$prefix . 'givenName'])) {
		//			if (isset($_SERVER[$prefix . 'displayName'])) {
		//				$buf_name = $_SERVER[$prefix . 'displayName'];
		//			} else {
		//				$buf_name = $_SERVER[$prefix . 'givenName'];
		//				$buf_name .= " ".$_SERVER[$prefix . 'sn'];
		//			}
		//			$this->session->setParameter(array('login_external', 'user_name_en'), $buf_name);
		//		}
		//		//所属(英語)
		//		if (isset($_SERVER[$prefix . 'o'])) {
		//			$buf_name = $_SERVER[$prefix . 'o'];
		//			$this->session->setParameter(array('login_external', 'affiliation_en'), $buf_name);
		//		}
		//		//部署(英語)
		//		if (isset($_SERVER[$prefix . 'ou'])) {
		//			$buf_name = $_SERVER[$prefix . 'ou'];
		//			$this->session->setParameter(array('login_external', 'section_en'), $buf_name);
		//		}

		$this->__setProfile($prefix, 'mail');			//メールアドレス
		$this->__setProfile($prefix, 'jaDisplayName');	//日本語氏名（表示名）
		$this->__setProfile($prefix, 'jasn');			//氏名（姓）の日本語
		$this->__setProfile($prefix, 'jaGivenName');	//氏名（名）の日本語
		$this->__setProfile($prefix, 'jao');			//所属(日本語)
		$this->__setProfile($prefix, 'jaou');			//部署(日本語)
		$this->__setProfile($prefix, 'displayName');	//英字氏名（表示名）
		$this->__setProfile($prefix, 'sn');				//氏名(姓)の英字
		$this->__setProfile($prefix, 'givenName');		//氏名(名)の英字
		$this->__setProfile($prefix, 'o');				//所属(英語)
		$this->__setProfile($prefix, 'ou');				//部署(英語)
	}

/**
 * プロフィール情報 セット
 *
 * @param string $prefix Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
 * @param string $itemKey Sessionの配列キーの一部
 * @return void
 */
	private function __setProfile($prefix, $itemKey) {
		$item = Hash::get($_SERVER, $prefix . $itemKey);
		if (! is_null($item)) {
			//$this->profile[$itemKey] = $item;
			$this->Session->write('AuthShibboleth.' . $itemKey, $item);
		}
		// * @return string 取得した値
		//return $item;
	}

/**
 * プロフィール情報 取得
 *
 * @param string $itemKey Sessionの配列キーの一部
 * @return string 取得した値
 */
	public function getProfileByItemKey($itemKey) {
		//return Hash::get($this->profile, $itemKey);
		return $this->Session->read('AuthShibboleth.' . $itemKey);
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
				// [まだ]nc3版はscope消す（shibboleth時は空なので）
				'scope' => '',				// shibboleth時は空
			);
			$idpUser = $this->_controller->IdpUser->saveIdpUser($data);
			if (! $idpUser) {
				throw new BadRequestException(print_r($this->_controller->IdpUser->validationErrors, true));
			}
		}

		// 外部ID連携詳細 保存
		$data = array(
			'idp_user_id' => $idpUser['IdpUser']['id'],		// idp_user.id
			'email' => $this->getProfileByItemKey('mail'),
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
