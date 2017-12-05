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

	///**
	// * beforeFilter
	// *
	// * @return void
	// **/
	//	public function beforeFilter() {
	//		parent::beforeFilter();
	//		$this->Auth->allow('entry_key', 'request', 'confirm', 'completion', 'approval', 'acceptance');
	//
	//		//ページタイトル
	//		$this->set('pageTitle', __d('auth', 'Sign up'));
	//
	//		SiteSettingUtil::setup('AutoRegist');
	//		//SiteSettingUtil::setup(array(
	//		//	// * 入会設定
	//		//	// ** 自動会員登録を許可する
	//		//	'AutoRegist.use_automatic_register',
	//		//	// ** アカウント登録の最終決定
	//		//	'AutoRegist.confirmation',
	//		//	// ** 入力キーの使用
	//		//	'AutoRegist.use_secret_key',
	//		//	// ** 入力キー
	//		//	'AutoRegist.secret_key',
	//		//	// ** 自動登録時の権限
	//		//	'AutoRegist.role_key',
	//		//	// ** 自動登録時にデフォルトルームに参加する
	//		//	'AutoRegist.prarticipate_default_room',
	//		//
	//		//	// ** 利用許諾文
	//		//	'AutoRegist.disclaimer',
	//		//	// ** 会員登録承認メールの件名
	//		//	'AutoRegist.approval_mail_subject',
	//		//	// ** 会員登録承認メールの本文
	//		//	'AutoRegist.approval_mail_body',
	//		//	// ** 会員登録受付メールの件名
	//		//	'AutoRegist.acceptance_mail_subject',
	//		//	// ** 会員登録受付メールの本文
	//		//	'AutoRegist.acceptance_mail_body',
	//		//));
	//
	//		if (! SiteSettingUtil::read('AutoRegist.use_automatic_register', false)) {
	//			return $this->setAction('throwBadRequest');
	//		}
	//
	//		if (in_array($this->params['action'], ['approval', 'acceptance'], true) &&
	//				Hash::get($this->request->query, 'activate_key')) {
	//			$this->helpers['NetCommons.Wizard']['navibar'] = Hash::remove(
	//				$this->helpers['NetCommons.Wizard']['navibar'], self::WIZARD_ENTRY_KEY
	//			);
	//		} else {
	//			//管理者の承認が必要の場合、ウィザードの文言変更
	//			$value = SiteSettingUtil::read('AutoRegist.confirmation');
	//			if ($value === AutoUserRegist::CONFIRMATION_ADMIN_APPROVAL) {
	//				$this->helpers['NetCommons.Wizard']['navibar'] = Hash::insert(
	//					$this->helpers['NetCommons.Wizard']['navibar'],
	//					self::WIZARD_COMPLETION . '.label',
	//					array('auth', 'Complete request registration.')
	//				);
	//			}
	//
	//			//入力キーのチェック
	//			$value = SiteSettingUtil::read('AutoRegist.use_secret_key');
	//			if ($value) {
	//				if (! in_array($this->params['action'], ['approval', 'acceptance'], true) &&
	//						! $this->Session->read('AutoUserRegistKey')) {
	//					if ($this->params['action'] === 'entry_key') {
	//						$this->Session->delete('AutoUserRegistKey');
	//						$this->Session->write('AutoUserRegistRedirect', 'request');
	//					} else {
	//						$this->Session->write('AutoUserRegistRedirect', $this->params['action']);
	//					}
	//					$this->setAction('entry_key');
	//				}
	//			} else {
	//				$this->helpers['NetCommons.Wizard']['navibar'] = Hash::remove(
	//					$this->helpers['NetCommons.Wizard']['navibar'], self::WIZARD_ENTRY_KEY
	//				);
	//			}
	//		}
	//	}

	///**
	// * キーの入力
	// *
	// * @return void
	// **/
	//	public function entry_key() {
	//		if ($this->request->is('post')) {
	//			$this->AutoUserRegist->set($this->request->data);
	//			if ($this->AutoUserRegist->validates()) {
	//				$this->Session->write('AutoUserRegistKey', true);
	//				return $this->redirect(
	//					'/auth/auto_user_regist/' . $this->Session->read('AutoUserRegistRedirect')
	//				);
	//			} else {
	//				$this->NetCommons->handleValidationError($this->AutoUserRegist->validationErrors);
	//			}
	//		} else {
	//			if (! SiteSettingUtil::read('AutoRegist.use_secret_key')) {
	//				return $this->redirect(
	//					'/auth/auto_user_regist/' . $this->Session->read('AutoUserRegistRedirect')
	//				);
	//			}
	//		}
	//	}

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
				// ユーザ紐づけ
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

	///**
	// * 新規登録の完了
	// *
	// * @return void
	// **/
	//	public function completion() {
	//		//ウィザードのリンク削除
	//		$this->helpers['NetCommons.Wizard']['navibar'] = Hash::remove(
	//			$this->helpers['NetCommons.Wizard']['navibar'],
	//			'{s}.url'
	//		);
	//
	//		$value = SiteSettingUtil::read('AutoRegist.confirmation');
	//		if ($value === AutoUserRegist::CONFIRMATION_USER_OWN) {
	//			$message = __d('auth', 'Confirmation e-mail will be sent to the registered address, ' .
	//								'after the system administrator approve your registration.');
	//			$redirectUrl = '/';
	//		} elseif ($value === AutoUserRegist::CONFIRMATION_AUTO_REGIST) {
	//			$message = __d('auth', 'Thank you for your registration. Click on the link, please login.');
	//			$redirectUrl = '/auth/auth/login';
	//		} else {
	//			$message = __d('auth', 'Your registration will be confirmed by the system administrator. <br>' .
	//								'When confirmed, it will be notified by e-mail.');
	//			$redirectUrl = '/';
	//		}
	//		$this->set('message', $message);
	//		$this->set('redirectUrl', $redirectUrl);
	//
	//		$userAttributes = $this->AutoUserRegist->getUserAttribures();
	//		$this->set('userAttributes', $userAttributes);
	//
	//		if ($this->Session->read('AutoUserRegist')) {
	//			$this->request->data = $this->Session->read('AutoUserRegist');
	//			$this->Session->delete('AutoUserRegist');
	//		} else {
	//			$this->request->data = array();
	//		}
	//	}

	///**
	// * 本人の登録確認
	// *
	// * @return void
	// **/
	//	public function approval() {
	//		//ウィザードのリンク削除
	//		$this->helpers['NetCommons.Wizard']['navibar'] = Hash::remove(
	//			$this->helpers['NetCommons.Wizard']['navibar'],
	//			'{s}.url'
	//		);
	//
	//		$result = $this->AutoUserRegist->saveUserStatus(
	//			$this->request->query,
	//			AutoUserRegist::CONFIRMATION_USER_OWN
	//		);
	//		if ($result) {
	//			$message = __d('auth', 'Thank you for your registration. Click on the link, please login.');
	//			$this->NetCommons->setFlashNotification($message, array('class' => 'success'));
	//			return $this->redirect('/auth/auth/login');
	//		} else {
	//			$this->view = 'acceptance';
	//			return $this->__setValidationError();
	//		}
	//	}

	///**
	// * 管理者の承認確認
	// *
	// * @return void
	// **/
	//	public function acceptance() {
	//		//ウィザードのリンク削除
	//		$this->helpers['NetCommons.Wizard']['navibar'] = Hash::remove(
	//			$this->helpers['NetCommons.Wizard']['navibar'],
	//			'{s}.url'
	//		);
	//
	//		$this->helpers['NetCommons.Wizard']['navibar'] = Hash::insert(
	//			$this->helpers['NetCommons.Wizard']['navibar'],
	//			self::WIZARD_COMPLETION . '.label',
	//			array('auth', 'Approval completion.')
	//		);
	//
	//		$result = $this->AutoUserRegist->saveUserStatus(
	//			$this->request->query,
	//			AutoUserRegist::CONFIRMATION_ADMIN_APPROVAL
	//		);
	//		if ($result) {
	//			$message = __d('auth', 'hank you for your registration.<br>' .
	//							'We have sent you the registration key to your registered e-mail address.');
	//			$this->set('message', $message);
	//			$this->set('options', array());
	//			$this->set('redirectUrl', '/');
	//
	//			$user = $this->User->find('first', array(
	//				'recursive' => -1,
	//				'conditions' => array('id' => $this->request->query['id'])
	//			));
	//			$user = Hash::merge($user, $result);
	//			$this->AutoUserRegistMail->sendMail(AutoUserRegist::CONFIRMATION_USER_OWN, $user);
	//
	//		} else {
	//			$this->view = 'acceptance';
	//			return $this->__setValidationError();
	//		}
	//	}

	///**
	// * バリデーションエラーメッセージのセット
	// *
	// * @return void
	// */
	//	private function __setValidationError() {
	//		$errorKey = array_keys($this->AutoUserRegist->validationErrors)[0];
	//		if ($errorKey === AutoUserRegist::INVALIDATE_BAD_REQUEST) {
	//			//不正リクエスト
	//			return $this->throwBadRequest();
	//		} elseif ($errorKey === AutoUserRegist::INVALIDATE_CANCELLED_OUT ||
	//					$errorKey === AutoUserRegist::INVALIDATE_ALREADY_ACTIVATED) {
	//			//既に削除された or 既に承認済み
	//			$message = array_shift($this->AutoUserRegist->validationErrors[$errorKey]);
	//			$options = array('class' => 'alert alert-warning');
	//		} else {
	//			//それ以外
	//			$message = __d('auth', 'Your registration was not approved.<br>' .
	//									'Please consult with the system administrator.');
	//			$options = array('class' => 'alert alert-danger');
	//		}
	//
	//		$this->set('redirectUrl', '/');
	//		$this->set('message', $message);
	//		$this->set('options', $options);
	//	}
}
