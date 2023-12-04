<?= $this->extend('Client/layout') ?>

<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery-3.7.0.js"></script>
<script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<script>
    const holding = [
        <?php
        foreach ($pusat as $holding) {
            echo '{label:"' . $holding['rsname'] . ' / ' . $holding['ptname'] . '",idx:' . (int)$holding['id'] . '},';
        }
        ?>
    ];
</script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="uk-height-1-1 uk-width-1-1 uk-section">
    <?= view('Views/Auth/_message_block') ?>
    <div class="uk-width-1-1 uk-height-1-1 uk-inline">

        <!-- Type Picker -->
        <div id="type-picker" class="uk-width-1-1 uk-height-1-1 uk-flex uk-flex-middle">
            <div class="uk-width-1-1">
                <div class="uk-h2 uk-text-center">Pilih Tipe Akun</div>
                <div class="uk-child-width-1-4@m uk-flex-center" uk-grid>
                    <div>
                        <a id="pick-pusat" class="uk-link-reset">
                            <div class="uk-card uk-card-primary uk-card-hover uk-card-body uk-h1 uk-text-center">Pusat</div>
                        </a>
                    </div>
                    <div>
                        <a id="pick-cabang" class="uk-link-reset">
                            <div class="uk-card uk-card-primary uk-card-hover uk-card-body uk-h1 uk-text-center">Cabang</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of Type Picker -->

        <!-- Form Pusat -->
        <form id="form-pusat" class="uk-width-1-1 uk-form-horizontal" action="clientreg" method="POST">
            <input name="type" value="0" hidden />

            <!-- Picker Form Pusat -->
            <div id="picker-form-pusat" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-type-picker" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data Perusahaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-text-center">Apakah perusahaan anda sudah terdaftar di aplikasi?</div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="uk-flex-center uk-child-width-auto" uk-grid>
                            <div><a id="to-company-form-pusat" class="uk-button uk-button-secondary">Belum</a></div>
                            <div><a id="to-company-search-pusat" class="uk-button uk-button-primary">Sudah</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Picker Form Pusat -->

            <!-- Company Form Pusat -->
            <div id="company-form-pusat" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-picker-form-pusat" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data Perusahaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="ptname">Nama PT <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="ptname" name="ptname" placeholder="PT Rumah Sakit XXXXXX" value="<?=old('ptname')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="rsname">Nama Alias <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="rsname" name="rsname" placeholder="Rumah Sakit XXXXXX" value="<?=old('rsname')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="address">Alamat</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="address" name="address" placeholder="Jl. Sekitar Rumah Sakit no.1, XXXXXX, XXXXXX, 55XXX" value="<?=old('address')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="npwp">NPWP Perusahaan</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="npwp" name="npwp" placeholder="60.XXX.XXX.X-XXX.XXX" value="<?=old('npwp')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="phone">No. Telp</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="phone" name="phone" placeholder="081XXXXXXXXX" value="<?=old('phone')?>" />
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary back-to-picker-form-pusat"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="company-form-to-user-pusat" type="button" class="uk-button uk-button-primary" disabled>Lanjut <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Company Form Pusat -->

            <!-- Company Search -->
            <div id="company-search-pusat" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-picker-form-pusat" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data Perusahaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">Tuliskan nama perusahaan anda kemudian pilih dari daftar yang muncul. Apabila perusahaan yang anda cari tidak muncul berarti perusahaan tersebut belum terdaftar dan anda perlu mendaftarkan perusahaan anda.</div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="companyid">Nama Perusahaan</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="companyname" name="companyname" placeholder="Rumah Sakit XXXXXX" />
                                <input id="companyid" name="companyid" hidden />
                            </div>
                            <script>
                                $(function() {
                                    $("#companyname").autocomplete({
                                        source: holding,
                                        select: function(e, i) {
                                            $("#companyid").val(i.item.idx);
                                            document.getElementById('company-search-to-user-pusat').removeAttribute('disabled');
                                        },
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary back-to-picker-form-pusat"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="company-search-to-user-pusat" type="button" class="uk-button uk-button-primary" disabled>Lanjut <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Company Search -->

            <!-- User Form Pusat -->
            <div id="user-form-pusat" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a id="user-pusat-back" class="uk-link-reset user-back-pusat" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data Akun</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="firstname">Nama Depan <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="firstname" name="firstname" value="<?=old('firstname')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="lastname">Nama Belakang <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="lastname" name="lastname" value="<?=old('lastname')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="position">Jabatan / Peran di Perusahaan <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="position" name="position" value="<?=old('position')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="username">Nama Pengguna / Username <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="username" name="username" value="<?=old('username')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="email">Email</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="email" id="email" name="email" value="<?=old('email')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password">Password <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="password" id="password" name="password" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="pass_confirm">Ulangi Password <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="password" id="pass_confirm" name="pass_confirm" autocomplete="off" required />
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary user-back-pusat"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="pusat-submit" type="submit" class="uk-button uk-button-primary" disabled>Daftar <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of User Form Pusat -->

        </form>
        <!-- end of Form Pusat -->

        <!-- Form Cabang -->
        <form id="form-cabang" class="uk-width-1-1 uk-form-horizontal" action="clientreg" method="POST">
            <input name="type" value="1" hidden />

            <!-- Company Search Cabang -->
            <div id="company-search-cabang" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-type-picker" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Perusahaan Pusat</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                    <div class="uk-margin">Tuliskan nama perusahaan pusat kemudian pilih dari daftar yang muncul. Apabila perusahaan yang anda cari tidak muncul berarti perusahaan tersebut belum terdaftar.</div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="companyname">Nama Perusahaan Pusat</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="companyname-cabang" name="companyname" placeholder="Rumah Sakit XXXXXX" />
                                <input id="companyid-cabang" name="companyid" hidden />
                            </div>
                            <script>
                                $(function() {
                                    $("#companyname-cabang").autocomplete({
                                        source: holding,
                                        select: function(e, i) {
                                            $("#companyid-cabang").val(i.item.idx);
                                            document.getElementById('to-company-form-cabang').removeAttribute('disabled');
                                        },
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary back-to-type-picker"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="to-company-form-cabang" type="button" class="uk-button uk-button-primary" disabled>Lanjut <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Company Search Cabang -->

            <!-- Company Form Cabang -->
            <div id="company-form-cabang" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-company-search-cabang" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data Perusahaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="ptname-cabang">Nama PT <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="ptname-cabang" name="ptname" placeholder="PT Rumah Sakit XXXXXX" value="<?=old('ptname')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="rsname-cabang">Nama Rumah Sakit <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="rsname-cabang" name="rsname" placeholder="Rumah Sakit XXXXXX" value="<?=old('rsname')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="address-cabang">Alamat <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="address-cabang" name="address" placeholder="Jl. Sekitar Rumah Sakit no.1, XXXXXX, XXXXXX, 55XXX" value="<?=old('address')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="npwp-cabang">NPWP Perusahaan <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="npwp-cabang" name="npwp" placeholder="60.XXX.XXX.X-XXX.XXX" value="<?=old('npwp')?>" />
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary back-to-company-search-cabang"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="to-user-cabang" type="button" class="uk-button uk-button-primary" disabled>Lanjut <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Company Form Cabang -->

            <!-- PIC Form Cabang -->
            <div id="pic-form-cabang" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a class="uk-link-reset back-to-company-form-cabang" uk-icon="icon:chevron-left; ratio:2;"></a>
                            </div>
                            <div class="uk-width-expand">
                                <h2>Data PIC Perusahaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="firstname-cabang">Nama Depan <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="firstname-cabang" name="firstname" value="<?=old('firstname')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="lastname-cabang">Nama Belakang <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="lastname-cabang" name="lastname" value="<?=old('lastname')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="username-cabang">Nama Pengguna / Username <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="username-cabang" name="username" value="<?=old('username')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="email-cabang">Email</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="email" id="email-cabang" name="email" value="<?=old('email')?>" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="phone-cabang">No. Telp <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" id="phone-cabang" name="phone" value="<?=old('phone')?>" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password-cabang">Password <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="password" id="password-cabang" name="password" required />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="pass_confirm-cabang">Ulangi Password <span class="uk-text-danger">*</span></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="password" id="pass_confirm-cabang" name="pass_confirm" required />
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <div class="<?= ($ismobile ? 'uk-flex-center' : 'uk-flex-between') ?> uk-child-width-auto" uk-grid>
                            <div><button type="button" class="uk-button uk-button-secondary back-to-company-form-cabang"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</button></div>
                            <div><button id="cabang-submit" type="submit" class="uk-button uk-button-primary" disabled>Daftar <span class="uk-margin-small-left" uk-icon="arrow-right"></span></button></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of PIC Form Cabang -->

        </form>
        <!-- end of Form Cabang -->

        <!-- Register Journey Script -->
        <script>
            document.getElementById('pick-pusat').addEventListener('click', function() {
                document.getElementById('type-picker').setAttribute('hidden', '');
                document.getElementById('picker-form-pusat').removeAttribute('hidden');
            });
            
            document.getElementById('pick-cabang').addEventListener('click', function() {
                document.getElementById('type-picker').setAttribute('hidden', '');
                document.getElementById('company-search-cabang').removeAttribute('hidden');
            });

            var backtypepicker = document.getElementsByClassName(' back-to-type-picker');
            for (var i = 0; i < backtypepicker.length; i++) {
                backtypepicker[i].addEventListener('click', function() {
                    document.getElementById('type-picker').removeAttribute('hidden');
                    document.getElementById('company-search-cabang').setAttribute('hidden', '');
                    document.getElementById('picker-form-pusat').setAttribute('hidden', '');
                });
            };

            document.getElementById('to-company-form-pusat').addEventListener('click', function() {
                document.getElementById('company-form-pusat').removeAttribute('hidden');
                document.getElementById('picker-form-pusat').setAttribute('hidden', '');
            });

            document.getElementById('to-company-search-pusat').addEventListener('click', function() {
                document.getElementById('company-search-pusat').removeAttribute('hidden');
                document.getElementById('picker-form-pusat').setAttribute('hidden', '');
            });

            var backpusatpicker = document.getElementsByClassName('back-to-picker-form-pusat');
            for (var a = 0; a < backpusatpicker.length; a++) {
                backpusatpicker[a].addEventListener('click', function() {
                    document.getElementById('picker-form-pusat').removeAttribute('hidden');
                    document.getElementById('company-form-pusat').setAttribute('hidden', '');
                    document.getElementById('company-search-pusat').setAttribute('hidden', '');
                    document.getElementById('company-form-cabang').setAttribute('hidden', '');
                });
            };

            document.getElementById('ptname').addEventListener('change', pusatcompanyform);
            document.getElementById('rsname').addEventListener('change', pusatcompanyform);
            function pusatcompanyform() {
                if ((document.getElementById('ptname').value != '') && (document.getElementById('rsname').value != '')) {
                    document.getElementById('company-form-to-user-pusat').removeAttribute('disabled');
                } else {
                    document.getElementById('company-form-to-user-pusat').setAttribute('disabled', '');
                }
            };

            target = '';            

            document.getElementById('company-search-to-user-pusat').addEventListener('click', function() {
                document.getElementById('user-pusat-back').removeAttribute('target');
                document.getElementById('user-pusat-back').setAttribute('target', 'company-search-pusat');
                target = document.getElementById('user-pusat-back').getAttribute('target');
                document.getElementById('user-form-pusat').removeAttribute('hidden');
                document.getElementById('company-search-pusat').setAttribute('hidden', '');
                document.getElementById('ptname').value = '';
                document.getElementById('rsname').value = '';
                document.getElementById('address').value = '';
                document.getElementById('npwp').value = '';
                document.getElementById('phone').value = '';
            });

            document.getElementById('company-form-to-user-pusat').addEventListener('click' , function() {
                document.getElementById('user-pusat-back').removeAttribute('target');
                document.getElementById('user-pusat-back').setAttribute('target', 'company-form-pusat');
                target = document.getElementById('user-pusat-back').getAttribute('target');
                document.getElementById('user-form-pusat').removeAttribute('hidden');
                document.getElementById('company-form-pusat').setAttribute('hidden', '');
                document.getElementById('companyname').value = '';
                document.getElementById('companyid').value = '';
            });
            
            var userpusatbackbutton = document.getElementsByClassName('user-back-pusat');
            for (var b = 0; b < userpusatbackbutton.length; b++) {
                userpusatbackbutton[b].addEventListener('click', userpusatback);
            };
            function userpusatback() {
                document.getElementById('user-form-pusat').setAttribute('hidden', '');
                document.getElementById(target).removeAttribute('hidden');
            };

            document.getElementById('firstname').addEventListener('change', pusatsubmit);
            document.getElementById('lastname').addEventListener('change', pusatsubmit);
            document.getElementById('username').addEventListener('change', pusatsubmit);
            document.getElementById('email').addEventListener('change', pusatsubmit);
            document.getElementById('position').addEventListener('change', pusatsubmit);
            document.getElementById('password').addEventListener('change', pusatsubmit);
            document.getElementById('pass_confirm').addEventListener('change', pusatsubmit);
            function pusatsubmit() {
                if ((document.getElementById('firstname').value != '') && (document.getElementById('lastname').value != '') && (document.getElementById('username').value != '') && (document.getElementById('position').value != '') && (document.getElementById('password').value != '') && (document.getElementById('pass_confirm').value != '')) {
                    document.getElementById('pusat-submit').removeAttribute('disabled');
                } else {
                    document.getElementById('pusat-submit').setAttribute('disabled', '');
                }
            }

            document.getElementById('to-company-form-cabang').addEventListener('click', function() {
                document.getElementById('company-search-cabang').setAttribute('hidden', '');
                document.getElementById('company-form-cabang').removeAttribute('hidden');
            });

            var companycabangbackbutton = document.getElementsByClassName('back-to-company-search-cabang');
            for (var c = 0; c < companycabangbackbutton.length; c++) {
                companycabangbackbutton[c].addEventListener('click', function() {
                    document.getElementById('company-search-cabang').removeAttribute('hidden');
                    document.getElementById('company-form-cabang').setAttribute('hidden', '');
                });
            };

            document.getElementById('ptname-cabang').addEventListener('change', companyformcabangbutton);
            document.getElementById('rsname-cabang').addEventListener('change', companyformcabangbutton);
            document.getElementById('address-cabang').addEventListener('change', companyformcabangbutton);
            document.getElementById('npwp-cabang').addEventListener('change', companyformcabangbutton);
            function companyformcabangbutton() {
                if ((document.getElementById('ptname-cabang').value != '') && (document.getElementById('rsname-cabang').value != '') && (document.getElementById('address-cabang').value != '') && (document.getElementById('npwp-cabang').value != '')) {
                    document.getElementById('to-user-cabang').removeAttribute('disabled');
                } else {
                    document.getElementById('to-user-cabang').setAttribute('disabled', '');
                }
            };

            document.getElementById('to-user-cabang').addEventListener('click', function() {
                document.getElementById('company-form-cabang').setAttribute('hidden', '');
                document.getElementById('pic-form-cabang').removeAttribute('hidden');
            });

            var usercabangbackbutton = document.getElementsByClassName('back-to-company-form-cabang');
            for (var d = 0; d < usercabangbackbutton.length; d++) {
                usercabangbackbutton[d].addEventListener('click', function() {
                    document.getElementById('company-form-cabang').removeAttribute('hidden');
                    document.getElementById('pic-form-cabang').setAttribute('hidden', '');
                });
            };

            document.getElementById('firstname-cabang').addEventListener('change', submitbuttoncabang);
            document.getElementById('lastname-cabang').addEventListener('change', submitbuttoncabang);
            document.getElementById('username-cabang').addEventListener('change', submitbuttoncabang);
            document.getElementById('phone-cabang').addEventListener('change', submitbuttoncabang);
            document.getElementById('password-cabang').addEventListener('change', submitbuttoncabang);
            document.getElementById('pass_confirm-cabang').addEventListener('change', submitbuttoncabang);
            function submitbuttoncabang() {
                if ((document.getElementById('firstname-cabang').value != '') && (document.getElementById('lastname-cabang').value != '') && (document.getElementById('username-cabang').value != '') && (document.getElementById('phone-cabang').value != '') && (document.getElementById('password-cabang').value != '') && (document.getElementById('pass_confirm-cabang').value != '')) {
                    document.getElementById('cabang-submit').removeAttribute('disabled');
                } else {
                    document.getElementById('cabang-submit').setAttribute('disabled', '');
                }
            };
        </script>
        <!-- end of Register Journey Script -->

    </div>
</div>
<?= $this->endSection() ?>