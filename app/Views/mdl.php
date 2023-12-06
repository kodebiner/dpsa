<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
    <script src="js/jquery-3.7.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
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
                <th>Harga</th>
                <th class="uk-text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($pakets as $paket) { ?>
                <tr>
                    <td><a class="uk-link-reset" id="toggle<?=$paket['id']?>" uk-toggle="target: .togglemdl<?= $paket['id'] ?>"><span id="close<?=$paket['id']?>" uk-icon="chevron-down" hidden></span><span id="open<?=$paket['id']?>" uk-icon="chevron-right"></span></a></td>
                    <td colspan="7" class="tm-h3" style="text-transform: uppercase;"><?= $paket['name'] ?></td>
                    <td class="uk-text-center">
                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                            <div>
                                <a class="uk-icon-button-success" href="#modaladdmdl<?= $paket['id'] ?>" uk-icon="plus" uk-toggle></a>
                            </div>
                            <div>
                                <a class="uk-icon-button" href="#modalupdate<?= $paket['id'] ?>" uk-icon="pencil" uk-toggle></a>
                            </div>
                        </div>
                    </td>
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
                                }
                                ?>
                            </td>
                            <td><?= $mdl['price'] ?></td>
                            <td class="uk-text-center">
                                <a class="uk-icon-button" href="#modalupdatemdl<?= $mdl['id'] ?>" uk-icon="pencil" uk-toggle></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tr>
                <script>
                    document.getElementById('toggle<?=$paket['id']?>').addEventListener('click', function() {
                        if (document.getElementById('close<?=$paket['id']?>').hasAttribute('hidden')) {
                            document.getElementById('close<?=$paket['id']?>').removeAttribute('hidden');
                            document.getElementById('open<?=$paket['id']?>').setAttribute('hidden', '');
                        } else {
                            document.getElementById('open<?=$paket['id']?>').removeAttribute('hidden');
                            document.getElementById('close<?=$paket['id']?>').setAttribute('hidden', '');
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

                <div id="createMDL" class="uk-margin-bottom">
                    <h4 class="tm-h4 uk-margin-remove">MDL</h4>
                    <div class="uk-text-right"><a onclick="createNewMDL()">+ Tambah Daftar MDL</a></div>
                    <div id="create0" class="uk-margin uk-child-width-1-3" uk-grid>
                        <div id="createMDLName0"><input type="text" class="uk-input" id="mdlName[0]" name="mdlName[0]" placeholder="Nama" required /></div>
                        <div id="createMDLDen0">
                            <select class="uk-select" id="mdlD[0]" name="mdlD[0]" required>
                                <option value="" selected disabled hidden>Pilih Satuan</option>
                                <option value="1">Unit</option>
                                <option value="2">Meter Lari</option>
                                <option value="3">Meter Persegi</option>
                            </select>
                        </div>
                        <div id="createMDLPrice0"><input type="number" class="uk-input" id="mdlP[0]" name="mdlP[0]" placeholder="Harga" required /></div>
                    </div>
                    <div id="dimentions0"></div>
                </div>
                
                <script type="text/javascript">
                    var createCount = 0;
                    document.getElementById('mdlD[0]').addEventListener('change', function() {
                        if (this.value == "2" || this.value == "3") {
                            var elements = document.getElementById('contdim[0]');
                            if (elements) {
                                elements.remove();
                            }
                            var dimentions = document.getElementById('dimentions0');

                            var contdim = document.createElement('div');
                            contdim.setAttribute('id', 'contdim[0]');
                            contdim.setAttribute('class', 'uk-child-width-1-3');
                            contdim.setAttribute('uk-grid', '');

                            var mdlL = document.createElement('div');
                            mdlL.setAttribute('id', 'mdlL[0]');

                            var mdlLInput = document.createElement('input');
                            mdlLInput.setAttribute('type', 'number');
                            mdlLInput.setAttribute('class', 'uk-input');
                            mdlLInput.setAttribute('placeholder', 'Panjang');
                            mdlLInput.setAttribute('id', 'mdlL[0]');
                            mdlLInput.setAttribute('name', 'mdlL[0]');
                            mdlLInput.setAttribute('required', '');

                            var mdlW = document.createElement('div');
                            mdlW.setAttribute('id', 'mdlW[0]');

                            var mdlWInput = document.createElement('input');
                            mdlWInput.setAttribute('type', 'number');
                            mdlWInput.setAttribute('class', 'uk-input');
                            mdlWInput.setAttribute('placeholder', 'Lebar');
                            mdlWInput.setAttribute('id', 'mdlW[0]');
                            mdlWInput.setAttribute('name', 'mdlW[0]');
                            mdlWInput.setAttribute('required', '');

                            var mdlH = document.createElement('div');
                            mdlH.setAttribute('id', 'mdlH[0]');

                            var mdlHInput = document.createElement('input');
                            mdlHInput.setAttribute('type', 'number');
                            mdlHInput.setAttribute('class', 'uk-input');
                            mdlHInput.setAttribute('placeholder', 'Tinggi');
                            mdlHInput.setAttribute('id', 'mdlH[0]');
                            mdlHInput.setAttribute('name', 'mdlH[0]');
                            mdlHInput.setAttribute('required', 'Tinggi')

                            mdlL.appendChild(mdlLInput);
                            contdim.appendChild(mdlL);
                            mdlW.appendChild(mdlWInput);
                            contdim.appendChild(mdlW);
                            mdlH.appendChild(mdlHInput);
                            contdim.appendChild(mdlH);
                            dimentions.appendChild(contdim);
                        } else {
                            var dimentions = document.getElementById('contdim[0]');
                            if (dimentions) {
                                dimentions.remove();
                            }
                        }
                    });

                    function createNewMDL() {
                        createCount++;

                        var createMDL = document.getElementById("createMDL");

                        var divdel = document.createElement('div');
                        divdel.setAttribute('id', 'delete' + createCount);

                        var divider = document.createElement('hr');
                        divider.setAttribute('id', 'divider' + createCount);
                        divider.setAttribute('class', 'uk-margin-top');

                        var newCreateMDL = document.createElement('div');
                        newCreateMDL.setAttribute('id', 'create' + createCount);
                        newCreateMDL.setAttribute('class', 'uk-margin uk-child-width-1-3');
                        newCreateMDL.setAttribute('uk-grid', '');

                        var newCreateDimentions = document.createElement('div');
                        newCreateDimentions.setAttribute('id', 'dimentions' + createCount);

                        var createMDLName = document.createElement('div');
                        createMDLName.setAttribute('id', 'createMDLName' + createCount);

                        var createMDLNameInput = document.createElement('input');
                        createMDLNameInput.setAttribute('type', 'text');
                        createMDLNameInput.setAttribute('class', 'uk-input');
                        createMDLNameInput.setAttribute('placeholder', 'Nama');
                        createMDLNameInput.setAttribute('id', 'mdlName[' + createCount + ']');
                        createMDLNameInput.setAttribute('name', 'mdlName[' + createCount + ']');
                        createMDLNameInput.setAttribute('required', '');

                        var createMDLPrice = document.createElement('div');
                        createMDLPrice.setAttribute('id', 'createMDLPrice' + createCount);

                        var createMDLPriceInput = document.createElement('input');
                        createMDLPriceInput.setAttribute('type', 'number');
                        createMDLPriceInput.setAttribute('class', 'uk-input');
                        createMDLPriceInput.setAttribute('placeholder', 'Harga');
                        createMDLPriceInput.setAttribute('id', 'mdlP[' + createCount + ']');
                        createMDLPriceInput.setAttribute('name', 'mdlP[' + createCount + ']');

                        var createMDLDen = document.createElement('div');
                        createMDLDen.setAttribute('id', 'createMDLDen' + createCount);

                        var createMDLDenInput = document.createElement('select');
                        createMDLDenInput.setAttribute('class', 'uk-select');
                        createMDLDenInput.setAttribute('id', 'mdlD[' + createCount + ']');
                        createMDLDenInput.setAttribute('name', 'mdlD[' + createCount + ']');

                        var option = document.createElement('option');
                        option.setAttribute('value', '');
                        option.setAttribute('selected', '');
                        option.setAttribute('disabled', '');
                        option.setAttribute('hidden', '');
                        option.innerHTML = "Pilih Satuan";

                        var option1 = document.createElement('option');
                        option1.setAttribute('value', '1');
                        option1.innerHTML = "Unit";

                        var option2 = document.createElement('option');
                        option2.setAttribute('value', '2');
                        option2.innerHTML = "Meter Lari";

                        var option3 = document.createElement('option');
                        option3.setAttribute('value', '3');
                        option3.innerHTML = "Meter Persegi";

                        var createRemove = document.createElement('div');
                        createRemove.setAttribute('id', 'remove' + createCount);
                        createRemove.setAttribute('class', 'uk-text-right');

                        var createRemoveButton = document.createElement('button');
                        createRemoveButton.setAttribute('onclick', 'createRemove(' + createCount + ')');
                        createRemoveButton.setAttribute('class', 'uk-button uk-button-danger');
                        createRemoveButton.setAttribute('uk-icon', 'close');

                        createMDLName.appendChild(createMDLNameInput);
                        newCreateMDL.appendChild(createMDLName);
                        createMDLDen.appendChild(createMDLDenInput);
                        newCreateMDL.appendChild(createMDLDen);
                        createMDLPrice.appendChild(createMDLPriceInput);
                        newCreateMDL.appendChild(createMDLPrice);
                        createMDLDenInput.appendChild(option);
                        createMDLDenInput.appendChild(option1);
                        createMDLDenInput.appendChild(option2);
                        createMDLDenInput.appendChild(option3);
                        divdel.appendChild(divider);
                        createRemove.appendChild(createRemoveButton);
                        divdel.appendChild(createRemove);
                        createMDL.appendChild(divdel);
                        createMDL.appendChild(newCreateMDL);
                        createMDL.appendChild(newCreateDimentions);

                        document.getElementById('mdlD[' + createCount + ']').addEventListener('change', function(createCount) {
                            if ((this.value == "2") || (this.value == "3")) {
                                var elements = document.getElementById('contdim[' + createCount + ']');
                                if (elements) {
                                    elements.remove();
                                }
                                var dimentions = document.getElementById('dimentions' + createCount);

                                var contdim = document.createElement('div');
                                contdim.setAttribute('id', 'contdim[' + createCount + ']');
                                contdim.setAttribute('class', 'uk-child-width-1-3 uk-flex-middle');
                                contdim.setAttribute('uk-grid', '');

                                var mdlL = document.createElement('div');
                                mdlL.setAttribute('id', 'mdlL[' + createCount + ']');

                                var mdlLInput = document.createElement('input');
                                mdlLInput.setAttribute('type', 'number');
                                mdlLInput.setAttribute('class', 'uk-input');
                                mdlLInput.setAttribute('placeholder', 'Panjang');
                                mdlLInput.setAttribute('id', 'mdlL[' + createCount + ']');
                                mdlLInput.setAttribute('name', 'mdlL[' + createCount + ']');
                                mdlLInput.setAttribute('required', '');

                                var mdlW = document.createElement('div');
                                mdlW.setAttribute('id', 'mdlW[' + createCount + ']');

                                var mdlWInput = document.createElement('input');
                                mdlWInput.setAttribute('type', 'number');
                                mdlWInput.setAttribute('class', 'uk-input');
                                mdlWInput.setAttribute('placeholder', 'Lebar');
                                mdlWInput.setAttribute('id', 'mdlW[' + createCount + ']');
                                mdlWInput.setAttribute('name', 'mdlW[' + createCount + ']');
                                mdlWInput.setAttribute('required', '');

                                var mdlH = document.createElement('div');
                                mdlH.setAttribute('id', 'mdlH[' + createCount + ']');

                                var mdlHInput = document.createElement('input');
                                mdlHInput.setAttribute('type', 'number');
                                mdlHInput.setAttribute('class', 'uk-input');
                                mdlHInput.setAttribute('placeholder', 'Tinggi');
                                mdlHInput.setAttribute('id', 'mdlH[' + createCount + ']');
                                mdlHInput.setAttribute('name', 'mdlH[' + createCount + ']');
                                mdlHInput.setAttribute('required', 'Tinggi')

                                mdlL.appendChild(mdlLInput);
                                contdim.appendChild(mdlL);
                                mdlW.appendChild(mdlWInput);
                                contdim.appendChild(mdlW);
                                mdlH.appendChild(mdlHInput);
                                contdim.appendChild(mdlH);
                                dimentions.appendChild(contdim);
                            } else {
                                var dim = document.getElementById('contdim[' + createCount + ']');
                                if (dim) {
                                    dim.remove();
                                }
                            }
                        });
                    };

                    function createRemove(i) {
                        var createRemoveElement = document.getElementById('create' + i);
                        var ElementRemoveDim    = document.getElementById('dimentions' + i);
                        var ElementRemoveDel    = document.getElementById('delete' + i);
                        createRemoveElement.remove();
                        ElementRemoveDim.remove();
                        ElementRemoveDel.remove();
                    };
                </script>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add Paket End -->
<?= $this->endSection() ?>