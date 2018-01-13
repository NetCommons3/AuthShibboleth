<?php
/**
 * AuthShibbolethComponentテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * AuthShibbolethComponentテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\test_app\Plugin\TestAuthShibboleth\Controller
 */
class TestAuthShibbolethComponentController extends AppController {

/**
 * 使用コンポーネント
 *
 * @var array
 */
	public $components = array(
		'AuthShibboleth.AuthShibboleth'
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->autoRender = true;
	}

}
