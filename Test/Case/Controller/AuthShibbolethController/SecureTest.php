<?php
/**
 * AuthShibbolethController::secure()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethController::secure()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\AuthShibbolethController
 */
class AuthShibbolethControllerSecureTest extends AuthShibbolethControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.auth_shibboleth.site_setting4_auth_shibboleth',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'auth_shibboleth';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'auth_shibboleth';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);

		/* @see NetCommonsControllerBaseTestCase::generateNc() でSessionをモックにしないための設定 */
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => '')
		));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * secure()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testSecureGet() {
		//テストデータ
		$_SERVER['eppn'] = 'test103@idp.e-rad.local';

		//テスト実行
		$this->_testGetAction(
			array('action' => 'secure'),
			array('method' => 'assertEmpty'), null, 'result'
		);

		//チェック
		// rm_auth_shibbolethプラグインのexloginアクションに遷移するリダイレクトURL
		$expected = 'https://example.com/auth_shibboleth/auth_shibboleth/exlogin';
		$this->assertEquals($expected, $this->controller->response->header()['Location']);
	}

/**
 * secure()アクションのGetリクエスト(必要な属性情報が得られない)テスト
 * 発生条件：$_SERVERにeppn なし
 *
 * @return void
 * @see https://book.cakephp.org/2.0/ja/development/testing.html#return 参考. returnする値の選択
 */
	public function testSecureGetAttributeEmpty() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'secure'),
			array('method' => 'assertEmpty'), null, 'result'
		);

		//チェック
		// shibboleth SPログアウト＆ログインページに遷移するリダイレクトURL
		$expected = 'https://example.com/Shibboleth.sso/Logout?return=https://example.com/auth/login';
		$this->assertEquals($expected, $this->controller->response->header()['Location']);
	}

}
