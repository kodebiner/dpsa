<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-3.7.0.js"></script>
    <script src="js/jquery-ui.js"></script>
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
            <?php foreach ($pakets as $paket) { ?>
                <tr>
                    <td><a class="uk-link-reset" id="toggle<?= $paket['id'] ?>" uk-toggle="target: .togglemdl<?= $paket['id'] ?>"><span id="close<?= $paket['id'] ?>" uk-icon="chevron-down" hidden></span><span id="open<?= $paket['id'] ?>" uk-icon="chevron-right"></span></a></td>
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
                <?php foreach ($mdls[$paket['id']] as $mdl) { ?>
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
                                    <a class="uk-icon-button-delete" href="mdl/delete/<?= $mdl['id'] ?>" uk-icon="trash" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <script>
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

<?php foreach ($pakets as $paket) { ?>
    <!-- Modal Edit Paket -->
    <div class="uk-modal-container" id="modalupdate<?= $paket['id'] ?>" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
            <div class="uk-modal-content">
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Ubah Paket <?= $paket['name'] ?></h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="paket/update/<?= $paket['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Nama</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" value="<?= $paket['name']; ?>" />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="parent kategori">Kategori</label>
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                <label><input class="uk-checkbox" type="checkbox" id="kategori" name="parent">Kategori</label>
                                <label><input class="uk-checkbox" type="checkbox" id="sub kategori">Sub Kategori</label>
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

    <!-- Modal Add MDL per Paket -->
    <div class="uk-modal-container" id="modaladdmdl<?= $paket['id'] ?>" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Tambah MDL <?= $paket['name'] ?></h2>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>

            <div class="uk-modal-body">
                <form class="uk-form-stacked" role="form" action="mdl/create/<?= $paket['id'] ?>" method="post">
                    <?= csrf_field() ?>

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

                    <script>
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
                    </script>

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

                    <script>
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
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Add MDL per Paket End -->

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

    <!-- Modal Update MDL per Paket -->
    <?php foreach ($mdls[$paket['id']] as $mdl) { ?>
        <div id="modalupdatemdl<?= $mdl['id'] ?>" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Ubah MDL <?= $mdl['name'] ?></h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="mdl/update/<?= $mdl['id'] ?>" method="post">
                        <?= csrf_field() ?>
                        <input hidden type="text" class="uk-input" id="paketid" name="paketid" value="<?= $mdl['paketid']; ?>" />

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
    <!-- Modal Update MDL per Paket End -->
<?php } ?>
<?= $this->endSection() ?>