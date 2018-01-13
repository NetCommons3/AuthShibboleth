<?php
/**
 * AuthShibbolethValidateBehavior::validateAuthShibboleth()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethModelTestCase', 'AuthShibboleth.TestSuite');

/**
 * AuthShibbolethValidateBehavior::validateAuthShibboleth()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Model\Behavior\AuthShibbolethValidateBehavior
 */
class AuthShibbolethValidateBehaviorValidateAuthShibbolethTest extends AuthShibbolethModelTestCase {

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
		$this->TestModel = ClassRegistry::init('TestAuthShibboleth.TestAuthShibbolethValidateBehaviorModel');
	}

/**
 * validateAuthShibboleth()テストのDataProvider
 *
 * ### 戻り値
 *  - data リクエストデータ配列
 *
 * @return array データ
 */
	public function dataProvider() {
		$result[0] = array();
		$result[0]['data'] = null;

		return $result;
	}

/**
 * validateAuthShibboleth()のテスト
 *
 * @param array $data リクエストデータ配列
 * @dataProvider dataProvider
 * @return void
 */
	public function testValidateAuthShibboleth($data) {
		//テスト実施
		//$result = $this->TestModel->validateAuthShibboleth($data);
		$this->TestModel->validateAuthShibboleth($data);

		//チェック
		$this->assertEmpty($this->TestModel->validationErrors, 'data空のため、エラー空の想定');
	}

}
