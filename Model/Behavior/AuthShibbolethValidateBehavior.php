<?php
/**
 * AuthShibbolethValidate Behavior
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('SiteSettingValidateBehavior', 'SiteManager.Model/Behavior');

/**
 * AuthShibbolethValidate Behavior
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Model\Behavior
 */
class AuthShibbolethValidateBehavior extends SiteSettingValidateBehavior {

/**
 * shibbolethログイン設定のValidate処理
 * ## 外部認証プラグインのvalitetaは、自動的に読み込み
 * ### ファンクション名ルール
 * validate + [プラグイン名]
 * 例）validateAuthShibboleth()
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @param array $data リクエストデータ配列
 * @return array リクエストデータ
 */
	public function validateAuthShibboleth(Model $model, $data) {
		if (! isset($data[$model->alias]['AuthShibboleth.auth_type_shibbloth_location'])) {
			return $data;
		}

		//ログイン設定
		// * shibbolethログイン
		// ** ウェブサーバに設定したShibboleth認証のロケーション
		$this->_validateRequired($model, $data, 'AuthShibboleth.auth_type_shibbloth_location');

		// **  IdPによる個人識別番号に利用する項目
		$this->_validateRequired($model, $data, 'AuthShibboleth.idp_userid');

		// ** Embedded DS
		// *** ログインしたままにする にチェックを入れて操作させない
		$value = (string)Hash::get($data[$model->alias]['AuthShibboleth.wayf_force_remember_for_session'], '0.value');
		if (! in_array($value, ['0', '1'], true)) {
			$this->_setValidationMessage($model, 'AuthShibboleth.wayf_force_remember_for_session', '0',
				__d('net_commons', 'Invalid request.'));
		}

		// *** WAYF URL
		$this->_validateRequired($model, $data, 'AuthShibboleth.wayf_URL');
		$this->__validateUrl($model, $data, 'AuthShibboleth.wayf_URL');

		// *** エンティティID
		$this->_validateRequired($model, $data, 'AuthShibboleth.wayf_sp_entityID');
		$this->__validateUrl($model, $data, 'AuthShibboleth.wayf_sp_entityID');

		// *** Shibboleth SPのハンドラURL
		$this->_validateRequired($model, $data, 'AuthShibboleth.wayf_sp_handlerURL');
		$this->__validateUrl($model, $data, 'AuthShibboleth.wayf_sp_handlerURL');

		// *** 認証後に開くURL
		$this->_validateRequired($model, $data, 'AuthShibboleth.wayf_return_url');
		$this->__validateUrl($model, $data, 'AuthShibboleth.wayf_return_url');

		// *** DiscpFeed URL
		$this->__validateUrl($model, $data, 'AuthShibboleth.wayf_discofeed_url');

		return $data;
	}

/**
 * サイト設定の必須Validate処理
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @param array $data リクエストデータ配列
 * @param string $key キー
 * @return array リクエストデータ
 */
	private function __validateUrl(Model $model, $data, $key) {
		if (! isset($data[$model->alias][$key])) {
			return $data;
		}
		foreach ($data[$model->alias][$key] as $check) {
			if (! Validation::url($check['value'], true)) {
				$this->_setValidationMessage($model, $key, $check['language_id'],
					sprintf(
						__d('net_commons', 'Unauthorized pattern for %s. Please input the data in %s format.'),
						__d($model->messagePlugin, $key),
						__d($model->messagePlugin, 'URL')
					));
			}
		}
		return $data;
	}

}
