<?php
/**
 * View/Elements/loginテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/loginテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\test_app\Plugin\TestAuthShibboleth\Controller
 */
class TestViewElementsLoginController extends AppController {

/**
 * login
 *
 * @return void
 */
	public function login() {
		$this->autoRender = true;
	}

}
