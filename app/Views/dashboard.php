<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<!-- <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css"> -->
<!-- <script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script> -->

<script src="js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if ($authorize->hasPermission('client.read', $uid)) { ?>
    <div class="uk-container uk-container-large">
        <!-- Page Heading -->
        <?php if ($ismobile) { ?>
            <h1 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek<br />
                <?php if (!empty($client['rsname'])) {
                    echo $client['rsname'];
                } ?>
            </h1>
            <div class="uk-margin uk-text-center">
                <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
            </div>
            <div id="filter" class="uk-margin" hidden>
                <form id="searchform" action="<?= $uri ?>" method="GET">
                    <div class="uk-margin-small uk-flex uk-flex-center">
                        <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                    </div>
                    <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                        <div>Tampilan</div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div>Per Halaman</div>
                    </div>
                </form>
            </div>
            <script>
                document.getElementById('filterbutton').addEventListener('click', toggleiconfilter);

                function toggleiconfilter() {
                    var close = document.getElementById('filterclose').hasAttribute('hidden');
                    if (close === true) {
                        document.getElementById('filteropen').setAttribute('hidden', '');
                        document.getElementById('filterclose').removeAttribute('hidden');
                    } else {
                        document.getElementById('filteropen').removeAttribute('hidden');
                        document.getElementById('filterclose').setAttribute('hidden', '');
                    }
                }
            </script>
        <?php } else { ?>
            <h1 class="tm-h1 uk-margin-remove">
                Daftar Proyek
                <?php if (!empty($client['rsname'])) {
                    echo $client['rsname'];
                } ?>
            </h1>
        <?php } ?>
        <hr class="uk-divider-icon">
        <!-- end of Page Heading -->

        <!-- Content -->
        <?php if (!$ismobile) { ?>
            <div>
                <?= view('Views/Auth/_message_block') ?>
            </div>
            <form class="uk-margin" id="searchform" action="<?= $uri ?>" method="GET">
                <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Cari:</div>
                            <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                        </div>
                    </div>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Tampilan</div>
                            <div>
                                <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                    <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                                </select>
                            </div>
                            <div>Per Halaman</div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>

        <?php if (!empty($projects)) { ?>
            <?php foreach ($projects as $project) {
                $progress   = "0";
                $status     = "Sedang Dalam Proses Persiapan";
                $today      = '';
                $dateline   = '';
                $inv4       = '';
                if ($project['type_design'] === "1") {
                    if(!empty($projectdesign[$project['id']]['design'])){
                        if($projectdesign[$project['id']]['design']['status'] != null){
                            
                            if ($projectdesign[$project['id']]['design']['status'] === '0') {
                                $progress = "10";
                                $status = "Menunggu Approval Desain";
                            }

                            if ($projectdesign[$project['id']]['design']['status'] === '1') {
                                $progress = "10";
                                $status = "Proses Revisi Desain";
                            }

                            if ($projectdesign[$project['id']]['design']['status'] === '2') {
                                $progress = "20";
                                $status = "Desain Disetujui";
                            }
                        }else{
                            $progress = "30";
                            $status = "Menunggu SPH DPSA";
                        }
                    }else{
                        $progress = "10";
                        $status = "Menunggu Desain Dari DPSA";
                    }
                } else {
                    $progress = "30";
                    $status = "Menunggu SPH";
                }

                if ($project['status_spk'] === "1") {
                    $progress = "30";
                    $status = "SPK DiSetujui";
                }

                if (!empty($projectdata[$project['id']]['progress'])) {
                    $produksi = round((int)$projectdata[$project['id']]['progress']);
                    $progress = round($projectdata[$project['id']]['progress'] + $progress);
                    $status   = "Retensi";
                }

                if (!empty($projectdata[$project['id']]['dateline']) && !empty($projectdata[$project['id']]['now'])) {
                    if ($projectdata[$project['id']]['now'] > $projectdata[$project['id']]['dateline']) {
                        $progress = "100";
                        $status   = "Proyek Selesai";
                    }
                }?>

                <!-- // Data project initialize -->
                <?php if (!empty($projectdata[$project['id']])) {
                    $projectId          = $projectdata[$project['id']]['project']['id'];
                    $projectStatus      = $projectdata[$project['id']]['project']['status'];
                    $datepro            = $projectdata[$project['id']]['project']['updated_at'];
                    $spkpro             = $projectdata[$project['id']]['project']['spk'];
                    $spkstatus          = $projectdata[$project['id']]['project']['status_spk'];
                } else {
                    $projectId          = "";
                    $projectStatus      = "";
                    $datepro            = "";
                    $spkpro             = "";
                    $spkstatus          = "";
                }
                $dateTimeObj = new DateTime($datepro, new DateTimeZone('Asia/Jakarta'));
                $dateFormatted =
                    IntlDateFormatter::formatObject(
                        $dateTimeObj,
                        'eeee, d MMMM y, HH:mm:ss',
                        'id'
                    );
                $tanggalpro = ucwords($dateFormatted);

                // Data design initialize
                if (!empty($projectdesign[$project['id']]['design']['submitted'])) {
                    $desainpro          = $projectdesign[$project['id']]['design']['submitted'];
                    $revisi             = $projectdesign[$project['id']]['design']['revision'];
                    $designId           = $projectdesign[$project['id']]['design']['id'];
                    $designStatus       = $projectdesign[$project['id']]['design']['status'];
                    $datedesign         = $projectdesign[$project['id']]['design']['updated_at'];
                } else {
                    $desainpro      = "";
                    $revisi         = "";
                    $designId       = "";
                    $designStatus   = "";
                    $datedesign     = "";
                }
                $dateTimeObj = new DateTime($datedesign, new DateTimeZone('Asia/Jakarta'));
                $dateFormatted =
                    IntlDateFormatter::formatObject(
                        $dateTimeObj,
                        'eeee, d MMMM y, HH:mm:ss',
                        'id'
                    );
                $tanggaldesign = ucwords($dateFormatted);
                ?>

                <?php if ($ismobile) { ?>
                    <div class="uk-margin uk-card uk-card-default" id="card-project<?= $project['id'] ?>">
                        <div class="uk-card-header">
                            <div class="uk-grid-small uk-flex-middle" uk-grid>
                                <div class="uk-width-expand">
                                    <h3 class="uk-card-title"><?= $project['name'] ?></h3>
                                </div>
                                <div class="uk-width-auto">
                                    <button id="open<?= $project['id'] ?>" class="uk-icon-button" uk-icon="chevron-down" uk-toggle="target: #body<?= $project['id'] ?>"></button>
                                    <button id="close<?= $project['id'] ?>" class="uk-icon-button" uk-icon="chevron-up" uk-toggle="target: #body<?= $project['id'] ?>" hidden></button>
                                </div>
                            </div>
                            <progress class="uk-progress" value="<?= $progress ?>" max="100"></progress>
                        </div>
                        <div id="body<?= $project['id'] ?>" class="uk-card-body" hidden>
                            <div class="uk-grid-divider" uk-grid>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Status Proyek</h4>
                                    <div class="uk-text-center"><?= $status ?></div>
                                </div>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Progress Produksi</h4>
                                    <div class="uk-text-center"><?= $progress ?>%</div>
                                </div>
                            </div>

                            <div class="uk-text-center" uk-grid>
                                <div class="uk-width-expand">
                                    <div class="uk-text-left">
                                        <h4>Detail Pesanan</h4>
                                    </div>
                                </div>
                                <div class="uk-width-1-4 uk-text-right">
                                    <div id="containerbtnsph<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 1.5" id="btndownsph<?= $project['id'] ?>"></div>
                                    <div id="containerbtnupsph<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 1.5" id="btnupsph<?= $project['id'] ?>"></div>
                                </div>
                            </div>

                            <?php if (!empty($project['id'])) { ?>
                                <div id="contentsph<?= $project['id'] ?>" class="uk-section uk-padding-remove-top" hidden>
                                    <div class="uk-container uk-container-small">
                                        <div class="uk-overflow-auto">
                                            <table class="uk-table uk-table-divider">
                                            <thead>
                                                    <tr>
                                                        <th class="">Nama</th>
                                                        <th class="uk-text-center">Panjang</th>
                                                        <th class="uk-text-center">Lebar</th>
                                                        <th class="uk-text-center">Tinggi</th>
                                                        <th class="uk-text-center">Volume</th>
                                                        <th class="uk-text-center">Satuan</th>
                                                        <th class="uk-text-center">Foto</th>
                                                        <th class="uk-text-center">Jumlah Pesanan</th>
                                                        <th class="uk-text-left">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($project['id'])) {
                                                        foreach ($rabs as $rab) {
                                                            if ($rab['projectid'] === $project['id']) {
                                                                foreach ($pakets as $paket) {
                                                                    if ($paket['id'] === $rab['paketid']) {
                                                                        foreach ($mdls as $mdl) {
                                                                            if ($mdl['id'] === $rab['mdlid']) { ?>
                                                                                <tr>
                                                                                    <td class=""><?= $mdl['name'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['length'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['width'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['height'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['volume'] ?></td>
                                                                                    <td class="uk-text-center">
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
                                                                                    <td class="uk-text-center">
                                                                                        <div uk-lightbox="">
                                                                                            <a class="uk-inline" href="img/mdl/<?=$mdl['photo']?>" role="button">
                                                                                                <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$mdl['photo']?>" width="40" height="40" alt="<?=$mdl['photo']?>">
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                    <?php
                                                                                    $price = "";
                                                                                    if ($mdl['denomination'] === "1") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "2") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "3") {
                                                                                        $luas   =   $mdl['height'] * $mdl['length'];
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "4") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    }
                                                                                    ?>
                                                                                    <td class="uk-text-left"><?= number_format($price, 0, ',', '.');" "; ?></td>
                                                                                </tr>
                                                    <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if(!empty($projectdata[$project['id']]['custrab'])) {?>
                                                        <tr>
                                                            <td class="uk-text-bold"></td>
                                                            <td class="-text-bold"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-bold">TAMBAHAN PESANAN</td>
                                                            <td class="uk-text-bold"></td>
                                                        </tr>
                                                        <?php foreach ($projectdata[$project['id']]['custrab'] as $custrab) {?>
                                                            <tr>
                                                                <td class=""></td>
                                                                <td class="uk-text-left"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-left"><?= strtoupper($custrab['name']) ?></td>
                                                                <td class="uk-text-left"><?= number_format($custrab['price'], 0, ',', '.');" "; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                            
                                        </div>
                                        <p class="uk-text-left uk-width-1-1" uk-margin>
                                            <?php if(!empty($project['sph'])) { ?>
                                                <a class="uk-button uk-button-primary uk-button-small" href="img/sph/<?= $project['sph'] ?>" target="_blank"> Download SPH</a>
                                            <?php }?>
                                        </p>
                                    </div>
                                </div>

                                <hr class="uk-margin-remove-top">

                                <!-- Mobile Design -->
                                <?php if (!empty($projectdesign[$project['id']]['design']['submitted'])) { ?>
                                    <div class="uk-text-center" uk-grid>
                                        <div class="uk-width-1-4">
                                            <div class="uk-text-left">
                                                <h4>Desain</h4>
                                            </div>
                                        </div>
                                        <div class="uk-width-expand" id="stt<?=$designId?>">
                                            <?php
                                            if ($designStatus === "0") {
                                                echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; border-color: #ff0000; color:#ff0000; font-weight: bold; width:100%;"> Menuggu Konfirmasi </div>';
                                            } elseif ($designStatus === "1") {
                                                echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; color: #FFEA00; border-color:#FFEA00;  font-weight: bold;"> Proses Revisi </div>';
                                            } else {
                                                echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;"> Terkonfirmasi </div>';
                                            }
                                            ?>
                                        </div>
                                        <div class="uk-width-1-4 uk-text-right">
                                            <div id="containerbtndsn<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 1.5" id="btndowndsn<?= $project['id'] ?>"></div>
                                            <div id="containerbtnupdsn<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 1.5" id="btnupdsn<?= $project['id'] ?>"></div>
                                        </div>
                                    </div>

                                    <div id="contentdsn<?= $project['id'] ?>" class="uk-section uk-padding-remove-top" hidden>
                                        <div class="uk-container uk-container-small">
                                            <div class="uk-width-1-1 uk-margin-bottom">
                                                
                                                <div class="">
                                                    <?php
                                                    if ($designStatus === "0") {
                                                        echo "Tanggal Upload Desain";
                                                    } elseif ($designStatus === "1") {
                                                        echo "Tanggal Revisi Desain";
                                                    } else {
                                                        echo "Tanggal Disetujui";
                                                    }
                                                    ?>
                                                </div>
                                                <div class="">
                                                    <?= $tanggaldesign ?>
                                                </div>

                                                <div class="uk-margin-top">
                                                    <span uk-icon="file-text"></span> <a href="img/design/<?= $desainpro ?>" target="_blank" download>File Design</a>
                                                </div>

                                                <?php if (!empty($project['ded'])) { ?>
                                                <div class="uk-margin-top">
                                                    <span uk-icon="file-text"></span> <a href="img/revisi/<?= $project['ded'] ?>" target="_blank" download>File Layout / DED</a>
                                                </div>
                                                <?php } ?>

                                                <?php if (!empty($projectdesign[$project['id']]['design']['revision'])) { ?>
                                                <div class="uk-margin-top">
                                                    <span uk-icon="file-text"></span> <a href="img/revisi/<?= $revisi ?>" target="_blank" download>File Revisi</a>
                                                </div>
                                                <?php } ?>
                                                
                                            </div>

                                            <?php if ($designStatus != "2") { ?>
                                                <?php if ($authorize->hasPermission('client.auth.branch', $uid)  || $authorize->hasPermission('client.auth.holding', $uid) ) { ?>
                                                    <div class="uk-text-left uk-margin-top" id="btndesain<?= $designId ?>" uk-margin>
                                                        <button class="uk-button uk-button-primary uk-button-small" value="2" id="acc<?= $designId ?>" >Konfirmasi</button>
                                                        <button class="uk-button uk-button-secondary uk-button-small" uk-toggle="target: #modal-revisi<?= $project['id'] ?>">Revisi</button>
                                                    </div>
                                                <?php } ?>
                                                <script>
                                                    $(document).ready(function(){
                                                        $("#acc<?= $designId ?>").click(function(){
                                                            let text = "Anda sudah yakin dengan desain ini?";
                                                            if (confirm(text) == true) {
                                                                $.ajax({
                                                                    url: "home/acc/<?= $designId ?>",
                                                                    method: "POST",
                                                                    data: {
                                                                        status: $('#acc<?= $designId ?>').val(),
                                                                    },
                                                                    dataType: "json",
                                                                    error: function() {
                                                                        console.log('error', arguments);
                                                                    },
                                                                    success: function() {
                                                                        console.log('success', arguments);
                                                                        $("#status<?= $designId ?>").remove();
                                                                        $("#btndesain<?= $designId ?>").remove();
                                                                        $("#stt<?=$designId?>").append("<div class='uk-text-light uk-text-center' id='status' . $designId . '' style='border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;'> Terkonfirmasi </div>");
                                                                    },
                                                                })
                                                            }
                                                        });
                                                    });
                                                </script>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <hr class="uk-margin-remove-top">
                                <?php } ?>
                                <!-- End Of Mobile Design -->

                                <div class="uk-text-center" uk-grid>
                                    <div class="uk-width-expand">
                                        <div class="uk-text-left">
                                            <h4>Produksi</h4>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-4 uk-text-right">
                                        <div id="containerbtnproduksi<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 1.5" id="btndownproduksi<?= $project['id'] ?>"></div>
                                        <div id="containerbtnupproduksi<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 1.5" id="btnupproduksi<?= $project['id'] ?>"></div>
                                    </div>
                                </div>
                                
                                <div id="contentproduksi<?= $project['id'] ?>" class="uk-section uk-padding-remove-top" hidden>
                                    <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                        <table class="uk-table uk-table-middle uk-table-divider">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th class="uk-text-center">Gambar Kerja</th>
                                                    <th class="uk-text-center">Mesin Awal</th>
                                                    <th class="uk-text-center">Tukang</th>
                                                    <th class="uk-text-center">Mesin Lanjutan</th>
                                                    <th class="uk-text-center">Finishing</th>
                                                    <th class="uk-text-center">Packing</th>
                                                    <th class="uk-text-center">Pengiriman</th>
                                                    <th class="uk-text-center">Setting</th>
                                                    <th class="uk-text-center">Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($projectdata[$project['id']]['production'] as $production) { ?>
                                                    <tr>
                                                        <td><?= $production['name'] ?></td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['gambar_kerja']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="gambarkerja<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['mesin_awal']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="mesinawal<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['tukang']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="tukang<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['mesin_lanjutan']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="mesinlanjutan<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['finishing']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="finishing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['packing']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="packing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['pengiriman']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="pengiriman<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                <div uk-icon="check"></div>
                                                            <?php } else { ?>
                                                                <input class="uk-checkbox" type="checkbox" name="setting<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="uk-text-center">
                                                            <div><?= $production['percentages'] ?> %</div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Bukti Pengiriman -->
                                    <div class="uk-margin">Bukti Pengiriman</div>
                                    <div class="uk-child-width-1-3 uk-grid-match uk-flex-middle uk-grid-divider" uk-grid uk-lightbox="animation: slide">
                                        <?php foreach($projectdata[$project['id']]['buktipengiriman'] as $sendproof) { ?>
                                            <div>
                                                <a class="uk-inline-clip uk-transition-toggle uk-link-toggle" href="img/bukti/pengiriman/<?= $sendproof['file'] ?>" data-caption="<?= $sendproof['file'] ?>">
                                                    <img src="img/bukti/pengiriman/<?= $sendproof['file'] ?>" alt="<?= $sendproof['file'] ?>" class="uk-transition-opaque">
                                                    <div class="uk-overlay-primary uk-transition-fade uk-position-cover"></div>
                                                    <div class="uk-position-center uk-transition-fade">
                                                        <div class="uk-overlay">
                                                            <div class="uk-h4 uk-margin-top uk-margin-remove-bottom uk-text-center uk-light" id="pengiriman<?= $sendproof['id'] ?>"></div>
                                                        </div>
                                                    </div>
                                                </a>

                                                <div class="uk-margin"><?= $sendproof['note'] ?></div>

                                                <script>
                                                    // Date In Indonesia
                                                    var publishupdate   = "<?= $sendproof['created_at'] ?>";
                                                    var thatdate        = publishupdate.split( /[- :]/ );
                                                    thatdate[1]--;
                                                    var publishthatdate = new Date( ...thatdate );
                                                    var publishyear     = publishthatdate.getFullYear();
                                                    var publishmonth    = publishthatdate.getMonth();
                                                    var publishdate     = publishthatdate.getDate();
                                                    var publishday      = publishthatdate.getDay();

                                                    switch(publishday) {
                                                        case 0: publishday     = "Minggu"; break;
                                                        case 1: publishday     = "Senin"; break;
                                                        case 2: publishday     = "Selasa"; break;
                                                        case 3: publishday     = "Rabu"; break;
                                                        case 4: publishday     = "Kamis"; break;
                                                        case 5: publishday     = "Jum'at"; break;
                                                        case 6: publishday     = "Sabtu"; break;
                                                    }
                                                    switch(publishmonth) {
                                                        case 0: publishmonth   = "Januari"; break;
                                                        case 1: publishmonth   = "Februari"; break;
                                                        case 2: publishmonth   = "Maret"; break;
                                                        case 3: publishmonth   = "April"; break;
                                                        case 4: publishmonth   = "Mei"; break;
                                                        case 5: publishmonth   = "Juni"; break;
                                                        case 6: publishmonth   = "Juli"; break;
                                                        case 7: publishmonth   = "Agustus"; break;
                                                        case 8: publishmonth   = "September"; break;
                                                        case 9: publishmonth   = "Oktober"; break;
                                                        case 10: publishmonth  = "November"; break;
                                                        case 11: publishmonth  = "Desember"; break;
                                                    }

                                                    var publishfulldate         = publishday + ", " + publishdate + " " + publishmonth + " " + publishyear;
                                                    document.getElementById("pengiriman<?= $sendproof['id'] ?>").innerHTML = publishfulldate;
                                                </script>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- Bukti Pengiriman End -->
                                </div>

                                <hr class="uk-margin-remove-top">

                                <div class="uk-text-center" uk-grid>
                                    <div class="uk-width-expand">
                                        <div class="uk-text-left">
                                            <h4>Invoice & SPK</h4>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-4 uk-text-right">
                                        <div id="containerbtnspk<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 1.5" id="btndownspk<?= $project['id'] ?>"></div>
                                        <div id="containerbtnupspk<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 1.5" id="btnupspk<?= $project['id'] ?>"></div>
                                    </div>
                                </div>

                                <!-- Invoice & SPK Mobile  -->
                                <div id="contentspk<?= $project['id'] ?>" class="uk-section uk-padding-remove-top" hidden>
                                    <?php if ($authorize->hasPermission('client.auth.branch', $uid) || $authorize->hasPermission('client.auth.holding', $uid) ) { ?>
                                        <div class="uk-container uk-container-small">
                                            <div class="uk-width-1-1">

                                                <!-- Invoice I -->
                                                <?php if(!empty($projectdata[$project['id']]['project'])){ if ($projectdata[$project['id']]['project']['status_spk'] === "1") {
                                                    if(!empty($projectdata[$project['id']]['invoice1']['file'])){ ?>
                                                        <p class="uk-margin-remove-top" uk-margin>
                                                            <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" href="img/invoice/<?= $projectdata[$project['id']]['invoice1']['file'] ?>" target="_blank">Download Invoice I</a>
                                                        </p>
                                                    <?php }
                                                    }
                                                } ?>

                                                <!-- Invoice II -->
                                                <?php if(!empty($projectdata[$project['id']]['sertrim'])){ 
                                                    if (isset($projectdata[$project['id']]['sertrim']['status']) && $progress >= "60" && $projectdata[$project['id']]['sertrim']['status'] === "0") { 
                                                        if(!empty($projectdata[$project['id']]['invoice2']['file'])){ ?>
                                                            <p class="uk-margin-remove-top" uk-margin>
                                                                <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" href="img/invoice/<?= $projectdata[$project['id']]['invoice2']['file'] ?>" target="_blank">Download Invoice II</a>
                                                            </p>
                                                    <?php }
                                                    }
                                                } ?>

                                                <!-- Invoice III -->
                                                <?php if(!empty($projectdata[$project['id']]['bast'])){ if (isset($projectdata[$project['id']]['bast']['status']) && $progress >= "95" && $projectdata[$project['id']]['bast']['status'] === "1" && !empty($projectdata[$project['id']]['bast']['file'])) {
                                                    if(!empty($projectdata[$project['id']]['invoice3']['file'])){ ?>
                                                        <p class="uk-margin-remove-top" uk-margin>
                                                            <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" href="img/invoice/<?= $projectdata[$project['id']]['invoice3']['file'] ?>" target="_blank">Download Invoice III</a>
                                                        </p>
                                                <?php }
                                                    }
                                                }?>

                                                <!-- Invoice IV -->
                                                <?php if(!empty($projectdata[$project['id']]['bast'])){
                                                    if (!empty($projectdata[$project['id']]['bast']['tanggal_bast'])) { 
                                                        if ($projectdata[$project['id']]['bast']['status'] === "1" && $projectdata[$project['id']]['now'] >=  $projectdata[$project['id']]['dateline'] && $progress >= "95") { 
                                                            if(!empty($projectdata[$project['id']]['invoice4']['file'])){ ?>
                                                                <p class="uk-margin-remove-top" uk-margin>
                                                                    <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" href="img/invoice/<?= $projectdata[$project['id']]['invoice4']['file'] ?>" target="_blank">Download Invoice IV</a>
                                                                </p>
                                                        <?php }
                                                        }
                                                    }
                                                }?>

                                                <hr>
                                                <!-- Upload SPK -->
                                                <?php if(!empty($projectdata[$project['id']]['project'])){ if ($projectdata[$project['id']]['project']['status_spk'] === null) { ?>
                                                    <p class="uk-margin-remove-top" uk-margin>
                                                        <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" uk-toggle="target: #modal-spk<?= $project['id'] ?>">Upload SPK</a>
                                                    </p>
                                                <?php }
                                                } ?>

                                                <!-- Download & Upload SPK -->
                                                <?php if(!empty($projectdata[$project['id']]['project'])){ if ($projectdata[$project['id']]['project']['status_spk'] === "0") { ?>
                                                    <p class="uk-margin-remove-top" uk-margin>
                                                        <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" uk-toggle="target: #modal-spk<?= $project['id'] ?>">Upload SPK</a>
                                                        <a class="uk-button uk-button-secondary uk-button-small uk-width-1-1" href="project/spk/<?= $spkpro ?>" target="_blank">Download SPK</a>
                                                    </p>
                                                <?php } 
                                                }?>

                                                <!-- Download SPK -->
                                                <?php if(!empty($projectdata[$project['id']]['project'])){ if ($projectdata[$project['id']]['project']['status_spk'] === "1") { ?>
                                                    <a class="uk-button uk-button-secondary uk-button-small uk-width-1-1" href="project/spk/<?= $spkpro ?>" target="_blank">Download SPK</a>
                                                    <?php } 
                                                }?>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- End Invoice & SPK Mobile -->

                                <hr class="uk-margin-remove-top">

                                <!-- Bukti Pembayaran Section -->
                                <div class="uk-text-center" uk-grid>
                                    <div class="uk-width-expand">
                                        <div class="uk-text-left">
                                            <h4>Bukti Pembayaran</h4>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-4 uk-text-right">
                                        <div id="containerbtnbuktipembayaran<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 1.5" id="btndownbuktipembayaran<?= $project['id'] ?>"></div>
                                        <div id="containerbtnupbuktipembayaran<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 1.5" id="btnupbuktipembayaran<?= $project['id'] ?>"></div>
                                    </div>
                                </div>

                                <div id="contentbuktipembayaran<?= $project['id'] ?>" hidden>
                                    <div class="uk-margin">
                                        <a class="uk-button uk-button-primary uk-button-small uk-width-1-1" uk-toggle="target: #modal-bukti-pembayaran<?= $project['id'] ?>">Upload Bukti Pembayaran</a>
                                    </div>
                                    <hr>
                                    <div class="uk-child-width-auto uk-grid-match uk-flex-middle" uk-grid uk-lightbox="animation: slide">
                                        <?php foreach($projectdata[$project['id']]['buktipembayaran'] as $payproof) { ?>
                                            <div>
                                                <div class="uk-card uk-card-default uk-card-body"><a href="/img/bukti/pembayaran/<?= $payproof['file'] ?>" data-caption="<?= $payproof['file'] ?>"><span uk-icon="file-pdf"></span><?= $payproof['file'] ?></a></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Bukti Pembayaran Section End -->
                            <?php } ?>
                        </div>
                    </div>
                    <script>
                        document.getElementById('open<?= $project['id'] ?>').addEventListener('click', buttontoggle<?= $project['id'] ?>);
                        document.getElementById('close<?= $project['id'] ?>').addEventListener('click', buttontoggle<?= $project['id'] ?>);

                        function buttontoggle<?= $project['id'] ?>() {
                            if (document.getElementById('close<?= $project['id'] ?>').hasAttribute('hidden')) {
                                document.getElementById('open<?= $project['id'] ?>').setAttribute('hidden', '');
                                document.getElementById('close<?= $project['id'] ?>').removeAttribute('hidden');
                            } else {
                                document.getElementById('open<?= $project['id'] ?>').removeAttribute('hidden');
                                document.getElementById('close<?= $project['id'] ?>').setAttribute('hidden', '');
                            }
                        }
                        
                        $(document).ready(function() {
                            $("span[id='btndownsph<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnupsph<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='containerbtnsph<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='contentsph<?= $project['id'] ?>']").attr("hidden", false);
                            });

                            $("span[id='btnupsph<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnsph<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentsph<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='containerbtnupsph<?= $project['id'] ?>']").attr("hidden", true);
                            });

                            $("span[id='btndownproduksi<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnupproduksi<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='containerbtnproduksi<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='contentproduksi<?= $project['id'] ?>']").attr("hidden", false);
                            });

                            $("span[id='btnupproduksi<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnproduksi<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentproduksi<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='containerbtnupproduksi<?= $project['id'] ?>']").attr("hidden", true);
                            });

                            $("span[id='btndownbuktipembayaran<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnupbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='containerbtnbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='contentbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                            });

                            $("span[id='btnupbuktipembayaran<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='containerbtnupbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                            });

                            $("span[id='btndownspk<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnupspk<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='containerbtnspk<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='contentspk<?= $project['id'] ?>']").attr("hidden", false);
                            });

                            $("span[id='btnupspk<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnspk<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentspk<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='containerbtnupspk<?= $project['id'] ?>']").attr("hidden", true);
                            });

                            $("span[id='btndowndsn<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtnupdsn<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentdsn<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='containerbtndsn<?= $project['id'] ?>']").attr("hidden", true);
                            });

                            $("span[id='btnupdsn<?= $project['id'] ?>']").click(function() {
                                $("div[id='containerbtndsn<?= $project['id'] ?>']").attr("hidden", false);
                                $("div[id='contentdsn<?= $project['id'] ?>']").attr("hidden", true);
                                $("div[id='containerbtnupdsn<?= $project['id'] ?>']").attr("hidden", true);
                            });

                        });
                    </script>
                <?php } else { ?>
                    <div class="uk-margin uk-card uk-card-default uk-card-hover uk-width-1-1" id="card-project<?= $project['id'] ?>">
                        <div class="uk-card-header">
                            <div class="uk-child-width-1-2" uk-grid>
                                <div>
                                    <div class="">
                                        <h3 class="uk-card-title">Proyek <?= $project['name'] ?></h3>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-child-width-1-1@m uk-text-right" uk-grid>
                                        <div>
                                            <div id="containerbtn<?= $project['id'] ?>"><span class="uk-icon-button" uk-icon="icon: chevron-down; ratio: 1.5" id="btndown<?= $project['id'] ?>"></div>
                                            <div id="containerbtnup<?= $project['id'] ?>" hidden><span class="uk-icon-button" uk-icon="icon: chevron-up; ratio: 1.5" id="btnup<?= $project['id'] ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <progress class="uk-progress" value="<?= $progress ?>" max="100" style="margin:10px"></progress>
                            <div class="uk-child-width-1-2" uk-grid>
                                <div>
                                    <div class="uk-width-auto uk-text-small uk-text-capitalize uk-margin-small-left">Status Proyek : <?= $status ?></div>
                                </div>
                                <div>
                                    <div class="uk-width-auto uk-text-default uk-text-capitalize uk-text-right"> <?= $progress . "%" ?></div>
                                </div>
                            </div>
                        </div>

                        <div id="content<?= $project['id'] ?>" hidden>
                            <div class="uk-card-body">
                                <div class="uk-grid" uk-grid>

                                    <div class="uk-width-1-1 uk-margin-bottom-remove">
                                        <div class="uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="">
                                                    <h4 class="uk-width-1-1">Detail Pesanan</h4>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                    <div>
                                                        <div id="containerbtnsph<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 2" id="btndownsph<?= $project['id'] ?>"></div>
                                                        <div id="containerbtnupsph<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 2" id="btnupsph<?= $project['id'] ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="uk-margin-bottom">
                                    </div>

                                    <?php if (!empty($project['id'])) { ?>
                                        <div class="uk-width-1-1 uk-margin-remove" id="contentsph<?= $project['id'] ?>" hidden>
                                            <table class="uk-table uk-table-responsive  uk-table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Nama</th>
                                                        <th class="uk-text-center">Panjang</th>
                                                        <th class="uk-text-center">Lebar</th>
                                                        <th class="uk-text-center">Tinggi</th>
                                                        <th class="uk-text-center">Volume</th>
                                                        <th class="uk-text-center">Satuan</th>
                                                        <th class="uk-text-center">Foto</th>
                                                        <th class="uk-text-center">Jumlah Pesanan</th>
                                                        <th class="uk-text-left">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($project['id'])) {
                                                        foreach ($rabs as $rab) {
                                                            if ($rab['projectid'] === $project['id']) {
                                                                foreach ($pakets as $paket) {
                                                                    if ($paket['id'] === $rab['paketid']) {
                                                                        foreach ($mdls as $mdl) {
                                                                            if ($mdl['id'] === $rab['mdlid']) { ?>
                                                                                <tr>
                                                                                    <td class=""><?= $mdl['name'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['length'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['width'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['height'] ?></td>
                                                                                    <td class="uk-text-center"><?= $mdl['volume'] ?></td>
                                                                                    <td class="uk-text-center">
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
                                                                                    <td class="uk-text-center">
                                                                                        <div uk-lightbox="">
                                                                                            <a class="uk-inline" href="img/mdl/<?=$mdl['photo']?>" role="button">
                                                                                                <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$mdl['photo']?>" width="40" height="40" alt="<?=$mdl['photo']?>">
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                    <?php
                                                                                    $price = "";
                                                                                    if ($mdl['denomination'] === "1") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "2") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "3") {
                                                                                        $luas   =   $mdl['height'] * $mdl['length'];
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "4") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    }
                                                                                    ?>
                                                                                    <td class="uk-text-left"><?= "Rp. " . number_format($price, 0, ',', '.');" "; ?></td>
                                                                                </tr>
                                                    <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if(!empty($projectdata[$project['id']]['custrab'])) {?>
                                                        <tr>
                                                            <td class="uk-text-bold"></td>
                                                            <td class="-text-bold"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-center"></td>
                                                            <td class="uk-text-bold">TAMBAHAN PESANAN</td>
                                                            <td class="uk-text-bold"></td>
                                                        </tr>
                                                        <?php foreach ($projectdata[$project['id']]['custrab'] as $custrab) {?>
                                                            <tr>
                                                                <td class=""></td>
                                                                <td class="uk-text-left"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-center"></td>
                                                                <td class="uk-text-left uk-width-1-6"><?= strtoupper($custrab['name']) ?></td>
                                                                <td class="uk-text-left"><?= "Rp. " . number_format($custrab['price'], 0, ',', '.');" "; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php if(!empty($project['sph'])) { ?>
                                                <p class="uk-text-right uk-width-1-1" uk-margin>
                                                    <a class="uk-button uk-button-primary uk-margin-small-right" href="img/sph/<?= $project['sph'] ?>" target="_blank"><span class='uk-margin-small-right uk-icon' uk-icon='icon: download; ratio: 1.2'></span>Download SPH</a>
                                                </p>
                                                <hr class="uk-margin">
                                            <?php } ?>
                                        </div>

                                        <!-- Desain -->
                                        <?php if (!empty($projectdesign[$project['id']]['design']['submitted'])) { ?>
                                            <div class="uk-width-1-1">
                                                <div>
                                                    <div class="uk-child-width-1-2" uk-grid>
                                                        <div>
                                                            <div class="uk-child-width-1-2" uk-grid>
                                                                <div class="uk-width-1-6">
                                                                    <div>
                                                                        <h4>Desain</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="uk-child-width-expand" id="st<?= $designId ?>">
                                                                    <?php
                                                                    if ($designStatus === "0") {
                                                                        echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; border-color: #ff0000; color:#ff0000;  font-weight: bold;"> Menuggu Konfirmasi </div>';
                                                                    } elseif ($designStatus === "1") {
                                                                        echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; color: #FFEA00; border-color:#FFEA00;  font-weight: bold;"> Proses Revisi </div>';
                                                                    } else {
                                                                        echo '<div class="uk-text-light uk-text-center" id="status' . $designId . '" style="border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;"> Terkonfirmasi </div>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                                <div>
                                                                    <div id="containerbtndsn<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 2" id="btndowndsn<?= $project['id'] ?>"></div>
                                                                    <div id="containerbtnupdsn<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 2" id="btnupdsn<?= $project['id'] ?>"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="contentdsn<?= $project['id'] ?>" hidden>
                                                        <div class="uk-margin" uk-grid>
                                                            <div class="uk-width-small@m">
                                                                <div class="">
                                                                    <?php
                                                                    if ($designStatus === "0") {
                                                                        echo "Tanggal Upload Desain";
                                                                    } elseif ($designStatus === "1") {
                                                                        echo "Tanggal Revisi Desain";
                                                                    } else {
                                                                        echo "Tanggal Disetujui";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="uk-width-1-3@m">
                                                                <div>
                                                                    <?= $tanggaldesign ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="uk-margin" uk-grid>
                                                            <div class="uk-width-small@m">
                                                                <div class="">File Design</div>
                                                            </div>
                                                            <div class="uk-width-1-3@m">
                                                                <div>
                                                                    <a href="img/design/<?= $desainpro ?>" target="_blank" download><span uk-icon="file-text"></span> <?= $desainpro ?> </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if (!empty($project['ded'])) { ?>
                                                            <div class="uk-margin" uk-grid>
                                                                <div class="uk-width-small@m">
                                                                    <div class="">File Layout/DED</div>
                                                                </div>
                                                                <div class="uk-width-1-3@m">
                                                                    <div>
                                                                        <a href="img/revisi/<?= $project['ded'] ?>" target="_blank" download><span uk-icon="file-text"></span> <?= $project['ded'] ?> </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if (!empty($projectdesign[$project['id']]['design']['revision'])) { ?>
                                                            <div class="uk-margin" uk-grid>
                                                                <div class="uk-width-small@m">
                                                                    <div class="">File Revisi</div>
                                                                </div>
                                                                <div class="uk-width-1-3@m">
                                                                    <div>
                                                                        <a href="img/revisi/<?= $revisi ?>" target="_blank" download><span uk-icon="file-text"></span><?= $revisi ?></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($designStatus != "2") { ?>
                                                            <?php if ($authorize->hasPermission('client.auth.branch', $uid) || $authorize->hasPermission('client.auth.holding', $uid) ) { ?>
                                                                <div class="uk-text-right" id="btndesain<?= $designId ?>" uk-margin>
                                                                    <button class="uk-button uk-button-primary" value="2" id="acc<?= $designId ?>">Konfirmasi</button>
                                                                    <button class="uk-button uk-button-secondary" uk-toggle="target: #modal-revisi<?= $project['id'] ?>">Revisi</button>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $("#acc<?= $designId ?>").click(function(){
                                                                    let text = "Anda sudah yakin dengan desain ini?";
                                                                    if (confirm(text) == true) {
                                                                        $.ajax({
                                                                            url: "home/acc/<?= $designId ?>",
                                                                            method: "POST",
                                                                            data: {
                                                                                status: $('#acc<?= $designId ?>').val(),
                                                                            },
                                                                            dataType: "json",
                                                                            error: function() {
                                                                                console.log('error', arguments);
                                                                            },
                                                                            success: function() {
                                                                                console.log('success', arguments);
                                                                                $("#status<?= $designId ?>").remove();
                                                                            $("#btndesain<?= $designId ?>").remove();
                                                                            $("#st<?= $designId ?>").append("<div class='uk-text-light uk-text-center' style='border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;'>Terkonfirmasi</div>");
                                                                            },
                                                                        })
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                                <hr class="uk-margin">
                                            </div>
                                        <?php } ?>
                                        <!-- end of desain -->

                                        <!-- Production Section -->
                                        <div class="uk-width-1-1 uk-margin-bottom-remove">
                                            <div class="uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <div class="">
                                                        <h4 class="uk-width-1-1">Produksi</h4>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                        <div>
                                                            <div id="containerbtnproduksi<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 2" id="btndownproduksi<?= $project['id'] ?>"></div>
                                                            <div id="containerbtnupproduksi<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 2" id="btnupproduksi<?= $project['id'] ?>"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                
                                        <div id="contentproduksi<?= $project['id'] ?>" class="uk-width-1-1 uk-margin-remove" hidden>
                                            <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                                <table class="uk-table uk-table-middle uk-table-divider">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <th class="uk-text-center">Gambar Kerja</th>
                                                            <th class="uk-text-center">Mesin Awal</th>
                                                            <th class="uk-text-center">Tukang</th>
                                                            <th class="uk-text-center">Mesin Lanjutan</th>
                                                            <th class="uk-text-center">Finishing</th>
                                                            <th class="uk-text-center">Packing</th>
                                                            <th class="uk-text-center">Pengiriman</th>
                                                            <th class="uk-text-center">Setting</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($projectdata[$project['id']]['production'] as $production) { ?>
                                                            <tr>
                                                                <td><?= $production['name'] ?></td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['gambar_kerja']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="gambarkerja<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_awal']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="mesinawal<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['tukang']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="tukang<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_lanjutan']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="mesinlanjutan<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['finishing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="finishing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['packing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="packing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['pengiriman']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="pengiriman<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="setting<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" disabled>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <div><?= $production['percentages'] ?> %</div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <hr>
                                            </div>

                                            <!-- Bukti Pengiriman -->
                                            <div class="uk-margin">Bukti Pengiriman</div>
                                            <div class="uk-child-width-1-6 uk-grid-match uk-flex-middle uk-grid-divider" uk-grid uk-lightbox="animation: slide">
                                                <?php foreach($projectdata[$project['id']]['buktipengiriman'] as $sendproof) { ?>
                                                    <div>
                                                        <a class="uk-inline-clip uk-transition-toggle uk-link-toggle" href="img/bukti/pengiriman/<?= $sendproof['file'] ?>" data-caption="<?= $sendproof['file'] ?>">
                                                            <img src="img/bukti/pengiriman/<?= $sendproof['file'] ?>" alt="<?= $sendproof['file'] ?>" class="uk-transition-opaque">
                                                            <div class="uk-overlay-primary uk-transition-fade uk-position-cover"></div>
                                                            <div class="uk-position-center uk-transition-fade">
                                                                <div class="uk-overlay">
                                                                    <div class="uk-h4 uk-margin-top uk-margin-remove-bottom uk-text-center uk-light" id="pengiriman<?= $sendproof['id'] ?>"></div>
                                                                </div>
                                                            </div>
                                                        </a>

                                                        <div class="uk-margin"><?= $sendproof['note'] ?></div>

                                                        <script>
                                                            // Date In Indonesia
                                                            var publishupdate   = "<?= $sendproof['created_at'] ?>";
                                                            var thatdate        = publishupdate.split( /[- :]/ );
                                                            thatdate[1]--;
                                                            var publishthatdate = new Date( ...thatdate );
                                                            var publishyear     = publishthatdate.getFullYear();
                                                            var publishmonth    = publishthatdate.getMonth();
                                                            var publishdate     = publishthatdate.getDate();
                                                            var publishday      = publishthatdate.getDay();

                                                            switch(publishday) {
                                                                case 0: publishday     = "Minggu"; break;
                                                                case 1: publishday     = "Senin"; break;
                                                                case 2: publishday     = "Selasa"; break;
                                                                case 3: publishday     = "Rabu"; break;
                                                                case 4: publishday     = "Kamis"; break;
                                                                case 5: publishday     = "Jum'at"; break;
                                                                case 6: publishday     = "Sabtu"; break;
                                                            }
                                                            switch(publishmonth) {
                                                                case 0: publishmonth   = "Januari"; break;
                                                                case 1: publishmonth   = "Februari"; break;
                                                                case 2: publishmonth   = "Maret"; break;
                                                                case 3: publishmonth   = "April"; break;
                                                                case 4: publishmonth   = "Mei"; break;
                                                                case 5: publishmonth   = "Juni"; break;
                                                                case 6: publishmonth   = "Juli"; break;
                                                                case 7: publishmonth   = "Agustus"; break;
                                                                case 8: publishmonth   = "September"; break;
                                                                case 9: publishmonth   = "Oktober"; break;
                                                                case 10: publishmonth  = "November"; break;
                                                                case 11: publishmonth  = "Desember"; break;
                                                            }

                                                            var publishfulldate         = publishday + ", " + publishdate + " " + publishmonth + " " + publishyear;
                                                            document.getElementById("pengiriman<?= $sendproof['id'] ?>").innerHTML = publishfulldate;
                                                        </script>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <!-- Bukti Pengiriman End -->
                                        </div>
                                        <!-- Production Section End -->

                                        <!-- Invoice -->
                                        <div class="uk-width-1-1 uk-margin-remove-bottom">
                                            <div class="uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <div class="">
                                                        <h4 class="uk-width-1-1">File Invoice</h4>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                        <div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-margin uk-margin-remove-top" uk-grid>
                                                <?php if ($authorize->hasPermission('client.auth.branch', $uid) || $authorize->hasPermission('client.auth.holding', $uid) ) { ?>
                                                    <div class="uk-width-1-1">
                                                        <!-- Invoice -->
                                                        <p class="uk-margin-remove-top" uk-margin>
                                                            <?php
                                                            // Invoice I
                                                            if(!empty($projectdata[$project['id']]['project']) && !empty($projectdata[$project['id']]['invoice1'])){
                                                                if ($projectdata[$project['id']]['project']['status_spk'] === "1" && !empty($projectdata[$project['id']]['invoice1']['file']) ) {
                                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' target='_blank' href='img/invoice/". $projectdata[$project['id']]['invoice1']['file']."'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice I</a>";
                                                                }
                                                            }

                                                            // Invoice II
                                                            if(!empty($projectdata[$project['id']]['sertrim'])  && !empty($projectdata[$project['id']]['invoice2'])){
                                                                if (isset($projectdata[$project['id']]['sertrim']['status']) && $progress >= "60" && $projectdata[$project['id']]['sertrim']['status'] === "0" &&  !empty($projectdata[$project['id']]['invoice2']['file'])) {
                                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' target='_blank' href='img/invoice/". $projectdata[$project['id']]['invoice2']['file']."'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice II</a>";
                                                                }
                                                            }

                                                            // Invoice III
                                                            if(!empty($projectdata[$project['id']]['bast'])  && !empty($projectdata[$project['id']]['invoice3'])){
                                                                if (isset($projectdata[$project['id']]['bast']['status']) && $progress >= "95" && $projectdata[$project['id']]['bast']['status'] === "1" && !empty($projectdata[$project['id']]['bast']['file']) && !empty($projectdata[$project['id']]['invoice3']['file'])) {
                                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' target='_blank' href='img/invoice/". $projectdata[$project['id']]['invoice3']['file']."'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice III</a>";
                                                                    $status = "Retensi";
                                                                }
                                                            }

                                                            // Invoice IV
                                                            if(!empty($projectdata[$project['id']]['bast']) && !empty($projectdata[$project['id']]['invoice4'])){
                                                                if (!empty($projectdata[$project['id']]['bast']['tanggal_bast'])) {
                                                                    if ($projectdata[$project['id']]['bast']['status'] === "1" && $projectdata[$project['id']]['now'] >=  $projectdata[$project['id']]['dateline'] && $progress >= "95" && !empty($projectdata[$project['id']]['invoice4']['file'])) {
                                                                        echo "<a id='btninv" . $project['id'] . "'  target='_blank' class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel/". $projectdata[$project['id']]['invoice4']['file']."'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice IV</a>";
                                                                        $progress   = "100";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <hr class="uk-margin">
                                        </div>
                                        <?php if (!empty($projectdata[$project['id']]['dateline']) && !empty($projectdata[$project['id']]['inv4'])) { ?>
                                            <script>
                                                $(document).ready(function() {

                                                    var proid = <?= $project['id'] ?>;
                                                    var today = new Date();
                                                    var dateline = new Date("<?= $projectdata[$project['id']]['dateline'] ?>");
                                                    var inv4 = "<?= $projectdata[$project['id']]['project']['inv4']; ?>";
                                                    var progress = "<?= (int)$progress ?>"

                                                    if (inv4 == '' && today != '' && dateline != '' && progress >= 95 && today > dateline) {
                                                        $.ajax({
                                                            url: "project/inv4/" + proid,
                                                            method: "POST",
                                                            data: {
                                                                id: proid,
                                                                dateline: "<?= $projectdata[$project['id']]['dateline'] ?>",
                                                            },
                                                            dataType: "json",
                                                            error: function() {
                                                                console.log('error', arguments);
                                                            },
                                                            success: function(data) {
                                                                console.log('success', arguments);
                                                                console.log(data);
                                                            }
                                                        });
                                                    } else {
                                                        console.log("nothing");
                                                    }
                                                });
                                            </script>
                                        <?php } ?>
                                        <!-- End Of Invoice -->

                                        <!-- SPK -->
                                        <div class="uk-width-1-1">
                                            <div class="uk-child-width-1-2 uk-margin-remove-bottom" uk-grid>
                                                <div>
                                                    <div class="uk-margin-remove-bottom">
                                                        <h4 class="uk-width-1-1">File SPK</h4>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                        <div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-margin-remove-top" uk-grid>
                                                <?php if ($authorize->hasPermission('client.auth.branch', $uid) || $authorize->hasPermission('client.auth.holding', $uid)) { ?>
                                                    <div class="uk-width-1-1">
                                                        <!-- SPK -->
                                                        <p class="" uk-margin>
                                                            <?php
                                                            if(!empty($projectdata[$project['id']]['project'])){
                                                                // if ($projectdata[$project['id']]['project']['status_spk'] === null) {
                                                                //     echo "<a class='uk-button uk-button-primary uk-margin-remove-top' uk-toggle='target: #modal-spk" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: upload; ratio: 1.2'></span>Upload SPK</a>";
                                                                //     // echo "<button class='uk-button uk-button-primary' uk-toggle='target: #modal-spk" . $project['id'] . "'>Upload SPK</button>";
                                                                // } elseif ($projectdata[$project['id']]['project']['status_spk'] === "0") {
                                                                //     echo "<button class='uk-button uk-button-primary uk-margin-right' uk-toggle='target: #modal-spk" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: upload; ratio: 1.2'></span>Upload SPK</button><a class='uk-button uk-button-secondary' target='_blank' href='img/spk/" . $spkpro . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: download; ratio: 1.2'></span>Download SPK</a>";
                                                                //     // echo "<a class='uk-button uk-button-primary' uk-toggle='target: #modal-spk" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: upload; ratio: 1.2'></span>Upload SPK</a>";
                                                                // } else {
                                                                //     echo "<a class='uk-button uk-button-secondary' href='img/spk/" . $spkpro . "' target='_blank'><span class='uk-margin-small-right uk-icon' uk-icon='icon: download; ratio: 1.2'></span>Download SPK</a>";
                                                                // }
                                                                if($projectdata[$project['id']]['project']['spk'] != null){
                                                                    echo "<button class='uk-button uk-button-primary uk-margin-right' uk-toggle='target: #modal-spk" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: upload; ratio: 1.2'></span>Upload SPK</button><a class='uk-button uk-button-secondary' target='_blank' href='img/spk/" . $spkpro . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon: download; ratio: 1.2'></span>Download SPK</a>";
                                                                }elseif($projectdata[$project['id']]['project']['spk'] === null){
                                                                    echo "<button class='uk-button uk-button-primary' uk-toggle='target: #modal-spk" . $project['id'] . "'>Upload SPK</button>";
                                                                }
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <hr class="uk-margin">
                                        </div>
                                        <!-- End Of SPK -->

                                        <!-- Bukti Pembayaran -->
                                        <div class="uk-width-1-1 uk-margin-bottom-remove">
                                            <div class="uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <h4 class="uk-width-1-1">Bukti Pembayaran</h4>
                                                    <button class="uk-button uk-button-primary" uk-toggle="target: #modal-bukti-pembayaran<?= $project['id'] ?>">Upload Bukti Pembayaran</button>
                                                </div>
                                                <div>
                                                    <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                        <div>
                                                            <div id="containerbtnbuktipembayaran<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 2" id="btndownbuktipembayaran<?= $project['id'] ?>"></div>
                                                            <div id="containerbtnupbuktipembayaran<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 2" id="btnupbuktipembayaran<?= $project['id'] ?>"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div id="contentbuktipembayaran<?= $project['id'] ?>" hidden>
                                            <div class="uk-child-width-auto uk-grid-match uk-flex-middle" uk-grid uk-lightbox="animation: slide">
                                                <?php foreach($projectdata[$project['id']]['buktipembayaran'] as $payproof) { ?>
                                                    <div>
                                                        <div class="uk-card uk-card-default uk-card-body"><a href="/img/bukti/pembayaran/<?= $payproof['file'] ?>" data-caption="<?= $payproof['file'] ?>"><span uk-icon="file-pdf"></span><?= $payproof['file'] ?></a></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Bukti Pembayaran End -->

                                        <script>
                                            $(document).ready(function() {
                                                $("span[id='btndown<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnup<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='content<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='containerbtn<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btnup<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtn<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='content<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='containerbtnup<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btndownproduksi<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnupproduksi<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='containerbtnproduksi<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='contentproduksi<?= $project['id'] ?>']").attr("hidden", false);
                                                });

                                                $("span[id='btnupproduksi<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnproduksi<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentproduksi<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='containerbtnupproduksi<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btndownbuktipembayaran<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnupbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='containerbtnbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='contentbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                                                });

                                                $("span[id='btnupbuktipembayaran<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnbuktipembayaran<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='containerbtnupbuktipembayaran<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btndownsph<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnupsph<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentsph<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='containerbtnsph<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btnupsph<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnsph<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentsph<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='containerbtnupsph<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btndowndsn<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtnupdsn<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentdsn<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='containerbtndsn<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                                $("span[id='btnupdsn<?= $project['id'] ?>']").click(function() {
                                                    $("div[id='containerbtndsn<?= $project['id'] ?>']").attr("hidden", false);
                                                    $("div[id='contentdsn<?= $project['id'] ?>']").attr("hidden", true);
                                                    $("div[id='containerbtnupdsn<?= $project['id'] ?>']").attr("hidden", true);
                                                });

                                            });
                                        </script>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <div class="uk-text-center uk-text-italic">Data tidak Ditemukan.</div>
        <?php } ?>
        <!-- end of Content -->

        <?= $pager ?>

        <!-- Report Section -->
        <?php
            $datachart = [];
            foreach ($projectsreport as $project) {
                $datachart[] = [
                    'tanggal'   => $project['created_at'],
                    'nilaispk'  => $projectdatareport[$project['id']]['rabvalue'] + $projectdatareport[$project['id']]['allcustomrab'],
                ];
            }
        ?>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([

                    ['Tanggal', 'Nilai SPK', ],
                    <?php
                    if (!empty($datachart)) {
                        foreach ($datachart as $chart) {
                            $date = date_create($chart['tanggal']);
                            $tanggal = date_format($date, 'Y-m-d');
                            $nilaispk = $chart['nilaispk'];
                            echo "['$tanggal', $nilaispk,],";
                        }
                    } else {
                        echo "['belum ada proyek', 0,],";
                    } ?>
                ]);

                var options = {
                    title: 'Laporan Nilai SPK',
                    hAxis: {
                        title: '',
                        titleTextStyle: {
                            color: '#333'
                        }
                    },
                    vAxis: {
                        minValue: 0
                    }
                };

                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>

        <!-- Section Report  -->
        <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-margin-large-top">
            <!-- Page Heading Report -->
            <?php if ($ismobile === true) { ?>
                <h1 class="tm-h1 uk-text-center uk-margin-remove">Daftar & Laporan Proyek</h1>
                <div class="uk-margin uk-text-center">
                    <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
                </div>
                <div id="filter" class="uk-margin" hidden>
                    <form id="searchform" action="home" method="GET">
                        <div class="uk-margin-small uk-flex uk-flex-center">
                            <input class="uk-input uk-form-width-medium" id="search" name="searchproyek" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
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
                <script>
                    document.getElementById('filterbutton').addEventListener('click', toggleiconfilter);

                    function toggleiconfilter() {
                        var close = document.getElementById('filterclose').hasAttribute('hidden');
                        if (close === true) {
                            document.getElementById('filteropen').setAttribute('hidden', '');
                            document.getElementById('filterclose').removeAttribute('hidden');
                        } else {
                            document.getElementById('filteropen').removeAttribute('hidden');
                            document.getElementById('filterclose').setAttribute('hidden', '');
                        }
                    }
                </script>
            <?php } else { ?>
                <h3 class="tm-h3 uk-margin-remove-bottom">Laporan Proyek Klien</h3>
            <?php } ?>
            <!-- end of Page Heading Report -->

            <div id="chart_div" class="uk-margin" style="width: 100%; height: 500px;"></div>

        </div>
        
        <div class="uk-child-width-1-3@s uk-text-left uk-margin" uk-grid>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Jumlah Proyek : <?= $total ?></div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">
                    <?php
                    $selesai = 0;
                    foreach ($projectsreport as $project) {
                        if ($projectdatareport[$project['id']]['dateline'] < $projectdatareport[$project['id']]['now']) {
                            $selesai += 1;
                        }
                    }
                    ?>
                    Total Proyek Selesai : <?= $selesai ?>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Proyek Dalam Proses : <?= $total - $selesai ?></div>
            </div>
        </div>
        <div class="uk-child-width-1-3@s uk-text-left" uk-grid>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total SPK :
                    <?php
                    $spkvalue = [];
                    foreach ($projectsreport as $project) {
                        $spkvalue[] = $projectdatareport[$project['id']]['rabvalue'] + $projectdatareport[$project['id']]['allcustomrab'];
                    }
                    echo "Rp." . number_format(array_sum($spkvalue), 0, ',', '.');
                    ?>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Terbayar :
                    <?php
                    $terbayar = [];
                    foreach ($projectsreport as $project) {
                        $terbayar[] = $projectdatareport[$project['id']]['pembayaran'];
                    }
                    echo "Rp." . number_format(array_sum($terbayar), 0, ',', '.');
                    ?>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Belum Terbayar :
                    <?php
                    $pembayarankurang = array_sum($terbayar);
                    $spkval = array_sum($spkvalue);
                    echo "Rp." . number_format($spkval - $pembayarankurang, 0, ',', '.');
                    ?>
                </div>
            </div>
        </div>

        <!-- form input -->
        <?php if ($ismobile === false) { ?>
            <div class="uk-child-width-1-2@s uk-text-left" uk-grid>
                <div class="uk-width-1-3">
                    <div class="">
                        <form id="short" action="home" method="get">
                            <div class="uk-inline">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                <input class="uk-input uk-width-medium" type="text" id="daterange" name="daterange" value="<?= date('m/d/Y', $startdate) ?> - <?= date('m/d/Y', $enddate) ?>" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="uk-width-auto">
                    <div>
                        <form class="uk-margin" id="searchform" action="home" method="GET">
                            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                                <div>
                                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                        <div>Cari:</div>
                                        <div><input class="uk-input uk-form-width-medium" id="search" placeholder="Masukkan Nama Proyek..." name="searchproyek" <?= (isset($input['searchproyek']) ? 'value="' . $input['searchproyek'] . '"' : '') ?> /></div>
                                    </div>
                                </div>
                                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                    <div>
                                        <a class="uk-button uk-button-primary uk-button-default uk-width-1-1" href="laporan/excel?daterange=<?=date('Y-m-d', $startdate)?>+-+<?=date('Y-m-d', $enddate)?>" target="_blank"><span uk-icon="download"></span>Laporan</a>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                        <div>Tampilan</div>
                                        <div>
                                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                                <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                                <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                                <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                                <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                                            </select>
                                        </div>
                                        <div>Per Halaman</div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>

        <script>
            $(document).ready(function() {
                $(function() {
                    $('input[name="daterange"]').daterangepicker({
                        maxDate: new Date(),
                        opens: 'right'
                    }, function(start, end, label) {
                        document.getElementById('daterange').value = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                        console.log(document.getElementById('daterange').value);
                        document.getElementById('short').submit();
                    });
                });
            });
        </script>

        <!-- Table Of Content -->
        <div class="uk-overflow-auto uk-margin-large-top uk-margin-large-bottom">
            <table class="uk-table uk-table-striped">
                <thead>
                    <tr>
                        <th class="uk-width-large">Nama Proyek</th>
                        <th class="uk-width-medium">Klien</th>
                        <th class="uk-width-medium">Marketing</th>
                        <th class="uk-width-medium">Nilai SPK</th>
                        <th class="uk-width-medium">Terbayar</th>
                        <th class="uk-width-medium">Belum Terbayar</th>
                        <th class="uk-text-center uk-width-medium">Status Proyek</th>
                        <th class="uk-text-center uk-width-medium">Tanggal Proyek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projectsreport as $project) { ?>
                        <tr>
                            <td class=""><?= $project['name'] ?></td>
                            <td class=""><?= $projectdatareport[$project['id']]['klien']['rsname'] ?></td>
                            <td class=""><?= $projectdatareport[$project['id']]['marketing']->username ?></td>
                            <td class=""><?= "Rp." . number_format($projectdatareport[$project['id']]['rabvalue'] + $projectdatareport[$project['id']]['allcustomrab'], 0, ',', '.')  ?></td>
                            <td class=""><?= "Rp." . number_format($projectdatareport[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
                            <td class=""><?= "Rp." . number_format(($projectdatareport[$project['id']]['rabvalue'] + $projectdatareport[$project['id']]['allcustomrab'])-$projectdatareport[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
                            <td class="uk-text-center">
                                <?php if ($projectdatareport[$project['id']]['dateline'] < $projectdatareport[$project['id']]['now']) {
                                    echo 'Selesai';
                                } else {
                                    echo 'Dalam Proses';
                                } ?>
                            </td>
                            <td class="uk-text-center"><?= $project['created_at'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?= $pagerpro ?>
        </div>
        <!-- End Of Section Report -->

        <!-- This Is The Modal Revisi -->
        <?php if ($authorize->hasPermission('client.auth.branch', $uid) || $authorize->hasPermission('client.auth.holding', $uid) ) { ?>
            <?php if (!empty($projects)){ 
                foreach ($projects as $project) {?>
                    <div id="modal-revisi<?= $project['id'] ?>" uk-modal>
                        <div class="uk-modal-dialog">
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                            <div class="uk-modal-header">
                                <h2 class="uk-modal-title">Revisi Desain</h2>
                            </div>
                            <div class="uk-modal-body">
                                <form class="uk-form-stacked" action="home/saverevisi/<?= $project['id'] ?>" method="post">
                                    <?php if (!empty($projectdesign[$project['id']]['design']['revision'])) { ?>
                                        <a href="img/revisi/<?= $revisi ?>" target="_blank" class="uk-link-reset">
                                            <div class="uk-card uk-card-default uk-card-hover uk-width-1-1@m">
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                                                        <div class="uk-width-expand">
                                                            <h6 class="uk-margin-remove-bottom">File Revisi</h6>
                                                            <p class="uk-text-meta uk-margin-remove-top"><?= $tanggaldesign ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-body">
                                                    <a href="img/revisi/<?= $revisi ?>" target="_blank" class="uk-link-reset">
                                                        <h6><a href="img/revisi/<?= $revisi ?>" uk-icon="file-text"></a> <a href="img/revisi/<?= $revisi ?>" target="_blank"><?= $revisi ?></a></h6>
                                                    </a>
                                                </div>
                                            </div>
                                        </a>
                                    <?php } ?>

                                    <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                        <label class="uk-form-label" for="photocreate">Unggah Catatan Revisi</label>
                                        <div class="uk-placeholder" id="placerev<?= $project['id'] ?>" hidden>
                                            <div uk-grid>
                                                <div class="uk-text-left uk-width-3-4">
                                                    <div id="uprevisi<?= $project['id'] ?>">
                                                    </div>
                                                </div>
                                                <div class="uk-text-right uk-width-1-4">
                                                    <div id="closed<?= $project['id'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                            <input id="photocreate<?= $project['id'] ?>" name="revisi" hidden />
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
                                            url: 'home/revisi',
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

                                                if (document.getElementById('display-container-create-<?= $project['id'] ?>')) {
                                                    document.getElementById('display-container-create-<?= $project['id'] ?>').remove();
                                                };

                                                document.getElementById('photocreate<?= $project['id'] ?>').value = filename;

                                                document.getElementById('placerev<?= $project['id'] ?>').removeAttribute('hidden');

                                                var uprev = document.getElementById('uprevisi<?= $project['id'] ?>');
                                                var closed = document.getElementById('closed<?= $project['id'] ?>');

                                                var divuprev = document.createElement('h6');
                                                divuprev.setAttribute('class', 'uk-margin-remove');
                                                divuprev.setAttribute('id', 'revision<?= $project['id'] ?>');


                                                var linkrev = document.createElement('a');
                                                linkrev.setAttribute('href', 'img/revisi/' + filename);
                                                linkrev.setAttribute('uk-icon', 'file-text');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/revisi/' + filename);
                                                link.setAttribute('target', '_blank');

                                                var linktext = document.createTextNode(filename);

                                                var divclosed = document.createElement('a');
                                                divclosed.setAttribute('uk-icon', 'icon: close');
                                                divclosed.setAttribute('onClick', 'removeImgCreate<?= $project['id'] ?>()');
                                                divclosed.setAttribute('id', 'closerev<?= $project['id'] ?>');

                                                uprev.appendChild(divuprev);
                                                divuprev.appendChild(linkrev);
                                                divuprev.appendChild(link);
                                                link.appendChild(linktext);
                                                closed.appendChild(divclosed);

                                                document.getElementById('js-upload-create-<?= $project['id'] ?>').setAttribute('hidden', '');
                                            },

                                            loadStart: function(e) {
                                                console.log('loadStart', arguments);

                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').removeAttribute('hidden');

                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').value = e.loaded;

                                            },

                                            progress: function(e) {
                                                console.log('progress', arguments);

                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            loadEnd: function(e) {
                                                console.log('loadEnd', arguments);

                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-create-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            completeAll: function() {
                                                console.log('completeAll', arguments);

                                                setTimeout(function() {
                                                    document.getElementById('js-progressbar-create-<?= $project['id'] ?>').setAttribute('hidden', 'hidden');
                                                    alert('Proses unggah data selesai, Silahkan kirim data revisi.');
                                                }, 1000);
                                            }

                                        });

                                        function removeImgCreate<?= $project['id'] ?>() {
                                            $.ajax({
                                                type: 'post',
                                                url: 'home/removerevisi',
                                                data: {
                                                    'revisi': document.getElementById('photocreate<?= $project['id'] ?>').value
                                                },
                                                dataType: 'json',

                                                error: function() {
                                                    console.log('error', arguments);
                                                },

                                                success: function() {
                                                    console.log('success', arguments);

                                                    var pesan = arguments[0][1];

                                                    document.getElementById('revision<?= $project['id'] ?>').remove();
                                                    document.getElementById('closerev<?= $project['id'] ?>').remove();
                                                    document.getElementById('placerev<?= $project['id'] ?>').setAttribute('hidden', '');
                                                    document.getElementById('photocreate<?= $project['id'] ?>').value = '';

                                                    document.getElementById('js-upload-create-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                    alert(pesan);
                                                }
                                            });
                                        };
                                    </script>
                                    <div class="uk-modal-footer uk-text-center">
                                        <button class="uk-button uk-button-primary" type="submit">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Of Modal Revisi -->

                    <!-- modal SPK -->
                    <div id="modal-spk<?= $project['id'] ?>" uk-modal>
                        <div class="uk-modal-dialog">
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                            <div class="uk-modal-header">
                                <h2 class="uk-modal-title">Unggah File SPK</h2>
                            </div>
                            <div class="uk-modal-body">
                                <form class="uk-form-stacked" action="upload/savespk/<?= $project['id'] ?>" method="post">

                                    <?php
                                    $dateTimeObj = new DateTime($datepro, new DateTimeZone('Asia/Jakarta'));
                                    $dateFormatted =
                                        IntlDateFormatter::formatObject(
                                            $dateTimeObj,
                                            'eeee, d MMMM y',
                                            'id'
                                        );
                                    ?>
                                    <?php if (!empty($projectdata[$project['id']]['project']['spk'])) { ?>
                                        <a href="img/spk/<?= $projectdata[$project['id']]['project']['spk'] ?>" target="_blank" class="uk-link-reset">
                                            <div class="uk-card uk-card-default uk-card-hover uk-width-1-1@m">
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                                                        <div class="uk-width-expand">
                                                            <h6 class="uk-margin-remove-bottom">File SPK</h6>
                                                            <p class="uk-text-meta uk-margin-remove-top"><?= ucwords($dateFormatted) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-body">
                                                    <a href="img/spk/<?= $projectdata[$project['id']]['project']['spk'] ?>" target="_blank" class="uk-link-reset">
                                                        <h6><a href="img/spk/<?= $projectdata[$project['id']]['project']['spk'] ?>" uk-icon="file-text"></a> <a href="img/spk/<?= $projectdata[$project['id']]['project']['spk'] ?>" target="_blank"><?= $projectdata[$project['id']]['project']['spk'] ?></a></h6>
                                                    </a>
                                                </div>
                                            </div>
                                        </a>
                                    <?php } ?>

                                    <div class="uk-margin" id="image-container-createspk-<?= $project['id'] ?>">
                                        <label class="uk-form-label" for="photocreate">Kirim File SPK</label>
                                        <div class="uk-placeholder" id="placespk<?= $project['id'] ?>" hidden>
                                            <div uk-grid>
                                                <div class="uk-text-left uk-width-3-4">
                                                    <div id="upspk<?= $project['id'] ?>"></div>
                                                </div>
                                                <div class="uk-text-right uk-width-1-4">
                                                    <div id="closespk<?= $project['id'] ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="image-containerspk-<?= $project['id'] ?>" class="uk-form-controls">
                                            <input id="photocreatespk<?= $project['id'] ?>" name="spk" hidden />
                                            <div id="js-upload-createspk-<?= $project['id'] ?>" class="js-upload-createspk-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                <span uk-icon="icon: cloud-upload"></span>
                                                <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                <div uk-form-custom>
                                                    <input type="file">
                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                </div>
                                            </div>
                                            <progress id="js-progressbar-createspk-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        var barspk = document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>');
                                        UIkit.upload('.js-upload-createspk-<?= $project['id'] ?>', {
                                            url: 'upload/spk',
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
                                                console.log(filename);

                                                if (document.getElementById('display-container-createspk-<?= $project['id'] ?>')) {
                                                    document.getElementById('display-container-createspk-<?= $project['id'] ?>').remove();
                                                };

                                                document.getElementById('photocreatespk<?= $project['id'] ?>').value = filename;

                                                document.getElementById('placespk<?= $project['id'] ?>').removeAttribute('hidden');

                                                var uprev = document.getElementById('upspk<?= $project['id'] ?>');
                                                var closed = document.getElementById('closespk<?= $project['id'] ?>');

                                                var divuprev = document.createElement('h6');
                                                divuprev.setAttribute('class', 'uk-margin-remove');
                                                divuprev.setAttribute('id', 'spk<?= $project['id'] ?>');

                                                var linkrev = document.createElement('a');
                                                linkrev.setAttribute('href', 'img/revisi/' + filename);
                                                linkrev.setAttribute('uk-icon', 'file-text');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/revisi/' + filename);
                                                link.setAttribute('target', '_blank');

                                                var linktext = document.createTextNode(filename);

                                                var divclosed = document.createElement('a');
                                                divclosed.setAttribute('uk-icon', 'icon: close');
                                                divclosed.setAttribute('onClick', 'removeImgCreatespk<?= $project['id'] ?>()');
                                                divclosed.setAttribute('id', 'closedspk<?= $project['id'] ?>');

                                                uprev.appendChild(divuprev);
                                                divuprev.appendChild(linkrev);
                                                divuprev.appendChild(link);
                                                link.appendChild(linktext);
                                                closed.appendChild(divclosed);

                                                document.getElementById('js-upload-createspk-<?= $project['id'] ?>').setAttribute('hidden', '');
                                            },

                                            loadStart: function(e) {
                                                console.log('loadStart', arguments);

                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').removeAttribute('hidden');

                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').value = e.loaded;

                                            },

                                            progress: function(e) {
                                                console.log('progress', arguments);

                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            loadEnd: function(e) {
                                                console.log('loadEnd', arguments);

                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            completeAll: function() {
                                                console.log('completeAll', arguments);

                                                setTimeout(function() {
                                                    document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>').setAttribute('hidden', 'hidden');
                                                    alert('<?= lang('Proses selesai, Silahkan Unggah Data.') ?>');
                                                }, 1000);
                                            }

                                        });

                                        function removeImgCreatespk<?= $project['id'] ?>() {
                                            $.ajax({
                                                type: 'post',
                                                url: 'upload/removespk',
                                                data: {
                                                    'spk': document.getElementById('photocreatespk<?= $project['id'] ?>').value
                                                },
                                                dataType: 'json',

                                                error: function() {
                                                    console.log('error', arguments);
                                                },

                                                success: function() {
                                                    console.log('success', arguments);

                                                    var pesan = arguments[0][1];

                                                    document.getElementById('spk<?= $project['id'] ?>').remove();
                                                    document.getElementById('closedspk<?= $project['id'] ?>').remove();
                                                    document.getElementById('placespk<?= $project['id'] ?>').setAttribute('hidden', '');
                                                    document.getElementById('photocreatespk<?= $project['id'] ?>').value = '';

                                                    document.getElementById('js-upload-createspk-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                    alert(pesan);
                                                }
                                            });
                                        };
                                    </script>
                                    <div class="uk-modal-footer uk-text-center">
                                        <button class="uk-button uk-button-primary" type="submit">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Bukti Pembayaran -->
                    <div id="modal-bukti-pembayaran<?= $project['id'] ?>" uk-modal>
                        <div class="uk-modal-dialog">
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                            <div class="uk-modal-header">
                                <h2 class="uk-modal-title">Unggah File Bukti Pembayaran</h2>
                            </div>
                            <div class="uk-modal-body">
                                <form class="uk-form-stacked" action="home/buktipembayaran/<?= $project['id'] ?>" method="post">
                                    <div class="uk-margin" id="image-container-createbuktipembayaran-<?= $project['id'] ?>">
                                        <label class="uk-form-label" for="photocreate">Kirim File Bukti Pembayaran</label>
                                        <div class="uk-placeholder" id="placepayproof<?= $project['id'] ?>" hidden>
                                            <div uk-grid>
                                                <div class="uk-text-left uk-width-3-4">
                                                    <div id="uppayproof<?= $project['id'] ?>"></div>
                                                </div>
                                                <div class="uk-text-right uk-width-1-4">
                                                    <div id="closepayproof<?= $project['id'] ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="image-containerbuktipembayaran-<?= $project['id'] ?>" class="uk-form-controls">
                                            <input id="photocreatebuktipembayaran<?= $project['id'] ?>" name="buktipembayaran" hidden />
                                            <div id="js-upload-createbuktipembayaran-<?= $project['id'] ?>" class="js-upload-createbuktipembayaran-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                <span uk-icon="icon: cloud-upload"></span>
                                                <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                <div uk-form-custom>
                                                    <input type="file">
                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                </div>
                                            </div>
                                            <progress id="js-progressbar-createbuktipembayaran-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        // Upload Bukti Pembayaran
                                        var bar = document.getElementById('js-progressbar-createbuktipembayaran-<?= $project['id'] ?>');

                                        UIkit.upload('.js-upload-createbuktipembayaran-<?= $project['id'] ?>', {
                                            url: 'upload/buktipembayaran',
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

                                                if (document.getElementById('display-container-createbuktipembayaran-<?= $project['id'] ?>')) {
                                                    document.getElementById('display-container-createbuktipembayaran-<?= $project['id'] ?>').remove();
                                                };

                                                document.getElementById('photocreatebuktipembayaran<?= $project['id'] ?>').value = filename;

                                                document.getElementById('placepayproof<?= $project['id'] ?>').removeAttribute('hidden');

                                                var uprev = document.getElementById('uppayproof<?= $project['id'] ?>');
                                                var closed = document.getElementById('closepayproof<?= $project['id'] ?>');

                                                var divuprev = document.createElement('h6');
                                                divuprev.setAttribute('class', 'uk-margin-remove');
                                                divuprev.setAttribute('id', 'uppayment<?= $project['id'] ?>');
                                                
                                                var linkrev = document.createElement('a');
                                                linkrev.setAttribute('href', 'img/bukti/pembayaran/' + filename);
                                                linkrev.setAttribute('uk-icon', 'file-text');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/bukti/pembayaran/' + filename);
                                                link.setAttribute('target', '_blank');

                                                var linktext = document.createTextNode(filename);

                                                var divclosed = document.createElement('a');
                                                divclosed.setAttribute('uk-icon', 'icon: close');
                                                divclosed.setAttribute('onClick', 'removeImgCreatebuktipembayaran<?= $project['id'] ?>()');
                                                divclosed.setAttribute('id', 'closedpayproof<?= $project['id'] ?>');

                                                uprev.appendChild(divuprev);
                                                divuprev.appendChild(linkrev);
                                                divuprev.appendChild(link);
                                                link.appendChild(linktext);
                                                closed.appendChild(divclosed);

                                                document.getElementById('js-upload-createbuktipembayaran-<?= $project['id'] ?>').setAttribute('hidden', '');
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

                                        function removeImgCreatebuktipembayaran<?= $project['id'] ?>() {
                                            $.ajax({
                                                type: 'post',
                                                url: 'upload/removebuktipembayaran',
                                                data: {
                                                    'buktipembayaran': document.getElementById('photocreatebuktipembayaran<?= $project['id'] ?>').value
                                                },
                                                dataType: 'json',

                                                error: function() {
                                                    console.log('error', arguments);
                                                },

                                                success: function() {
                                                    console.log('success', arguments);

                                                    var pesan = arguments[0][1];

                                                    document.getElementById('uppayment<?= $project['id'] ?>').remove();
                                                    document.getElementById('closedpayproof<?= $project['id'] ?>').remove();
                                                    document.getElementById('placepayproof<?= $project['id'] ?>').setAttribute('hidden', '');
                                                    document.getElementById('photocreatebuktipembayaran<?= $project['id'] ?>').value = '';

                                                    document.getElementById('js-upload-createbuktipembayaran-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                    alert(pesan);
                                                }
                                            });
                                        };
                                    </script>
                                    <div class="uk-modal-footer uk-text-center">
                                        <button class="uk-button uk-button-primary" type="submit">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Bukti Pembayaran End -->
                <?php }
            }
        }?>
        <!-- End Modal SPK & Revisi -->
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>
    </div>
<?php } ?>
<?php if (!empty($input)) {
    if ($ismobile == false) { ?>
        <script>
            $(document).ready(function() {
                var unhidecontent = document.getElementById('content<?=$input?>');
                unhidecontent.removeAttribute('hidden');

                var opendropdown = document.getElementById('containerbtnup<?=$input?>');
                opendropdown.removeAttribute('hidden');

                var closedropdown = document.getElementById('containerbtn<?=$input?>');
                closedropdown.setAttribute('hidden', '');
            });
                
            window.addEventListener('load', () => setTimeout(() => {
                document.querySelector('#card-project<?= $input ?>').scrollIntoView()
            }))
        </script>
    <?php } else { ?>
        <script>
            $(document).ready(function() {
                var unhidebody = document.getElementById('body<?=$input?>');
                unhidebody.removeAttribute('hidden');

                var openmobile = document.getElementById('open<?=$input?>');
                openmobile.removeAttribute('hidden');

                var closemobile = document.getElementById('close<?=$input?>');
                closemobile.setAttribute('hidden', '');
            });
            
            window.addEventListener('load', () => setTimeout(() => {
                document.querySelector('#card-project<?= $input ?>').scrollIntoView()
            }))
        </script>
    <?php }
} ?>
<?= $this->endSection() ?>