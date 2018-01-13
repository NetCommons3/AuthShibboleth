<?php
/**
 * AuthShibbolethController::mapping()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethController::mapping()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\AuthShibbolethController
 */
class AuthShibbolethControllerMappingTest extends AuthShibbolethControllerTestCase {

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
 * mapping()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testMappingGet() {
		//テストデータ
		CakeSession::write('AuthShibboleth.eppn', 'test101@idp.e-rad.local');

		//テスト実行
		$this->_testGetAction(
			array('action' => 'mapping'),
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		//var_export($this->view);
	}

/**
 * mapping()アクションのGetリクエスト例外テスト
 * 発生条件：セッションにAuthShibboleth.eppn なし
 *
 * @return void
 */
	public function testMappingGetException() {
		//テストデータ
		CakeSession::delete('AuthShibboleth.eppn');

		//テスト実行
		$this->_testGetAction(
			array('action' => 'mapping'),
			array('method' => 'assertNotEmpty'), 'BadRequestException', 'view'
		);
	}
}
