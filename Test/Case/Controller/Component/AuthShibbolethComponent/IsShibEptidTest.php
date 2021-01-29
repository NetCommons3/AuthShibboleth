<?php
/**
 * AuthShibbolethComponent::isShibEptid()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethComponentTestCase', 'AuthShibboleth.TestSuite');
App::uses('AuthShibbolethComponent', 'AuthShibboleth.Controller/Component');

/**
 * AuthShibbolethComponent::isShibEptid()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethComponent
 */
class AuthShibbolethComponentIsShibEptidTest extends AuthShibbolethComponentTestCase {

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
		/* @see NetCommonsControllerBaseTestCase::generateNc() でSessionをモックにしないための設定 */
		$this->generate('TestAuthShibboleth.TestAuthShibbolethComponent', [
			'Security',
		]);
		CakeSession::clear(false);

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
 * isShibEptid()のテスト
 *
 * @return void
 */
	public function testIsShibEptid() {
		//テストデータ
		CakeSession::write('AuthShibboleth.eppn', 'test103@idp.e-rad.local');

		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethComponent::isShibEptid() */
		$result = $this->controller->AuthShibboleth->isShibEptid();

		//チェック
		$this->assertEquals('0', $result, 'eppnセッション設定のため、0の想定');
	}

/**
 * isShibEptid()のpersistent-idテスト
 *
 * @return void
 */
	public function testIsShibEptidPersistentId() {
		//テストデータ
		CakeSession::write('AuthShibboleth.' . AuthShibbolethComponent::PERSISTENT_ID,
			'test103@persistent-id@idp.e-rad.local');

		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethComponent::isShibEptid() */
		$result = $this->controller->AuthShibboleth->isShibEptid();

		//チェック
		$this->assertEquals('1', $result, 'persistent-idセッションのみ設定のため、1の想定');
	}

/**
 * isShibEptid()の空テスト
 *
 * @return void
 */
	public function testIsShibEptidNull() {
		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethComponent::isShibEptid() */
		$result = $this->controller->AuthShibboleth->isShibEptid();

		// チェック
		$this->assertNull($result, 'セッション未設定のため、nullの想定');
	}

}
