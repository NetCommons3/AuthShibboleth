<?php
/**
 * AuthShibbolethComponent::getIdpUserid()のテスト
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
 * AuthShibbolethComponent::getIdpUserid()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethComponent
 */
class AuthShibbolethComponentGetIdpUseridTest extends AuthShibbolethComponentTestCase {

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
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethComponent', array(
			'components' => array('Session' => '')
		));
		CakeSession::destroy();

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
 * getIdpUserid()のテスト
 *
 * @return void
 */
	public function testGetIdpUserid() {
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
		/* @see AuthShibbolethComponent::getIdpUserid() */
		$result = $this->controller->AuthShibboleth->getIdpUserid();
		$this->assertNotEmpty($result, 'eppnセッション設定のため、値が返ってくる想定');
	}

/**
 * getIdpUserid()のpersistent-idテスト
 *
 * @return void
 */
	public function testGetIdpUseridPersistentId() {
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
		/* @see AuthShibbolethComponent::getIdpUserid() */
		$result = $this->controller->AuthShibboleth->getIdpUserid();
		$this->assertNotEmpty($result, 'persistent-idセッション設定のため、値が返ってくる想定');
	}

/**
 * getIdpUserid()の空テスト
 *
 * @return void
 */
	public function testGetIdpUseridEmpty() {
		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		$result = $this->controller->AuthShibboleth->getIdpUserid();
		$this->assertEmpty($result, 'セッション未設定のため、空の想定');
	}

}
