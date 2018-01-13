<?php
/**
 * View/Elements/settingテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/settingテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\test_app\Plugin\TestAuthShibboleth\Controller
 */
class TestViewElementsSettingController extends AppController {

/**
 * 使用するHelper
 *
 * @var array
 */
	public $helpers = array(
		'SystemManager.SystemManager',
	);

/**
 * setting
 *
 * @return void
 */
	public function setting() {
		$this->autoRender = true;
	}

}
