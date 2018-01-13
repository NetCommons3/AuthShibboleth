<?php
/**
 * View/Elements/settingのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * View/Elements/settingのテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\View\Elements\Setting
 */
class AuthShibbolethViewElementsSettingTest extends AuthShibbolethControllerTestCase {

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
		//テストコントローラ生成
		$this->generateNc('TestAuthShibboleth.TestViewElementsSetting');
	}

/**
 * View/Elements/settingのテスト
 *
 * @return void
 */
	public function testSetting() {
		//テスト実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_view_elements_setting/setting',
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$pattern = '/' . preg_quote('View/Elements/setting', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		//var_export($this->view);
	}

}
