<?php
/**
 * 新規登録Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AutoUserRegistController', 'Auth.Controller');

/**
 * 新規登録Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller
 * @property AutoUserRegist $AutoUserRegist
 * @property AuthShibbolethComponent $AuthShibboleth
 * @property NetCommonsComponent $NetCommons
 * @property AutoUserRegistMail $AutoUserRegistMail
 */
class AuthShibbolethAutoUserRegistController extends AutoUserRegistController {

/**
 * Other components
 *
 * @var array
 */
	public $components = array(
		//'Security',
		'AuthShibboleth.AuthShibboleth',
	);

/**
 * 使用するHelpers
 *
 * - [Auth.AutoUserRegistForm](../../Auth/classes/AutoUserRegistForm.html)
 * - [NetCommons.Wizard](../../NetCommons/classes/WizardHelper.html)
 *
 * @var array
 */
	public $helpers = array(
		'Auth.AutoUserRegistForm',
		'NetCommons.Wizard' => array(
			'navibar' => array(
				//				self::WIZARD_ENTRY_KEY => array(
				//					'url' => array(
				//						'controller' => 'auto_user_regist', 'action' => 'entry_key',
				//					),
				//					'label' => array('auth', 'Entry secret key?'),
				//				),
				self::WIZARD_REQUEST => array(
					'url' => array(
						'controller' => 'auth_shibboleth_auto_user_regist', 'action' => 'request',
					),
					'label' => array('auth', 'Registration?'),
				),
				self::WIZARD_CONFIRM => array(
					'url' => array(
						'controller' => 'auth_shibboleth_auto_user_regist', 'action' => 'confirm',
					),
					'label' => array('auth', 'Entry confirm.'),
				),
				//				self::WIZARD_COMPLETION => array(
				//					'url' => array(
				//						'controller' => 'auto_user_regist', 'action' => 'update',
				//					),
				//					'label' => array('auth', 'Complete registration.'),
				//				),
			),
			'cancelUrl' => null
		),
	);

/**
 * 新規登録の受付
 *
 * @return CakeResponse
 * @see AutoUserRegistController::request()  からコピー
 **/
	public function request() {
		if ($this->request->is('post')) {
			$this->request->data['User']['id'] = null;
			if ($this->AutoUserRegist->validateRequest($this->request->data)) {
				$this->Session->write('AutoUserRegist', $this->request->data);
				//return $this->redirect('/auth/auto_user_regist/confirm');
				return $this->redirect('/auth_shibboleth/auth_shibboleth_auto_user_regist/confirm');
			} else {
				$this->NetCommons->handleValidationError($this->AutoUserRegist->validationErrors);
			}
		} else {
			if ($this->Session->read('AutoUserRegist')) {
				$this->request->data = $this->Session->read('AutoUserRegist');
			} else {
				$this->request->data = $this->AutoUserRegist->createUser();
			}
		}

		$userAttributes = $this->AutoUserRegist->getUserAttribures();
		$this->set('userAttributes', $userAttributes);

		$this->view = 'Auth.AutoUserRegist/request';
	}

/**
 * 新規登録の確認
 *
 * @return CakeResponse
 * @see AutoUserRegistController::confirm()  からコピー
 **/
	public function confirm() {
		$this->request->data = $this->Session->read('AutoUserRegist');

		if ($this->request->is('post')) {
			$user = $this->AutoUserRegist->saveAutoUserRegist($this->request->data);
			if ($user) {
				// --- ユーザ紐づけ
				$this->AuthShibboleth->saveUserMapping($user['User']['id']);

				$user = Hash::merge($this->request->data, Hash::remove($user, 'User.password'));
				$this->Session->write('AutoUserRegist', Hash::remove($this->request->data, 'User.password'));

				//メール送信
				$this->AutoUserRegistMail->sendMail(SiteSettingUtil::read('AutoRegist.confirmation'), $user);

				return $this->redirect('/auth/auto_user_regist/completion');
			} else {
				//$this->view = 'request';
				$this->view = 'Auth.AutoUserRegist/request';
				$this->NetCommons->handleValidationError($this->AutoUserRegist->validationErrors);
			}
		}

		$userAttributes = $this->AutoUserRegist->getUserAttribures();
		$this->set('userAttributes', $userAttributes);

		$this->view = 'Auth.AutoUserRegist/confirm';
	}
}
