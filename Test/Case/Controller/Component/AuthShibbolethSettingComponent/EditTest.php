<?php
/**
 * AuthShibbolethSettingComponent::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethSettingComponent::edit()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethSettingComponent
 */
class AuthShibbolethSettingComponentEditTest extends AuthShibbolethControllerTestCase {

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
 * edit()のGetテスト
 *
 * @return void
 */
	public function testEditGet() {
		//テストコントローラ生成
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethSettingComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_setting_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethSettingComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethSettingComponent::edit() */
		$this->controller->AuthShibbolethSetting->edit();

		// チェック
		//var_export($this->controller->request->data['SiteSetting']);
		$this->assertNotEmpty($this->controller->request->data['SiteSetting'],
			'フィクスチャーでsite_settingにデータ登録しているため、値がセットされている想定');
	}

}
