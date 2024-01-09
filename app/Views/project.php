<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
    <?= $this->section('main') ?>
    <?php if ($ismobile === true) { ?>
        <h3 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h3>
        <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
            <div class="uk-text-center uk-margin">
                <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="uk-margin uk-child-width-auto uk-flex-between" uk-grid>
            <div>
                <h3 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h3>
            </div>
            <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                <div>
                    <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
                </div>
            <?php } ?>
        </div>
        <?= view('Views/Auth/_message_block') ?>
    <?php } ?>
    <hr class="uk-divider-icon uk-margin-remove-top">

    <div class="uk-container uk-container-large">
        <?php foreach ($projects as $project) { ?>
            <?php if ($ismobile === true) { ?>
                <div class="uk-card uk-card-default uk-width-1-1 uk-margin">
                    <div class="uk-card-header">
                        <div class="uk-flex-middle uk-grid-small" uk-grid>
                            <div class="uk-width-3-4">
                                <h3 class="uk-card-title">
                                    <?php foreach ($company as $comp) {
                                        if ($comp['id'] === $project['clientid']) {
                                            $klien = $comp['rsname'];
                                        }
                                    }
                                    ?>
                                    <span> <?= $project['name'] . " - " . $klien ?></span>
                                </h3>
                            </div>
                            <div class="uk-width-1-4 uk-text-center">
                                <button id="openproject<?= $project['id'] ?>" class="uk-icon-button uk-button-secondary" uk-icon="chevron-down" uk-toggle="target: #bodyproject<?= $project['id'] ?>"></button>
                                <button id="closeproject<?= $project['id'] ?>" class="uk-icon-button uk-button-secondary" uk-icon="chevron-up" hidden uk-toggle="target: #bodyproject<?= $project['id'] ?>"></button>
                            </div>
                        </div>
                    </div>
                    <div id="bodyproject<?= $project['id'] ?>" hidden>
                        <div class="uk-card-body">
                            <div class="uk-grid-divider uk-grid-small" uk-grid uk-height-match="target: > div > .match-height">
                                <div class="uk-width-1-1">
                                    <h4 class="match-height">Detail Pesanan</h4>
                                    <p><?= $project['brief'] ?></p>
                                </div>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center match-height">Status</h3>
                                        <div class="uk-text-center">
                                            <?php
                                            if ($project['status'] === "1") {
                                                echo "Proses Desain";
                                            } elseif ($project['status'] === "2") {
                                                echo "Menunggu Approval Desain";
                                            } elseif ($project['status'] === "3") {
                                                echo "Pengajuan RAB";
                                            } elseif ($project['status'] === "4") {
                                                echo "Dalam Proses Produksi";
                                            } elseif ($project['status'] === "5") {
                                                echo "Setting";
                                            }
                                            ?>
                                        </div>
                                </div>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center match-height">Progress Proyek</h3>
                                        <div class="uk-text-center">
                                            <?php
                                            if ($project['status'] === "1") {
                                                echo "5 %";
                                            } elseif ($project['status'] === "2") {
                                                echo "10 %";
                                            } elseif ($project['status'] === "3") {
                                                echo "20 %";
                                            } elseif ($project['status'] === "4") {
                                                if ($project['production'] === "0") {
                                                    $persentasi = "30";
                                                } else {
                                                    $qty = round($project['production'] / 100 * 65, 2);
                                                    $persentasi = 30 + $qty;
                                                }
                                                echo "$persentasi %";
                                            } elseif ($project['status'] === "5") {
                                                echo "100%";
                                            }
                                            ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($authorize->hasPermission('admin.project.edit', $uid)) { ?>
                            <div class="uk-card-footer uk-text-center">
                                <a class="uk-button uk-button-secondary" href="#modalupdatepro<?= $project['id'] ?>" uk-toggle><?= lang('Global.updateData') ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <script>
                    document.getElementById('openproject<?= $project['id'] ?>').addEventListener('click', opentoggle<?= $project['id'] ?>);
                    document.getElementById('closeproject<?= $project['id'] ?>').addEventListener('click', closetoggle<?= $project['id'] ?>);

                    function opentoggle<?= $project['id'] ?>() {
                        document.getElementById('openproject<?= $project['id'] ?>').setAttribute('hidden', '');
                        document.getElementById('closeproject<?= $project['id'] ?>').removeAttribute('hidden');
                    };

                    function closetoggle<?= $project['id'] ?>() {
                        document.getElementById('openproject<?= $project['id'] ?>').removeAttribute('hidden');
                        document.getElementById('closeproject<?= $project['id'] ?>').setAttribute('hidden', '');
                    };
                </script>
            <?php } else { ?>
                <div class="uk-card uk-card-default uk-width-1-1 uk-margin">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <span uk-icon="icon: home; ratio: 1.5"></span>
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="tm-h1">
                                    <?php foreach ($company as $comp) {
                                        if ($comp['id'] === $project['clientid']) {
                                            $klien = $comp['rsname'];
                                        }
                                    }
                                    ?>
                                    <span class="tm-h2"><?= $project['name'] . " - " . $klien ?> </span>
                                </h3>
                            </div>
                            <?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
                                <div class="uk-text-right uk-width-auto">
                                    <a class="uk-button uk-button-secondary uk-margin-small-right" href="#modalupdatepro<?= $project['id'] ?>" uk-toggle><?= lang('Global.updateData') ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <h4 class="tm-h1">Detail Pesanan</h4>
                        <p><?= $project['brief'] ?></p>
                    </div>
                    <hr class="uk-divider-icon">
                    <div class="uk-grid-divider uk-child-width-1-2@m" uk-grid>
                        <div>
                            <div class="uk-text-center">
                                <h3 class="tm-h4"><span uk-icon="icon: list; ratio: 1"></span> Status</h3>
                                <p>
                                    <?php if ($project['status'] === "1") {
                                        echo "Proses Desain";
                                    } elseif ($project['status'] === "2") {
                                        echo "Menunggu Approval Desain";
                                    } elseif ($project['status'] === "3") {
                                        echo "Pengajuan RAB";
                                    } elseif ($project['status'] === "4") {
                                        echo "Dalam Proses Produksi";
                                    } elseif ($project['status'] === "5") {
                                        echo "Setting";
                                    } ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="uk-text-center">
                                <h3 class="tm-h4"><span uk-icon="icon: future; ratio: 1"></span> Progress Proyek</h3>
                                <p>
                                    <?php
                                    if ($project['status'] === "1") {
                                        echo "5 %";
                                    } elseif ($project['status'] === "2") {
                                        echo "10 %";
                                    } elseif ($project['status'] === "3") {
                                        echo "20 %";
                                    } elseif ($project['status'] === "4") {
                                        if ($project['production'] === "0") {
                                            $persentasi = "30";
                                        } else {
                                            $qty = round($project['production'] / 100 * 65, 2);
                                            $persentasi = 30 + $qty;
                                        }
                                        echo "$persentasi %";
                                    } elseif ($project['status'] === "5") {
                                        echo "100%";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?= $pager->links('projects', 'uikit_full') ?>
    </div>

    <!-- Modal Add Proyek -->
    <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
        <div class="uk-modal-container" id="modaladd" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <button class="uk-modal-close-default" type="button" uk-close></button>
                        <h2 class="uk-modal-title">Tambah Proyek</h2>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" action="project/create" method="post">

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Nama Proyek</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" name="name" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon" required>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Detail Pesanan</label>
                                <div class="uk-form-controls">
                                    <textarea class="uk-textarea" rows="5" placeholder="Detail Pesanan" name="brief" aria-label="Brief" required></textarea>
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="paket">Cari Paket</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="paketname" name="paketname" placeholder="Nama Paket">
                                </div>
                            </div>

                            <div id="listmdl"></div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Progres Proyek</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" name="status" id="status" required>
                                        <option value="" selected disabled>Pilih Progres</option>
                                        <option value="1">Proses Desain</option>
                                        <option value="2">Menunggu Approval Desain</option>
                                        <option value="3">Pengajuan RAB</option>
                                        <option value="4">Dalam Proses Produksi</option>
                                        <option value="5">Setting</option>
                                    </select>
                                </div>
                            </div>

                            <div class="uk-margin" id="proqty" hidden>
                                <label class="uk-form-label" for="company">Presentase Produksi</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" name="proqty" placeholder="Prosentase Produksi" type="number" max="100" aria-label="Not clickable icon">
                                </div>
                            </div>

                            <!-- Add Client Auto Complete -->
                            <div class="uk-margin" id="pusat">
                                <label class="uk-form-label" for="company">Perusahaan</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="company" name="company" placeholder="Masukkan nama perusahaan yang terdaftar sebagai klien..." required>
                                    <input id="compid" name="company" value="" hidden>
                                </div>
                            </div>
                            <!-- End Of Add Client -->
                            <div class="uk-modal-footer uk-text-right">
                                <button class="uk-button uk-button-default uk-modal-close" type="button">Batal</button>
                                <button class="uk-button uk-button-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript">
        // Auto Complete Paket
        paketList = [
            <?php foreach ($pakets as $paket) {
                echo '{label:"' . $paket['name'] . '",idx:' . $paket['id'] . '},';
            } ?>
        ];
        $(function() {
            $("#paketname").autocomplete({
                source: paketList,
                select: function(e, i) {
                    var data = {
                        'id': i.item.idx
                    };
                    $.ajax({
                        url: "project/mdl",
                        method: "POST",
                        data: data,
                        dataType: "json",
                        error: function() {
                            console.log('error', arguments);
                        },
                        success: function() {
                            console.log('success', arguments);
                            document.getElementById('listmdl').removeAttribute('hidden');

                            var pakets = document.getElementById('listmdl');

                            var elements = document.getElementById('mdldraft' + i.item.idx);
                            if (elements) {
                                elements.remove();
                            }

                            var containerlist = document.createElement('div');
                            containerlist.setAttribute('id', 'mdldraft' + i.item.idx)

                            var divider = document.createElement('hr');
                            divider.setAttribute('style', 'border-bottom: 2px solid #000;');

                            var paketnamegrid = document.createElement('div');
                            paketnamegrid.setAttribute('class', 'uk-flex-middle uk-flex-center');
                            paketnamegrid.setAttribute('uk-grid', '');

                            var paketnamecon = document.createElement('div');
                            paketnamecon.setAttribute('class', 'uk-width-5-6 uk-text-center');

                            var paketname = document.createElement('div');
                            paketname.setAttribute('class', 'uk-h3');
                            paketname.setAttribute('style', 'text-transform: uppercase;');
                            paketname.innerHTML = i.item.label;

                            var closecontainer = document.createElement('div');
                            closecontainer.setAttribute('class', 'uk-width-1-6');

                            var closebutton = document.createElement('a');
                            closebutton.setAttribute('class', 'uk-icon-button-delete');
                            closebutton.setAttribute('uk-icon', 'close');
                            closebutton.setAttribute('onclick', 'removeList(' + i.item.idx + ')');

                            var tablecon = document.createElement('div');
                            tablecon.setAttribute('class', 'uk-overflow-auto');

                            var tables = document.createElement('table');
                            tables.setAttribute('class', 'uk-table uk-table-middle uk-table-divider');

                            var thead = document.createElement('thead');

                            var trhead = document.createElement('tr');

                            var thchecklist = document.createElement('th');
                            thchecklist.innerHTML = 'Checklist';

                            var thname = document.createElement('th');
                            thname.innerHTML = 'Nama';

                            var thlength = document.createElement('th');
                            thlength.innerHTML = 'Panjang';

                            var thwidth = document.createElement('th');
                            thwidth.innerHTML = 'Lebar';

                            var thheigth = document.createElement('th');
                            thheigth.innerHTML = 'Tinggi';

                            var thvol = document.createElement('th');
                            thvol.innerHTML = 'Volume';

                            var thden = document.createElement('th');
                            thden.innerHTML = 'Satuan';

                            var thqty = document.createElement('th');
                            thqty.innerHTML = 'Jumlah Item';

                            var thprice = document.createElement('th');
                            thprice.innerHTML = 'Harga';

                            var tbody = document.createElement('tbody');

                            mdlarray = arguments[0];

                            for (k in mdlarray) {
                                var trbody = document.createElement('tr');

                                var tdchecklist = document.createElement('td');

                                var inputchecklist = document.createElement('input');
                                inputchecklist.setAttribute('type', 'checkbox');
                                inputchecklist.setAttribute('class', 'uk-checkbox');
                                inputchecklist.setAttribute('id', 'checklist[' + mdlarray[k]['id'] + ']');
                                inputchecklist.setAttribute('name', 'checklist[' + mdlarray[k]['id'] + ']');

                                var tdname = document.createElement('td');
                                tdname.innerHTML = mdlarray[k]['name']

                                var tdlength = document.createElement('td');
                                tdlength.innerHTML = mdlarray[k]['length']

                                var tdwidth = document.createElement('td');
                                tdwidth.innerHTML = mdlarray[k]['width']

                                var tdheight = document.createElement('td');
                                tdheight.innerHTML = mdlarray[k]['height']

                                var tdvol = document.createElement('td');
                                tdvol.innerHTML = mdlarray[k]['volume']

                                var tdden = document.createElement('td');
                                tdden.innerHTML = mdlarray[k]['denomination']

                                var tdqty = document.createElement('td');
                                tdqty.setAttribute('class', 'uk-form-controls');

                                var inputqty = document.createElement('input');
                                inputqty.setAttribute('class', 'uk-input uk-form-width-small');
                                inputqty.setAttribute('type', 'number');
                                inputqty.setAttribute('id', 'qty[' + mdlarray[k]['id'] + ']');
                                inputqty.setAttribute('name', 'qty[' + mdlarray[k]['id'] + ']');
                                inputqty.setAttribute('value', '0');
                                inputqty.setAttribute('onchange', 'price(' + mdlarray[k]['id'] + ')');

                                var tdprice = document.createElement('td');
                                tdprice.setAttribute('id', 'showprice[' + mdlarray[k]['id'] + ']');
                                tdprice.innerHTML = 0;

                                var hiddenprice = document.createElement('div');
                                hiddenprice.setAttribute('id', 'price[' + mdlarray[k]['id'] + ']');
                                hiddenprice.setAttribute('hidden', '');
                                hiddenprice.innerHTML = mdlarray[k]['price'];

                                tdqty.appendChild(inputqty);
                                tdchecklist.appendChild(inputchecklist);
                                trbody.appendChild(tdchecklist);
                                trbody.appendChild(tdname);
                                trbody.appendChild(tdlength);
                                trbody.appendChild(tdwidth);
                                trbody.appendChild(tdheight);
                                trbody.appendChild(tdvol);
                                trbody.appendChild(tdden);
                                trbody.appendChild(tdqty);
                                trbody.appendChild(tdprice);
                                trbody.appendChild(hiddenprice);
                                tbody.appendChild(trbody);
                            }
                            trhead.appendChild(thchecklist);
                            trhead.appendChild(thname);
                            trhead.appendChild(thlength);
                            trhead.appendChild(thwidth);
                            trhead.appendChild(thheigth);
                            trhead.appendChild(thvol);
                            trhead.appendChild(thden);
                            trhead.appendChild(thqty);
                            trhead.appendChild(thprice);
                            thead.appendChild(trhead);
                            tables.appendChild(thead);
                            tables.appendChild(tbody);
                            tablecon.appendChild(tables);
                            paketnamegrid.appendChild(paketnamecon);
                            paketnamecon.appendChild(paketname);
                            paketnamegrid.appendChild(closecontainer);
                            closecontainer.appendChild(closebutton);
                            containerlist.appendChild(paketnamegrid);
                            containerlist.appendChild(tablecon);
                            containerlist.appendChild(divider);
                            pakets.appendChild(containerlist);
                        },
                    })
                },
                minLength: 2
            });
        });

        function price(z) {
            var baseprice = document.getElementById('price[' + z + ']').innerHTML;
            var baseqty = document.getElementById('qty[' + z + ']').value;
            var pricetd = document.getElementById('showprice[' + z + ']');
            var projprice = baseprice * baseqty;
            var checkbox = document.getElementById('checklist[' + z + ']');
            pricetd.innerHTML = projprice;

            if (baseqty > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        };

        function removeList(i) {
            const removeList = document.getElementById('mdldraft' + i);
            removeList.remove();
        };

        // Percentation Production
        $(document).ready(function() {
            $("#status").change(function() {
                if ((this.value) == 4) {
                    $("#proqty").removeAttr("hidden");
                } else {
                    $("#proqty").attr("hidden", true);
                }
            });
        });

        // Auto Complete Company
        $(function() {
            var company = [
                <?php if (!empty($company)) {
                    foreach ($company as $comp) {
                        if ($comp['parentid'] === "0") {
                            $rsklasification = $comp['rsname'] . " (pusat)";
                            echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                        } else {
                            $rsklasification = $comp['rsname'] . " (cabang)";
                            echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                        }
                    }
                } ?>
            ];
            console.log(company);
            $("#company").autocomplete({
                source: company,
                select: function(e, i) {
                    $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                },
                minLength: 2
            });
        });
    </script>
    <!-- Modal Add Proyek End -->

    <!-- Modal Update Proyek -->
    <?php if ($authorize->hasPermission('admin.project.read', $uid)) {
        foreach ($projects as $project) { ?>
            <div class="uk-modal-container" id="modalupdatepro<?= $project['id'] ?>" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Ubah Data Proyek</h2>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" action="project/update/<?= $project['id'] ?>" method="post">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Nama Proyek</label>
                                <div class="uk-uk-form-controls">
                                    <input class="uk-input" name="name" value="<?= $project['name'] ?>" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon">
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Detail Pemesanan</label>
                                <div class="uk-form-controls">
                                    <textarea class="uk-textarea" rows="5" placeholder="Detail Pemesanan" name="brief" aria-label="Brief"><?= $project['brief'] ?></textarea>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="status">Progres Produksi</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" name="status" id="status<?= $project['id'] ?>">
                                        <option value="" selected disabled>Pilih Progres</option>
                                        <option value="1" <?= ($project['status'] === '1' ? 'selected' : '') ?>>Proses Desain</option>
                                        <option value="2" <?= ($project['status'] === '2' ? 'selected' : '') ?>>Menunggu Approval Desain</option>
                                        <option value="3" <?= ($project['status'] === '3' ? 'selected' : '') ?>>Pengajuan RAB</option>
                                        <option value="4" <?= ($project['status'] === '4' ? 'selected' : '') ?>>Dalam Proses Produksi</option>
                                        <option value="5" <?= ($project['status'] === '5' ? 'selected' : '') ?>>Setting</option>
                                    </select>
                                </div>
                            </div>

                            <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                <div>
                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Detail Pemesanan</div>
                                </div>
                                <div class="uk-text-right">
                                    <a class="uk-link-reset uk-icon-button" id="toggle<?= $project['id'] ?>" uk-toggle="target: .togglesph<?= $project['id'] ?>"><span class="uk-light" id="close<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="open<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                </div>
                            </div>

                            <div class="uk-padding uk-padding-remove-vertical togglesph<?= $project['id'] ?>" hidden>
                                <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/<?=$project['id']?>">Download SPH</a>
                                <hr>
                                <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                    <table class="uk-table uk-table-middle uk-table-divider">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Nama</th>
                                                <th>Panjang</th>
                                                <th>Lebar</th>
                                                <th>Tinggi</th>
                                                <th>Volume</th>
                                                <th>Satuan</th>
                                                <th>Jumlah Pesanan</th>
                                                <th>Harga</th>
                                                <th class="uk-text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($projectdata[$project['id']]['paket'] as $paket) { ?>
                                                <tr>
                                                    <td colspan="8" class="tm-h3" style="text-transform: uppercase;"><?= $paket['name'] ?></td>
                                                    <?php foreach ($paket['mdl'] as $mdl) { ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if ($mdl['checked']) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = '';
                                                        }
                                                        ?>
                                                        <input type="checkbox" class="uk-checkbox" <?= $checked ?> id="checked[<?= $project['id'] ?><?= $mdl['id'] ?>]" name="checked<?= $project['id'] ?>[<?= $mdl['id'] ?>]" />
                                                    </td>
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
                                                    <td class="uk-form-controls">
                                                        <input type="number" id="eqty[<?= $project['id'] ?><?= $mdl['id'] ?>]" name="eqty<?= $project['id'] ?>[<?= $mdl['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $mdl['qty'] ?>" onchange="eprice(<?= $project['id'] ?><?= $mdl['id'] ?>)" />
                                                    </td>
                                                    <div id="eprice[<?= $project['id'] ?><?= $mdl['id'] ?>]" hidden><?= $mdl['price'] ?></div>
                                                    <td id="eshowprice[<?= $project['id'] ?><?= $mdl['id'] ?>]"><?= (int)$mdl['qty'] * (int)$mdl['price'] ?></td>
                                                </tr>
                                                <script>
                                                    function eprice(n) {
                                                        var ebaseprice = document.getElementById('eprice[' + n + ']').innerHTML;
                                                        var ebaseqty = document.getElementById('eqty[' + n + ']').value;
                                                        var epricetd = document.getElementById('eshowprice[' + n + ']');
                                                        var echeckbox = document.getElementById('checked[' + n + ']');
                                                        var eprojprice = ebaseprice * ebaseqty;
                                                        epricetd.innerHTML = eprojprice;

                                                        if (ebaseqty > 0) {
                                                            echeckbox.checked = true;
                                                        } else {
                                                            echeckbox.checked = false;
                                                        }
                                                    };
                                                </script>
                                            <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="uk-h4">Tambah Pesanan</div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="paket">Cari Paket</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="paketname<?= $project['id'] ?>" name="paketname<?= $project['id'] ?>" placeholder="Nama Paket">
                                    </div>
                                </div>

                                <div id="listmdl<?= $project['id'] ?>"></div>
                            </div>

                            <script>
                                document.getElementById('toggle<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('close<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('close<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('open<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('open<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('close<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                                
                                $(document).ready(function() {
                                    if ($("#status<?= $project['id'] ?>").val() == "4") {
                                        $("#proqty<?= $project['id'] ?>").removeAttr("hidden");
                                    }
                                    $("select[id='status<?= $project['id'] ?>']").change(function() {
                                        if ((this.value) == 4) {
                                            $("#proqty<?= $project['id'] ?>").removeAttr("hidden");
                                        } else {
                                            $("#proqty<?= $project['id'] ?>").attr("hidden", true);
                                        }
                                    });
                                });

                                $(document).ready(function() {
                                    if ($("#status<?= $project['id'] ?>").val() == "1") {
                                        $("#image-container-create-<?= $project['id'] ?>").removeAttr("hidden");
                                    }
                                    $("select[id='status<?= $project['id'] ?>']").change(function() {
                                        if ((this.value) == 1) {
                                            $("#image-container-create-<?= $project['id'] ?>").removeAttr("hidden");
                                        } else {
                                            $("#image-container-create-<?= $project['id'] ?>").attr("hidden", true);
                                        }
                                    });
                                });

                                autopaket<?= $project['id'] ?> = [
                                    <?php foreach ($projectdata[$project['id']]['autopaket'] as $autopaket) {
                                        echo '{label:"' . $autopaket['name'] . '",idx:' . $autopaket['id'] . '},';
                                    } ?>
                                ];
                                $(function() {
                                    $("#paketname<?= $project['id'] ?>").autocomplete({
                                        source: autopaket<?= $project['id'] ?>,
                                        select: function(e, i) {
                                            var data = {
                                                'id': i.item.idx
                                            };
                                            $.ajax({
                                                url: "project/mdl",
                                                method: "POST",
                                                data: data,
                                                dataType: "json",
                                                error: function() {
                                                    console.log('error', arguments);
                                                },
                                                success: function() {
                                                    console.log('success', arguments);
                                                    document.getElementById('listmdl<?= $project['id'] ?>').removeAttribute('hidden');

                                                    var pakets = document.getElementById('listmdl<?= $project['id'] ?>');

                                                    var elements = document.getElementById('mdldraft<?= $project['id'] ?>' + i.item.idx);
                                                    if (elements) {
                                                        elements.remove();
                                                    }

                                                    var containerlist = document.createElement('div');
                                                    containerlist.setAttribute('id', 'mdldraft<?= $project['id'] ?>' + i.item.idx)

                                                    var divider = document.createElement('hr');
                                                    divider.setAttribute('style', 'border-bottom: 2px solid #000;');

                                                    var paketnamegrid = document.createElement('div');
                                                    paketnamegrid.setAttribute('class', 'uk-flex-middle uk-flex-center');
                                                    paketnamegrid.setAttribute('uk-grid', '');

                                                    var paketnamecon = document.createElement('div');
                                                    paketnamecon.setAttribute('class', 'uk-width-5-6 uk-text-center');

                                                    var paketname = document.createElement('div');
                                                    paketname.setAttribute('class', 'uk-h3');
                                                    paketname.setAttribute('style', 'text-transform: uppercase;');
                                                    paketname.innerHTML = i.item.label;

                                                    var closecontainer = document.createElement('div');
                                                    closecontainer.setAttribute('class', 'uk-width-1-6');

                                                    var closebutton = document.createElement('a');
                                                    closebutton.setAttribute('class', 'uk-icon-button-delete');
                                                    closebutton.setAttribute('uk-icon', 'close');
                                                    closebutton.setAttribute('onclick', 'removeList<?= $project['id'] ?>(' + i.item.idx + ')');

                                                    var tablecon = document.createElement('div');
                                                    tablecon.setAttribute('class', 'uk-overflow-auto');

                                                    var tables = document.createElement('table');
                                                    tables.setAttribute('class', 'uk-table uk-table-middle uk-table-divider');

                                                    var thead = document.createElement('thead');

                                                    var trhead = document.createElement('tr');

                                                    var thchecklist = document.createElement('th');
                                                    thchecklist.innerHTML = 'Checklist';

                                                    var thname = document.createElement('th');
                                                    thname.innerHTML = 'Nama';

                                                    var thlength = document.createElement('th');
                                                    thlength.innerHTML = 'Panjang';

                                                    var thwidth = document.createElement('th');
                                                    thwidth.innerHTML = 'Lebar';

                                                    var thheigth = document.createElement('th');
                                                    thheigth.innerHTML = 'Tinggi';

                                                    var thvol = document.createElement('th');
                                                    thvol.innerHTML = 'Volume';

                                                    var thden = document.createElement('th');
                                                    thden.innerHTML = 'Satuan';

                                                    var thqty = document.createElement('th');
                                                    thqty.innerHTML = 'Jumlah Item';

                                                    var thprice = document.createElement('th');
                                                    thprice.innerHTML = 'Harga';

                                                    var tbody = document.createElement('tbody');

                                                    emdlarray = arguments[0];

                                                    for (t in emdlarray) {
                                                        var trbody = document.createElement('tr');

                                                        var tdchecklist = document.createElement('td');

                                                        var inputchecklist = document.createElement('input');
                                                        inputchecklist.setAttribute('type', 'checkbox');
                                                        inputchecklist.setAttribute('class', 'uk-checkbox');
                                                        inputchecklist.setAttribute('id', 'checked[<?= $project['id'] ?>' + emdlarray[t]['id'] + ']');
                                                        inputchecklist.setAttribute('name', 'checked<?= $project['id'] ?>[' + emdlarray[t]['id'] + ']');

                                                        var tdname = document.createElement('td');
                                                        tdname.innerHTML = emdlarray[t]['name']

                                                        var tdlength = document.createElement('td');
                                                        tdlength.innerHTML = emdlarray[t]['length']

                                                        var tdwidth = document.createElement('td');
                                                        tdwidth.innerHTML = emdlarray[t]['width']

                                                        var tdheight = document.createElement('td');
                                                        tdheight.innerHTML = emdlarray[t]['height']

                                                        var tdvol = document.createElement('td');
                                                        tdvol.innerHTML = emdlarray[t]['volume']

                                                        var tdden = document.createElement('td');
                                                        tdden.innerHTML = emdlarray[t]['denomination']

                                                        var tdqty = document.createElement('td');
                                                        tdqty.setAttribute('class', 'uk-form-controls');

                                                        var inputqty = document.createElement('input');
                                                        inputqty.setAttribute('class', 'uk-input uk-form-width-small');
                                                        inputqty.setAttribute('type', 'number');
                                                        inputqty.setAttribute('id', 'eqty[<?= $project['id'] ?>' + emdlarray[t]['id'] + ']');
                                                        inputqty.setAttribute('name', 'eqty<?= $project['id'] ?>[' + emdlarray[t]['id'] + ']');
                                                        inputqty.setAttribute('value', '0');
                                                        inputqty.setAttribute('onchange', 'price<?= $project['id'] ?>(' + emdlarray[t]['id'] + ')');

                                                        var tdprice = document.createElement('td');
                                                        tdprice.setAttribute('id', 'eshowprice[<?= $project['id'] ?>' + emdlarray[t]['id'] + ']');
                                                        tdprice.innerHTML = 0;

                                                        var hiddenprice = document.createElement('div');
                                                        hiddenprice.setAttribute('id', 'eprice[<?= $project['id'] ?>' + emdlarray[t]['id'] + ']');
                                                        hiddenprice.setAttribute('hidden', '');
                                                        hiddenprice.innerHTML = emdlarray[t]['price'];

                                                        tdqty.appendChild(inputqty);
                                                        tdchecklist.appendChild(inputchecklist);
                                                        trbody.appendChild(tdchecklist);
                                                        trbody.appendChild(tdname);
                                                        trbody.appendChild(tdlength);
                                                        trbody.appendChild(tdwidth);
                                                        trbody.appendChild(tdheight);
                                                        trbody.appendChild(tdvol);
                                                        trbody.appendChild(tdden);
                                                        trbody.appendChild(tdqty);
                                                        trbody.appendChild(tdprice);
                                                        trbody.appendChild(hiddenprice);
                                                        tbody.appendChild(trbody);
                                                    }
                                                    trhead.appendChild(thchecklist);
                                                    trhead.appendChild(thname);
                                                    trhead.appendChild(thlength);
                                                    trhead.appendChild(thwidth);
                                                    trhead.appendChild(thheigth);
                                                    trhead.appendChild(thvol);
                                                    trhead.appendChild(thden);
                                                    trhead.appendChild(thqty);
                                                    trhead.appendChild(thprice);
                                                    thead.appendChild(trhead);
                                                    tables.appendChild(thead);
                                                    tables.appendChild(tbody);
                                                    tablecon.appendChild(tables);
                                                    paketnamegrid.appendChild(paketnamecon);
                                                    paketnamecon.appendChild(paketname);
                                                    paketnamegrid.appendChild(closecontainer);
                                                    closecontainer.appendChild(closebutton);
                                                    containerlist.appendChild(paketnamegrid);
                                                    containerlist.appendChild(tablecon);
                                                    containerlist.appendChild(divider);
                                                    pakets.appendChild(containerlist);
                                                },
                                            })
                                        },
                                        minLength: 2
                                    })
                                })

                                function price<?= $project['id'] ?>(l) {
                                    var ebaseprice = document.getElementById('eprice[<?= $project['id'] ?>' + l + ']').innerHTML;
                                    var ebaseqty = document.getElementById('eqty[<?= $project['id'] ?>' + l + ']').value;
                                    var epricetd = document.getElementById('eshowprice[<?= $project['id'] ?>' + l + ']');
                                    var echeckbox = document.getElementById('checked[<?= $project['id'] ?>' + l + ']');
                                    var eprojprice = ebaseprice * ebaseqty;
                                    epricetd.innerHTML = eprojprice;

                                    if (ebaseqty > 0) {
                                        echeckbox.checked = true;
                                    } else {
                                        echeckbox.checked = false;
                                    }
                                };

                                function removeList<?= $project['id'] ?>(d) {
                                    const removeList = document.getElementById('mdldraft<?= $project['id'] ?>' + d);
                                    removeList.remove();
                                };
                            </script>

                            <div class="uk-margin" id="proqty<?= $project['id'] ?>" hidden>
                                <label class="uk-form-label" for="company">Prosentase Produksi</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" name="proqty" value="<?= $project['production'] ?>" placeholder="Prosentase Produksi" type="number" max="100" min="0" aria-label="Not clickable icon">
                                </div>
                            </div>

                            <div>
                                <label class="uk-form-label" for="desain">Desain</label>
                                <?php foreach ($projectdata[$project['id']]['design'] as $design) {
                                    if (!empty($design['submitted'])) { ?>
                                        <div class="uk-form-horizontal">
                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">Tanggal Desain</label>
                                                <div class="uk-form-controls"> : <?= date('d M Y, H:i', strtotime($design['updated_at'])); ?></div>
                                            </div>

                                            <?php if (!empty($design['revision'])) { ?>
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                    <div class="uk-form-controls"> : <a href="/img/design/<?= $design['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $design['submitted'] ?></a></div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                    <div class="uk-form-controls"> : <a href="/img/revisi/<?= $design['revision'] ?>"><span uk-icon="file-pdf"></span><?= $design['revision'] ?></a></div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                    <div class="uk-form-controls"> : <a href="/img/design/<?= $design['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $design['submitted'] ?></a></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                    <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                        <input id="photocreate<?= $project['id'] ?>" name="submitted" hidden />
                                        <div id="js-upload-create-<?= $project['id'] ?>" class="js-upload-create-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                            <span uk-icon="icon: cloud-upload"></span>
                                            <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                            <div uk-form-custom>
                                                <input type="file">
                                                <span class="uk-link uk-preserve-color">pilih satu</span>
                                            </div>
                                        </div>
                                        <progress id="js-progressbar-create-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                    var bar = document.getElementById('js-progressbar-create-<?= $project['id'] ?>');

                                    UIkit.upload('.js-upload-create-<?= $project['id'] ?>', {
                                        url: 'upload/designcreate',
                                        multiple: false,
                                        name: 'uploads',
                                        param: {lorem:'ipsum'},
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

                                            if (document.getElementById('display-container-create-<?= $project['id'] ?>')) {
                                                document.getElementById('display-container-create-<?= $project['id'] ?>').remove();
                                            };

                                            document.getElementById('photocreate<?= $project['id'] ?>').value = filename;

                                            var imgContainer = document.getElementById('image-container-create-<?= $project['id'] ?>');

                                            var displayContainer = document.createElement('div');
                                            displayContainer.setAttribute('id', 'display-container-create-<?= $project['id'] ?>');
                                            displayContainer.setAttribute('class', 'uk-inline uk-width-1-1');

                                            var displayImg = document.createElement('div');
                                            displayImg.setAttribute('class', 'uk-placeholder uk-text-center');

                                            var textfont = document.createElement('h6');

                                            var linkrev = document.createElement('span')
                                            linkrev.setAttribute('uk-icon', 'file-pdf');

                                            var link = document.createElement('a');
                                            link.setAttribute('href', 'img/design/' + filename);
                                            link.setAttribute('target', '_blank');

                                            var closeContainer = document.createElement('div');
                                            closeContainer.setAttribute('class', 'uk-position-small uk-position-right');

                                            var closeButton = document.createElement('a');
                                            closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                            closeButton.setAttribute('onClick', 'removeImgCreate<?= $project['id'] ?>()');
                                            closeButton.setAttribute('uk-icon', 'close');

                                            var linktext = document.createTextNode(filename);

                                            closeContainer.appendChild(closeButton);
                                            displayContainer.appendChild(displayImg);
                                            displayContainer.appendChild(closeContainer);
                                            displayImg.appendChild(textfont);
                                            textfont.appendChild(link);
                                            link.appendChild(linkrev);
                                            link.appendChild(linktext);
                                            imgContainer.appendChild(displayContainer);

                                            document.getElementById('js-upload-create-<?= $project['id'] ?>').setAttribute('hidden', '');
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

                                    function removeImgCreate<?= $project['id'] ?>() {
                                        $.ajax({
                                            type: 'post',
                                            url: 'upload/removedesigncreate',
                                            data: {
                                                'submitted': document.getElementById('photocreate<?= $project['id'] ?>').value
                                            },
                                            dataType: 'json',

                                            error: function() {
                                                console.log('error', arguments);
                                            },

                                            success: function() {
                                                console.log('success', arguments);

                                                var pesan = arguments[0][1];

                                                document.getElementById('display-container-create-<?= $project['id'] ?>').remove();
                                                document.getElementById('photocreate<?= $project['id'] ?>').value = '';

                                                alert(pesan);

                                                document.getElementById('js-upload-create-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                            }
                                        });
                                    };
                                </script>
                            </div>

                            <!-- Add Client Auto Complete -->
                            <?php if (!empty($company)) {
                                foreach ($company as $comp) {
                                    if ($comp['id'] === $project['id']) {
                                        $klien = $comp['rsname'];
                                    }
                                }
                            } ?>

                            <div class="uk-margin" id="pusat">
                                <label class="uk-form-label" for="company">Perusahaan</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="company" name="company" value="<?= $klien ?>" placeholder="<?= $klien ?>" required>
                                    <input id="compid" name="company" value="<?= $project['clientid'] ?>" hidden>
                                </div>

                                <script type="text/javascript">
                                    $(function() {
                                        var company = [
                                            <?php if (!empty($company)) {
                                                foreach ($company as $comp) {
                                                    if ($comp['parentid'] === "0") {
                                                        $rsklasification = $comp['rsname'] . " (pusat)";
                                                        echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                    } else {
                                                        $rsklasification = $comp['rsname'] . " (cabang)";
                                                        echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                    }
                                                }
                                            } ?>
                                        ];
                                        console.log(company);
                                        $("#company").autocomplete({
                                            source: company,
                                            select: function(e, i) {
                                                $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                                            },
                                            minLength: 2
                                        });
                                    });
                                </script>
                            </div>
                            <!-- End Of Add Client -->

                            <div class="uk-modal-footer uk-text-right">
                                <button class="uk-button uk-button-default uk-modal-close" type="button">Batal</button>
                                <a class="uk-button uk-button-danger" href="project/delete/<?= $project['id'] ?>" onclick="return confirm('<?= 'Anda yakin ingin menghapus data ' . $project['name'] . '?' ?>')" type="button">Hapus</a>
                                <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
    <!-- Modal Update Proyek End -->
<?php } else {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
} ?>

<?= $this->endSection() ?>