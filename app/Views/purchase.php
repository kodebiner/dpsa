<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if (in_groups('superuser',$uid)) { ?>
    <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
        <!-- Page Heading -->
        <?php if ($ismobile === false) { ?>
            <div class="tm-card-header uk-light uk-margin-remove-left">
                <div uk-grid class="uk-flex-middle uk-child-width-1-2">
                    <div>
                        <h3 class="tm-h3">Pesanan Pembelian</h3>
                    </div>

                    <!-- Button Trigger Modal Add -->
                    <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                        <div class="uk-text-right">
                            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Buat Pesanan</button>
                        </div>
                    <?php } ?>
                    <!-- End Of Button Trigger Modal Add -->
                </div>
            </div>
        <?php } else { ?>
            <h3 class="tm-h3 uk-text-center">Daftar MDL</h3>
            <div class="uk-child-width-auto uk-flex-center" uk-grid>
                <!-- Button Trigger Modal Add -->
                <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                    <div>
                        <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Buat Pesanan</button>
                    </div>
                <?php } ?>
                <!-- Button Trigger Modal Add End -->

                <!-- Button Filter -->
                <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
                    <div>
                        <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                    </div>
                <?php } ?>
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
                        <!-- <th>No. Urut</th> -->
                        <th>Detail</th>
                        <th>Nama</th>
                        <th>Panjang</th>
                        <th>Lebar</th>
                        <th>Tinggi</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th class="uk-width-medium">Keterangan</th>
                        <th class="uk-width-small">Harga</th>
                        <th class="uk-width-small">photo</th>
                        <th class="uk-text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($parents as $parent) { ?>
                        <tr>
                            <td><a class="uk-link-reset" id="togglepaket<?= $parent['id'] ?>" uk-toggle="target: .togglepaket<?= $parent['id'] ?>"><span id="closepaket<?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket<?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td>
                            <td colspan="9" class="tm-h3" style="text-transform: uppercase;"><?= $parent['name'] ?></td>
                        </tr>
                        <?php
                        $paketCount = count($mdldata[$parent['id']]['paket']);
                        foreach ($mdldata[$parent['id']]['paket'] as $paket) {
                        ?>
                            <?php
                            if ($idparent === $parent['id']) {
                                $parenthide = '';
                            } else {
                                $parenthide = 'hidden';
                            }

                            if ($idpaket === $paket['id']) {
                                $pakethide = '';
                            } else {
                                $pakethide = 'hidden';
                            }
                            ?>
                            <tr class="togglepaket<?= $parent['id'] ?>" <?=$parenthide?>>
                                <td class="uk-text-right"><a class="uk-link-reset" id="toggle<?= $paket['id'] ?>" uk-toggle="target: .togglemdl<?= $paket['id'] ?>"><span id="close<?= $paket['id'] ?>" uk-icon="chevron-down" hidden></span><span id="open<?= $paket['id'] ?>" uk-icon="chevron-right"></span></a></td>
                                <td colspan="9" class="tm-h4" style="text-transform: uppercase; font-weight: 400;"><?= $paket['name'] ?></td>
                            </tr>
                            <?php
                            $mdlCount = count($paket['mdl']);
                            foreach ($paket['mdl'] as $mdl) { ?>
                                <tr class="togglemdl<?= $paket['id'] ?>" id="togglemdl<?= $mdl['id'] ?>" <?=$pakethide?>>
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
                                    <td class="">
                                        <div uk-lightbox="">
                                            <a class="uk-inline" href="img/mdl/<?=$mdl['photo']?>" role="button">
                                                <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$mdl['photo']?>" width="40" height="40" alt="<?=$mdl['photo']?>">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="uk-text-center">
                                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                            <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                                                <div>
                                                    <a class="uk-icon-button" href="#modalupdatemdl<?= $paket['id'].$mdl['id'] ?>" uk-icon="cart" uk-toggle></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <script>
                                    // Reposiition MDL List
                                    $('#mdlList<?= $paket['id'] ?><?= $mdl['id'] ?>').change(function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: "mdl/reorderingmdl",
                                            data: {
                                                id: <?= $mdl['id'] ?>,
                                                paket: <?= $paket['id'] ?>,
                                                order: $("#mdlList<?= $paket['id'] ?><?= $mdl['id'] ?>").val()
                                            },
                                            dataType: "json",
                                            error: function(mdlOrder) {
                                                console.log('error', arguments);
                                            },
                                            success: function(mdlOrder) {
                                                console.log(mdlOrder);
                                                location.reload();
                                            }
                                        });
                                    });
                                </script>
                            <?php
                            }
                            ?>
                            <script>
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
                                
                                // Reposiition Paket List
                                $('#paketList<?= $paket['id'] ?>').change(function() {
                                    $.ajax({
                                        type: 'POST',
                                        url: "mdl/reorderingpaket",
                                        data: {
                                            id: <?= $paket['id'] ?>,
                                            parent: <?= $parent['id'] ?>,
                                            order: $("#paketList<?= $paket['id'] ?>").val()
                                        },
                                        dataType: "json",
                                        error: function(paketOrder) {
                                            console.log('error', arguments);
                                        },
                                        success: function(paketOrder) {
                                            console.log(paketOrder);
                                            location.reload();
                                        }
                                    });
                                });
                            </script>
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

                            // Reposiition Parent List
                            $('#parentList<?= $parent['id'] ?>').change(function() {
                                $.ajax({
                                    type: 'POST',
                                    url: "mdl/reorderingparent",
                                    data: {
                                        id: <?= $parent['id'] ?>,
                                        order: $("#parentList<?= $parent['id'] ?>").val()
                                    },
                                    dataType: "json",
                                    error: function(parentOrder) {
                                        console.log('error', arguments);
                                    },
                                    success: function(parentOrder) {
                                        console.log(parentOrder);
                                        location.reload();
                                    }
                                });
                            });
                        </script>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- End Table Of Content -->

        <div class="uk-card uk-card-default uk-margin-large-top uk-width-1-1@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">Detail Pesanan</h3>
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <table class="uk-table uk-table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Panjang</th>
                            <th>Lebar</th>
                            <th>Tinggi</th>
                            <th>Volume</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Photo</th>
                            <th>Harga</th>
                            <th>Jumlah Pesanan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>Table Data</td>
                            <td>
                                <div class="uk-margin">
                                    <input class="uk-input uk-form-width-small uk-text-center" type="number" placeholder="1" aria-label="X-Small">
                                </div>
                            </td>
                            <td><a href="" uk-icon="trash"></a></td>
                        </tr>
                    </tbody>
                </table>
                <!-- <div class="uk-text-right">
                    <a href="#" class="uk-button uk-button-primary">Buat Pesanan</a>
                </div> -->
            </div>
            <div class="uk-card-footer uk-text-right">
                <a href="#" class="uk-button uk-button-primary">Buat Pesanan</a>
            </div>
        </div>
    
    <?php }
} ?>
<?= $this->endSection() ?>