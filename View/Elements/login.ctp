<?php
/**
 * 学認Embedded DS表示（Authプラグインから参照）
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/auth_shibboleth/css/style.css');

// [まだ]ログイン設定で切り替え可能にしたいなぁ
$url = array('plugin' => 'auth_shibboleth', 'controller' => 'auth_shibboleth', 'action' => 'discovery');
//$url = SiteSettingUtil::read('AuthShibboleth.wayf_return_url');
?>

<div class="auth-shibboleth-login">
	<?php echo $this->NetCommonsHtml->link(
		__d('auth_shibboleth', 'Login by other ID'),
		$url
	); ?>
</div>
