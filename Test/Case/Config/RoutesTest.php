<?php
/**
 * Config/routesのテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsRoutesTestCase', 'NetCommons.TestSuite');

/**
 * Config/routesのテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Case\Config
 */
class RoutesTest extends NetCommonsRoutesTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'auth_shibboleth';

/**
 * DataProvider
 *
 * ### 戻り値
 *  - url URL
 *  - expected 期待値
 *  - settingMode セッティングモード
 *
 * @return array
 */
	public function dataProvider() {
		//テストデータ
		return array(
			array(
				'url' => '/auth_shibboleth/auth_auto_user_regist/request',
				'expected' => array(
					'plugin' => 'auth_shibboleth',
					'controller' => 'auth_shibboleth_auto_user_regist',
					'action' => 'request',
				),
				'settingMode' => false
			),
		);
	}

}
