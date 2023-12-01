<?= $this->extend('Client/layout') ?>

<?= $this->section('main') ?>
<div class="uk-height-1-1 uk-width-1-1 uk-inline uk-flex uk-flex-middle">

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
    <form id="form-pusat" action="pusat" method="POST">
        <!-- Company Form Pusat -->
        <div id="company-form-pusat" class="uk-width-1-1 uk-container" hidden>
            <div class="uk-width-1-1 uk-card uk-card-default">
                <div class="uk-card-body">
                    <h3 class="uk-card-title uk-text-center">Data </h3>
                </div>
            </div>
        </div>
        <!-- end of Company Form Pusat -->
    </form>
    <!-- end of Form Pusat -->

    <script>
        document.getElementById('pick-pusat').addEventListener('click', function() {
            document.getElementById('type-picker').setAttribute('hidden', '');
            document.getElementById('company-form-pusat').removeAttribute('hidden');
        });
    </script>
</div>
<?= $this->endSection() ?>