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
            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Paket</button>
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
    <form class="uk-margin" id="searchform" action="paket" method="GET">
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
        <form id="searchform" action="paket" method="GET">
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

                <div class="uk-margin-bottom">
                    <label class="uk-form-label" for="product">MDL</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" id="mdlname" name="mdlname">
                    </div>
                </div>

                <div id="listmdl"></div>

                <script type="text/javascript">
                    $(function() {
                        var mcontainer = document.getElementById('listmdl');
                        var mdlList = [
                            <?php foreach ($mdls as $mdl) {
                                echo '{label:"'.$mdl['name'].'", idx:'.$mdl['id'].'},';
                            } ?>
                        ];
                        $("#mdlname").autocomplete({
                            source: mdlList,
                            select: function(e, i) {
                                var elemexist   = document.getElementById('mdlcontainer'+i.item.idx);
                                if ( $( "#mdlcontainer"+i.item.idx ).length ) {
                                    alert('Data Sudah Tersedia');
                                } else {
                                    var mcontainer          = document.getElementById('listmdl');

                                    var mdlcontainer        = document.createElement('div');
                                    mdlcontainer.setAttribute('id', 'mdlcontainer'+i.item.idx);
                                    mdlcontainer.setAttribute('class', 'uk-child-width-1-4 uk-margin-small uk-flex uk-flex-middle');
                                    mdlcontainer.setAttribute('uk-grid', '');

                                    var mdlnamecontainer    = document.createElement('div');

                                    var mdlname             = document.createElement('div');
                                    mdlname.innerHTML       = i.item.label;

                                    var mdlidcontainer      = document.createElement('div');

                                    var mdlid               = document.createElement('input');
                                    mdlid.setAttribute('name', 'mdlid');
                                    mdlid.setAttribute('value', i.item.idx);
                                    mdlid.setAttribute('hidden', '');

                                    var closecontainer      = document.createElement('div');

                                    var closebutton         = document.createElement('a');
                                    closebutton.setAttribute('class', 'uk-text-danger');
                                    closebutton.setAttribute('onclick', 'removeMdl('+i.item.idx+')');
                                    closebutton.setAttribute('uk-icon', 'close');

                                    mdlnamecontainer.appendChild(mdlname);
                                    mdlidcontainer.appendChild(mdlid);
                                    closecontainer.appendChild(closebutton);
                                    mdlcontainer.appendChild(mdlnamecontainer);
                                    mdlcontainer.appendChild(mdlidcontainer);
                                    mdlcontainer.appendChild(closecontainer);
                                    mcontainer.appendChild(mdlcontainer);
                                }
                            },
                            minLength: 2
                        });
                    });
                    
                    function removeMdl(id) {
                        document.getElementById('mdlcontainer'+id).remove();
                    }
                </script>

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