<?php
/**
 * SiteSetting4AuthShibbolethFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */


App::uses('SiteSettingFixture', 'SiteManager.Test/Fixture');

/**
 * SiteSetting4RmAuthShibbolethFixture
 * SiteSetting4testFixture よりコピー
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\AuthShibboleth\Test\Fixture
 */
class SiteSetting4AuthShibbolethFixture extends SiteSettingFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'SiteSetting';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'site_settings';

/**
 * Records
 *
 * @var array
 */
	public $records = array();

/**
 * Initialize the fixture.
 *
 * @return void
 * @see RenameAuthShibbolethSetting
 */
	public function init() {
		require_once CakePlugin::path('AuthShibboleth') . 'Config' . DS . 'Migration' . DS . '1512352000_auth_shibboleth_setting.php';
		$records = (new AuthShibbolethSetting())->records[$this->name];
		$this->records = array_merge($this->records, $records);

		parent::init();
	}

}
