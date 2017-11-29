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
 */
class AuthShibbolethComponent extends Component {
/**
 * @var string フェデレーション内のエンティティを一意に定めます
 * @see https://meatwiki.nii.ac.jp/confluence/pages/viewpage.action?pageId=12158166 属性リスト
 */
	const ENTITY_ID = "eppn";

/**
 * @var string フェデレーション内のエンティティを匿名で表す
 */
	const PERSISTENT_ID = "persistent-id";

	///**
	// * Other components
	// *
	// * @var array
	// */
	//	public $components = array(
	//		'Session',
	//	);

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

	///**
	// * Called after the Controller::beforeFilter() and before the controller action
	// *
	// * @param Controller $controller Controller with components to startup
	// * @return void
	// * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::startup
	// */
	//	public function startup(Controller $controller) {
	//		$controller->ContentComment = ClassRegistry::init('ContentComments.ContentComment');
	//
	//		// コンテントコメントからエラーメッセージを受け取る仕組み
	//		/* @link http://skgckj.hateblo.jp/entry/2014/02/09/005111 */
	//		$controller->ContentComment->validationErrors =
	//			$this->Session->read('ContentComments.forRedirect.errors');
	//	}

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
 * entityIdの存在チェック
 *
 * @return bool 成功 or 失敗
 */
	public function isEntityId() {
		// --- getEntityId()
		// Shibbolethの設定によって、eppn属性にREDIRECT_が付与されてしまうことがある
		$entityId = "";
		$persistentId = "";
		//var_dump($_SERVER);
		for ($i = 0; $i < 5; $i++) {
			$prefix = str_repeat("REDIRECT_", $i);
			$entityId = !empty($_SERVER[$prefix . AuthShibbolethComponent::ENTITY_ID]) ?
				$_SERVER[$prefix . AuthShibbolethComponent::ENTITY_ID] : "";
			if (!empty($_SERVER[$prefix . AuthShibbolethComponent::PERSISTENT_ID])) {
				$persistentId = $_SERVER[$prefix . AuthShibbolethComponent::PERSISTENT_ID];
			}
			if ($entityId != "") {
				break;
			}
		}
		if ($entityId == "" && $persistentId != "") {
			$entityId = $persistentId;
		}

		if (empty($entityId)) {
			//$commonMain =& $container->getComponent("commonMain");
			//$session->setParameter("login_wayf_not_auto_login", _ON);
			//$this->Session->write('AuthShibboleth.loginWayfNotAutoLogin', '1');
			//$url = BASE_URL_HTTPS . "/Shibboleth.sso/Logout?return=" . rawurlencode(BASE_URL);
			//$commonMain->redirectHeader($url, 10, $errStr);

			//return $errStr;
			return false;
		}
		//$session->removeParameter("login_wayf_not_auto_login");
		//$this->Session->delete('AuthShibboleth.loginWayfNotAutoLogin');
		//return true;

		// パーミッションがあるかチェック
		//		if (!$this->__checkPermission()) {
		//			return false;
		//		}

		return true;
	}

	///**
	// * パーミッションがあるかチェック
	// *
	// * @return bool true:パーミッションあり or false:パーミッションなし
	// */
	//	private function __checkPermission() {
	//		// 登録処理
	//		if ($this->_controller->action == 'add') {
	//			// 投稿許可ありか
	//			return $this->__isCreatable();
	//		}
	//
	//		// 編集処理 or 削除処理
	//		if ($this->_controller->action == 'edit' || $this->_controller->action == 'delete') {
	//			// 編集許可ありか
	//			return $this->__isEditable();
	//		}
	//
	//		// 承認処理 and 承認許可あり
	//		if ($this->_controller->action == 'approve' &&
	//				Current::permission('content_comment_publishable')) {
	//			return true;
	//		}
	//		return false;
	//	}
	//
	///**
	// * 投稿許可ありか
	// *
	// * @return bool true:あり or false:なし
	// */
	//	private function __isCreatable() {
	//		// 投稿許可あり
	//		if (Current::permission('content_comment_creatable')) {
	//			return true;
	//		}
	//
	//		$isVisitorCreatable = $this->_controller->request->data('_tmp.is_visitor_creatable');
	//		//  ビジター投稿許可あり
	//		if ($isVisitorCreatable) {
	//			return true;
	//		}
	//		return false;
	//	}
	//
	///**
	// * 編集許可ありか
	// *
	// * @return bool true:あり or false:なし
	// */
	//	private function __isEditable() {
	//		// 編集許可あり
	//		if (Current::permission('content_comment_editable')) {
	//			return true;
	//		}
	//		// 自分で投稿したコメントなら、編集・削除可能
	//		if ($this->_controller->data['ContentComment']['created_user'] == (int)Current::read('User.id')) {
	//			return true;
	//		}
	//		return false;
	//	}
	//
	///**
	// * dataの準備
	// *
	// * @return array data
	// */
	//	private function __readyData() {
	//		$data['ContentComment'] = $this->_controller->request->data('ContentComment');
	//		$data['ContentComment']['block_key'] = Current::read('Block.key');
	//		$data['_mail'] = $this->_controller->request->data('_mail');
	//		$data['_mail']['url'] = $this->_controller->request->referer();
	//		if ($this->_controller->action == 'approve') {
	//			$data['_mail']['is_comment_approve_action'] = 1;
	//		} else {
	//			$data['_mail']['is_comment_approve_action'] = 0;
	//		}
	//
	//		return $data;
	//	}
}
