<?php
/**
 * View/Elements/AuthShibboleth/dsのテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * View/Elements/AuthShibboleth/dsのテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\View\Elements\AuthShibboleth\Ds
 */
class AuthShibbolethViewElementsAuthShibbolethDsTest extends AuthShibbolethControllerTestCase {

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
		$this->generateNc('TestAuthShibboleth.TestViewElementsAuthShibbolethDs');
	}

/**
 * View/Elements/AuthShibboleth/dsのテスト
 *
 * @return void
 */
	public function testDs() {
		SiteSettingUtil::write('AuthShibboleth.wayf_discofeed_url',
			'https://point.switch.ch/Shibboleth.sso/DiscoFeed', 0);
		SiteSettingUtil::write('AuthShibboleth.wayf_force_remember_for_session',
			'1', 0);

		//テスト実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_view_elements_auth_shibboleth_ds/discovery',
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$pattern = '/' . preg_quote('View/Elements/AuthShibboleth/ds', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		//var_export($this->view);
	}

/**
 * View/Elements/RmAuthShibboleth/dsのwayfAutoLogin OFFテスト
 *
 * @return void
 */
	public function testDsWayfAutoLoginOff() {
		CakeSession::write('AuthShibbolethDs.wayfAutoLogin', false);

		//テスト実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_view_elements_auth_shibboleth_ds/discovery',
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$pattern = '/' . preg_quote('View/Elements/AuthShibboleth/ds', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		//var_export($this->view);
	}

}
