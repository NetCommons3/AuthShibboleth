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
		$this->Auth->allow('login', 'logout', 'mapping');
	}

/**
 * 学認Embedded DS表示
 *
 * @return void
 **/
	public function login() {
		$this->view = 'AuthShibboleth.AuthShibboleth/login';
	}

/**
 * ユーザ紐づけ
 *
 * @return void
 * @throws ForbiddenException 403 entityId取得できず
 **/
	public function mapping() {
		if (! $this->AuthShibboleth->isEntityId()) {
			//$session->setParameter("login_wayf_not_auto_login", _ON);
			//$this->Session->write('AuthShibboleth.loginWayfNotAutoLogin', '1');
			//$url = BASE_URL_HTTPS . "/Shibboleth.sso/Logout?return=" . rawurlencode(BASE_URL);
			//$commonMain->redirectHeader($url, 10, $errStr);
			$this->request->base = '/';

			// $baseUrl = Router::url('/', true);
			// var_dump($baseUrl);
			// $baseUrl2 = $baseUrl . 'auth/login';
			// $redirect = $baseUrl . "Shibboleth.sso/Logout?return=" . $baseUrl2;

			// login_error_externalId = "選択した所属機関認証システムから必要な属性情報が得られないため、<br />ログインすることができません。<br /><div style='margin-top:15px;'>選択した所属機関認証システムの管理者にお問い合わせください。</div>"
			// @see https://book.cakephp.org/2.0/ja/development/exceptions.html#cakephp CakePHPの組み込み例外
			// 400
			//throw new BadRequestException(__d('net_commons', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'));
			// 403
			throw new ForbiddenException(__d('net_commons', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'));
		}
		// リダイレクトでちゃんと表示できるようにする
	}
}
