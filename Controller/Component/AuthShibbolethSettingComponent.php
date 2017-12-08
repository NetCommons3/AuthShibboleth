<?php
/**
 * 設定の編集 Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * 設定の編集 Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller\Component
 * @property SessionComponent $Session
 */
class AuthShibbolethSettingComponent extends Component {

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
 * edit
 *
 * @return void
 */
	public function edit() {
		if ($this->_controller->request->is('post')) {

			// 現在、外部認証プラグインのタブがJS切り替えのため、AuthLdapとか外部認証プラグインが複数になった時、validateでAuthShibbolethはvalidate
			// したけど、AuthLdapはしてないとか、そんな状況になりそうだなぁ。
			$this->_controller->SiteSetting->validatePlugins = array('AuthShibboleth');

			// [まだ] 保存すると空の1行が余計に登録される
			//登録処理
			$this->_controller->SiteManager->saveData();

		} else {
			$this->_controller->request->data['SiteSetting']
				= $this->_controller->SiteSetting->getSiteSettingForEdit(
						array('SiteSetting.key' => array(
							//ログイン設定
							// * shibbolethログイン
							// ** IdPによる個人識別番号に利用する項目
							'AuthShibboleth.idp_userid',
							// ** 学認 Embedded DS
							// *** WAYF URL
							'AuthShibboleth.wayf_URL',
							// *** エンティティID
							'AuthShibboleth.wayf_sp_entityID',
							// *** Shibboleth SPのハンドラURL
							'AuthShibboleth.wayf_sp_handlerURL',
							// *** 認証後に開くURL
							'AuthShibboleth.wayf_return_url',
							// **** ベースURL
							'AuthShibboleth.base_url',
							// *** ログインしたままにする にチェックを入れて操作させない
							'AuthShibboleth.wayf_force_remember_for_session',
							// *** DiscpFeed URL
							'AuthShibboleth.wayf_discofeed_url',
							// *** 他のフェデレーションのIdPを追加する
							'AuthShibboleth.wayf_additional_idps',
						)
					));
		}
	}

}
