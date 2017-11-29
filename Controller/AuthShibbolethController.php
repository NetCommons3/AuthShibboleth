<?php
/**
 * AuthShibboleth Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AuthShibbolethAppController', 'AuthShibboleth.Controller');

/**
 * AuthShibboleth Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Controller
 */
class AuthShibbolethController extends AuthShibbolethAppController {

/**
 * Other components
 *
 * @var array
 */
	public $components = array(
		'AuthShibboleth.AuthShibboleth',
	);

/**
 * beforeFilter
 *
 * @return void
 **/
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('ds', 'mapping');

		//$here = Router::parse($this->request->here(false));
		//if ($here['action'] === 'mapping') {
			// ログイン画面表示
			//$this->set('plugin', 'auth_general');
			//$this->set('plugin', 'auth_shibboleth');
			//メールを送れるかどうか
			//$this->set('isMailSend', $this->ForgotPass->isMailSendCommon('auth', 'auth'));
		//}
	}

/**
 * 学認Embedded DS表示（URLで直接開く）
 * https://example.com/auth_shibboleth/auth_shibboleth/ds
 *
 * @return void
 **/
	public function ds() {
		$this->view = 'AuthShibboleth.AuthShibboleth/ds';
	}

/**
 * ユーザ紐づけ
 *
 * @return CakeResponse
 **/
	public function mapping() {
		//メールを送れるかどうか
		$this->set('isMailSend', $this->ForgotPass->isMailSendCommon('auth', 'auth'));

		if ($this->request->is('post')) {
			$this->_login();

			//登録処理
			//			$data = $this->data;
			//			$data['Video']['status'] = $this->Workflow->parseStatus();
			//			unset($data['Video']['id']);

			// 登録
			//if ($this->Video->saveVideo($data)) {
				//return $this->redirect(NetCommonsUrl::backToPageUrl());
			//}

			//$this->NetCommons->handleValidationError($this->Video->validationErrors);
		} else {
			//表示処理
			if (! $this->AuthShibboleth->isEntityId()) {
				//$session->setParameter("login_wayf_not_auto_login", _ON);
				//$this->Session->write('AuthShibboleth.loginWayfNotAutoLogin', '1');
				//$url = BASE_URL_HTTPS . "/Shibboleth.sso/Logout?return=" . rawurlencode(BASE_URL);
				//$commonMain->redirectHeader($url, 10, $errStr);
				// secure/index.php からアクセスさせ、Router::url('/', true)で取得すると https://example.com/secure/になる。
				// secure/にNC3がインストールされているわけではないため、一時的にbaseを'/'にする。
				// [まだ] 使い終わったら元に戻すがいいと思う。
				// [懸念点] サブディレクトリにインストールしたNC3でちゃんと動くか調査
				$this->request->base = '/';

				$baseUrl = Router::url('/', true);
				// var_dump($baseUrl);
				$baseUrl2 = $baseUrl . 'auth/login';
				$redirect = $baseUrl . "Shibboleth.sso/Logout?return=" . $baseUrl2;

				// login_error_externalId = "選択した所属機関認証システムから必要な属性情報が得られないため、<br />ログインすることができません。<br /><div style='margin-top:15px;'>選択した所属機関認証システムの管理者にお問い合わせください。</div>"
				// @see https://book.cakephp.org/2.0/ja/development/exceptions.html#cakephp CakePHPの組み込み例外
				// 400
				//throw new BadRequestException(__d('net_commons', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'));
				//  * @throws ForbiddenException 403 entityId取得できず
				// 403
				//throw new ForbiddenException(__d('net_commons', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'));
				// [まだ]これ、ログインエラーと同じにできかなぁ。ログインは登録じゃないけど動きはvalideteでチェックしてるように見えた。
				// リダイレクト参考
				//$this->Auth->loginRedirect = $this->_getDefaultStartPage();
				//return $this->redirect($this->Auth->redirect());

				// メッセージ表示
				$this->NetCommons->setFlashNotification(
					__d('auth_shibboleth', '選択した所属機関認証システムから必要な属性情報が得られないため、ログインすることができません。選択した所属機関認証システムの管理者にお問い合わせください。'),
					array(
						'class' => 'danger',
						'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
					),
					400
				);

				// リダイレクト
				return $this->redirect($redirect);
			}
			// [まだ] secure/index.phpのアクセスで、リダイレクトでちゃんと表示できるようにする

			if ($this->Auth->login()) {
				// ログインしてたら
				$this->_login();
			}
		}
	}

/**
 * ユーザ紐づけ画面のログイン
 *
 * @return CakeResponse
 **/
	protected function _login() {
		//		if (! $this->request->is('post')) {
		//			return $this->throwBadRequest();
		//		}

		//parent::login();
		//CakeLog::debug('[' . __METHOD__ . '] ' . __FILE__ . ' (line ' . __LINE__ . ')');

		//ページタイトル
		//$this->set('pageTitle', __d('auth', 'Login'));

		//メールを送れるかどうか
		// $this->set('isMailSend', $this->ForgotPass->isMailSendCommon('auth', 'auth'));

		//if ($this->request->is('post')) {
		//Auth->login()を実行すると、$this->UserがUsers.UserからModelAppに置き換わってしまい、
		//エラーになるため、変数に保持しておく。
		$User = $this->User;

		$this->Auth->authenticate['all']['scope'] = array(
			'User.status' => '1'
		);

		//$this->__setNc2Authenticate();

		if ($this->Auth->login()) {
			// ユーザ紐づけ


			// user情報更新
			$User->updateLoginTime($this->Auth->user('id'));
			Current::write('User', $this->Auth->user());
			if ($this->Auth->user('language') !== UserAttributeChoice::LANGUAGE_KEY_AUTO) {
				$this->Session->write('Config.language', $this->Auth->user('language'));
			}

			// login_logging_announce = "すべてのブラウザを閉じるまで「ログイン認証済み」となります。<br /><br style='line-height:16px;' />他者が使用する可能性のあるパソコンで接続した場合は、<br />席を立つ前に<span style='text-decoration:underline;'>必ずすべてのブラウザを閉じてください。</span>"
			// メッセージ表示
			$this->NetCommons->setFlashNotification(
				__d('auth_shibboleth', 'すべてのブラウザを閉じるまで「ログイン認証済み」となります。他者が使用する可能性のあるパソコンで接続した場合は、席を立つ前に必ずすべてのブラウザを閉じてください。'),
				array(
					'class' => 'success',
					'interval' => 4000,
				)
			);

			// リダイレクト
			$this->Auth->loginRedirect = $this->_getDefaultStartPage();
			return $this->redirect($this->Auth->redirectUrl());
		}

		$this->NetCommons->setFlashNotification(
			__d('auth', 'Invalid username or password, try again'),
			array(
				'class' => 'danger',
				'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
			),
			400
		);

			//$this->redirect($this->Auth->loginAction);
		//}

		//$this->view = 'AuthShibboleth.AuthShibboleth/mapping';
	}
}
