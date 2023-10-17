<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
	<div class="uk-width-1-1">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
						<h3 class="uk-card-title uk-text-center"><?=lang('Auth.loginTitle')?> DPSA</h3>
                        <?= view('Views/Auth/_message_block') ?>
						<form class="uk-form-stacked" action="<?= url_to('login') ?>" method="post">
                            <?= csrf_field() ?>
							<div class="uk-margin">
                                <?php if ($config->validFields === ['email']): ?>
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input type="email" class="uk-input <?php if (session('errors.login')) : ?>tm-form-invalid<?php endif ?>" name="login" placeholder="<?=lang('Auth.username')?>" value="<?= old('login')?>" required>
                                    </div>
                                    <div class="uk-text-small uk-text-italic uk-text-danger">
                                        <?= session('errors.login') ?>
                                    </div>
                                <?php else: ?>
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                        <input type="text" class="uk-input <?php if (session('errors.login')) : ?>tm-form-invalid<?php endif ?>" name="login" placeholder="<?=lang('Auth.emailOrUsername')?>" value="<?= old('login')?>" required>
                                    </div>
                                    <div class="uk-text-small uk-text-italic uk-text-danger">
                                        <?= session('errors.login') ?>
                                    </div>
                                <?php endif; ?>
							</div>
							<div class="uk-margin">
								<div class="uk-inline uk-width-1-1">
									<span class="uk-form-icon" uk-icon="icon: lock"></span>
                                    <input type="password" name="password" class="uk-input  <?php if (session('errors.password')) : ?>tm-form-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>" required>	
								</div>
                                <div class="uk-text-small uk-text-italic uk-text-danger">
                                    <?= session('errors.password') ?>
                                </div>
							</div>
                            <?php if ($config->allowRemembering): ?>
                                <div class="uk-margin">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remember" class="uk-checkbox" <?php if (old('remember')) : ?> checked <?php endif ?>> <?=lang('Auth.rememberMe')?>
                                    </label>
                                </div>
                            <?php endif; ?>
							<div class="uk-margin">
                                <button type="submit" class="uk-button uk-button-primary"><?=lang('Auth.loginAction')?></button>
                            </div>
							<div class="uk-text-small uk-text-center">
                                <?php if (($config->allowRegistration) || ($config->activeResetter)) : ?>
                                    <hr>
                                    <?php if ($config->allowRegistration) : ?>
                                        <div><a href="<?= url_to('register') ?>"><?=lang('Auth.needAnAccount')?></a></div>
                                    <?php endif; ?>
                                    <?php if ($config->activeResetter): ?>
                                        <div><a href="<?= url_to('forgot') ?>"><?=lang('Auth.forgotYourPassword')?></a></div>
                                    <?php endif; ?>
                                <?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection() ?>
