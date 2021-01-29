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
 * @property NetCommonsComponent $NetCommons
 */
class AuthShibbolethComponent extends Component {

/**
 * @var string フェデレーション内のエンティティを匿名で表す
 */
	const PERSISTENT_ID = 'persistent-id';

/**
 * Other components
 *
 * @var array
 */
	public $components = array(
		'Session',
		'NetCommons.NetCommons',
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
		$controller->ExternalIdpUser = ClassRegistry::init('Auth.ExternalIdpUser');
		$controller->User = ClassRegistry::init('Users.User');
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
			$idpUseridSetting = SiteSettingUtil::read('AuthShibboleth.idp_userid');
			$this->_setSession($prefix, $idpUseridSetting);
			$this->_setSession($prefix, self::PERSISTENT_ID);

			$idpUserid = $this->Session->read('AuthShibboleth.' . $idpUseridSetting);
			if ($idpUserid) {
				break;
			}
		}

		if (! $this->isIdpUserid()) {
			return;
		}

		//		$this->__setSession($prefix, 'mail');			//メールアドレス
		//		$this->__setSession($prefix, 'jaDisplayName');	//日本語氏名（表示名）
		//		$this->__setSession($prefix, 'jasn');			//氏名（姓）の日本語
		//		$this->__setSession($prefix, 'jaGivenName');	//氏名（名）の日本語
		//		$this->__setSession($prefix, 'jao');			//所属(日本語)
		//		$this->__setSession($prefix, 'jaou');			//部署(日本語)
		//		$this->__setSession($prefix, 'displayName');	//英字氏名（表示名）
		//		$this->__setSession($prefix, 'sn');				//氏名(姓)の英字
		//		$this->__setSession($prefix, 'givenName');		//氏名(名)の英字
		//		$this->__setSession($prefix, 'o');				//所属(英語)
		//		$this->__setSession($prefix, 'ou');				//部署(英語)
		$this->_setSession($prefix, 'Shib-Identity-Provider');				//IdPエンティティID
	}

/**
 * セッション セット
 *
 * @param string $prefix Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
 * @param string $itemKey Sessionの配列キーの一部
 * @return void
 */
	protected function _setSession($prefix, $itemKey) {
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
		$idpUseridSetting = SiteSettingUtil::read('AuthShibboleth.idp_userid');
		$idpUserid = $this->Session->read('AuthShibboleth.' . $idpUseridSetting);
		$persistentId = $this->Session->read('AuthShibboleth.' . self::PERSISTENT_ID);
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
		$idpUseridSetting = SiteSettingUtil::read('AuthShibboleth.idp_userid');
		$idpUserid = $this->Session->read('AuthShibboleth.' . $idpUseridSetting);
		$persistentId = $this->Session->read('AuthShibboleth.' . self::PERSISTENT_ID);
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
		$idpUseridSetting = SiteSettingUtil::read('AuthShibboleth.idp_userid');
		$idpUserid = $this->Session->read('AuthShibboleth.' . $idpUseridSetting);
		$persistentId = $this->Session->read('AuthShibboleth.' . self::PERSISTENT_ID);
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
 * ログイン関連付け
 *
 * @param int $userId ユーザID
 * @return void
 * @throws UnauthorizedException
 */
	public function saveUserMapping($userId) {
		// IdPによる個人識別番号 取得
		$idpUserid = $this->getIdpUserid();
		if (!$idpUserid) {
			throw new UnauthorizedException();
		}

		// IdPによる個人識別番号 で取得
		$externalIdpUser = $this->_controller->ExternalIdpUser->findByIdpUserid($idpUserid);

		// 外部ID連携 保存
		$data = array(
			'user_id' => $userId,
			'idp_userid' => $idpUserid,		// IdPによる個人識別番号
			'is_shib_eptid' => $this->isShibEptid(),	// ePTID(eduPersonTargetedID)かどうか
			'status' => '2',			// 2:有効
			// nc3版はscope消した（shibboleth時は空なので）
			//'scope' => '',				// shibboleth時は空
		);
		if ($externalIdpUser) {
			// データあれば更新
			$data['id'] = $externalIdpUser['ExternalIdpUser']['id'];
		}

		// 保存
		$externalIdpUser = $this->_controller->ExternalIdpUser->saveExternalIdpUser($data);
		if (! $externalIdpUser) {
			throw new UnauthorizedException();
		}
	}

/**
 * 「他サービスを用いたログイン処理」後の処理・画面遷移先
 *
 * * 関連付けられてるなら、ID関連付けを行う。呼び出し元でログイン処理をする
 * * 関連付けされていないなら、「ログイン関連付け画面」へリダイレクト
 * * 関連付けたユーザが利用不可かいないなら、「ログイン関連付け画面」へリダイレクト
 * * 関連付けたユーザが承認待ちなら、メッセージを表示してトップページへリダイレクト
 * * 関連付けが無効に設定されていたら、メッセージを表示してトップページへリダイレクト
 *
 * @return string リダイレクト先
 * @throws UnauthorizedException
 */
	public function exLoginRedirect() {
		// IdPによる個人識別番号 取得
		$idpUserid = $this->getIdpUserid();
		if (!$idpUserid) {
			return '';
		}

		// IdPによる個人識別番号 で取得
		$idpUser = $this->_controller->ExternalIdpUser->findByIdpUserid($idpUserid);
		if (! $idpUser) {
			// 関連付けされていないなら「ログイン関連付け画面」へリダイレクト
			return '/auth_shibboleth/auth_shibboleth/mapping';
		}

		// ユーザ情報取得
		$user = $this->_getUserById($idpUser['ExternalIdpUser']['user_id']);

		// User.is_deleted = 0：削除していない  1：削除済み'
		// User.status = 0:利用不可  1:利用可  2.承認待ち  3.承認済み（メールでのユーザ自身承認前） null:削除済み
		if ($user['User']['is_deleted'] === '1' || $user['User']['status'] === '0') {
			// status = 0:ユーザ利用不可か is_deleted = 1：削除済みなら「ログイン関連付け画面」へリダイレクト
			return '/auth_shibboleth/auth_shibboleth/mapping';

		} elseif ($user['User']['status'] === '2' || $user['User']['status'] === '3') {
			// status =2.承認待ち  3.承認済みならメッセージ表示してtopページにリダイレクト
			$this->NetCommons->setFlashNotification(
				__d('auth',
					'Account is pending approval. Please wait until your account is approved, ' .
					'or confirm that you have not received your membership confirmation email.'),
				array(
					'class' => 'warning',
					'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
				),
				400
			);
			return '/';
		}

		if ($idpUser['ExternalIdpUser']['status'] === '2') {
			// 2:有効 （関連付けられてる）なら、ID関連付けを行う。呼び出し元でログイン処理をする
			// ID関連付け
			$this->saveUserMapping($user['User']['id']);

			// ログイン処理へ（実際のリダイレクトパスではなく、呼び出し元で判定に使う）
			return 'login';

		} elseif ($idpUser['ExternalIdpUser']['status'] === '0') {
			// 0:無効 ならメッセージ表示してtopページにリダイレクト
			$this->NetCommons->setFlashNotification(
				__d('auth',
					'The ID association information of the corresponding service is invalid. ' .
					'Please login with a different ID and review ID linkage settings or ' .
					'contact your institution.'),
				array(
					'class' => 'danger',
					'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
				),
				400
			);
			return '/';
		}

		return '';
	}

/**
 * ユーザ情報 取得
 *
 * @param int $userId ユーザID
 * @return array ユーザ情報
 * @throws UnauthorizedException
 */
	protected function _getUserById($userId) {
		$user = $this->_controller->User->findById($userId);
		if (! $user) {
			throw new UnauthorizedException();
		}
		return $user;
	}
}
