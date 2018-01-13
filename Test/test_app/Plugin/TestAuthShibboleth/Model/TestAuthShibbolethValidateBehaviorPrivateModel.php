<?php
/**
 * AuthShibbolethValidateBehavior::__validateUrl()テスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * AuthShibbolethValidateBehavior::__validateUrl()テスト用Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\test_app\Plugin\TestAuthShibboleth\Model
 */
class TestAuthShibbolethValidateBehaviorPrivateModel extends AppModel {

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'AuthShibboleth.AuthShibbolethValidate'
	);

}
