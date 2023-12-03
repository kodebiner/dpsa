<?= $this->extend('Client/layout') ?>

<?= $this->section('main') ?>
<div class="uk-height-1-1 uk-width-1-1 uk-flex uk-flex-middle">
    <div class="uk-width-1-1 uk-inline">

        <!-- Type Picker -->
        <div id="type-picker" class="uk-width-1-1">
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
        <!-- end of Type Picker -->

        <!-- Form Pusat -->
        <form id="form-pusat" class="uk-width-1-1" action="pusat" method="POST">

            <!-- Picker Form Pusat -->
            <div id="picker-form-pusat" class="uk-container" hidden>
                <div class="uk-width-1-1 uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-grid-divider uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <a id="pusat-back-to-type-picker" class="uk-link-reset" uk-icon="icon:chevron-left; ratio:2;"></a>
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
                        Lorem Ipsum
                    </div>
                    <div class="uk-card-footer">
                        <div class="uk-flex-between uk-child-width-auto" uk-grid>
                            <div><a class="uk-button uk-button-secondary back-to-picker-form-pusat"><span class="uk-margin-small-right" uk-icon="arrow-left"></span> Kembali</a></div>
                            <div><a class="uk-button uk-button-primary">Lanjut <span class="uk-margin-small-left" uk-icon="arrow-right"></span></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Company Form Pusat -->

            <!-- Company Search -->
            <!-- end of Company Search -->
        </form>
        <!-- end of Form Pusat -->

        <script>
            document.getElementById('pick-pusat').addEventListener('click', function() {
                document.getElementById('type-picker').setAttribute('hidden', '');
                document.getElementById('picker-form-pusat').removeAttribute('hidden');
            });

            document.getElementById('pusat-back-to-type-picker').addEventListener('click', function() {
                document.getElementById('type-picker').removeAttribute('hidden');
                document.getElementById('picker-form-pusat').setAttribute('hidden', '');
            });

            document.getElementById('to-company-form-pusat').addEventListener('click', function() {
                document.getElementById('company-form-pusat').removeAttribute('hidden');
                document.getElementById('picker-form-pusat').setAttribute('hidden', '');
            });

            var backpusatpicker = document.getElementsByClassName('back-to-picker-form-pusat');
            for (var a = 0; a < backpusatpicker.length; a++) {
                backpusatpicker[a].addEventListener('click', function() {
                document.getElementById('picker-form-pusat').removeAttribute('hidden');
                document.getElementById('company-form-pusat').setAttribute('hidden', '');
                });
            };
        </script>

    </div>
</div>
<?= $this->endSection() ?>