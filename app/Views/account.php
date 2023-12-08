<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<!-- Page Heading -->
<div class="tm-card-header uk-light">
    <div uk-grid class="uk-width-1-1@m uk-flex-middle">
        <div class="uk-width-1-2@m">
            <h3 class="tm-h3">Profil Pengguna</h3>
        </div>
    </div>
</div>
<!-- End Of Page Heading -->

<?= view('Views/Auth/_message_block') ?>

<!-- Content -->
<div class="uk-margin">
    <form class="uk-form-horizontal uk-light" method="post" role="form" action="account/update">
    <input class="uk-input" id="id" name="id" type="hidden" value="<?=$account->id?>">
        <div class="uk-child-width-1-2@m" uk-grid>
            <div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="username">Nama Pengguna</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="username" name="username" type="text" value="<?=$account->username?>">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="email">Email</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="email" name="email" type="email" value="<?=$account->email?>">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="firstname">Nama Depan</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="firstname" name="firstname" type="text" value="<?=$account->firstname?>">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="lastname"></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="lastname" name="lastname" type="text" value="<?=$account->lastname?>">
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title uk-margin-remove">Ubah Kata Sandi</h3>
                        <p class="uk-margin-remove">Ubah Kata Sandi Akun Anda</p>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">
                            <label class="uk-form-label uk-preserve-color" for="oldPass">Kata Sandi Lama</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-preserve-color" id="oldPass" name="oldPass" type="password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label uk-preserve-color" for="newPass">Kata Sandi Baru</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-preserve-color" id="newPass" name="newPass" type="password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label uk-preserve-color" for="newPassConf">Konfirmasi Kata Sandi Baru</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-preserve-color" id="newPassConf" name="newPassConf" type="password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="uk-divider-icon"/>

        <div class="uk-margin uk-text-right">
            <button class="uk-button uk-button-primary uk-button-large uk-preserve-color" type="submit"><?=lang('Global.save')?></button>
        </div>
    </form>
</div>
<!-- End of Content -->
<?= $this->endSection() ?>