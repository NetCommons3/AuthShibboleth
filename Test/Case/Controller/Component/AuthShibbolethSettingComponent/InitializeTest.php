<?php
/**
 * AuthShibbolethSettingComponent::initialize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethComponentTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethSettingComponent::initialize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethSettingComponent
 */
class AuthShibbolethSettingComponentInitializeTest extends AuthShibbolethComponentTestCase {

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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'AuthShibboleth', 'TestAuthShibboleth');
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
 * initialize()のテスト
 *
 * @return void
 */
	public function testInitialize() {
		//テストコントローラ生成
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethSettingComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_setting_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethSettingComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
	}

}
