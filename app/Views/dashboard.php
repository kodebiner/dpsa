<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="uk-container uk-container-large">

    <!-- Page Heading -->
    <?php if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) { ?>
        <?php if ($ismobile) { ?>
            <!-- <h1 class="tm-h1 uk-text-center uk-margin-remove"></?= lang("Global.projectList") ?><br/><//?=$client->firstname?> </?=$client->lastname?></h1> -->
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
            <!-- <h1 class="tm-h1 uk-margin-remove"></?= lang("Global.projectList") ?> </?=$client->firstname?> </?=$client->lastname?></h1> -->
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
            <?php
            $progress = "";
            $status = "";
            foreach ($projects as $project) { ?>
                <?php if ($project['status'] === "1") {
                    $status = "Proses Desain";
                    $progress = '5';
                } elseif ($project['status'] === "2") {
                    $status = "Menunggu Approval Desain";
                    $progress = '10';
                } elseif ($project['status'] === "3") {
                    $status = "Pengajuan RAB";
                    $progress = '20';
                } elseif ($project['status'] === "4") {
                    $status = "Dalam Proses Produksi";
                    if ($project['production'] === "0") {
                        $progress = '30';
                    } else {
                        $qty = round($project['production'] / 100 * 65, 2);
                        $progress = $qty;
                    }
                } elseif ($project['status'] === "5") {
                    $status = "Setting";
                    $progress = '95';
                } ?>
                <?php if ($ismobile) { ?>
                    <div class="uk-margin uk-card uk-card-default">
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
                                <div class="uk-width-1-1">
                                    <h4>Detail Pesanan</h4>
                                </div>
                                <?php if (!empty($project['id'])) { ?>
                                    <div class="uk-overflow-auto uk-margin">
                                        <table class="uk-table">
                                            <thead>
                                                <tr>
                                                    <th class="">Nama</th>
                                                    <th class="uk-text-center">Panjang</th>
                                                    <th class="uk-text-center">Lebar</th>
                                                    <th class="uk-text-center">Tinggi</th>
                                                    <th class="uk-text-center">Volume</th>
                                                    <th class="uk-text-center">Satuan</th>
                                                    <th class="uk-text-center">Jumlah Pesanan</th>
                                                    <th class="uk-text-center">Harga</th>
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
                                                                                <td class="uk-text-center"><?= $mdl['denomination'] ?></td>
                                                                                <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['price'] ?></td>
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
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Status Proyek</h4>
                                    <div class="uk-text-center"><?= $status ?></div>
                                </div>
                                <?php if ($project['status'] === '4') { ?>
                                    <div class="uk-width-1-2">
                                        <h4 class="uk-text-center">Progress Produksi</h4>
                                        <div class="uk-text-center"><?= $project['production'] ?>%</div>
                                    </div>
                                <?php } ?>
                            </div>
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
                    </script>
                <?php } else { ?>
                    <div class="uk-margin uk-card uk-card-default uk-card-hover uk-width-1-1">
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
                            <progress class="uk-progress" value="<?= $progress ?>" max="100"></progress>
                        </div>

                        <?php
                        // Data project initialize
                        if (!empty($projectdesign[$project['id']]['design']['updated_at'])) {
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
                            // dd($projectdesign);
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
                        <div id="content<?= $project['id'] ?>" hidden>
                            <div class="uk-card-body">
                                <div class="uk-grid" uk-grid>
                                    <div class="uk-width-1-2">
                                        <h4 class="">Status Proyek</h4>
                                        <div class=""><?= $status ?></div>
                                    </div>
                                    <?php if ($project['status'] === '4') { ?>
                                        <div class="uk-width-1-2">
                                            <h4 class="uk-text-center">Progress Produksi</h4>
                                            <div class="uk-text-center"><?= $project['production'] ?>%</div>
                                        </div>
                                    <?php } ?>

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
                                            <table class="uk-table uk-table-responsive uk-table-divider">
                                                <thead>
                                                    <tr>
                                                        <th class="">Nama</th>
                                                        <th class="uk-text-center">Panjang</th>
                                                        <th class="uk-text-center">Lebar</th>
                                                        <th class="uk-text-center">Tinggi</th>
                                                        <th class="uk-text-center">Volume</th>
                                                        <th class="uk-text-center">Satuan</th>
                                                        <th class="uk-text-center">Jumlah Pesanan</th>
                                                        <th class="uk-text-center">Harga</th>
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
                                                                                    <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                    <?php
                                                                                    $price = "";
                                                                                    if ($mdl['denomination'] === "1") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "2") {
                                                                                        $price  = $mdl['length'] * $mdl['price'];
                                                                                    } elseif ($mdl['denomination'] === "3") {
                                                                                        $luas   =   $mdl['height'] * $mdl['length'];
                                                                                        $price  =   $mdl['price'] * $luas;
                                                                                    } elseif ($mdl['denomination'] === "4") {
                                                                                        $price  = $rab['qty'] * $mdl['price'];
                                                                                    }
                                                                                    ?>
                                                                                    <td class="uk-text-center"><?= "Rp. " . number_format($price, 0, ',', '.');" "; ?></td>
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
                                                </tbody>
                                            </table>
                                            <p class="uk-text-right uk-width-1-1" uk-margin>
                                                <!-- <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/<?= $project['id'] ?>">Download SPH</a> -->
                                                <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphview/<?= $project['id'] ?>">Download SPH</a>
                                            </p>
                                            <hr class="uk-margin">
                                        </div>

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
                                                                <a href="img/revisi/<?= $desainpro ?>" target="_blank" uk-icon="file-text"></a> <a href="img/design/<?= $desainpro ?>" target="_blank"> <?= $desainpro ?> </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="uk-margin" uk-grid>
                                                        <div class="uk-width-small@m">
                                                            <div class="">File Revisi</div>
                                                        </div>
                                                        <div class="uk-width-1-3@m">
                                                            <div>
                                                                <?php if (!empty($projectdesign[$project['id']]['design']['revision'])) { ?>
                                                                    <a href="img/revisi/<?= $revisi ?>" target="_blank" uk-icon="file-text"></a> <a href="img/revisi/<?= $revisi ?>" target="_blank"><?= $revisi ?> </a>
                                                                <?php } else { ?>
                                                                    -
                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if ($designStatus != "2") { ?>
                                                        <div class="uk-text-right" id="btndesain<?= $designId ?>" uk-margin>
                                                            <button class="uk-button uk-button-primary" value="2" id="acc<?= $designId ?>" onclick="myFunction()">Konfirmasi</button>
                                                            <button class="uk-button uk-button-secondary" uk-toggle="target: #modal-revisi<?= $project['id'] ?>">Revisi</button>
                                                        </div>
                                                    <?php } ?>
                                                    <script>
                                                        function myFunction() {
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

                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                            <hr class="uk-margin">
                                        </div>

                                        <!-- This is the modal -->
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
                                        <!-- end of modal revisi -->
                                    <?php } ?>
                                    <!-- end of desain -->

                                    <!-- Invoice -->
                                    <div class="uk-width-1-1 uk-margin-bottom-remove">
                                        <div class="uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="">
                                                    <h4 class="uk-width-1-1">Invoice</h4>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="uk-child-width-1-1 uk-text-right" uk-grid>
                                                    <div>
                                                        <div id="containerbtninv<?= $project['id'] ?>"><span uk-icon="icon: chevron-down; ratio: 2" id="btndowninv<?= $project['id'] ?>"></div>
                                                        <div id="containerbtnupinv<?= $project['id'] ?>" hidden><span uk-icon="icon: chevron-up; ratio: 2" id="btnupinv<?= $project['id'] ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="uk-margin">
                                    </div>
                                    <!-- End Of Invoice -->

                                    <!-- SPK -->
                                    <p class="uk-text-right uk-width-1-1" uk-margin>
                                        <?php
                                        if ($projectdata[$project['id']]['project']['status_spk'] === null) {
                                            echo "<button class='uk-button uk-button-primary' uk-toggle='target: #modal-spk" . $project['id'] . "'>Upload SPK</button>";
                                        } elseif ($projectdata[$project['id']]['project']['status_spk'] === "0") {
                                            echo "<button class='uk-button uk-button-primary uk-margin-right' uk-toggle='target: #modal-spk" . $project['id'] . "'>Upload SPK</button><a class='uk-button uk-button-secondary' target='_blank' href='img/spk/" . $spkpro . "'>Download SPK</a>";
                                        } else {
                                            echo "<a class='uk-button uk-button-secondary' href='img/spk/" . $spkpro . " target='_blank'>Download SPK</a>";
                                        }
                                        ?>
                                    </p>

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
                                                    <?php if (!empty($spkpro)) { ?>
                                                        <a href="img/spk/<?= $spkpro ?>" target="_blank" class="uk-link-reset">
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
                                                                    <a href="img/spk/<?= $spkpro ?>" target="_blank" class="uk-link-reset">
                                                                        <h6><a href="img/spk/<?= $spkpro ?>" uk-icon="file-text"></a> <a href="img/spk/<?= $spkpro ?>" target="_blank"><?= $spkpro ?></a></h6>
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
                                                                    <div id="upspk<?= $project['id'] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="uk-text-right uk-width-1-4">
                                                                    <div id="closespk<?= $project['id'] ?>">
                                                                    </div>
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
                                    <!-- End Modal SPK -->

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
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>
    <?php } ?>
</div>
<?= $this->endSection() ?>