<?php
/**
 * Migration file
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * AuthShibboleth CakeMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Config\Migration
 */
class AuthShibboleth extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'auth_shibboleth';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'idp_user_profiles' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
					'idp_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => '外部ID連携のID'),
					'email' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'eメール', 'charset' => 'utf8'),
					'profile' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'プロフィール情報 | phpでserializeした内容を登録する', 'charset' => 'utf8'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '最終更新日時'),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '最終更新者'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB', 'comment' => '外部ID連携詳細'),
				),
				'idp_users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
					'idp_userid' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'IdPによる個人識別番号', 'charset' => 'utf8'),
					'is_shib_eptid' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'ePTID(eduPersonTargetedID)かどうか | null：Shibboleth以外  0：ePPN(eduPersonPrincipalName)  1：ePTID(eduPersonTargetedID)'),
					'status' => array('type' => 'integer', 'null' => true, 'default' => '2', 'length' => 4, 'unsigned' => false, 'comment' => '状態 | 0：無効  2：有効'),
					'scope' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2000, 'collate' => 'utf8_general_ci', 'comment' => 'スコープ', 'charset' => 'utf8'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '最終更新日時'),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '最終更新者'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB', 'comment' => '外部ID連携'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'idp_user_profiles', 'idp_users'
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
		return true;
	}
}
