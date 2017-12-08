<?php
/**
 * AuthShibbolethController::discovery()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethController::discovery()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\AuthShibbolethController
 */
class AuthShibbolethControllerDiscoveryTest extends AuthShibbolethControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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
 * discovery()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testDiscoveryGet() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'discovery'),
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$message = 'embedded-wayf.js';
		$this->assertTextContains($message, $this->view);
	}

}
