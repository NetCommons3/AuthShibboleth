<?php
/**
 * Auth Shibboleth
 * これがある事で直接IdPに接続可能になる
 *
 * @author Noriko Arai,Ryuji Masukawa
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
// シボレスのクッキー情報を破棄
if (! empty($_COOKIE)) {
	foreach ($_COOKIE as $name => $value) {
		if (preg_match("/^_shib/iu", $name)) {
			setcookie($name, '', time() - 3600, "/");
		}
	}
}

$url = '/auth_shibboleth/auth_shibboleth/mapping';
//header("Location: " . $url);
//exit;
$_SERVER['REQUEST_URI'] = $url;

//require_once dirname(__FILE__) . '/../index.php';
require_once dirname(__FILE__) . '/../app/webroot/index.php';

