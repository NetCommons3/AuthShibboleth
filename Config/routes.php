<?php
/**
 * AuthShibboleth routes
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

Router::connect(
	'/auth_shibboleth/auth_auto_user_regist/:action',
	[
		'plugin' => 'auth_shibboleth',
		'controller' => 'auth_shibboleth_auto_user_regist',
	]
);
