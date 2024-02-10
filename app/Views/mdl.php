<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <link rel="stylesheet" href="css/select2.min.css"/>
    <!-- <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <!-- <script src="js/jquery-3.7.0.js"></script> -->
    <script src="js/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>
<?= $this->endSection() ?>
<?= $this->section('main') ?>

<!-- Page Heading -->
<?php if ($ismobile === false) { ?>
    <div class="tm-card-header uk-light uk-margin-remove-left">
        <div uk-grid class="uk-flex-middle uk-child-width-1-2">
            <div>
                <h3 class="tm-h3">Daftar MDL</h3>
            </div>

            <!-- Button Trigger Modal Add -->
            <div class="uk-text-right">
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Kategori</button>
            </div>
            <!-- End Of Button Trigger Modal Add -->
        </div>
    </div>
<?php } else { ?>
    <h3 class="tm-h3 uk-text-center">Daftar MDL</h3>
    <div class="uk-child-width-auto uk-flex-center" uk-grid>
        <!-- Button Trigger Modal Add -->
        <div>
            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Kategori</button>
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

<!-- form input -->
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

<!-- script form -->
<script>
    document.getElementById('search').addEventListener("change", submitform);
    document.getElementById('perpage').addEventListener("change", submitform);

    function submitform() {
        document.getElementById('searchform').submit();
    };
</script>
<!-- end script form -->

<?= view('Views/Auth/_message_block') ?>

<!-- Table Of Content -->
<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-middle uk-table-large uk-table-hover uk-table-divider">
        <thead>
            <tr>
                <th>Detail</th>
                <th>Nama</th>
                <th>Panjang</th>
                <th>Lebar</th>
                <th>Tinggi</th>
                <th>Volume</th>
                <th>Satuan</th>
                <th class="uk-width-medium">Keterangan</th>
                <th class="uk-width-small">Harga</th>
                <th class="uk-text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($parents as $parent) { ?>
                <tr>
                    <td><a class="uk-link-reset" id="togglepaket<?= $parent['id'] ?>" uk-toggle="target: .togglepaket<?= $parent['id'] ?>"><span id="closepaket<?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket<?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td>
                    <td colspan="8" class="tm-h3" style="text-transform: uppercase;"><?= $parent['name'] ?></td>
                    <td class="uk-text-center">
                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                            <div>
                                <a class="uk-icon-button" href="#modalupdatepaket<?= $parent['id'] ?>" uk-icon="pencil" uk-toggle></a>
                            </div>
                            <div>
                                <a class="uk-icon-button-delete" href="paket/delete/<?= $parent['id'] ?>" uk-icon="trash" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php foreach ($mdldata[$parent['id']]['paket'] as $paket) { ?>
                    <tr class="togglepaket<?= $parent['id'] ?>" hidden>
                        <td class="uk-text-right"><a class="uk-link-reset" id="toggle<?= $paket['id'] ?>" uk-toggle="target: .togglemdl<?= $paket['id'] ?>"><span id="close<?= $paket['id'] ?>" uk-icon="chevron-down" hidden></span><span id="open<?= $paket['id'] ?>" uk-icon="chevron-right"></span></a></td>
                        <td colspan="8" class="tm-h3" style="text-transform: uppercase;"><?= $paket['name'] ?></td>
                        <td class="uk-text-center">
                            <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                <div>
                                    <a class="uk-icon-button-success" href="#modaladdmdl<?= $paket['id'] ?>" uk-icon="plus" uk-toggle></a>
                                </div>
                                <div>
                                    <a class="uk-icon-button" href="#modalupdate<?= $paket['id'] ?>" uk-icon="pencil" uk-toggle></a>
                                </div>
                                <div>
                                    <a class="uk-icon-button-delete" href="paket/delete/<?= $paket['id'] ?>" uk-icon="trash" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="togglemdl<?= $paket['id'] ?>" hidden>
                        <td></td>
                        <td colspan="9">
                            <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                <div>
                                    <button class="uk-button uk-button-primary" uk-toggle="target: #modalimport<?= $paket['id'] ?>">Import MDL</button>
                                </div>
                                <div>
                                    <a class="uk-button uk-button-danger" href="paket/deleteallmdl/<?= $paket['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus semua data MDL?')">Hapus Semua MDL</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php foreach ($paket['mdl'] as $mdl) { ?>
                        <tr class="togglemdl<?= $paket['id'] ?>" hidden>
                            <td></td>
                            <td><?= $mdl['name'] ?></td>
                            <td><?= $mdl['length'] ?></td>
                            <td><?= $mdl['width'] ?></td>
                            <td><?= $mdl['height'] ?></td>
                            <td><?= $mdl['volume'] ?></td>
                            <td>
                                <?php
                                if ($mdl['denomination'] === "1") {
                                    echo "Unit";
                                } elseif ($mdl['denomination'] === "2") {
                                    echo "Meter Lari";
                                } elseif ($mdl['denomination'] === "3") {
                                    echo "Meter Persegi";
                                } elseif ($mdl['denomination'] === "4") {
                                    echo "Set";
                                }
                                ?>
                            </td>
                            <td class=""><?= $mdl['keterangan'] ?></td>
                            <td><?= "Rp. " . number_format((int)$mdl['price'], 0, ',', '.');" "; ?></td>
                            <td class="uk-text-center">
                                <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                    <div>
                                        <a class="uk-icon-button" href="#modalupdatemdl<?= $mdl['id'] ?>" uk-icon="pencil" uk-toggle></a>
                                    </div>
                                    <div>
                                        <form class="uk-form-stacked" role="form" action="mdl/delete/<?= $mdl['id'] ?>" method="post">
                                            <input type="hidden" name="paketid" value="<?= $paket['id']; ?>">
                                            <button type="submit" uk-icon="trash" class="uk-icon-button-delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <script>
                        // Dropdown Paket
                        document.getElementById('togglepaket<?= $parent['id'] ?>').addEventListener('click', function() {
                            if (document.getElementById('closepaket<?= $parent['id'] ?>').hasAttribute('hidden')) {
                                document.getElementById('closepaket<?= $parent['id'] ?>').removeAttribute('hidden');
                                document.getElementById('openpaket<?= $parent['id'] ?>').setAttribute('hidden', '');
                            } else {
                                document.getElementById('openpaket<?= $parent['id'] ?>').removeAttribute('hidden');
                                document.getElementById('closepaket<?= $parent['id'] ?>').setAttribute('hidden', '');

                            }
                        });

                        // Dropdown MDL
                        document.getElementById('toggle<?= $paket['id'] ?>').addEventListener('click', function() {
                            if (document.getElementById('close<?= $paket['id'] ?>').hasAttribute('hidden')) {
                                document.getElementById('close<?= $paket['id'] ?>').removeAttribute('hidden');
                                document.getElementById('open<?= $paket['id'] ?>').setAttribute('hidden', '');
                            } else {
                                document.getElementById('open<?= $paket['id'] ?>').removeAttribute('hidden');
                                document.getElementById('close<?= $paket['id'] ?>').setAttribute('hidden', '');

                            }
                        });
                    </script>
                <?php }
            } ?>
            <tr>
                <td><a class="uk-link-reset" id="toggleuncate" uk-toggle="target: .toggleuncate"><span id="closeuncate" uk-icon="chevron-down" hidden></span><span id="openuncate" uk-icon="chevron-right"></span></a></td>
                <td class="tm-h3" style="text-transform: uppercase;">Belum Terkategori</td>
            </tr>
            <?php foreach ($mdldata['mdluncate'] as $mdluncate) { ?>
                <tr class="toggleuncate" hidden>
                    <td></td>
                    <td><?= $mdluncate['name'] ?></td>
                    <td><?= $mdluncate['length'] ?></td>
                    <td><?= $mdluncate['width'] ?></td>
                    <td><?= $mdluncate['height'] ?></td>
                    <td><?= $mdluncate['volume'] ?></td>
                    <td>
                        <?php
                        if ($mdluncate['denomination'] === "1") {
                            echo "Unit";
                        } elseif ($mdluncate['denomination'] === "2") {
                            echo "Meter Lari";
                        } elseif ($mdluncate['denomination'] === "3") {
                            echo "Meter Persegi";
                        } elseif ($mdluncate['denomination'] === "4") {
                            echo "Set";
                        }
                        ?>
                    </td>
                    <td class=""><?= $mdluncate['keterangan'] ?></td>
                    <td><?= "Rp. " . number_format((int)$mdluncate['price'], 0, ',', '.');" "; ?></td>
                    <td class="uk-text-center">
                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                            <div>
                                <a class="uk-icon-button" href="#modalupdateuncate<?= $mdluncate['id'] ?>" uk-icon="pencil" uk-toggle></a>
                            </div>
                            <div>
                                <form class="uk-form-stacked" role="form" action="mdl/delete/<?= $mdluncate['id'] ?>" method="post">
                                    <button type="submit" uk-icon="trash" class="uk-icon-button-delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- End Table Of Content -->

<!-- Modal Add Paket -->
<div class="uk-modal-container" id="modaladd" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Tambah Kategori</h2>
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

                <div class="uk-margin">
                    <label class="uk-form-label" for="parent kategori">Kategori</label>
                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                        <label><input class="uk-checkbox" type="checkbox" id="kategori" name="parent"> Kategori</label>
                        <label><input class="uk-checkbox" type="checkbox" id="sub kategori"> Sub Kategori</label>
                    </div>
                </div>

                <div class="uk-margin" id="parent" hidden>
                    <label class="uk-form-label" for="parent">Pilih Kategori</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" name="parent">
                            <option value="" selected disabled>Daftar Kategori</option>
                            <?php
                            foreach ($parents as $parent) {
                                if ($parent['parentid'] === '0') {
                                    echo '<option value="' . $parent['id'] . '">' . $parent['name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("input[id='sub kategori']").change(function() {
            if ($(this).is(':checked')) {
                $("input[id='kategori']").prop("checked", false);
                $("#parent").removeAttr("hidden");
            } else {
                $("#parent").attr("hidden", true);
            }
        });
        $("input[id='kategori']").click(function() {
            $("input[id='sub kategori']").prop("checked", false);
            $("#parent").attr("hidden", true);
            $("input[id='kategori']").val("");
        });
    });
</script>
<!-- Modal Add Paket End -->

<?php foreach ($parents as $parent) { ?>
    <!-- Modal Edit Paket -->
    <div class="uk-modal-container" id="modalupdatepaket<?= $parent['id'] ?>" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
            <div class="uk-modal-content">
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Ubah Kategori <?= $parent['name'] ?></h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="paket/update/<?= $parent['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Nama</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" value="<?= $parent['name']; ?>" />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="parent">Tipe Kategori</label>
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                <?php if ($parent['parentid'] != "0") { ?>
                                    <label><input class="uk-checkbox" type="checkbox" id="edit kategori<?= $parent['id'] ?>" name="parent" value="0"> Kategori</label>
                                    <label><input class="uk-checkbox" type="checkbox" id="edit subkategori<?= $parent['id'] ?>" checked> Sub Kategori</label>
                                <?php } else { ?>
                                    <label><input class="uk-checkbox" type="checkbox" id="edit kategori<?= $parent['id'] ?>" name="parent" value="0" checked> Kategori</label>
                                    <label><input class="uk-checkbox" type="checkbox" id="edit subkategori<?= $parent['id'] ?>"> Sub Kategori</label>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="uk-margin" id="parent<?= $parent['id'] ?>" hidden>
                            <label class="uk-form-label" for="parent">Pilih Kategori</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="parent" id="select<?= $parent['id'] ?>">
                                    <option value="" selected disabled>Daftar Kategori</option>
                                    <?php
                                    foreach ($autoparents as $autoparent) {
                                        if ($autoparent['id'] === $parent['id']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . $autoparent['id'] . '" ' . $selected . '>' . $autoparent['name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        
                        <script type="text/javascript">
                            $(document).ready(function() {
                                if ($("input[id='edit subkategori<?= $parent['id'] ?>']").is(':checked')) {
                                    $("#parent<?= $parent['id'] ?>").removeAttr("hidden");
                                    $("#select<?= $parent['id'] ?>").prop("required", true);
                                }

                                $("input[id='edit subkategori<?= $parent['id'] ?>']").change(function() {
                                    if ($(this).is(':checked')) {
                                        $("input[id='edit kategori<?= $parent['id'] ?>']").prop("checked", false);
                                        $("#parent<?= $parent['id'] ?>").removeAttr("hidden");
                                        $("#select<?= $parent['id'] ?>").prop("required", true);
                                    } else {
                                        $("#editparent<?= $parent['id'] ?>").attr("hidden", true);
                                        $("#select<?= $parent['id'] ?>").prop("required", false);
                                        $("#select<?= $parent['id'] ?>").val("0");
                                    }
                                });

                                $("input[id='edit kategori<?= $parent['id'] ?>']").click(function() {
                                    $("input[id='edit subkategori<?= $parent['id'] ?>']").prop("checked", false);
                                    $("#parent<?= $parent['id'] ?>").attr("hidden", true);
                                    $("#select<?= $parent['id'] ?>").prop("required", false);
                                    $("#select<?= $parent['id'] ?>").val("0");
                                });
                            });
                        </script>

                        <div class="uk-modal-footer">
                            <div class="uk-flex-right">
                                <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Paket End -->
    
    <?php foreach ($mdldata[$parent['id']]['paket'] as $paket) { ?>
        <!-- Modal Edit Sub Paket -->
        <div class="uk-modal-container" id="modalupdate<?= $paket['id'] ?>" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Ubah Sub Kategori <?= $paket['name'] ?></h2>
                        <button class="uk-modal-close-default" type="button" uk-close></button>
                    </div>

                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" role="form" action="paket/update/<?= $paket['id'] ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="name">Nama Sub Kategori</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="name" name="name" value="<?= $paket['name']; ?>" />
                                </div>
                            </div>

                            <div class="uk-modal-footer">
                                <div class="uk-flex-right">
                                    <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Sub Paket End -->

        <!-- Modal Add MDL per Sub Paket -->
        <div class="uk-modal-container" id="modaladdmdl<?= $paket['id'] ?>" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Tambah MDL <?= $paket['name'] ?></h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="mdl/create/<?= $paket['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="uk-margin">
                            <label class="uk-form-label" for="findmdl">Cari MDL yang sudah tersedia</label>
                            <select id="mdl-search<?= $paket['id'] ?>" class="js-example-data-array" multiple="multiple" style="width:100%;"></select>
                        </div>

                        <div class="uk-text-right uk-margin">
                            <button class="uk-button uk-button-secondary" type="button" uk-toggle="target: .togglenewmdl<?= $paket['id'] ?>">Buat MDL Baru</button>
                        </div>

                        <script>
                            $("#mdl-search<?= $paket['id'] ?>").select2({
                                placeholder: 'Cari...',
                                minimumInputLength: 3,
                                // allowClear: true,
                                // minimumResultsForSearch: 10,
                                ajax: {
                                    url: 'mdl/datapaket',
                                    dataType: 'json',
                                    type: 'GET',
                                    data: function (term) {
                                        return {
                                            search: term,
                                            paketid: <?=$paket['id']?>
                                        };
                                    },
                                    processResults: function (data) {
                                        console.log(data);
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.text,
                                                    id: item.id
                                                }
                                            })
                                        };
                                    }
                                },
                                // templateResult: renderOption,
                                // escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                                // templateResult: formatRepo, // omitted for brevity, see the source of this page
                                templateSelection: function(mdldata) {
                                    $.ajax({
                                        url: 'mdl/submitcat',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {
                                            paketid: <?=$paket['id']?>,
                                            mdlid: mdldata.id
                                        },
                                        success: function(mdl) {
                                            console.log(mdl);
                                        }
                                    });
                                    $('#mdl-search').select2().val(null).trigger('change');
                                    location.reload();
                                },

                                // data: data,
                                // placeholder: "Select a state",
                                // allowClear: true,
                                // width: 'resolve', // need to override the changed default
                            });
                        </script>

                        <div class="togglenewmdl<?= $paket['id'] ?>" hidden>
                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="name">Nama</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="denomination">Satuan</label>
                                <select class="uk-select" aria-label="Satuan" id="denomination<?= $paket['id'] ?>" name="denomination" required>
                                    <option value="" selected disabled hidden>Pilih Satuan</option>
                                    <option value="1">Unit</option>
                                    <option value="2">Meter Lari</option>
                                    <option value="3">Meter Persegi</option>
                                    <option value="4">Set</option>
                                </select>
                            </div>

                            <div id="dimentions<?= $paket['id'] ?>"></div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="price">Keterangan</label>
                                <div class="uk-margin">
                                    <textarea class="uk-textarea" type="text" name="keterangan" rows="5" placeholder="Keterangan" aria-label="Textarea"></textarea>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="price">Harga</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="price" name="price" placeholder="Harga" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="" data-type="currency" required />
                                </div>
                            </div>

                            <div class="uk-margin" id="image-container-createmdl-<?= $paket['id'] ?>">
                                <div id="image-containermdl-<?= $paket['id'] ?>" class="uk-form-controls">
                                    <input id="photocreatemdl<?= $paket['id'] ?>" name="photo" hidden />
                                    <div id="js-upload-createmdl-<?= $paket['id'] ?>" class="js-upload-createmdl-<?= $paket['id'] ?> uk-placeholder uk-text-center">
                                        <span uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle">Tarik dan lepas foto disini atau</span>
                                        <div uk-form-custom>
                                            <input type="file">
                                            <span class="uk-link uk-preserve-color">pilih satu</span>
                                        </div>
                                    </div>
                                    <progress id="js-progressbar-createmdl-<?= $paket['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                </div>
                            </div>

                            <script>
                                // Denomination
                                document.getElementById('denomination<?= $paket['id'] ?>').addEventListener('change', function() {
                                    if (this.value == "1" || this.value == "2" || this.value == "3" || this.value == "4") {
                                        var elements = document.getElementById('contdim<?= $paket['id'] ?>');
                                        if (elements) {
                                            elements.remove();
                                        }
                                        var dimentions = document.getElementById('dimentions<?= $paket['id'] ?>');

                                        var contdim = document.createElement('div');
                                        contdim.setAttribute('id', 'contdim<?= $paket['id'] ?>');

                                        var contlength = document.createElement('div');
                                        contlength.setAttribute('class', 'uk-margin');

                                        var lablength = document.createElement('label');
                                        lablength.setAttribute('class', 'uk-form-label');
                                        lablength.setAttribute('for', 'length');
                                        lablength.innerHTML = "Panjang";

                                        var coninputl = document.createElement('div');
                                        coninputl.setAttribute('class', 'uk-form-controls');

                                        var inputl = document.createElement('input');
                                        inputl.setAttribute('class', 'uk-input');
                                        inputl.setAttribute('type', 'text');
                                        inputl.setAttribute('id', 'length<?= $paket['id'] ?>');
                                        inputl.setAttribute('name', 'length');
                                        inputl.setAttribute('placeholder', 'Panjang');
                                        inputl.setAttribute('required', '');

                                        var contw = document.createElement('div');
                                        contw.setAttribute('class', 'uk-margin');

                                        var labw = document.createElement('label');
                                        labw.setAttribute('class', 'uk-form-label');
                                        labw.setAttribute('for', 'width');
                                        labw.innerHTML = "Lebar";

                                        var coninputw = document.createElement('div');
                                        coninputw.setAttribute('class', 'uk-form-controls');

                                        var inputw = document.createElement('input');
                                        inputw.setAttribute('class', 'uk-input');
                                        inputw.setAttribute('type', 'text');
                                        inputw.setAttribute('id', 'width<?= $paket['id'] ?>');
                                        inputw.setAttribute('name', 'width');
                                        inputw.setAttribute('placeholder', 'Lebar');
                                        inputw.setAttribute('required', '');

                                        var conth = document.createElement('div');
                                        conth.setAttribute('class', 'uk-margin');

                                        var labh = document.createElement('label');
                                        labh.setAttribute('class', 'uk-form-label');
                                        labh.setAttribute('for', 'height');
                                        labh.innerHTML = "Tinggi";

                                        var coninputh = document.createElement('div');
                                        coninputh.setAttribute('class', 'uk-form-controls');

                                        var inputh = document.createElement('input');
                                        inputh.setAttribute('class', 'uk-input');
                                        inputh.setAttribute('type', 'text');
                                        inputh.setAttribute('id', 'height<?= $paket['id'] ?>');
                                        inputh.setAttribute('name', 'height');
                                        inputh.setAttribute('placeholder', 'Tinggi');
                                        inputh.setAttribute('required', '');

                                        coninputl.appendChild(inputl);
                                        contlength.appendChild(lablength);
                                        contlength.appendChild(coninputl);
                                        contdim.appendChild(contlength);
                                        coninputw.appendChild(inputw);
                                        contw.appendChild(labw);
                                        contw.appendChild(coninputw);
                                        contdim.appendChild(contw);
                                        coninputh.appendChild(inputh);
                                        conth.appendChild(labh);
                                        conth.appendChild(coninputh);
                                        contdim.appendChild(conth);
                                        dimentions.appendChild(contdim);
                                    } else {
                                        var dim = document.getElementById('contdim<?= $paket['id'] ?>');
                                        if (dim) {
                                            dim.remove();
                                        }
                                    }
                                });

                                // Upload Photo MDL
                                var bar = document.getElementById('js-progressbar-createmdl-<?= $paket['id'] ?>');

                                UIkit.upload('.js-upload-createmdl-<?= $paket['id'] ?>', {
                                    url: 'upload/photomdl',
                                    multiple: false,
                                    name: 'uploads',
                                    param: {
                                        lorem: 'ipsum'
                                    },
                                    method: 'POST',
                                    type: 'json',

                                    beforeSend: function() {
                                        console.log('beforeSend', arguments);
                                    },
                                    beforeAll: function() {
                                        console.log('beforeAll', arguments);
                                    },
                                    load: function() {
                                        console.log('load', arguments);
                                    },
                                    error: function() {
                                        console.log('error', arguments);
                                        var error = arguments[0].xhr.response.message.uploads;
                                        alert(error);
                                    },

                                    complete: function() {
                                        console.log('complete', arguments);

                                        var filename = arguments[0].response;

                                        if (document.getElementById('display-container-createmdl-<?= $paket['id'] ?>')) {
                                            document.getElementById('display-container-createmdl-<?= $paket['id'] ?>').remove();
                                        };

                                        document.getElementById('photocreatemdl<?= $paket['id'] ?>').value = filename;

                                        var imgContainer = document.getElementById('image-container-createmdl-<?= $paket['id'] ?>');

                                        var displayContainer = document.createElement('div');
                                        displayContainer.setAttribute('id', 'display-container-createmdl-<?= $paket['id'] ?>');
                                        displayContainer.setAttribute('class', 'uk-inline');

                                        var displayImg = document.createElement('div');
                                        displayImg.setAttribute('uk-lightbox', 'animation: fade');
                                        displayImg.setAttribute('class', 'uk-inline');

                                        var link = document.createElement('a');
                                        link.setAttribute('href', 'img/mdl/' + filename);

                                        var image = document.createElement('img');
                                        image.setAttribute('src', 'img/mdl/' + filename);

                                        var closeContainer = document.createElement('div');
                                        closeContainer.setAttribute('class', 'uk-position-small uk-position-right');

                                        var closeButton = document.createElement('a');
                                        closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                        closeButton.setAttribute('onClick', 'removeImgCreatemdl<?= $paket['id'] ?>()');
                                        closeButton.setAttribute('uk-icon', 'close');

                                        closeContainer.appendChild(closeButton);
                                        displayContainer.appendChild(displayImg);
                                        displayContainer.appendChild(closeContainer);
                                        link.appendChild(image);
                                        displayImg.appendChild(link);
                                        imgContainer.appendChild(displayContainer);

                                        document.getElementById('js-upload-createmdl-<?= $paket['id'] ?>').setAttribute('hidden', '');
                                    },

                                    loadStart: function(e) {
                                        console.log('loadStart', arguments);

                                        bar.removeAttribute('hidden');
                                        bar.max = e.total;
                                        bar.value = e.loaded;
                                    },

                                    progress: function(e) {
                                        console.log('progress', arguments);

                                        bar.max = e.total;
                                        bar.value = e.loaded;
                                    },

                                    loadEnd: function(e) {
                                        console.log('loadEnd', arguments);

                                        bar.max = e.total;
                                        bar.value = e.loaded;
                                    },

                                    completeAll: function() {
                                        console.log('completeAll', arguments);

                                        setTimeout(function() {
                                            bar.setAttribute('hidden', 'hidden');
                                        }, 1000);

                                        alert('Data Berhasil Terunggah');
                                    }
                                });

                                function removeImgCreatemdl<?= $paket['id'] ?>() {
                                    $.ajax({
                                        type: 'post',
                                        url: 'upload/removephotomdl',
                                        data: {
                                            'photo': document.getElementById('photocreatemdl<?= $paket['id'] ?>').value
                                        },
                                        dataType: 'json',

                                        error: function() {
                                            console.log('error', arguments);
                                        },

                                        success: function() {
                                            console.log('success', arguments);

                                            var pesan = arguments[0][1];

                                            document.getElementById('display-container-createmdl-<?= $paket['id'] ?>').remove();
                                            document.getElementById('photocreatemdl<?= $paket['id'] ?>').value = '';

                                            alert(pesan);

                                            document.getElementById('js-upload-createmdl-<?= $paket['id'] ?>').removeAttribute('hidden', '');
                                        }
                                    });
                                };

                                // Currency
                                $("input[data-type='currency']").on({
                                    keyup: function() {
                                        formatCurrency($(this));
                                    },
                                    blur: function() {
                                        formatCurrency($(this), "blur");
                                    }
                                });

                                function formatNumber(n) {
                                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }

                                function formatCurrency(input, blur) {

                                    var input_val = input.val();
                                    if (input_val === "") {
                                        return;
                                    }

                                    var original_len = input_val.length;

                                    var caret_pos = input.prop("selectionStart");

                                    if (input_val.indexOf(".") >= 0) {

                                        var decimal_pos = input_val.indexOf(".");

                                        var left_side = input_val.substring(0, decimal_pos);
                                        var right_side = input_val.substring(decimal_pos);

                                        left_side = formatNumber(left_side);

                                        right_side = formatNumber(right_side);

                                        if (blur === "blur") {
                                            right_side += "00";
                                        }

                                        right_side = right_side.substring(0, 2);

                                        input_val = "Rp" + left_side + "." + right_side;

                                    } else {
                                        input_val = formatNumber(input_val);
                                        input_val = "Rp" + input_val;

                                        if (blur === "blur") {
                                            input_val += ".00";
                                        }
                                    }

                                    input.val(input_val);

                                    var updated_len = input_val.length;
                                    caret_pos = updated_len - original_len + caret_pos;
                                    input[0].setSelectionRange(caret_pos, caret_pos);
                                }
                            </script>

                            <div class="uk-modal-footer">
                                <div class="uk-text-right">
                                    <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Add MDL per Sub Paket End -->

        <!-- Modal Import MDL -->
        <div class="uk-modal-container" id="modalimport<?= $paket['id'] ?>" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Upload File MDL</h2>
                </div>
                <div class="uk-modal-body">
                    <form class="uk-form-stacked" action="upload/importmdl/<?= $paket['id'] ?>" method="post" enctype="multipart/form-data">
                        <div class="uk-margin" id="image-container-importmdl-<?= $paket['id'] ?>">
                            <div class="uk-form-controls">
                                <input id="fileimportmdl<?= $paket['id'] ?>" name="mdl" hidden />
                                <div id="js-upload-importmdl-<?= $paket['id'] ?>" class="js-upload-importmdl-<?= $paket['id'] ?> uk-placeholder uk-text-center">
                                    <span uk-icon="icon: cloud-upload"></span>
                                    <span class="uk-text-middle">Tarik dan lepas file MDL disini atau</span>
                                    <div uk-form-custom>
                                        <input type="file">
                                        <span class="uk-link uk-preserve-color">pilih satu</span>
                                    </div>
                                </div>
                                <progress id="js-progressbar-importmdl-<?= $paket['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                            </div>
                        </div>

                        <!-- Script Import MDL -->
                        <script type="text/javascript">
                            var bar = document.getElementById('js-progressbar-importmdl-<?= $paket['id'] ?>');

                            UIkit.upload('.js-upload-importmdl-<?= $paket['id'] ?>', {
                                url: 'upload/mdl/<?= $paket['id'] ?>',
                                multiple: false,
                                name: 'uploads',
                                param: {
                                    lorem: 'ipsum'
                                },
                                method: 'POST',
                                type: 'json',

                                beforeSend: function() {
                                    console.log('beforeSend', arguments);
                                },
                                beforeAll: function() {
                                    console.log('beforeAll', arguments);
                                },
                                load: function() {
                                    console.log('load', arguments);
                                },
                                error: function() {
                                    console.log('error', arguments);
                                    var error = arguments[0].xhr.response.message.uploads;
                                    alert(error);
                                },

                                complete: function() {
                                    console.log('complete', arguments);
                                },

                                loadStart: function(e) {
                                    console.log('loadStart', arguments);

                                    bar.removeAttribute('hidden');
                                    bar.max = e.total;
                                    bar.value = e.loaded;
                                },

                                progress: function(e) {
                                    console.log('progress', arguments);

                                    bar.max = e.total;
                                    bar.value = e.loaded;
                                },

                                loadEnd: function(e) {
                                    console.log('loadEnd', arguments);

                                    bar.max = e.total;
                                    bar.value = e.loaded;
                                },

                                completeAll: function() {
                                    console.log('completeAll', arguments);

                                    setTimeout(function() {
                                        bar.setAttribute('hidden', 'hidden');
                                    }, 1000);

                                    location.reload();
                                }
                            });
                        </script>
                        <!-- Script Import MDL End -->
                        <!-- <div class="uk-modal-footer uk-text-center">
                            <button class="uk-button uk-button-primary" type="submit">Kirim</button>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Import MDL End -->

        <!-- Modal Update MDL per Sub Paket -->
        <?php foreach ($paket['mdl'] as $mdl) { ?>
            <div class="uk-modal-container" id="modalupdatemdl<?= $mdl['id'] ?>" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Ubah MDL <?= $mdl['name'] ?></h2>
                        <button class="uk-modal-close-default" type="button" uk-close></button>
                    </div>

                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" role="form" action="mdl/update/<?= $mdl['id'] ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="name">Nama</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="name" name="name" value="<?= $mdl['name'] ?>" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="denomination"> Satuan</label>
                                <select class="uk-select" aria-label="Satuan" id="denominations<?= $mdl['id'] ?>" name="denomination" required>
                                    <option value="1" <?php if ($mdl['denomination'] === "1") { echo 'selected'; } ?>>Unit</option>
                                    <option value="2" <?php if ($mdl['denomination'] === "2") { echo 'selected'; } ?>>Meter Lari</option>
                                    <option value="3" <?php if ($mdl['denomination'] === "3") { echo 'selected'; } ?>>Meter Persegi</option>
                                    <option value="4" <?php if ($mdl['denomination'] === "4") { echo 'selected'; } ?>>Set</option>
                                </select>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    if ($("#denominations<?= $mdl['id'] ?>").val() == "1") {
                                        $("#contupmdl<?= $mdl['id'] ?>").attr("hidden", false);
                                    }

                                    $("select[id='denominations<?= $mdl['id'] ?>']").change(function() {
                                        if ((this.value) === "1") {
                                            $('#contupmdl<?= $mdl['id'] ?>').attr("hidden", true);
                                        } else {
                                            $('#contupmdl<?= $mdl['id'] ?>').removeAttr("hidden");
                                        }
                                    });
                                });
                            </script>

                            <div id="contupmdl<?= $mdl['id'] ?>">

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="length">Panjang</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="length" name="length" value="<?= $mdl['length'] ?>" required />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="width">Lebar</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="width" name="width" value="<?= $mdl['width'] ?>" required />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="height">Tinggi</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="height" name="height" value="<?= $mdl['height'] ?>" required />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="volume">Volume</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="volume" name="volume" value="<?= $mdl['volume'] ?>" required />
                                    </div>
                                </div>

                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="price">Harga</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="price" name="price" placeholder="<?php echo "Rp. " . number_format((int)$mdl['price'], 0, ',', '.'); " "; ?>" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="<?= $mdl['price'] ?>" data-type="curencyupdate" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="price">Keterangan</label>
                                <div class="uk-margin">
                                    <textarea class="uk-textarea" type="text" name="keterangan" rows="5" placeholder="<?= $mdl['keterangan'] ?>" value="<?= $mdl['keterangan'] ?>" aria-label="Textarea"><?= $mdl['keterangan'] ?></textarea>
                                </div>
                            </div>

                            <script>
                                $("input[data-type='curencyupdate']").on({
                                    keyup: function() {
                                        formatCurrency($(this));
                                    },
                                    blur: function() {
                                        formatCurrency($(this), "blur");
                                    }
                                });


                                function formatNumber(n) {
                                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }

                                function formatCurrency(input, blur) {

                                    var input_val = input.val();

                                    if (input_val === "") {
                                        return;
                                    }

                                    var original_len = input_val.length;

                                    var caret_pos = input.prop("selectionStart");

                                    if (input_val.indexOf(".") >= 0) {

                                        var decimal_pos = input_val.indexOf(".");

                                        var left_side = input_val.substring(0, decimal_pos);
                                        var right_side = input_val.substring(decimal_pos);

                                        left_side = formatNumber(left_side);

                                        right_side = formatNumber(right_side);

                                        if (blur === "blur") {
                                            right_side += "00";
                                        }

                                        right_side = right_side.substring(0, 2);

                                        input_val = "Rp" + left_side + "." + right_side;

                                    } else {

                                        input_val = formatNumber(input_val);
                                        input_val = "Rp" + input_val;

                                        if (blur === "blur") {
                                            input_val += ".00";
                                        }
                                    }

                                    input.val(input_val);

                                    var updated_len = input_val.length;
                                    caret_pos = updated_len - original_len + caret_pos;
                                    input[0].setSelectionRange(caret_pos, caret_pos);
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
        <?php } ?>
        <!-- Modal Update MDL per Sub Paket End -->
    <?php } ?>
<?php } ?>

<!-- Modal Update MDL Uncategories -->
<?php foreach ($mdldata['mdluncate'] as $mdluncate) { ?>
    <div class="uk-modal-container" id="modalupdateuncate<?= $mdluncate['id'] ?>" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Ubah MDL <?= $mdluncate['name'] ?></h2>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>

            <div class="uk-modal-body">
                <form class="uk-form-stacked" role="form" action="mdl/update/<?= $mdluncate['id'] ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="uk-margin-bottom">
                        <label class="uk-form-label" for="name">Nama</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="name" name="name" value="<?= $mdluncate['name'] ?>" />
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="denomination"> Satuan</label>
                        <select class="uk-select" aria-label="Satuan" id="denominations<?= $mdluncate['id'] ?>" name="denomination" required>
                            <option value="1" <?php if ($mdluncate['denomination'] === "1") { echo 'selected'; } ?>>Unit</option>
                            <option value="2" <?php if ($mdluncate['denomination'] === "2") { echo 'selected'; } ?>>Meter Lari</option>
                            <option value="3" <?php if ($mdluncate['denomination'] === "3") { echo 'selected'; } ?>>Meter Persegi</option>
                            <option value="4" <?php if ($mdluncate['denomination'] === "4") { echo 'selected'; } ?>>Set</option>
                        </select>
                    </div>

                    <script>
                        $(document).ready(function() {
                            if ($("#denominations<?= $mdluncate['id'] ?>").val() == "1") {
                                $("#contupmdl<?= $mdluncate['id'] ?>").attr("hidden", false);
                            }

                            $("select[id='denominations<?= $mdluncate['id'] ?>']").change(function() {
                                if ((this.value) === "1") {
                                    $('#contupmdl<?= $mdluncate['id'] ?>').attr("hidden", true);
                                } else {
                                    $('#contupmdl<?= $mdluncate['id'] ?>').removeAttr("hidden");
                                }
                            });
                        });
                    </script>

                    <div id="contupmdl<?= $mdluncate['id'] ?>">

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="length">Panjang</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="length" name="length" value="<?= $mdluncate['length'] ?>" required />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="width">Lebar</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="width" name="width" value="<?= $mdluncate['width'] ?>" required />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="height">Tinggi</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="height" name="height" value="<?= $mdluncate['height'] ?>" required />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="volume">Volume</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="volume" name="volume" value="<?= $mdluncate['volume'] ?>" required />
                            </div>
                        </div>

                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="price">Harga</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="price" name="price" placeholder="<?php echo 'Rp. ' . number_format((int)$mdluncate['price'], 0, ',', '.'); ' '; ?>" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="<?= $mdluncate['price'] ?>" data-type="curencyupdate" />
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="price">Keterangan</label>
                        <div class="uk-margin">
                            <textarea class="uk-textarea" type="text" name="keterangan" rows="5" placeholder="<?= $mdluncate['keterangan'] ?>" value="<?= $mdluncate['keterangan'] ?>" aria-label="Textarea"><?= $mdluncate['keterangan'] ?></textarea>
                        </div>
                    </div>

                    <script>
                        $("input[data-type='curencyupdate']").on({
                            keyup: function() {
                                formatCurrency($(this));
                            },
                            blur: function() {
                                formatCurrency($(this), "blur");
                            }
                        });


                        function formatNumber(n) {
                            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }

                        function formatCurrency(input, blur) {

                            var input_val = input.val();

                            if (input_val === "") {
                                return;
                            }

                            var original_len = input_val.length;

                            var caret_pos = input.prop("selectionStart");

                            if (input_val.indexOf(".") >= 0) {

                                var decimal_pos = input_val.indexOf(".");

                                var left_side = input_val.substring(0, decimal_pos);
                                var right_side = input_val.substring(decimal_pos);

                                left_side = formatNumber(left_side);

                                right_side = formatNumber(right_side);

                                if (blur === "blur") {
                                    right_side += "00";
                                }

                                right_side = right_side.substring(0, 2);

                                input_val = "Rp" + left_side + "." + right_side;

                            } else {

                                input_val = formatNumber(input_val);
                                input_val = "Rp" + input_val;

                                if (blur === "blur") {
                                    input_val += ".00";
                                }
                            }

                            input.val(input_val);

                            var updated_len = input_val.length;
                            caret_pos = updated_len - original_len + caret_pos;
                            input[0].setSelectionRange(caret_pos, caret_pos);
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
<?php } ?>
<!-- Modal Update MDL per Sub Paket End -->
<?= $this->endSection() ?>