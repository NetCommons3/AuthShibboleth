<?php
/**
 * 学認Embedded DS表示（URLで直接開く）
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<h2>
	<?php echo __d('auth_shibboleth', 'Login by other ID'); ?>
</h2>

<?php /* DS */ ?>
<?php echo $this->element('AuthShibboleth.AuthShibboleth/ds', array(
	'wayfBorderColor' => '#ddd',	// 枠線あり
));
