<?php
/**
 * AuthShibbolethValidateBehavior::__validateUrl()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethModelTestCase', 'AuthShibboleth.TestSuite');
App::uses('AuthShibbolethValidateBehavior', 'AuthShibboleth.Model/Behavior');

/**
 * AuthShibbolethValidateBehavior::__validateUrl()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Model\Behavior\AuthShibbolethValidateBehavior
 */
class AuthShibbolethValidateBehaviorPrivateValidateUrlTest extends AuthShibbolethModelTestCase {

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
 * Test model alias
 *
 * @var string
 */
	protected $_testModelAlias = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'AuthShibboleth', 'TestAuthShibboleth');
		$this->TestModel = ClassRegistry::init('TestAuthShibboleth.TestAuthShibbolethValidateBehaviorPrivateModel');
		$this->_testModelAlias = $this->TestModel->alias;
	}

/**
 * __validateUrl()テストのDataProvider
 *
 * ### 戻り値
 *  - data リクエストデータ配列
 *  - key キー
 *
 * @return array データ
 */
	public function dataProvider() {
		$key = 'AuthShibboleth.wayf_URL';
		$result['空テスト']['data'] = null;
		$result['空テスト']['key'] = null;
		$result['空value']['data'][$this->_testModelAlias][$key][0]['value'] = '';
		$result['空value']['data'][$this->_testModelAlias][$key][0]['language_id'] = 0;
		$result['空value']['key'] = $key;
		$result['正常テスト']['data'][$this->_testModelAlias][$key][0]['value'] = 'https://example.com/';
		$result['正常テスト']['data'][$this->_testModelAlias][$key][0]['language_id'] = 0;
		$result['正常テスト']['key'] = $key;

		return $result;
	}

/**
 * __validateUrl()のテスト
 *
 * @param array $data リクエストデータ配列
 * @param string $key キー
 * @dataProvider dataProvider
 * @return void
 * @see AuthShibbolethValidateBehavior::__validateUrl()
 */
	public function testValidateUrl($data, $key) {
		$behavior = new AuthShibbolethValidateBehavior();

		//テスト実施
		$this->_testReflectionMethod(
			$behavior, '__validateUrl', array($this->TestModel, $data, $key)
		);

		//チェック
		$this->assertEmpty($this->TestModel->validationErrors, 'validationErrorsなしテストケースの想定です');
	}

/**
 * __validateUrl()のエラー時テスト
 *
 * @return void
 * @see AuthShibbolethValidateBehavior::__validateUrl()
 */
	public function testValidateUrlError() {
		$behavior = new AuthShibbolethValidateBehavior();

		//テストデータ
		$key = 'AuthShibboleth.wayf_URL';
		$data[$this->TestModel->alias][$key][0]['value'] = 'xxx';
		$data[$this->TestModel->alias][$key][0]['language_id'] = 0;

		//テスト実施
		$this->_testReflectionMethod(
			$behavior, '__validateUrl', array($this->TestModel, $data, $key)
		);

		//チェック
		//var_export($this->TestModel->validationErrors);
		$this->assertNotEmpty($this->TestModel->validationErrors[$key], 'validationErrorsありテストケースの想定です');
	}

}
