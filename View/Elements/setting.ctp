<?php
/**
 * 設定画面（SystemManagerプラグインから参照）
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article>

	<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.auth_type_shibbloth_location', array(
		'type' => 'text',
		'label' => __d('auth_shibboleth', 'AuthShibboleth.auth_type_shibbloth_location'),
		'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.auth_type_shibbloth_location.placeholder'),
		'required' => true,
	)); ?>

	<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.idp_userid', array(
		'type' => 'text',
		'label' => __d('auth_shibboleth', 'AuthShibboleth.idp_userid'),
		'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.idp_userid.placeholder'),
		'help' => __d('auth_shibboleth', 'AuthShibboleth.idp_userid.help'),
		'required' => true,
	)); ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo __d('auth_shibboleth', 'Embedded DS'); ?>
		</div>

		<div class="panel-body">
			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_force_remember_for_session', array(
				'type' => 'radio',
				'options' => array(
					'1' => __d('net_commons', 'Yes'),
					'0' => __d('net_commons', 'No'),
				),
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_force_remember_for_session',
					__d('auth_shibboleth', 'Stay logged in')),
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_URL', array(
				'type' => 'text',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_URL'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_URL.placeholder'),
				'required' => true,
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_sp_entityID', array(
				'type' => 'text',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_sp_entityID'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_sp_entityID.placeholder'),
				'required' => true,
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_sp_handlerURL', array(
				'type' => 'text',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_sp_handlerURL'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_sp_handlerURL.placeholder'),
				'required' => true,
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_return_url', array(
				'type' => 'text',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_return_url'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_return_url.placeholder'),
				'required' => true,
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_discofeed_url', array(
				'type' => 'text',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_discofeed_url'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_discofeed_url.placeholder'),
				'help' => __d('auth_shibboleth', 'AuthShibboleth.wayf_discofeed_url.help'),
			)); ?>

			<?php echo $this->SystemManager->inputCommon('SiteSetting', 'AuthShibboleth.wayf_additional_idps', array(
				'type' => 'textarea',
				'label' => __d('auth_shibboleth', 'AuthShibboleth.wayf_additional_idps'),
				'placeholder' => __d('auth_shibboleth', 'AuthShibboleth.wayf_additional_idps.placeholder'),
			)); ?>
		</div>
	</div>

</article>
