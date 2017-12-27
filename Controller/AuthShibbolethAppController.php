<?php
/**
 * AuthShibbolethApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthController', 'Auth.Controller');

/**
 * 認証処理
 * ※AuthControllerを継承する
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller
 * @property AuthShibbolethComponent $AuthShibboleth
 * @property ExternalIdpUser $ExternalIdpUser
 * @property User $User
 */
class AuthShibbolethAppController extends AuthController {

}
