<?php
/**
 * ユーザ紐づけ
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="container">
	<div style="border-bottom: solid 1px #ddd;">
		<h2>
			<?php echo __d('auth_shibboleth', 'いつも利用している%sのIDを関連づけますか。', SiteSettingUtil::read('App.site_name')); ?>
		</h2>
		<p>
			<?php echo __d('auth_shibboleth', '指定されたサービスのIDと%sのIDを関連付けることができます。', SiteSettingUtil::read('App.site_name')); ?>
		</p>
	</div>

	<div class="row" style="display: flex; flex-wrap: wrap;">
		<div class="col-xs-6" style="border-right: solid 1px #ddd;">
			<br />
			<p>
				<?php echo __d('auth_shibboleth', '%sのIDでログインしてください。', SiteSettingUtil::read('App.site_name')); ?>
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

				<div >

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
							//'label' => __d('auth', 'Password'),
							'placeholder' => __d('auth', 'Please enter your password.'),
							'required' => true,
							'class' => 'form-control allow-submit',
						)); ?>
					</div>

					<button class="btn btn-primary btn-block" type="submit">
						<?php echo __d('auth', 'Login'); ?>
					</button>

<!--					<hr>-->
<!---->
<!--					--><?php //if ($isMailSend && ! SiteSettingUtil::read('App.close_site') && SiteSettingUtil::read('ForgotPass.use_password_reissue')) : ?>
<!--						<div>-->
<!--							--><?php //echo $this->NetCommonsHtml->link(
//								__d('auth', 'Forgot your Password? Please click here.'),
//								array('plugin' => 'auth', 'controller' => 'forgot_pass', 'action' => 'request')
//							); ?>
<!--						</div>-->
<!--					--><?php //endif; ?>
				</div>
				<?php echo $this->NetCommonsForm->end(); ?>

			</article>

		</div>

		<div class="col-xs-6">
			<br />
			<p>
				<?php echo __d('auth_shibboleth',
					'まだ、%sにアカウントをお持ちでない方は、新規登録にお進みください。',
					SiteSettingUtil::read('App.site_name')); ?>
			</p>

			<a href="/auth_shibboleth/auth_shibboleth_auto_user_regist/request" class="btn btn-default btn-block">
				<?php echo __d('auth_shibboleth', '新規登録'); ?>
			</a>
		</div>
	</div>

</div>
