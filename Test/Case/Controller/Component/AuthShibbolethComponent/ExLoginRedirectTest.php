<?php
/**
 * AuthShibbolethComponent::exLoginRedirect()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethComponentTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethComponent::exLoginRedirect()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethComponent
 */
class AuthShibbolethComponentExLoginRedirectTest extends AuthShibbolethComponentTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.auth.external_idp_user'
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
		/* @see NetCommonsControllerBaseTestCase::generateNc() でSessionをモックにしないための設定 */
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethComponent', array(
			'components' => array('Session' => '')
		));

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
 * exLoginRedirect()のテスト
 *
 * @return void
 */
	public function testExLoginRedirect() {
		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		$result = $this->controller->AuthShibboleth->exLoginRedirect();

		// チェック
		$expected = '/auth_shibboleth/auth_shibboleth/mapping';
		$this->assertEquals($expected, $result,
			'関連付けされていないなら「ログイン関連付け画面」へリダイレクト。リダイレクト先が違います。');
	}

}
