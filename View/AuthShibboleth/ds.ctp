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
	<?php echo __d('auth_shibboleth', '他の機関のIdPによるShibbolethログイン'); ?>
</h2>

<?php /* DS説明 */ ?>
<!--<div class="well well-sm">-->
<!--	--><?php //echo __d('auth_shibboleth', 'AuthShibboleth.ds_description'); ?>
<!--</div>-->

<?php /* DS */ ?>
<?php echo $this->element('AuthShibboleth.AuthShibboleth/ds', array(
	'wayfBorderColor' => '#ddd',	// 枠線あり
));