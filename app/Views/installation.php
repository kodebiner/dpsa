<!doctype html>
<html dir="ltr "lang="<?=$lang?>" vocab="http://schema.org/" style="overflow-y: hidden;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="<?=base_url();?>">
        <title><?=$title?></title>
        <meta name="description" content="<?=$description?>">
        <meta name="author" content="PT. Kodebiner Teknologi Indonesia">
        <link rel="icon" href="favicon/favicon.ico">
        <link rel="apple-touch-icon" sizes="16x16" href="favicon/apple-icon-16x16.png">
        <link rel="apple-touch-icon" sizes="32x32" href="favicon/apple-icon-32x32.png">
        <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="96x96" href="favicon/apple-icon-96x96.png">
        <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
        <link rel="apple-touch-icon" sizes="192x192" href="favicon/apple-icon-192x192.png">
        <link rel="manifest" href="favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" href="css/theme.css">
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
    </head>
    <body>
        <div class="uk-width-1-1 uk-flex uk-flex-middle uk-flex-center" uk-height-viewport>
            <div class="uk-card uk-card-default">
                <div class="uk-card-header">
                    <h1 class="uk-card-title uk-text-uppercase uk-text-center">Installation</h1>
                </div>
                <form class="uk-form-horizontal" action="installation" method="post">
                    <div class="uk-card-body">
                        <?= view('Views/Auth/_message_block') ?>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="firstname"><?=lang('Global.firstname')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.firstname')) : ?> tm-form-invalid<?php endif ?>" type="text" id="firstname" name="firstname" value="<?= old('firstname') ?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="lastname"><?=lang('Global.lastname')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.lastname')) : ?> tm-form-invalid<?php endif ?>" type="text" id="lastname" name="lastname" value="<?= old('lastname') ?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="username"><?=lang('Auth.username')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.username')) : ?> tm-form-invalid<?php endif ?>" type="text" id="username" name="username" value="<?= old('username') ?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="email"><?=lang('Auth.email')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.email')) : ?> tm-form-invalid<?php endif ?>" type="email" id="email" name="email" value="<?= old('email') ?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password"><?=lang('Auth.password')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.password')) : ?> tm-form-invalid<?php endif ?>" type="password" id="password" name="password" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="pass_confirm"><?=lang('Auth.repeatPassword')?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-width-large<?php if (session('errors.pass_confirm')) : ?> tm-form-invalid<?php endif ?>" type="password" id="pass_confirm" name="pass_confirm" autocomplete="off" required />
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="uk-width-1-1 uk-text-center">
                            <button class="uk-button uk-button-primary" type="submit"><span uk-icon="settings"></span> Install</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
