<?php
/**
 * AuthShibbolethComponent::setIdpUserData()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethControllerTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethComponent::setIdpUserData()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Controller\Component\AuthShibbolethComponent
 */
class AuthShibbolethComponentSetIdpUserDataTest extends AuthShibbolethControllerTestCase {

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
 * setIdpUserData()のテスト
 *
 * @return void
 */
	public function testSetIdpUserData() {
		//テストコントローラ生成
		/* @see NetCommonsControllerBaseTestCase::generateNc() でSessionをモックにしないための設定
		 * Sessionをモックにするとなぜか、$_SERVERにセットした値が消えるので、モックにしないよう設定 */
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethComponent', array(
			'components' => array('Session' => '')
		));

		//テストデータ
		$_SERVER['eppn'] = 'test103@idp.e-rad.local';

		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethComponent::setIdpUserData() */
		$this->controller->AuthShibboleth->setIdpUserData();

		$this->assertEquals($_SERVER['eppn'], CakeSession::read('AuthShibboleth.eppn'),
			'セッションAuthShibboleth.eppnは$_SERVERにセットしてるので' .
			$_SERVER['eppn'] . 'がセットされている想定です');
	}

/**
 * setIdpUserData()のセッション空テスト
 *
 * @return void
 */
	public function testSetIdpUserDataEmptySession() {
		//テストコントローラ生成
		$this->generateNc('TestAuthShibboleth.TestAuthShibbolethComponent');

		//テストデータ
		CakeSession::delete('AuthShibboleth.eppn');

		//テストアクション実行
		$this->_testGetAction(
			'/test_auth_shibboleth/test_auth_shibboleth_component/index',
			array('method' => 'assertNotEmpty'), null, 'view'
		);
		$pattern = '/' . preg_quote('Controller/Component/TestAuthShibbolethComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		/* @see AuthShibbolethComponent::setIdpUserData() */
		$this->controller->AuthShibboleth->setIdpUserData();
		$this->assertEmpty(CakeSession::read('AuthShibboleth.eppn'),
			'セッションAuthShibboleth.eppnは$_SERVERにセットしてないので空の想定です');
	}

}
