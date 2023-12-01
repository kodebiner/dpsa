<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Page Heading -->
<?php if ($ismobile === false) { ?>
    <div class="tm-card-header uk-light uk-margin-remove-left">
        <div uk-grid class="uk-flex-middle uk-child-width-1-2@m">
            <div>
                <h3 class="tm-h3">Daftar Paket</h3>
            </div>

            <!-- Button Trigger Modal Add -->
            <div class="uk-text-right">
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Paket</button>
            </div>
            <!-- End Of Button Trigger Modal Add -->
        </div>
    </div>
<?php } else { ?>
    <h3 class="tm-h3 uk-text-center">Daftar Paket</h3>
    <div class="uk-child-width-auto uk-flex-center" uk-grid>
        <!-- Button Trigger Modal Add -->
        <div>
            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah MDL</button>
        </div>
        <!-- Button Trigger Modal Add End -->

        <!-- Button Filter -->
        <div>
            <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
        </div>
        <!-- Button Filter End -->
    </div>
<?php } ?>
<!-- End Of Page Heading -->

<!-- Form Filter Input -->
<?php if ($ismobile === false) { ?>
    <form class="uk-margin" id="searchform" action="mdl" method="GET">
        <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
            <div>
                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                    <div>Cari :</div>
                    <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                </div>
            </div>
            <div>
                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                    <div><?= lang('Global.display') ?></div>
                    <div>
                        <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                            <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                            <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                            <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                            <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                        </select>
                    </div>
                    <div><?= lang('Global.perPage') ?></div>
                </div>
            </div>
        </div>
    </form>
<?php } else { ?>
    <div id="filter" class="uk-margin" hidden>
        <form id="searchform" action="mdl" method="GET">
            <div class="uk-margin-small uk-flex uk-flex-center">
                <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
            </div>
            <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                <div><?= lang('Global.display') ?></div>
                <div>
                    <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                        <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                        <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                        <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                        <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                    </select>
                </div>
                <div><?= lang('Global.perPage') ?></div>
            </div>
        </form>
    </div>
<?php } ?>

<!-- Script Form Filter -->
<script>
    document.getElementById('search').addEventListener("change", submitform);
    document.getElementById('perpage').addEventListener("change", submitform);

    function submitform() {
        document.getElementById('searchform').submit();
    };
</script>
<!-- Script Form Filter End -->
<!-- Form Filter Input End -->

<?= view('Views/Auth/_message_block') ?>

<!-- Table Of Content -->
<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-middle uk-table-hover uk-table-divider">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($pakets as $paket) { ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $paket['name'] ?></td>
                    <td><a class="uk-icon-button" href="#modalupdate<?= $paket['id'] ?>" uk-icon="pencil" uk-toggle></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?= $pager ?>
<!-- End Table Of Content -->

<!-- Modal Add Paket -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Tambah Paket</h2>
            <button class="uk-modal-close-default" type="button" uk-close></button>
        </div>

        <div class="uk-modal-body">
            <form class="uk-form-stacked" role="form" action="paket/create" method="post">
                <?= csrf_field() ?>

                <div class="uk-margin-bottom">
                    <label class="uk-form-label" for="name">Nama</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required />
                    </div>
                </div>

                <div id="createPaket" class="uk-margin-bottom">
                    <h4 class="tm-h4 uk-margin-remove">MDL</h4>
                    <div class="uk-text-right">
                        <a onclick="createNewPaket()">+ Tambah MDL Lagi</a>
                    </div>

                    <div id="mdlcontainer0" class="uk-margin-small" uk-grid>
                        <div class="uk-width-5-6">
                            <input class="uk-input" id="mdlname0" required/>
                            <input id="mdlid0" name="mdlid[0]" hidden/>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $(function() {
                            var mdlList = [
                                <?php foreach ($mdls as $mdl) {
                                    echo '{label:"'.$mdl['name'].'", idx:'.$mdl['id'].'},';
                                } ?>
                            ];
                            $("#mdlname0").autocomplete({
                                source: mdlList,
                                select: function(e, i) {
                                    $('#mdlid0').val(i.item.idx);
                                },
                                minLength: 2
                            });
                        });

                        var mdlidx = 0;
                        function createNewPaket() {
                            mdlidx ++;
                            const paketcontainer = document.getElementById('createPaket');

                            const mdlcontainer = document.createElement('div');
                            mdlcontainer.setAttribute('id', 'mdlcontainer'+mdlidx);
                            mdlcontainer.setAttribute('class', 'uk-margin-small');
                            mdlcontainer.setAttribute('uk-grid', '');

                            const formcontainer = document.createElement('div');
                            formcontainer.setAttribute('class', 'uk-width-5-6');

                            const mdlname = document.createElement('input');
                            mdlname.setAttribute('id', 'mdlname'+mdlidx);
                            mdlname.setAttribute('class', 'uk-input');

                            const mdlid = document.createElement('input');
                            mdlid.setAttribute('id', 'mdlid'+mdlidx);
                            mdlid.setAttribute('name', 'mdlid['+mdlidx+']');
                            mdlid.setAttribute('hidden', '');

                            const closecontainer = document.createElement('div');
                            closecontainer.setAttribute('class', 'uk-width-1-6 uk-flex uk-flex-middle');

                            const closebutton = document.createElement('a');
                            closebutton.setAttribute('class', 'uk-text-danger');
                            closebutton.setAttribute('onclick', 'removeMdl('+mdlidx+')');
                            closebutton.setAttribute('uk-icon', 'close');

                            formcontainer.appendChild(mdlname);
                            formcontainer.appendChild(mdlid);
                            closecontainer.appendChild(closebutton);
                            mdlcontainer.appendChild(formcontainer);
                            mdlcontainer.appendChild(closecontainer);
                            paketcontainer.appendChild(mdlcontainer);

                            $(function() {
                                var mdlsList = [
                                    <?php foreach ($mdls as $mdl) {
                                        echo '{label:"'.$mdl['name'].'", idx:'.$mdl['id'].'},';
                                    } ?>
                                ];
                                $("#mdlname"+mdlidx).autocomplete({
                                    source: mdlsList,
                                    select: function(e, i) {
                                        $('#mdlid'+mdlidx).val(i.item.idx);
                                    },
                                    minLength: 2
                                });
                            });
                        };
                                        
                        function removeMdl(i) {
                            mdls = document.getElementById('mdlcontainer'+i);
                            mdls.remove();
                        }
                    </script>
                </div>

                <div class="uk-modal-footer">
                    <div class="uk-text-right">
                        <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add Paket End -->

<!-- Modal Edit Paket -->
<?php foreach ($pakets as $paket) { ?>
    <div id="modalupdate<?= $paket['id'] ?>" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
            <div class="uk-modal-content">
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Ubah Paket</h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="paket/update/<?= $paket['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Paket</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" value="<?= $paket['name']; ?>"/>
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <h4 class="tm-h4 uk-margin-remove">Detail Paket</h4>
                            <div class="uk-h6 uk-margin-remove">
                                <a href="paket/detail/<?= $paket['id']; ?>">Kelola Detail Paket</a>
                            </div>
                        </div>

                        <div class="uk-modal-footer">
                            <div class="uk-child-width-auto uk-flex-right" uk-grid>
                                <div>
                                    <a class="uk-button uk-button-danger" href="paket/delete/<?= $paket['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')" type="button">Hapus</a>
                                </div>
                                <div>
                                    <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Modal Edit Paket End -->
<?= $this->endSection() ?>