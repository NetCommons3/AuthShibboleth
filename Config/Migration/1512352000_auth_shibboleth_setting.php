<?php
/**
 * Migration file
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * システム管理用データ - ログイン設定＞shibboleth
 *
 * @package NetCommons\AuthShibboleth\Config\Migration
 * @see SiteManagerRecords マイグレーションからコピー
 */
class AuthShibbolethSetting extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'auth_shibboleth_setting';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * recodes
 *
 * @var array $migration
 */
	public $records = array(
		'SiteSetting' => array(
			//ログイン設定
			// * shibbolethログイン
			// ** IdPによる個人識別番号に利用する項目
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.idp_userid',
				'value' => 'eppn',
			),
			// ** Embedded DS
			// *** WAYF URL
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_URL',
				'value' => 'https://ds.gakunin.nii.ac.jp/WAYF',
			),
			// *** エンティティID
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_sp_entityID',
				'value' => 'https://example.com/shibboleth-sp',
				// $this->after()でvalueを置換
			),
			// *** Shibboleth SPのハンドラURL
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_sp_handlerURL',
				'value' => 'https://example.com/Shibboleth.sso',
				// $this->after()でvalueを置換
			),
			// *** 認証後に開くURL
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_return_url',
				'value' => 'https://example.com/secure/index.php',
				// $this->after()でvalueを置換
			),
			// **** ベースURL（認証後のURLを開いた後のリダイレクトに利用します）
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.base_url',
				'value' => 'https://example.com/',
				// $this->after()でvalueを置換
			),
			// *** ログインしたままにする にチェックを入れて操作させない
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_force_remember_for_session',
				'value' => '0',
			),
			// *** DiscpFeed URL
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_discofeed_url',
				'value' => '',
			),
			// *** 他のフェデレーションのIdPを追加する
			array(
				'language_id' => 0,
				'key' => 'AuthShibboleth.wayf_additional_idps',
				'value' => '',
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'down') {
			// [プラグインアンインストール時は、'key' => 'AuthShibboleth.xxxx'を消す
			foreach ($this->records as $model => $records) {
				if (!$this->deleteRecords($model, $records, 'key')) {
					return false;
				}
			}
			return true;
		}

		$baseUrl = Router::url('/', true);
		foreach ($this->records['SiteSetting'] as &$record) {
			$record['value'] = str_replace('https://example.com/', $baseUrl, $record['value']);
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
