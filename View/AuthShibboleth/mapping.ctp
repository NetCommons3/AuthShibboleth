<?php
/**
 * ログイン関連付け
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/auth_shibboleth/css/style.css');
?>

<div class="container">
	<div class="auth-shibboleth-mapping-title">
		<h2>
			<?php echo __d('auth_shibboleth', 'Do you associate the ID of %s you always use?', SiteSettingUtil::read('App.site_name')); ?>
		</h2>
		<p>
			<?php echo __d('auth_shibboleth', 'You can associate the ID of the specified service with the ID of %s.', SiteSettingUtil::read('App.site_name')); ?>
		</p>
	</div>

	<div class="row auth-shibboleth-mapping-main">

		<?php if (SiteSettingUtil::read('AutoRegist.use_automatic_register', false)) : ?>
		<div class="col-xs-6 auth-shibboleth-mapping-login">
		<?php else : ?>
		<div class="col-xs-12">
		<?php endif; ?>

			<br />
			<p>
				<?php echo __d('auth_shibboleth', 'Please login with the ID of %s.', SiteSettingUtil::read('App.site_name')); ?>
			</p>

			<article>

				<?php echo $this->NetCommonsForm->create('User', array(
						'id' => Inflector::camelize('auth_shibboleth'),
						'url' => array(
							'plugin' => 'auth_shibboleth',
							'controller' => 'auth_shibboleth',
							'action' => 'mapping')
					)
				); ?>

					<?php echo $this->NetCommonsForm->input('username', array(
						'label' => __d('auth', 'Username'),
						'placeholder' => __d('auth', 'Please enter your username.'),
						'required' => true,
						'class' => 'form-control allow-submit',
					)); ?>

					<div class="form-group">
						<div class="clearfix">
							<div class="pull-left">
								<?php echo $this->NetCommonsForm->label('password', __d('auth', 'Password'), array(
									'required' => true,
								)); ?>
							</div>
							<div class="pull-right">
								<?php if ($isMailSend && ! SiteSettingUtil::read('App.close_site') && SiteSettingUtil::read('ForgotPass.use_password_reissue')) : ?>
									<?php echo $this->NetCommonsHtml->link(
										__d('auth', 'Forgot your Password? Please click here.'),
										array('plugin' => 'auth', 'controller' => 'forgot_pass', 'action' => 'request')
									); ?>
								<?php endif; ?>
							</div>
						</div>
						<?php echo $this->NetCommonsForm->input('password', array(
							'placeholder' => __d('auth', 'Please enter your password.'),
							'required' => true,
							'class' => 'form-control allow-submit',
						)); ?>
					</div>

					<button class="btn btn-primary btn-block" type="submit">
						<?php echo __d('auth', 'Login'); ?>
					</button>

				<?php echo $this->NetCommonsForm->end(); ?>

			</article>

		</div>

		<?php if (SiteSettingUtil::read('AutoRegist.use_automatic_register', false)) : ?>
			<div class="col-xs-6">
				<br />
				<p>
					<?php echo __d('auth_shibboleth',
						'Still, if you do not have an account at %s please go to %s.',
						array(SiteSettingUtil::read('App.site_name'), __d('auth', 'Sign up'))); ?>
				</p>

				<a href="/auth_shibboleth/auth_shibboleth_auto_user_regist/request" class="btn btn-default btn-block">
					<?php echo __d('auth', 'Sign up'); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

</div>
