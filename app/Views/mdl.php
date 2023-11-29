<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
<script src="js/jquery-3.7.0.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<?= $this->endSection() ?>
<?= $this->section('main') ?>

<!-- Page Heading -->
<?php if ($this->data['authorize']->hasPermission('admin.mdl.read', $this->data['uid'])) { ?>
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div uk-grid class="uk-flex-middle">
                <div class="uk-width-1-2@m">
                    <h3 class="tm-h3">Daftar MDL</h3>
                </div>

                <!-- Button Trigger Modal Add -->
                <?php if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) { ?>
                    <div class="uk-width-1-2@m uk-text-right@m">
                        <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah MDL</button>
                    </div>
                <?php } ?>
                <!-- End Of Button Trigger Modal Add -->
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center"><?= lang('Global.clientList') ?></h3>
        <div class="uk-child-width-auto uk-flex-center" uk-grid>
            <div>
                <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
            </div>
            <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                <div>
                    <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah MDL</button>
                </div>
            <?php } ?>
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
                        <div>
                            <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                                <option value="0" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '0') ? 'selected' : '') ?>>satuan</option>
                                <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Unit</option>
                                <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Meter Lari</option>
                                <option value="3" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '3') ? 'selected' : '') ?>>Meter Persegi</option>
                            </select>
                        </div>
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
                    <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                </div>
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                        <option value="0" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '0') ? 'selected' : '') ?>>satuan</option>
                        <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Unit</option>
                        <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Meter Lari</option>
                        <option value="3" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '3') ? 'selected' : '') ?>>Meter Persegi</option>
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
        document.getElementById('rolesearch').addEventListener("change", submitform);
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
                    <th>No</th>
                    <th>Nama</th>
                    <th>Panjang</th>
                    <th>Lebar</th>
                    <th>Tinggi</th>
                    <th>Volume</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($mdls as $mdl) { ?>
                    <tr>
                        <td><?= $i++ ?></td>
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
                            }
                            ?>
                        </td>
                        <td><?= $mdl['price'] ?></td>
                        <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                            <td><a class="uk-icon-button" href="#modalupdate<?= $mdl['id'] ?>" uk-icon="pencil" uk-toggle></a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?= $pager ?>
    <!-- End Table Of Content -->

    <!-- Modal Add MDL -->
    <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
        <div id="modaladd" uk-modal>
            <div class="uk-modal-dialog" uk-overflow-auto>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Tambah MDL</h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="mdl/create" method="post">
                        <?= csrf_field() ?>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Nama</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="denomination">Satuan</label>
                            <select class="uk-select" aria-label="Satuan" id="denomination" name="denomination" required>
                                <option value="" selected disabled hidden>Pilih Satuan</option>
                                <option value="1">Unit</option>
                                <option value="2">Meter Lari</option>
                                <option value="3">Meter Persegi</option>
                            </select>
                        </div>

                        <script>
                            document.getElementById('denomination').addEventListener('change', function() {
                                if (this.value == "2" || this.value == "3") {
                                    var elements = document.getElementById('contdim');
                                    if (elements) {
                                        elements.remove();
                                    }
                                    var dimentions = document.getElementById('dimentions');

                                    var contdim = document.createElement('div');
                                    contdim.setAttribute('id', 'contdim');

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
                                    inputl.setAttribute('type', 'number');
                                    inputl.setAttribute('id', 'length');
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
                                    inputw.setAttribute('type', 'number');
                                    inputw.setAttribute('id', 'width');
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
                                    inputh.setAttribute('type', 'number');
                                    inputh.setAttribute('id', 'height');
                                    inputh.setAttribute('name', 'height');
                                    inputh.setAttribute('placeholder', 'Tinggi');
                                    inputh.setAttribute('required', '');

                                    var contv = document.createElement('div');
                                    contv.setAttribute('class', 'uk-margin');

                                    var labv = document.createElement('label');
                                    labv.setAttribute('class', 'uk-form-label');
                                    labv.setAttribute('for', 'volume');
                                    labv.innerHTML = "Volume";

                                    var coninputv = document.createElement('div');
                                    coninputv.setAttribute('class', 'uk-form-controls');

                                    var inputv = document.createElement('input');
                                    inputv.setAttribute('class', 'uk-input');
                                    inputv.setAttribute('type', 'number');
                                    inputv.setAttribute('id', 'volume');
                                    inputv.setAttribute('name', 'volume');
                                    inputv.setAttribute('placeholder', 'Volume');
                                    inputv.setAttribute('required', '');

                                    coninputl.appendChild(inputl);
                                    coninputw.appendChild(inputw);
                                    coninputh.appendChild(inputh);
                                    coninputv.appendChild(inputv);
                                    contv.appendChild(labv);
                                    contv.appendChild(coninputv);
                                    conth.appendChild(labh);
                                    conth.appendChild(coninputh);
                                    contw.appendChild(labw);
                                    contw.appendChild(coninputw);
                                    contlength.appendChild(lablength);
                                    contlength.appendChild(coninputl);
                                    contdim.appendChild(contlength);
                                    contdim.appendChild(contw);
                                    contdim.appendChild(conth);
                                    contdim.appendChild(contv);
                                    dimentions.appendChild(contdim);
                                } else {
                                    var dimentions = document.getElementById('contdim');
                                    if (dimentions) {
                                        dimentions.remove();
                                    }
                                }
                            });
                        </script>

                        <div id="dimentions"></div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="price">Harga</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="price" name="price" placeholder="Harga" required />
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
    <?php } ?>
    <!-- Modal Add MDL End -->

    <!-- Modal Edit MDL -->
    <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
        <?php foreach ($mdls as $mdl) { ?>
            <div id="modalupdate<?= $mdl['id'] ?>" uk-modal>
                <div class="uk-modal-dialog" uk-overflow-auto>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Ubah MDL</h2>
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
                                    <option value="1" <?php if ($mdl['denomination'] === "1") {
                                                            echo 'selected';
                                                        } ?>>Unit</option>
                                    <option value="2" <?php if ($mdl['denomination'] === "2") {
                                                            echo 'selected';
                                                        } ?>>Meter Lari</option>
                                    <option value="3" <?php if ($mdl['denomination'] === "3") {
                                                            echo 'selected';
                                                        } ?>>Meter Persegi</option>
                                </select>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    if ($("#denominations<?= $mdl['id'] ?>").val() == "1") {
                                        $("#contupmdl<?= $mdl['id'] ?>").attr("hidden", true);
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
                                        <input type="number" class="uk-input" id="length" name="length" value="<?= $mdl['length'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="width">Lebar</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="width" name="width" value="<?= $mdl['width'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="height">Tinggi</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="height" name="height" value="<?= $mdl['height'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="volume">Volume</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="volume" name="volume" value="<?= $mdl['volume'] ?>" />
                                    </div>
                                </div>

                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="price">Harga</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="price" name="price" value="<?= $mdl['price'] ?>" />
                                </div>
                            </div>
                            <div class="uk-modal-footer">
                                <div class="uk-text-right">
                                    <?php if ($authorize->hasPermission('admin.mdl.delete', $uid)) { ?>
                                        <a class="uk-button uk-button-danger" href="mdl/delete/<?= $mdl['id'] ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')" type="button">Hapus</a>
                                    <?php } ?>
                                    <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                                        <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- Modal Edit MDL End -->
<?php } else {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
} ?>


<?= $this->endSection() ?>