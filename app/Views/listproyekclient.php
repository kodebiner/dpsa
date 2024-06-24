<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>

<?= $this->endSection() ?>

<?php

    if ($authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid) ){
        $togledesign = "";
    }else{
        $togledesign = "hidden";
    }

    if ($authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('ppic.project.edit', $uid)){
        $togleproduction = "";
    }else{
        $togleproduction = "hidden";
    }

    if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)){
        $bastview = "";
    }else{
        $bastview = "hidden";
    }

    if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)){
        $tooglespk = "";
    }else{
        $tooglespk = "hidden";
    }

    if ($authorize->hasPermission('finance.project.edit', $uid)){
        $toglefinance = "";
    }else{
        $toglefinance = "hidden";
    }

    if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)){
        $toglebukti = "";
    }else{
        $toglebukti = "hidden";
    }

    if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)){
        $togledetailpesanan = "";
    }else{
        $togledetailpesanan = "hidden";
    }

?>


<?= $this->section('main') ?>
<?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
    <?php if ($ismobile === true) { ?>
        <h3 class="tm-h1 uk-text-center uk-margin-remove">DAFTAR PROYEK <?= $compname['rsname'] ?></h3>
        <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
            <div class="uk-text-center uk-margin">
                <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
            </div>

            <div id="filter" class="uk-margin" hidden>
                <form id="searchform" action="project/listprojectclient/<?=$compname['id']?>" method="GET">
                    <div class="uk-margin-small uk-flex uk-flex-center">
                        <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($inputpage['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> />
                    </div>
                    <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                        <div>Tampilan</div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div>Per Halaman</div>
                    </div>
                </form>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="uk-margin uk-child-width-auto uk-flex-between" uk-grid>
            <div>
                <h3 class="tm-h1 uk-text-center uk-margin-remove">DAFTAR PROYEK <?= $compname['rsname'] ?></h3>
            </div>
            <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                <div>
                    <!-- <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button> -->
                </div>
            <?php } ?>
        </div>
        <?= view('Views/Auth/_message_block') ?>
    <?php } ?>
    <hr class="uk-divider-icon uk-margin-remove-top">

    <!-- form input -->
    <?php if ($ismobile === false) { ?>
        <form class="uk-margin" id="searchform" action="project/listprojectclient/<?=$compname['id']?>" method="GET">
            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                <div>
                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                        <div>Cari:</div>
                        <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($inputpage['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> /></div>
                    </div>
                </div>
                <div>
                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                        <div>Tampilan</div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div>Per Halaman</div>
                    </div>
                </div>
            </div>
        </form>
    <?php } ?>
    <!-- form input -->

    <!-- script form -->
    <script>
        document.getElementById('search').addEventListener("change", submitform);
        document.getElementById('perpage').addEventListener("change", submitform);

        function submitform() {
            document.getElementById('searchform').submit();
        };
    </script>
    <!-- end script form -->

    <div class="uk-container uk-container-large">
        <?php foreach ($projects as $project) {
            $progress   = "0";
            $status     = "Sedang Dalam Proses Persiapan";
            if ($project['type_design'] === "1") {
                if (!empty($projectdata[$project['id']]['design'])) {

                    if ($projectdata[$project['id']]['design']['status'] === '0') {
                        $progress = "10";
                        $status = "Menunggu Aprroval Desain";
                    }

                    if ($projectdata[$project['id']]['design']['status'] === '1') {
                        $progress = "10";
                        $status = "Menunggu Proses Revisi Desain";
                    }

                    if ($projectdata[$project['id']]['design']['status'] === '2') {
                        $progress = "20";
                        $status = "Desain Disetujui";
                    }
                } else {
                    $progress = "10";
                    $status = "Menunggu Desain";
                }
            } else {
                $status = "Menunggu SPH";
                $progress = "30";
            }

            if ($project['status_spk'] === "1") {
                $progress = "30";
                $status = "SPK DiSetujui";
            }

            if($projectdata[$project['id']]['employeProduction'] === "exist" && $progress >= 30){
                $status = "Dalam Proses Produksi";
            }

            if (!empty($projectdata[$project['id']]['progress'])) {
                $produksi = round((int)$projectdata[$project['id']]['progress']);
                $progress = round($projectdata[$project['id']]['progress'] + $progress);
                if ($progress < 95) {
                    $status   = "Dalam Proses Produksi";
                }
                if (!empty($projectdata[$project['id']]['bastfile']['status']) && ($progress >= "95" || $progress >= 95 || is_float($progress) >= 95)  && $projectdata[$project['id']]['bastfile']['status'] === "1" && !empty($projectdata[$project['id']]['bastfile']['file']) && !empty($projectdata[$project['id']]['sertrim']['status'] === "0")) {
                    $status   = "Retensi";
                }
            }

            if (!empty($projectdata[$project['id']]['dateline']) && !empty($projectdata[$project['id']]['now'])) {
                if ($projectdata[$project['id']]['now'] > $projectdata[$project['id']]['dateline']) {
                    $progress = "100";
                    $status   = "Proyek Selesai";
                }
            }

            if ($ismobile === true) { ?>
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
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center match-height">Status</h4>
                                    <div class="uk-text-center">
                                        <?= $status ?>
                                    </div>
                                </div>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center match-height">Progress Proyek</h3>
                                    <div class="uk-text-center">
                                        <?= $progress ?> %
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-card-footer uk-text-center">
                            <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('ppic.project.edit', $uid) ||  $authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                <button class="uk-button uk-button-secondary" type="button" uk-toggle="target: #modalupdatepro<?= $project['id'] ?>">Ubah Data</button>
                            <?php } ?>
                        </div>
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
                    <div class="uk-card-body">
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
                            <div class="uk-text-right uk-width-auto">
                                <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('admin.project.create', $uid) || $authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                    <button class="uk-button uk-button-secondary uk-margin-small-right" type="button" uk-toggle="target: #modalupdatepro<?= $project['id'] ?>">Ubah Data</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <hr class="uk-divider-icon">
                    <div class="uk-grid-divider uk-child-width-1-2@m" uk-grid>
                        <div>
                            <div class="uk-text-center">
                                <h3 class="tm-h4"><span uk-icon="icon: list; ratio: 1"></span>Status</h3>
                                <p>
                                    <?= $status ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="uk-text-center">
                                <h3 class="tm-h4"><span uk-icon="icon: future; ratio: 1"></span>Progress Proyek</h3>
                                <p>
                                    <?= $progress . "%" ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>
        <?= $pager ?>
    </div>

    <!-- Modal Update Proyek -->
    <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('admin.project.create', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid)  || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
        <?php foreach ($projects as $project) {
            $progress   = "0";
            $status     = "Sedang Dalam Proses Persiapan";
            if ($project['type_design'] === "1") {
                if (!empty($projectdata[$project['id']]['design'])) {

                    if ($projectdata[$project['id']]['design']['status'] === '0') {
                        $progress = "10";
                        $status = "Menunggu Aprroval Desain";
                    }

                    if ($projectdata[$project['id']]['design']['status'] === '1') {
                        $progress = "10";
                        $status = "Menunggu Proses Revisi Desain";
                    }

                    if ($projectdata[$project['id']]['design']['status'] === '2') {
                        $progress = "20";
                        $status = "Desain Disetujui";
                    }
                } else {
                    $progress = "10";
                    $status = "Menunggu Desain";
                }
            } else {
                $status = "Menunggu SPH";
                $progress = "30";
            }

            if ($project['status_spk'] === "1") {
                $progress = "30";
                $status = "SPK DiSetujui";
            }

            if (!empty($projectdata[$project['id']]['progress'])) {
                $produksi = round((int)$projectdata[$project['id']]['progress']);
                $progress = ((int)$projectdata[$project['id']]['progress'] + (int)$progress);
                if (!empty($projectdata[$project['id']]['bastfile']['status']) && ($progress >= "95" || $progress >= 95)  && $projectdata[$project['id']]['bastfile']['status'] === "1" && !empty($projectdata[$project['id']]['bastfile']['file']) && !empty($projectdata[$project['id']]['sertrim']['status'] === "0")) {
                    $status   = "Retensi";
                }
            }

            if (!empty($projectdata[$project['id']]['dateline']) && !empty($projectdata[$project['id']]['now'])) {
                if ($projectdata[$project['id']]['now'] > $projectdata[$project['id']]['dateline']) {
                    $progress = "100";
                    $status   = "Proyek Selesai";
                }
            } ?>
            <div class="uk-modal-container" id="modalupdatepro<?= $project['id'] ?>" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <button class="uk-modal-close-default uk-icon-button-delete" type="button" uk-close></button>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Ubah Data Proyek</h2>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" action="project/update/<?= $project['id'] ?>" method="post">
                            <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="company">Nama Proyek</label>
                                    <div class="uk-uk-form-controls">
                                        <input class="uk-input" name="name" value="<?= $project['name'] ?>" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon">
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="company">Nama Proyek</label>
                                    <div class="uk-uk-form-controls">
                                        <input class="uk-input" type="text" name="name" placeholder="<?= $project['name'] ?>" aria-label="disabled" value="<?= $project['name'] ?>" disabled>
                                        <input class="uk-input" name="name" value="<?= $project['name'] ?>" placeholder="Nama Proyek" type="text" hidden>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Add Client Auto Complete -->
                            <?php if (!empty($company)) {
                                foreach ($company as $comp) {
                                    if ($comp['id'] === $project['clientid']) {
                                        $klien = $comp['rsname'];
                                    }
                                }
                            } ?>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Kode Marketing</label>
                                <div class="uk-uk-form-controls">
                                    <input class="uk-input" type="text" name="marketing" placeholder="<?= $projectdata[$project['id']]['marketing']; ?>" aria-label="disabled" value="<?= $projectdata[$project['id']]['marketing']; ?>" disabled>
                                </div>
                            </div>

                            <div class="uk-margin" id="pusat">
                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                    <label class="uk-form-label" for="company">Perusahaan</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="companyupdated<?= $project['id'] ?>" name="company" value="<?= $klien ?>" placeholder="<?= $klien ?>" required>
                                        <input id="compid" name="company" value="<?= $project['clientid'] ?>" hidden>
                                    </div>
                                <?php } else { ?>
                                    <label class="uk-form-label" for="company">Perusahaan</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" type="text" placeholder="<?= $klien ?>" aria-label="disabled" value="<?= $klien ?>" disabled>
                                        <input class="uk-input" type="text" name="company" placeholder="<?= $klien ?>" aria-label="disabled" value="<?= $klien ?>" hidden>
                                        <input id="compid" name="company" value="<?= $project['clientid'] ?>" hidden>
                                    </div>
                                <?php } ?>
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

                                        // console.log(company);
                                        console.log(company);
                                        $("#companyupdated<?= $project['id'] ?>").autocomplete({
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

                            <!-- Desain Section -->
                            <div <?=$togledesign?>>
                                <?php if (($project['type_design'] === '1')) { ?>
                                    <?php if (empty($projectdata[$project['id']]['design'])) { ?>
                                        <div class="uk-margin uk-child-width-1-2 uk-flex-middle" uk-grid>
                                            <div>
                                                <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                    <div>
                                                        <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Desain</div>
                                                    </div>
                                                    <div>
                                                        <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #007ec8; color: #007ec8; font-weight: bold; padding: 3px;">Menunggu Upload Desain</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset uk-icon-button" id="toggledesain<?= $project['id'] ?>" uk-toggle="target: .toggledesain<?= $project['id'] ?>"><span class="uk-light" id="closedesain<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="opendesain<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>
    
                                        <div class="toggledesain<?= $project['id'] ?>" hidden>
                                            <div class="uk-form-horizontal">
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">Tanggal Desain</label>
                                                    <div class="uk-form-controls">: -</div>
                                                </div>
    
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                    <div class="uk-form-controls">: -</div>
                                                </div>
    
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                    <div class="uk-form-controls">: -</div>
                                                </div>
                                            </div>
    
                                            <?php if ($authorize->hasPermission('design.project.edit', $uid)) { ?>
                                                <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                                    <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                                        <input id="photocreate<?= $project['id'] ?>" name="submitted" hidden />
                                                        <div id="js-upload-create-<?= $project['id'] ?>" class="js-upload-create-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                            <span uk-icon="icon: cloud-upload"></span>
                                                            <span class="uk-text-middle">Tarik dan lepas file desain disini atau</span>
                                                            <div uk-form-custom>
                                                                <input type="file">
                                                                <span class="uk-link uk-preserve-color">pilih satu</span>
                                                            </div>
                                                        </div>
                                                        <progress id="js-progressbar-create-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php } else {
                                        if ($projectdata[$project['id']]['design']['status'] === '0') { ?>
                                            <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                        <div>
                                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Desain</div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #ff0000; color: #ff0000; font-weight: bold; padding: 3px;">Menuggu Persetujuan</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-text-right">
                                                    <a class="uk-link-reset uk-icon-button" id="toggledesain<?= $project['id'] ?>" uk-toggle="target: .toggledesain<?= $project['id'] ?>"><span class="uk-light" id="closedesain<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="opendesain<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                                </div>
                                            </div>
    
                                            <div class="toggledesain<?= $project['id'] ?>" hidden>
                                                <div class="uk-form-horizontal">
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">Tanggal Upload Desain</label>
                                                        <div class="uk-form-controls">: <?= date('d M Y, H:i', strtotime($projectdata[$project['id']]['design']['updated_at'])); ?></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                        <div class="uk-form-controls">: <a href="/img/design/<?= $projectdata[$project['id']]['design']['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['submitted'] ?></a></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                        <div class="uk-form-controls">: -</div>
                                                    </div>
                                                </div>
                                                <?php if ($authorize->hasPermission('design.project.edit', $uid)) { ?>
                                                    <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                                        <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                                            <input id="photocreate<?= $project['id'] ?>" name="submitted" hidden />
                                                            <div id="js-upload-create-<?= $project['id'] ?>" class="js-upload-create-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                                <span uk-icon="icon: cloud-upload"></span>
                                                                <span class="uk-text-middle">Tarik dan lepas file desain disini atau</span>
                                                                <div uk-form-custom>
                                                                    <input type="file">
                                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                                </div>
                                                            </div>
                                                            <progress id="js-progressbar-create-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
    
                                        <?php if ($projectdata[$project['id']]['design']['status'] === '1') { ?>
                                            <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                        <div>
                                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Desain</div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #FFEA00; color: #FFEA00; font-weight: bold; padding: 3px;">Proses Revisi</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-text-right">
                                                    <a class="uk-link-reset uk-icon-button" id="toggledesain<?= $project['id'] ?>" uk-toggle="target: .toggledesain<?= $project['id'] ?>"><span class="uk-light" id="closedesain<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="opendesain<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                                </div>
                                            </div>
    
                                            <div class="toggledesain<?= $project['id'] ?>" hidden>
                                                <div class="uk-form-horizontal">
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">Tanggal Revisi</label>
                                                        <div class="uk-form-controls">: <?= date('d M Y, H:i', strtotime($projectdata[$project['id']]['design']['updated_at'])); ?></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                        <div class="uk-form-controls">: <a href="/img/design/<?= $projectdata[$project['id']]['design']['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['submitted'] ?></a></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                        <div class="uk-form-controls">: <a href="/img/revisi/<?= $projectdata[$project['id']]['design']['revision'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['revision'] ?></a></div>
                                                    </div>
                                                </div>
                                                <?php if ($authorize->hasPermission('design.project.edit', $uid)) { ?>
                                                    <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                                        <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                                            <input id="photocreate<?= $project['id'] ?>" name="submitted" hidden />
                                                            <div id="js-upload-create-<?= $project['id'] ?>" class="js-upload-create-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                                <span uk-icon="icon: cloud-upload"></span>
                                                                <span class="uk-text-middle">Tarik dan lepas file desain disini atau</span>
                                                                <div uk-form-custom>
                                                                    <input type="file">
                                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                                </div>
                                                            </div>
                                                            <progress id="js-progressbar-create-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
    
                                        <?php if ($projectdata[$project['id']]['design']['status'] === '2') { ?>
                                            <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                                <div>
                                                    <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                        <div>
                                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Desain</div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #32CD32; color: #32CD32; font-weight: bold; padding: 3px;">Disetujui</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-text-right">
                                                    <a class="uk-link-reset uk-icon-button" id="toggledesain<?= $project['id'] ?>" uk-toggle="target: .toggledesain<?= $project['id'] ?>"><span class="uk-light" id="closedesain<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="opendesain<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                                </div>
                                            </div>
    
                                            <div class="toggledesain<?= $project['id'] ?>" hidden>
                                                <div class="uk-form-horizontal">
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">Tanggal Disetujui</label>
                                                        <div class="uk-form-controls">: <?= date('d M Y, H:i', strtotime($projectdata[$project['id']]['design']['updated_at'])); ?></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Design</label>
                                                        <div class="uk-form-controls">: <a href="/img/design/<?= $projectdata[$project['id']]['design']['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['submitted'] ?></a>&nbsp;&nbsp;<?php if ($projectdata[$project['id']]['verdesign'] > 1) { echo "<a href=".base_url("version?project=".$project['id']."&type=1").">+".($projectdata[$project['id']]['verdesign']-1)."&nbsp;ver</a>"; } ?></div>
                                                    </div>
    
                                                    <div class="uk-margin-small">
                                                        <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                        <div class="uk-form-controls">: <a href="/img/revisi/<?= $projectdata[$project['id']]['design']['revision'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['revision'] ?></a>&nbsp;&nbsp;<?php if ($projectdata[$project['id']]['verrevisi'] > 1) { echo "<a href=".base_url("version?project=".$project['id']."&type=2").">+".($projectdata[$project['id']]['verrevisi']-1)."&nbsp;ver</a>"; } ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>
    
                                    <script type="text/javascript">
                                        // Script Desain
                                        var bar = document.getElementById('js-progressbar-create-<?= $project['id'] ?>');
    
                                        UIkit.upload('.js-upload-create-<?= $project['id'] ?>', {
                                            url: 'upload/designcreate',
                                            multiple: false,
                                            name: 'uploads',
                                            // param: {
                                            //     lorem: 'ipsum'
                                            // },
                                            contentType: false,
                                            processData: false,
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
    
                                        // Dropdown Desain
                                        document.getElementById('toggledesain<?= $project['id'] ?>').addEventListener('click', function() {
                                            if (document.getElementById('closedesain<?= $project['id'] ?>').hasAttribute('hidden')) {
                                                document.getElementById('closedesain<?= $project['id'] ?>').removeAttribute('hidden');
                                                document.getElementById('opendesain<?= $project['id'] ?>').setAttribute('hidden', '');
                                            } else {
                                                document.getElementById('opendesain<?= $project['id'] ?>').removeAttribute('hidden');
                                                document.getElementById('closedesain<?= $project['id'] ?>').setAttribute('hidden', '');
                                            }
                                        });
                                    </script>
                                <?php } ?>
                            </div>
                            <!-- Desain Section End -->

                            <!-- Detail Pemesanan Section -->
                            <div <?=$togledetailpesanan?>>
                                <?php if ((!empty($projectdata[$project['id']]['design'])) || ($project['type_design'] === '0')) {
                                    if (((!empty($projectdata[$project['id']]['design'])) && ($projectdata[$project['id']]['design']['status'] === '2')) || ($project['type_design'] === '0')) { ?>
                                        <div class="uk-margin uk-child-width-1-2 uk-flex-middle" uk-grid>
                                            <div>
                                                <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Detail Pemesanan</div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset uk-icon-button" id="toggle<?= $project['id'] ?>" uk-toggle="target: .togglesph<?= $project['id'] ?>"><span class="uk-light" id="close<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="open<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>

                                        <div class="uk-padding uk-padding-remove-vertical togglesph<?= $project['id'] ?>" hidden>
                                            <?php if (!$authorize->hasPermission('marketing.project.edit', $uid)) { ?>

                                                <!-- NEW VIEW SHOWING ITEM ORDER MDL -->
                                                <?php 
                                                $rabhide = "hidden";
                                                $formrabhide = "";

                                                if (!empty($projectdata[$project['id']]['newrab']) || !empty($projectdata[$project['id']]['customrab'])) { 
                                                    $rabhide = ""; 
                                                    $formrabhide = "hidden";
                                                    ?>
                                                    <div class="uk-overflow-auto uk-margin uk-margin-remove-top" id="rabview<?=$project['id']?>">
                                                        <table class="uk-table uk-table-divider">
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="8" class="tm-h3 uk-text-bold" style="text-transform: uppercase;">Daftar Pesanan</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Ruang</th>
                                                                    <th>Nama</th>
                                                                    <th>Panjang</th>
                                                                    <th>Lebar</th>
                                                                    <th>Tinggi</th>
                                                                    <th>Volume</th>
                                                                    <th>Satuan</th>
                                                                    <th class="uk-table-expand">Keterangan</th>
                                                                    <th>Foto</th>
                                                                    <th class="uk-text-nowrap">Jumlah Pesanan</th>
                                                                    <th>Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- ROW RAB DATA -->
                                                                <?php foreach ($projectdata[$project['id']]['newrab'] as $rabexist) { ?>
                                                                    <tr id="mdl<?=$rabexist['id']?>">
                                                                        <td class="uk-text-nowrap">
                                                                            <?= $rabexist['paketname'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $rabexist['name'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['length'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['width'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['height'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['volume'] ?>
                                                                        </td>
                                                                        <td class="uk-text-nowrap">
                                                                            <?php
                                                                                if ($rabexist['denomination'] === "1") {
                                                                                    echo "Unit";
                                                                                } elseif ($rabexist['denomination'] === "2") {
                                                                                    echo "Meter Lari";
                                                                                } elseif ($rabexist['denomination'] === "3") {
                                                                                    echo "Meter Persegi";
                                                                                } elseif ($rabexist['denomination'] === "4") {
                                                                                    echo "Set";
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $rabexist['keterangan'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <div uk-lightbox="">
                                                                                <a class="uk-inline" href="img/mdl/<?=$rabexist['photo']?>" role="button">
                                                                                    <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$rabexist['photo']?>" width="40" height="40" alt="<?=$rabexist['photo']?>">
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['qty'] ?>
                                                                        </td>
                                                                        <?php 

                                                                            // Value Condition
                                                                            $lengthrab = 0;
                                                                            $heigthrab = 0;
                                                                            $volumerab = 0;

                                                                            if(!empty($rabexist['length'])){
                                                                                $lengthrab = $rabexist['length'];
                                                                            }

                                                                            if(!empty($rabexist['height'])){
                                                                                $heigthrab = $rabexist['height'];
                                                                            }

                                                                            if(!empty($rabexist['volume'])){
                                                                                $volumerab = $rabexist['volume'];
                                                                            }

                                                                            if($rabexist['denomination'] === "2"){
                                                                                $hargatrab = ((int)$rabexist['price'] * $volumerab) * (int)$rabexist['qty'];
                                                                            }elseif($rabexist['denomination'] === "3"){
                                                                                $hargatrab = ((int)$rabexist['price'] * ($heigthrab * $lengthrab)) * (int)$rabexist['qty'];
                                                                            }else{
                                                                                $hargarab = (int)$rabexist['price']  * (int)$rabexist['qty'];
                                                                            }
                                                                        ?>
                                                                        <td class="uk-text-nowrap">
                                                                            <?= "Rp. " . number_format($hargarab, 0, ',', '.'); " "; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <!-- END ROW RAB DATA -->

                                                                <!-- ROW RAB CUSTOM-->
                                                                <?php if(!empty($projectdata[$project['id']]['customrab'])) {?>
                                                                    <tr>
                                                                    <td colspan="8" class="tm-h3 uk-text-bold" style="text-transform: uppercase;">Tambahan Pesanan</td>
                                                                        <td class="-text-bold"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-bold"></td>
                                                                        <td class="uk-text-bold"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-nowrap">Nama</td>
                                                                        <td class="">Panjang</td>
                                                                        <td class="uk-text-center">Lebar</td>
                                                                        <td class="uk-text-center">Tinggi</td>
                                                                        <td class="uk-text-center">Volume</td>
                                                                        <td class="uk-text-center">Satuan</td>
                                                                        <td class="uk-text-center">Jumlah</td>
                                                                        <td class="uk-text-nowrap">Harga</td>
                                                                    </tr>
                                                                    <?php foreach ($projectdata[$project['id']]['customrab'] as $custrab) {?>
                                                                        <tr>
                                                                            <!-- <td class=""></td> -->
                                                                            <td class="uk-text-nowrap"><?= strtoupper($custrab['name']) ?></td>
                                                                            <td class="uk-text-center"><?=$custrab['length']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['width']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['height']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['volume']?></td>
                                                                            <td class="uk-text-nowrap">
                                                                                <?php
                                                                                    if ($custrab['denomination'] === "1") {
                                                                                        echo "Unit";
                                                                                    } elseif ($custrab['denomination'] === "2") {
                                                                                        echo "Meter Lari";
                                                                                    } elseif ($custrab['denomination'] === "3") {
                                                                                        echo "Meter Persegi";
                                                                                    } elseif ($custrab['denomination'] === "4") {
                                                                                        echo "Set";
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <!-- <td class="uk-text-center"></td> -->
                                                                            <!-- <td class="uk-text-center"></td> -->
                                                                            <td class="uk-text-center"><?=$custrab['qty']?></td>
                                                                            <?php 

                                                                            // Value Condition
                                                                            $lengthcustrab = 0;
                                                                            $heigthcustrab = 0;
                                                                            $volumecustrab = 0;

                                                                            if(!empty($custrab['length'])){
                                                                                $lengthcustrab = $custrab['length'];
                                                                            }

                                                                            if(!empty($custrab['height'])){
                                                                                $heigthcustrab = $custrab['height'];
                                                                            }

                                                                            if(!empty($custrab['volume'])){
                                                                                $volumecustrab = $custrab['volume'];
                                                                            }

                                                                            if($custrab['denomination'] === "2"){
                                                                                $hargacustrab = ((int)$custrab['price'] * $volumecustrab) * (int)$custrab['qty'];
                                                                            }elseif($custrab['denomination'] === "3"){
                                                                                $hargacustrab = ((int)$custrab['price'] * ($heigthcustrab * $lengthcustrab)) * (int)$custrab['qty'];
                                                                            }else{
                                                                                $hargacustrab = (int)$custrab['price']  * (int)$custrab['qty'];
                                                                            }
                                                                            ?>
                                                                            <td class="uk-text-left uk-text-nowrap"><?= "Rp. " . number_format($hargacustrab , 0, ',', '.');" "; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <!-- END ROW RAB CUSTOM -->

                                                                <?php if (!empty($projectdata[$project['id']]['shippingcost'])) { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="tm-h3" style="text-transform: uppercase;">Biaya Pengiriman</td>
                                                                        <td>
                                                                            <?php if (!empty($projectdata[$project['id']]['shippingcost'])) {echo 'Rp. ' . number_format((int)$projectdata[$project['id']]['shippingcost']['price'], 0, ',', '.'); ' ';} ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } ?>
                                                <!-- END NEW VIEW SHOWING ITEM ORDER MDL -->
                                            <?php } ?>

                                            <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                                <!-- New View RAB Data -->
                                                <?php 
                                                $rabhide = "hidden";
                                                $formrabhide = "";

                                                if (!empty($projectdata[$project['id']]['newrab']) || !empty($projectdata[$project['id']]['customrab'])) { 
                                                    $rabhide = ""; 
                                                    $formrabhide = "hidden";
                                                    ?>
                                                    <div class="uk-overflow-auto uk-margin uk-margin-remove-top" id="rabview<?=$project['id']?>">
                                                        <table class="uk-table uk-table-divider">
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="8" class="tm-h3 uk-text-bold" style="text-transform: uppercase;">Daftar Pesanan</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Ruang</th>
                                                                    <th>Nama</th>
                                                                    <th>Panjang</th>
                                                                    <th>Lebar</th>
                                                                    <th>Tinggi</th>
                                                                    <th>Volume</th>
                                                                    <th>Satuan</th>
                                                                    <th class="uk-table-expand">Keterangan</th>
                                                                    <th>Foto</th>
                                                                    <th class="uk-text-nowrap">Jumlah Pesanan</th>
                                                                    <th>Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- ROW RAB DATA -->
                                                                <?php foreach ($projectdata[$project['id']]['newrab'] as $rabexist) { ?>
                                                                    <tr id="mdl<?=$rabexist['id']?>">
                                                                        <td class="uk-text-nowrap">
                                                                            <?= $rabexist['paketname'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $rabexist['name'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['length'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['width'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['height'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['volume'] ?>
                                                                        </td>
                                                                        <td class="uk-text-nowrap">
                                                                            <?php
                                                                                if ($rabexist['denomination'] === "1") {
                                                                                    echo "Unit";
                                                                                } elseif ($rabexist['denomination'] === "2") {
                                                                                    echo "Meter Lari";
                                                                                } elseif ($rabexist['denomination'] === "3") {
                                                                                    echo "Meter Persegi";
                                                                                } elseif ($rabexist['denomination'] === "4") {
                                                                                    echo "Set";
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $rabexist['keterangan'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <div uk-lightbox="">
                                                                                <a class="uk-inline" href="img/mdl/<?=$rabexist['photo']?>" role="button">
                                                                                    <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$rabexist['photo']?>" width="40" height="40" alt="<?=$rabexist['photo']?>">
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $rabexist['qty'] ?>
                                                                        </td>
                                                                        <?php

                                                                        // Value Condition
                                                                        $lengthrabexist = 0;
                                                                        $heigthrabexist = 0;
                                                                        $volumerabexist = 0;

                                                                        if(!empty($rabexist['length'])){
                                                                            $lengthrabexist = $rabexist['length'];
                                                                        }

                                                                        if(!empty($rabexist['height'])){
                                                                            $heigthrabexist = $rabexist['height'];
                                                                        }

                                                                        if(!empty($rabexist['volume'])){
                                                                            $volumerabexist = $rabexist['volume'];
                                                                        }

                                                                        if($rabexist['denomination'] === "2"){
                                                                            $hargarab = ((int)$rabexist['price'] * $volumerabexist) * (int)$rabexist['qty'];
                                                                        }elseif($rabexist['denomination'] === "3"){
                                                                            $hargarab = ((int)$rabexist['price'] * ($heigthrabexist *  $lengthrabexist)) * (int)$rabexist['qty'];
                                                                        }else{
                                                                            $hargarab = (int)$rabexist['price'] * (int)$rabexist['qty'];
                                                                        }
                                                                        ?>
                                                                        <td class="uk-text-nowrap">
                                                                            <?= "Rp. " . number_format($hargarab, 0, ',', '.'); " "; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <!-- END ROW RAB DATA -->

                                                                <!-- ROW RAB CUSTOM-->
                                                                <?php if(!empty($projectdata[$project['id']]['customrab'])) {?>
                                                                    <tr>
                                                                    <td colspan="8" class="tm-h3 uk-text-bold" style="text-transform: uppercase;">Tambahan Pesanan</td>
                                                                        <td class="-text-bold"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-center"></td>
                                                                        <td class="uk-text-bold"></td>
                                                                        <td class="uk-text-bold"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-nowrap">Nama</td>
                                                                        <td class="">Panjang</td>
                                                                        <td class="uk-text-center">Lebar</td>
                                                                        <td class="uk-text-center">Tinggi</td>
                                                                        <td class="uk-text-center">Volume</td>
                                                                        <td class="uk-text-center">Satuan</td>
                                                                        <td class="uk-text-center">Jumlah</td>
                                                                        <td class="uk-text-nowrap">Harga</td>
                                                                    </tr>
                                                                    <?php foreach ($projectdata[$project['id']]['customrab'] as $custrab) {?>
                                                                        <tr>
                                                                            <!-- <td class=""></td> -->
                                                                            <td class="uk-text-nowrap"><?= strtoupper($custrab['name']) ?></td>
                                                                            <td class="uk-text-center"><?=$custrab['length']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['width']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['height']?></td>
                                                                            <td class="uk-text-center"><?=$custrab['volume']?></td>
                                                                            <td class="uk-text-nowrap">
                                                                                <?php
                                                                                    if ($custrab['denomination'] === "1") {
                                                                                        echo "Unit";
                                                                                    } elseif ($custrab['denomination'] === "2") {
                                                                                        echo "Meter Lari";
                                                                                    } elseif ($custrab['denomination'] === "3") {
                                                                                        echo "Meter Persegi";
                                                                                    } elseif ($custrab['denomination'] === "4") {
                                                                                        echo "Set";
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <td class="uk-text-center"><?=$custrab['qty']?></td>
                                                                            <?php

                                                                            // Value Condition
                                                                            $lengthcustrab = 0;
                                                                            $heigthcustrab = 0;
                                                                            $volumecustrab = 0;

                                                                            if(!empty($custrab['length'])){
                                                                                $lengthcustrab = $custrab['length'];
                                                                            }

                                                                            if(!empty($custrab['height'])){
                                                                                $heigthcustrab = $custrab['height'];
                                                                            }

                                                                            if(!empty($custrab['volume'])){
                                                                                $volumecustrab = $custrab['volume'];
                                                                            }

                                                                            if($custrab['denomination'] === "2"){
                                                                                $hargarabcust = ((int)$custrab['price'] * $volumecustrab) * (int)$custrab['qty'];
                                                                            }elseif($custrab['denomination'] === "3"){
                                                                                $hargarabcust = ((int)$custrab['price'] * ($lengthcustrab * $heigthcustrab)) * (int)$custrab['qty'];
                                                                            }else{
                                                                                $hargarabcust = (int)$custrab['price'] * (int)$custrab['qty'];
                                                                            }
                                                                            ?>
                                                                            <td class="uk-text-left uk-text-nowrap"><?= "Rp. " . number_format($hargarabcust, 0, ',', '.');" "; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <!-- END ROW RAB CUSTOM -->

                                                                <?php if (!empty($projectdata[$project['id']]['shippingcost'])) { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="tm-h3" style="text-transform: uppercase;">Biaya Pengiriman</td>
                                                                        <td>
                                                                            <?php if (!empty($projectdata[$project['id']]['shippingcost'])) {echo 'Rp. ' . number_format((int)$projectdata[$project['id']]['shippingcost']['price'], 0, ',', '.'); ' ';} ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- End New View RAB Data -->
                                                    
                                                    <!-- BUTTON KELOLA RAB -->
                                                    <a class="uk-button uk-button-default uk-margin" id="buttonrab<?=$project['id']?>" <?=$rabhide?>>EDIT PESANAN</a>
                                                <?php } ?>
                                                
                                                <script>
                                                    $(document).ready(function(){
                                                        // OPEN BUTTON FUNCTION
                                                        $("#buttonrab<?=$project['id']?>").click(function(){
                                                            $("#formeditrab<?=$project['id']?>").removeAttr("hidden");
                                                            $("#closebuttonrab<?=$project['id']?>").removeAttr("hidden");
                                                            $("#buttonrab<?=$project['id']?>").attr("hidden",true);
                                                            $("#rabview<?=$project['id']?>").attr("hidden",true);
                                                        });

                                                        // CLOSE BUTTON FUNCTION
                                                        $("#closebuttonrab<?=$project['id']?>").click(function(){
                                                            $("#formeditrab<?=$project['id']?>").attr("hidden",true);
                                                            $("#closebuttonrab<?=$project['id']?>").attr("hidden",true);
                                                            $("#buttonrab<?=$project['id']?>").removeAttr("hidden");
                                                            $("#rabview<?=$project['id']?>").removeAttr("hidden");
                                                        });
                                                    });
                                                </script>
                                                <!-- END BUTTON KELOLA RAB -->
                                            <?php } ?>

                                                <!-- Current MDL Proyek Removed -->
                                                <?php if (!empty($projectdata[$project['id']]['allrabdatadeleted'])) { ?>
                                                    <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                                        <table class="uk-table uk-table-divider uk-text-muted">
                                                            <!-- <caption class="uk-text-muted tm-h3">MDL Dalam Proyek Yang Telah Terhapus Dari Data MDL</caption> -->
                                                            
                                                            <thead>
                                                                <tr>
                                                                    <td colspan="8" class="tm-h3 uk-text-muted" style="text-transform: uppercase;">MDL Dalam Proyek Yang Telah Terhapus Dari Daftar MDL</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kelola</th>
                                                                    <th>Nama</th>
                                                                    <th>Panjang</th>
                                                                    <th>Lebar</th>
                                                                    <th>Tinggi</th>
                                                                    <th>Volume</th>
                                                                    <th>Satuan</th>
                                                                    <th>Keterangan</th>
                                                                    <!-- <th>Foto</th> -->
                                                                    <th>Jumlah Pesanan</th>
                                                                    <th>Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($projectdata[$project['id']]['allrabdatadeleted'] as $allcurrentrabdata) { ?>
                                                                    <tr class="uk-text-muted" id="mdl<?=$allcurrentrabdata['id']?>">
                                                                        <td class="uk-text-center">
                                                                            <a onclick="removeMDL<?=$allcurrentrabdata['id']?>()" uk-icon="trash"></a>
                                                                        </td>
                                                                        <td>
                                                                            <?= $allcurrentrabdata['name'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $allcurrentrabdata['length'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $allcurrentrabdata['width'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $allcurrentrabdata['height'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?= $allcurrentrabdata['volume'] ?>
                                                                        </td>
                                                                        <td class="uk-text-center">
                                                                            <?php
                                                                                if ($allcurrentrabdata['denomination'] === "1") {
                                                                                    echo "Unit";
                                                                                } elseif ($allcurrentrabdata['denomination'] === "2") {
                                                                                    echo "Meter Lari";
                                                                                } elseif ($allcurrentrabdata['denomination'] === "3") {
                                                                                    echo "Meter Persegi";
                                                                                } elseif ($allcurrentrabdata['denomination'] === "4") {
                                                                                    echo "Set";
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $allcurrentrabdata['keterangan'] ?>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" class="uk-input uk-form-width-small" placeholder="<?= $allcurrentrabdata['qty'] ?>" disabled/>
                                                                        </td>
                                                                        <?php 

                                                                            // Value Condition
                                                                            $lengthcurrentcustrab = 0;
                                                                            $heigthcurrentcustrab = 0;
                                                                            $volumecurrentcustrab = 0;

                                                                            if(!empty($allcurrentrabdata['length'])){
                                                                                $lengthcurrentcustrab = $allcurrentrabdata['length'];
                                                                            }

                                                                            if(!empty($allcurrentrabdata['height'])){
                                                                                $heigthcurrentcustrab = $allcurrentrabdata['height'];
                                                                            }

                                                                            if(!empty($allcurrentrabdata['volume'])){
                                                                                $volumecurrentcustrab = $allcurrentrabdata['volume'];
                                                                            }

                                                                            if($allcurrentrabdata['denomination'] === "2"){
                                                                                $hargamdldeleted = ((int)$allcurrentrabdata['price'] * $volumecurrentcustrab)  * (int)$allcurrentrabdata['qty'];
                                                                            }elseif($allcurrentrabdata['denomination'] === "3"){
                                                                                $hargamdldeleted = ((int)$allcurrentrabdata['price'] * ($lengthcurrentcustrab * $heigthcurrentcustrab))  * (int)$allcurrentrabdata['qty'];
                                                                            }else{
                                                                                $hargamdldeleted = (int)$allcurrentrabdata['price']  * (int)$allcurrentrabdata['qty'];
                                                                            }
                                                                        ?>
                                                                        <td>
                                                                        <?= "Rp. " . number_format( $hargamdldeleted, 0, ',', '.'); " "; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <script>
                                                                        function removeMDL<?= $allcurrentrabdata['id']; ?>() {
                                                                            let text = "Anda yakin ingin menghapus <?=$allcurrentrabdata['name']?> dari proyek <?=$project['name']?>";
                                                                            if (confirm(text) == true) {
                                                                                $.ajax({
                                                                                    url: "project/removemdlpro/<?= $allcurrentrabdata['id'] ?>",
                                                                                    method: "POST",
                                                                                    name: 'mdldata',
                                                                                    data: {
                                                                                        mdlid: <?= $allcurrentrabdata['id'] ?>,
                                                                                        proid: <?=$project['id']?>,
                                                                                    },
                                                                                    dataType: "json",
                                                                                    error: function() {
                                                                                        console.log('error', arguments);
                                                                                    },
                                                                                    success: function() {
                                                                                        console.log('success', arguments);
                                                                                        alert('MDL berhasil di hapus dari proyek.');
                                                                                        $("#mdl<?=$allcurrentrabdata['id']?>").remove();
                                                                                    },
                                                                                })
                                                                            }
                                                                        }
                                                                    </script>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } ?>
                                                <!-- End MDL Proyek Removed -->

                                                <!-- EDIT MDL / RAB DATA -->
                                                <div id="formeditrab<?=$project['id']?>" <?= $formrabhide ?>>
                                                    <?php if (!empty($projectdata[$project['id']]['paket']) || !empty($projectdata[$project['id']]['customrab'])) { ?>
                                                        <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                                            <table class="uk-table uk-table-middle uk-table-divider">
                                                                <?php if (!empty($projectdata[$project['id']]['paket'])){ ?>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Status</th>
                                                                            <th>Nama</th>
                                                                            <th>Panjang</th>
                                                                            <th>Lebar</th>
                                                                            <th>Tinggi</th>
                                                                            <th>Volume</th>
                                                                            <th>Satuan</th>
                                                                            <th>Keterangan</th>
                                                                            <th>Foto</th>
                                                                            <th>Jumlah Pesanan</th>
                                                                            <th>Harga</th>
                                                                        </tr>
                                                                    </thead>
                                                                <?php } ?>
                                                                <tbody>
                                                                    <?php if (!empty($projectdata[$project['id']]['paket'])){ 
                                                                        foreach ($projectdata[$project['id']]['paket'] as $paket) { ?>
                                                                            <tr>
                                                                                <td colspan="8" class="tm-h3" style="text-transform: uppercase;"><?= $paket['name'] ?></td>
                                                                            </tr>
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
                                                                                        <input type="checkbox" class="uk-checkbox" <?= $checked ?> id="checked[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]" name="checked<?= $project['id'] ?>[<?= $mdl['id'] ?>]" />
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
                                                                                        } elseif ($mdl['denomination'] === "4") {
                                                                                            echo "Set";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td><?= $mdl['keterangan'] ?></td>
                                                                                    <td>
                                                                                        <div uk-lightbox="">
                                                                                            <a class="uk-inline" href="img/mdl/<?= $mdl['photo'] ?>" role="button">
                                                                                                <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?= $mdl['photo'] ?>" width="40" height="40" alt="<?= $mdl['photo'] ?>">
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="uk-form-controls">
                                                                                        <input type="number" id="eqty[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]" name="eqty<?= $project['id'] ?>[<?= $paket['id'] ?>][<?= $mdl['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $mdl['qty'] ?>" onchange="eprice(<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>)" />
                                                                                    </td>
                                                                                    <div id="eprice[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]" hidden><?= $mdl['price'] ?></div>
                                                                                    <td id="eshowprice[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]"><?= "Rp. " . number_format((int)$mdl['qty'] * (int)$mdl['price'], 0, ',', '.'); " "; ?></td>
                                                                                </tr>
                                                                        <?php }
                                                                    } ?>
                                                                        <script>
                                                                            function eprice(n) {
                                                                                var ebaseprice = document.getElementById('eprice[' + n + ']').innerHTML;
                                                                                var ebaseqty = document.getElementById('eqty[' + n + ']').value;
                                                                                var epricetd = document.getElementById('eshowprice[' + n + ']');
                                                                                var echeckbox = document.getElementById('checked[' + n + ']');
                                                                                var eprojprice = ebaseprice * ebaseqty;
                                                                                epricetd.innerHTML = 'Rp. ' + Intl.NumberFormat('de-DE').format(eprojprice);

                                                                                if (ebaseqty > 0) {
                                                                                    echeckbox.checked = true;
                                                                                } else {
                                                                                    echeckbox.checked = false;
                                                                                }
                                                                            };
                                                                        </script>
                                                                    <?php } ?>

                                                                    <!-- MDL Remove Form Center List -->
                                                                    <!-- <tr>
                                                                        <td colspan="9" class="tm-h3 uk-text-muted" style="text-transform: uppercase;">MDL Dalam Proyek Yang Telah Terhapus Dari Daftar MDL</td>
                                                                    </tr> -->

                                                                    <!-- </?php foreach ($projectdata[$project['id']]['allrabdatadeleted'] as $allcurrentrabdata) { ?> -->
                                                                        <!-- <tr class="uk-text-muted">
                                                                            <td>
                                                                                <input type="checkbox" class="uk-checkbox" checked disabled/>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['name'] ?>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['length'] ?>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['width'] ?>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['height'] ?>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['volume'] ?>
                                                                            </td>
                                                                            <td>
                                                                                </?php
                                                                                    if ($allcurrentrabdata['denomination'] === "1") {
                                                                                        echo "Unit";
                                                                                    } elseif ($allcurrentrabdata['denomination'] === "2") {
                                                                                        echo "Meter Lari";
                                                                                    } elseif ($allcurrentrabdata['denomination'] === "3") {
                                                                                        echo "Meter Persegi";
                                                                                    } elseif ($allcurrentrabdata['denomination'] === "4") {
                                                                                        echo "Set";
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                </?= $allcurrentrabdata['keterangan'] ?>
                                                                            </td> -->
                                                                            <!-- <td>
                                                                                <div uk-lightbox="">
                                                                                    <a class="uk-inline" href="img/mdl/ </?= $allcurrentrabdata['photo'] ?>" role="button">
                                                                                        <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/ </?= $allcurrentrabdata['photo'] ?>" width="40" height="40" alt="</?= $mdl['photo'] ?>">
                                                                                    </a>
                                                                                </div>
                                                                            </td> -->
                                                                            <!-- <td>
                                                                                <input type="number" class="uk-input uk-form-width-small" placeholder="</?= $allcurrentrabdata['qty'] ?>" disabled/>
                                                                            </td>
                                                                            <td>
                                                                            </?= "Rp. " . number_format( $allcurrentrabdata['price']  * $allcurrentrabdata['qty'], 0, ',', '.'); " "; ?>
                                                                            </td>
                                                                        </tr> -->
                                                                    <!-- </?php } ?> -->
                                                                    <!-- End MDL Remove Form Center List -->

                                                                    <tr>
                                                                        <td colspan="9" class="tm-h3" style="text-transform: uppercase;">Biaya Pengiriman</td>
                                                                        <td>
                                                                            <input type="text" class="uk-input uk-form-width-small" id="shippingcost" name="shippingcost" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" data-type="curencyupdate<?= $project['id'] ?>" value="<?php if (!empty($projectdata[$project['id']]['shippingcost'])) {echo 'Rp' . number_format((int)$projectdata[$project['id']]['shippingcost']['price'], 0, ',', ','); ' ';} ?>" />
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td colspan="8" class="tm-h3" style="text-transform: uppercase;">Custom Pemesanan</td>
                                                                    </tr>
                                                                    <?php if (!empty($projectdata[$project['id']]['customrab'])) {
                                                                        foreach ($projectdata[$project['id']]['customrab'] as $customrab) { ?>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input type="text" id="namecustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="namecustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['name'] ?>" />
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="lengthcustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="lengthcustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['length'] ?>"/>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="widthcustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="widthcustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['width'] ?>"/>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="heightcustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="heightcustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['height'] ?>"/>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="volumecustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="volumecustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['volume'] ?>"/>
                                                                                </td>
                                                                                <td>
                                                                                    <select class="uk-select uk-form-width-medium" aria-label="Satuan" id="denominationcustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="denominationcustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]">
                                                                                        <option value="" selected disabled hidden>Pilih Satuan</option>
                                                                                        <option value="1" <?php if ($customrab['denomination'] === "1") { echo 'selected'; } ?>>Unit</option>
                                                                                        <option value="2" <?php if ($customrab['denomination'] === "2") { echo 'selected'; } ?>>Meter Lari</option>
                                                                                        <option value="3" <?php if ($customrab['denomination'] === "3") { echo 'selected'; } ?>>Meter Persegi</option>
                                                                                        <option value="4" <?php if ($customrab['denomination'] === "4") { echo 'selected'; } ?>>Set</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input type="number" id="qtycustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="qtycustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['qty'] ?>"/>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="pricecustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="pricecustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" data-type="curencyupdate<?= $project['id'] ?><?= $customrab['id'] ?>" class="uk-input uk-form-width-small" value="<?= "Rp" . number_format((int)$customrab['price'], 0, ',', ',');' '; ?>" />
                                                                                </td>
                                                                            </tr>
                                                                            <script>
                                                                                $("input[data-type='curencyupdate<?= $project['id'] ?><?= $customrab['id'] ?>']").on({
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
                                                                                            right_side += "";
                                                                                        }

                                                                                        right_side = right_side.substring(0, 0);

                                                                                        input_val = "Rp" + left_side + "." + right_side;

                                                                                    } else {

                                                                                        input_val = formatNumber(input_val);
                                                                                        input_val = "Rp" + input_val;

                                                                                        if (blur === "blur") {
                                                                                            input_val += "";
                                                                                        }
                                                                                    }

                                                                                    input.val(input_val);

                                                                                    var updated_len = input_val.length;
                                                                                    caret_pos = updated_len - original_len + caret_pos;
                                                                                    input[0].setSelectionRange(caret_pos, caret_pos);
                                                                                }
                                                                            </script>
                                                                    <?php }
                                                                    } ?>

                                                                    <script>
                                                                        $("input[data-type='curencyupdate<?= $project['id'] ?>']").on({
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
                                                                                    right_side += "";
                                                                                }

                                                                                right_side = right_side.substring(0, 0);

                                                                                input_val = "Rp" + left_side + "." + right_side;

                                                                            } else {

                                                                                input_val = formatNumber(input_val);
                                                                                input_val = "Rp" + input_val;

                                                                                if (blur === "blur") {
                                                                                    input_val += "";
                                                                                }
                                                                            }

                                                                            input.val(input_val);

                                                                            var updated_len = input_val.length;
                                                                            caret_pos = updated_len - original_len + caret_pos;
                                                                            input[0].setSelectionRange(caret_pos, caret_pos);
                                                                        }
                                                                    </script>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="uk-h4">Tambah Pesanan</div>
                                                    <?php } ?>

                                                    <div class="uk-margin-bottom">
                                                        <label class="uk-form-label" for="paket">Cari Sub Kategori</label>
                                                        <div class="uk-form-controls">
                                                            <input type="text" class="uk-input" id="paketname<?= $project['id'] ?>" name="paketname<?= $project['id'] ?>" placeholder="Nama Sub Kategori">
                                                        </div>
                                                    </div>

                                                    <div id="listmdl<?= $project['id'] ?>"></div>

                                                    <div id="createCustomRab<?= $project['id'] ?>" class="uk-margin">
                                                        <div class="uk-child-width-1-2" uk-grid>
                                                            <div>
                                                                <label class="uk-form-label" for="custompemesanan">Custom Pemesanan</label>
                                                            </div>
                                                            <div class="uk-text-right">
                                                                <a onclick="createNewCustomRab(<?= $project['id'] ?>)">+ Tambah Custom Pemesanan</a>
                                                            </div>
                                                        </div>
                                                        <div id="create<?= $project['id'] ?>0" class="uk-margin uk-child-width-auto" uk-grid>
                                                            <div id="createName<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customname<?= $project['id'] ?>[0]" name="customname<?= $project['id'] ?>[0]" placeholder="Nama" />
                                                            </div>
                                                            <div id="createLength<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customlength<?= $project['id'] ?>[0]" name="customlength<?= $project['id'] ?>[0]" placeholder="Panjang" />
                                                            </div>
                                                            <div id="createWidth<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customwidth<?= $project['id'] ?>[0]" name="customwidth<?= $project['id'] ?>[0]" placeholder="Lebar" />
                                                            </div>
                                                            <div id="createHeight<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customheight<?= $project['id'] ?>[0]" name="customheight<?= $project['id'] ?>[0]" placeholder="Tinggi" />
                                                            </div>
                                                            <div id="createVol<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customvol<?= $project['id'] ?>[0]" name="customvol<?= $project['id'] ?>[0]" placeholder="Volume" />
                                                            </div>
                                                            <div id="createDen<?= $project['id'] ?>0">
                                                                <select class="uk-select uk-form-width-medium" aria-label="Satuan" id="customden<?= $project['id'] ?>[0]" name="customden<?= $project['id'] ?>[0]">
                                                                    <option value="" selected disabled hidden>Pilih Satuan</option>
                                                                    <option value="1">Unit</option>
                                                                    <option value="2">Meter Lari</option>
                                                                    <option value="3">Meter Persegi</option>
                                                                    <option value="4">Set</option>
                                                                </select>
                                                            </div>
                                                            <div id="createPrice<?= $project['id'] ?>0">
                                                                <input type="text" class="uk-input uk-form-width-medium" id="customprice<?= $project['id'] ?>[0]" name="customprice<?= $project['id'] ?>[0]" placeholder="Harga" />
                                                            </div>
                                                            <div id="createQty<?= $project['id'] ?>0">
                                                                <input type="number" min="1" class="uk-input uk-form-width-medium" id="customqty<?= $project['id'] ?>[0]" name="customqty<?= $project['id'] ?>[0]" placeholder="Jumlah" />
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                    <script type="text/javascript">
                                                        var createCount = 0;
                                                        var createx = 0;
                                                        var elementExists = document.getElementById("customprice<?= $project['id'] ?>");

                                                        function createNewCustomRab(x) {
                                                            createCount++;

                                                            const createCustomRab = document.getElementById('createCustomRab' + x + '');

                                                            const newCreateCustomRab = document.createElement('div');
                                                            newCreateCustomRab.setAttribute('id', 'create' + x + '' + createCount);
                                                            newCreateCustomRab.setAttribute('class', 'uk-margin uk-child-width-auto');
                                                            newCreateCustomRab.setAttribute('uk-grid', '');

                                                            const createName = document.createElement('div');
                                                            createName.setAttribute('id', 'createName' + x + '' + createCount);

                                                            const createNameInput = document.createElement('input');
                                                            createNameInput.setAttribute('type', 'text');
                                                            createNameInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createNameInput.setAttribute('placeholder', 'Nama');
                                                            createNameInput.setAttribute('id', 'customname' + x + '[' + createCount + ']');
                                                            createNameInput.setAttribute('name', 'customname' + x + '[' + createCount + ']');

                                                            const createLength = document.createElement('div');
                                                            createLength.setAttribute('id', 'createLength' + x + '' + createCount);

                                                            const createLengthInput = document.createElement('input');
                                                            createLengthInput.setAttribute('type', 'text');
                                                            createLengthInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createLengthInput.setAttribute('placeholder', 'Panjang');
                                                            createLengthInput.setAttribute('id', 'customlength' + x + '[' + createCount + ']');
                                                            createLengthInput.setAttribute('name', 'customlength' + x + '[' + createCount + ']');

                                                            const createWidth = document.createElement('div');
                                                            createWidth.setAttribute('id', 'createWidth' + x + '' + createCount);

                                                            const createWidthInput = document.createElement('input');
                                                            createWidthInput.setAttribute('type', 'text');
                                                            createWidthInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createWidthInput.setAttribute('placeholder', 'Lebar');
                                                            createWidthInput.setAttribute('id', 'customwidth' + x + '[' + createCount + ']');
                                                            createWidthInput.setAttribute('name', 'customwidth' + x + '[' + createCount + ']');

                                                            const createHeight = document.createElement('div');
                                                            createHeight.setAttribute('id', 'createHeight' + x + '' + createCount);

                                                            const createHeightInput = document.createElement('input');
                                                            createHeightInput.setAttribute('type', 'text');
                                                            createHeightInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createHeightInput.setAttribute('placeholder', 'Tinggi');
                                                            createHeightInput.setAttribute('id', 'customheight' + x + '[' + createCount + ']');
                                                            createHeightInput.setAttribute('name', 'customheight' + x + '[' + createCount + ']');

                                                            const createVol = document.createElement('div');
                                                            createVol.setAttribute('id', 'createVol' + x + '' + createCount);

                                                            const createVolInput = document.createElement('input');
                                                            createVolInput.setAttribute('type', 'text');
                                                            createVolInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createVolInput.setAttribute('placeholder', 'Volume');
                                                            createVolInput.setAttribute('id', 'customvol' + x + '[' + createCount + ']');
                                                            createVolInput.setAttribute('name', 'customvol' + x + '[' + createCount + ']');

                                                            const createDen = document.createElement('div');
                                                            createDen.setAttribute('id', 'createDen' + x + '' + createCount);

                                                            const createDenInput = document.createElement('select');
                                                            createDenInput.setAttribute('class', 'uk-select uk-form-width-medium');
                                                            createDenInput.setAttribute('placeholder', 'Satuan');
                                                            createDenInput.setAttribute('id', 'customden' + x + '[' + createCount + ']');
                                                            createDenInput.setAttribute('name', 'customden' + x + '[' + createCount + ']');

                                                            const createOption1 = document.createElement('option');
                                                            createOption1.setAttribute('hidden', '');
                                                            createOption1.setAttribute('selected', '');
                                                            createOption1.setAttribute('disabled', '');
                                                            createOption1.setAttribute('value', '');
                                                            createOption1.innerHTML = "Pilih Satuan"

                                                            const createOption2 = document.createElement('option');
                                                            createOption2.setAttribute('value', '1');
                                                            createOption2.innerHTML = "Unit"

                                                            const createOption3 = document.createElement('option');
                                                            createOption3.setAttribute('value', '2');
                                                            createOption3.innerHTML = "Meter Lari"

                                                            const createOption4 = document.createElement('option');
                                                            createOption4.setAttribute('value', '3');
                                                            createOption4.innerHTML = "Meter Persegi"

                                                            const createOption5 = document.createElement('option');
                                                            createOption5.setAttribute('value', '4');
                                                            createOption5.innerHTML = "Set"

                                                            const createPrice = document.createElement('div');
                                                            createPrice.setAttribute('id', 'createPrice' + x + '' + createCount);

                                                            const createPriceInput = document.createElement('input');
                                                            createPriceInput.setAttribute('type', 'text');
                                                            createPriceInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createPriceInput.setAttribute('placeholder', 'Harga');
                                                            createPriceInput.setAttribute('id', 'customprice' + x + '[' + createCount + ']');
                                                            createPriceInput.setAttribute('name', 'customprice' + x + '[' + createCount + ']');
                                                            createPriceInput.setAttribute('data-type', 'customprice' + x + '[' + createCount + ']');

                                                            const createQty = document.createElement('div');
                                                            createQty.setAttribute('id', 'createQty' + x + '' + createCount);

                                                            const createQtyInput = document.createElement('input');
                                                            createQtyInput.setAttribute('type', 'text');
                                                            createQtyInput.setAttribute('class', 'uk-input uk-form-width-medium');
                                                            createQtyInput.setAttribute('placeholder', 'Jumlah');
                                                            createQtyInput.setAttribute('id', 'customqty' + x + '[' + createCount + ']');
                                                            createQtyInput.setAttribute('qty', 'customqty' + x + '[' + createCount + ']');

                                                            const line = document.createElement('hr');
                                                            line.setAttribute('id', 'customline' + x + '['+ createCount +']');

                                                            const createRemove = document.createElement('div');
                                                            createRemove.setAttribute('id', 'remove' + x + '' + createCount);
                                                            createRemove.setAttribute('class', 'uk-text-center uk-text-bold uk-text-danger uk-flex uk-flex-middle');

                                                            const createRemoveButton = document.createElement('a');
                                                            createRemoveButton.setAttribute('onclick', 'createRemove' + x + '(' + createCount + ')');
                                                            createRemoveButton.setAttribute('class', 'uk-link-reset');
                                                            createRemoveButton.innerHTML = 'X';

                                                            createName.appendChild(createNameInput);
                                                            newCreateCustomRab.appendChild(createName);
                                                            createLength.appendChild(createLengthInput);
                                                            newCreateCustomRab.appendChild(createLength);
                                                            createWidth.appendChild(createWidthInput);
                                                            newCreateCustomRab.appendChild(createWidth);
                                                            createHeight.appendChild(createHeightInput);
                                                            newCreateCustomRab.appendChild(createHeight);
                                                            createVol.appendChild(createVolInput);
                                                            newCreateCustomRab.appendChild(createVol);
                                                            createDenInput.appendChild(createOption1);
                                                            createDenInput.appendChild(createOption2);
                                                            createDenInput.appendChild(createOption3);
                                                            createDenInput.appendChild(createOption4);
                                                            createDenInput.appendChild(createOption5);
                                                            createDen.appendChild(createDenInput);
                                                            newCreateCustomRab.appendChild(createDen);
                                                            createPrice.appendChild(createPriceInput);
                                                            newCreateCustomRab.appendChild(createPrice);
                                                            createQty.appendChild(createQtyInput);
                                                            newCreateCustomRab.appendChild(createQty);
                                                            createRemove.appendChild(createRemoveButton);
                                                            newCreateCustomRab.appendChild(createRemove);
                                                            createCustomRab.appendChild(newCreateCustomRab);
                                                            createCustomRab.appendChild(line);

                                                            $("input[id='customprice"+ x +"["+createCount+"]']").on({
                                                                keyup: function() {
                                                                    formatNumber($(this));
                                                                    formatCurrency($(this));
                                                                },
                                                                blur: function() {
                                                                    formatCurrency($(this), "blur");
                                                                }
                                                            });

                                                            function formatNumber(n) {
                                                                return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                                            };

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
                                                                        right_side += "";
                                                                    }

                                                                    right_side = right_side.substring(0, 0);

                                                                    input_val = "Rp" + left_side + "." + right_side;

                                                                } else {

                                                                    input_val = formatNumber(input_val);
                                                                    input_val = "Rp" + input_val;

                                                                    if (blur === "blur") {
                                                                        input_val += "";
                                                                    }
                                                                }

                                                                input.val(input_val);

                                                                var updated_len = input_val.length;
                                                                caret_pos = updated_len - original_len + caret_pos;
                                                                input[0].setSelectionRange(caret_pos, caret_pos);
                                                            }
                                                            
                                                        };

                                                        // Currency
                                                        $("input[id='customprice"+ <?= $project['id'] ?> +"[0]']").on({
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
                                                                        right_side += "";
                                                                    }

                                                                    right_side = right_side.substring(0, 0);

                                                                    input_val = "Rp" + left_side + "." + right_side;

                                                                } else {

                                                                    input_val = formatNumber(input_val);
                                                                    input_val = "Rp" + input_val;

                                                                    if (blur === "blur") {
                                                                        input_val += "";
                                                                    }
                                                                }

                                                                input.val(input_val);

                                                                var updated_len = input_val.length;
                                                                caret_pos = updated_len - original_len + caret_pos;
                                                                input[0].setSelectionRange(caret_pos, caret_pos);
                                                            }

                                                        function createRemove<?= $project['id'] ?>(i) {
                                                            const createRemoveElement = document.getElementById('create<?= $project['id'] ?>' + i);
                                                            document.getElementById('customline<?= $project['id'] ?>['+ i + ']').remove();
                                                            createRemoveElement.remove();
                                                        };
                                                    </script>
                                                </div>
                                                <!-- END EDIT MDL / RAB DATA -->

                                                <!-- BUTTON TUTUP EDIT PESANAN -->
                                                <a class="uk-button uk-button-default uk-margin" id="closebuttonrab<?=$project['id']?>" hidden>TUTUP EDIT PESANAN</a>
                                                <!-- BUTTON TUTUP EDIT PESANAN -->

                                                
                                                <div class="uk-margin-bottom">
                                                    <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left" for="paket">Nomor SPH</label>
                                                    <div class="uk-form-controls">
                                                        <?php if($authorize->hasPermission('marketing.project.edit', $uid)){ $upload = "UPLOAD" ?>
                                                                <input type="text" class="uk-input" id="nosph<?= $project['id'] ?>" name="nosph<?= $project['id'] ?>" <?php if (!empty($project['no_sph'])) {$nosph = $project['no_sph'];echo "value='$nosph'";} ?> placeholder="Nomor SPH">
                                                            <?php }else{ $upload = "FILE"?>
                                                                <input type="text" class="uk-input" id="nosph<?= $project['id'] ?>" name="nosph<?= $project['id'] ?>" <?php if (!empty($project['no_sph'])) {$nosph = $project['no_sph'];echo "value='$nosph'";} ?> placeholder="Nomor SPH" readonly>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <a id="downloadsph<?=$project['id']?>" class="uk-button uk-button-primary uk-margin-small-right" href="project/sphview/<?= $project['id'] ?>" target="_blank">Download SPH</a>

                                                <!-- SPH -->
                                                <div class="uk-margin" id="image-container-createsph-<?= $project['id'] ?>">
                                                    <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate"><?=$upload?> SPH</label>&nbsp;&nbsp;<?php if ($projectdata[$project['id']]['versph'] > 1) { echo "<a href=".base_url("version?project=".$project['id']."&type=3").">+".($projectdata[$project['id']]['versph']-1)."&nbsp;ver</a>"; } ?>
                                                    <div class="uk-child-width-auto uk-text-center uk-margin-top" id="containersph-<?= $project['id'] ?>" uk-grid>
                                                        <div id="sph-file-<?= $project['id'] ?>">
                                                            <?php if (!empty($project['sph'])) { ?>
                                                                <div id="sph-card<?= $project['id'] ?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                                    <div class="uk-position-small uk-position-right"><?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?><a class="tm-img-remove2 uk-border-circle uk-icon" id="removeCardFilesph<?= $project['id']; ?>" onclick="removeCardFilesph<?= $project['id'] ?>()" uk-icon="close"></a><?php } ?></div>
                                                                    <a href="img/sph/<?= $project['sph'] ?>" target="_blank"><span uk-icon="file-text" ;></span><?= $project['sph'] ?> </a>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <script>
                                                            function removeCardFilesph<?= $project['id']; ?>() {
                                                                let text = "Hapus file sph ini?";
                                                                if (confirm(text) == true) {
                                                                    $.ajax({
                                                                        url: "project/removesph/<?= $project['id'] ?>",
                                                                        method: "POST",
                                                                        data: {
                                                                            sph: <?= $project['id'] ?>,
                                                                        },
                                                                        dataType: "json",
                                                                        error: function() {
                                                                            console.log('error', arguments);
                                                                        },
                                                                        success: function() {
                                                                            console.log('success', arguments);
                                                                            alert('data berhasil di hapus');
                                                                            $("#sph-file-<?= $project['id'] ?>").remove();
                                                                            $("#downloadsph<?= $project['id'] ?>").remove();
                                                                        },
                                                                    })
                                                                }
                                                            }
                                                        </script>
                                                    </div>
                                                    <?php if ( $authorize->hasPermission('marketing.project.edit', $uid) ) { ?>
                                                        <div id="image-containersph-<?= $project['id'] ?>" class="uk-form-controls">
                                                            <input id="photocreatesph<?= $project['id'] ?>" name="sph" hidden />
                                                            <div id="js-upload-createsph-<?= $project['id'] ?>" class="js-upload-createsph-<?= $project['id'] ?> uk-placeholder uk-text-center uk-margin-remove-top">
                                                                <span uk-icon="icon: cloud-upload"></span>
                                                                <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                                <div uk-form-custom>
                                                                    <input type="file">
                                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                                </div>
                                                            </div>
                                                            <progress id="js-progressbar-createsph-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <script>
                                                    UIkit.upload('.js-upload-createsph-<?= $project['id'] ?>', {
                                                        url: 'upload/sph/<?= $project['id'] ?>',
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

                                                            console.log(arguments[0].response);
                                                            var id = arguments[0].response.id;
                                                            var filename = arguments[0].response.file;
                                                            var proid = arguments[0].response.proid;

                                                            console.log(id, filename, proid);

                                                            if (document.getElementById('sph-file-' + id)) {
                                                                document.getElementById('sph-file-' + id).remove();
                                                            };

                                                            var contsph = document.getElementById('containersph-<?= $project['id'] ?>');

                                                            var container = document.createElement('div');
                                                            container.setAttribute('id', 'sph-file-' + id);

                                                            var cardsph = document.createElement('div');
                                                            cardsph.setAttribute('class', 'uk-card uk-card-default uk-card-body uk-margin-bottom');

                                                            var divclosed = document.createElement('div');
                                                            divclosed.setAttribute('class', 'uk-position-small uk-position-right');

                                                            var close = document.createElement('a');
                                                            close.setAttribute('id', 'remove-sph-' + id);
                                                            close.setAttribute('class', 'tm-img-remove2 uk-border-circle uk-icon');
                                                            close.setAttribute('onClick', 'removeCardFilesph(' + id + ',' + proid + ')');
                                                            close.setAttribute('uk-icon', 'close');

                                                            var link = document.createElement('a');
                                                            link.setAttribute('href', 'img/sph/' + filename);
                                                            link.setAttribute('target', '_blank');

                                                            var file = document.createTextNode(filename);

                                                            var icon = document.createElement('span');
                                                            icon.setAttribute('uk-icon', 'file-text');

                                                            contsph.appendChild(container);
                                                            container.appendChild(cardsph);
                                                            cardsph.appendChild(divclosed);
                                                            divclosed.appendChild(close);
                                                            cardsph.appendChild(link);
                                                            link.appendChild(icon);
                                                            link.appendChild(file);
                                                        },

                                                        loadStart: function(e) {
                                                            console.log('loadStart', arguments);

                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').removeAttribute('hidden');

                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').max = e.total;
                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').value = e.loaded;

                                                        },

                                                        progress: function(e) {
                                                            console.log('progress', arguments);

                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').max = e.total;
                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').value = e.loaded;
                                                        },

                                                        loadEnd: function(e) {
                                                            console.log('loadEnd', arguments);

                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').max = e.total;
                                                            document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').value = e.loaded;
                                                        },

                                                        completeAll: function() {
                                                            console.log('completeAll', arguments);

                                                            setTimeout(function() {
                                                                document.getElementById('js-progressbar-createsph-<?= $project['id'] ?>').setAttribute('hidden', 'hidden');
                                                                alert('<?= lang('Proses selesai, File sph berhasil di unggah.') ?>');
                                                            }, 1000);
                                                        }

                                                    });

                                                    function removeCardFilesph(id, proid) {
                                                        let text = "Hapus file sph ini?";
                                                        if (confirm(text) == true) {
                                                            $.ajax({
                                                                url: "project/removesph/" + id,
                                                                method: "POST",
                                                                data: {
                                                                    sph: id,
                                                                },
                                                                dataType: "json",
                                                                error: function() {
                                                                    console.log('error', arguments);
                                                                },
                                                                success: function() {
                                                                    console.log('success', arguments);
                                                                    $("#sph-file-" + id).remove();
                                                                },
                                                            })
                                                        }
                                                    }
                                                </script>
                                                
                                                
                                                <!-- <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/</?= $project['id'] ?>" target="_blank">Download SPH</a> -->
                                                <!-- </?php if(!empty($project['sph'])){?> -->
                                                <!-- </?php } ?> -->
                                                <hr>
                                                <!-- end SPH -->
                                            <!-- </?php } ?> -->
                                        </div>
                                        <!-- </?php } ?> -->
                                        <!-- </?php } else { ?> -->
                                        <!-- <div class="uk-padding uk-padding-remove-vertical togglesph</?= $project['id'] ?>" hidden>
                                                <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphview/</?= $project['id'] ?>">Download SPH</a>
                                                <div class="uk-overflow-auto uk-margin uk-margin-remove-top">
                                                    <table class="uk-table uk-table-middle uk-table-divider">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Panjang</th>
                                                                <th>Lebar</th>
                                                                <th>Tinggi</th>
                                                                <th>Volume</th>
                                                                <th>Satuan</th>
                                                                <th>Keterangan</th>
                                                                <th>Jumlah Pesanan</th>
                                                                <th>Harga</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            </?php if (!empty($projectdata[$project['id']]['rab'])) {
                                                                foreach ($projectdata[$project['id']]['rab'] as $mdlrab) { ?>
                                                                    <tr>
                                                                        <td></?= $mdlrab['name'] ?></td>
                                                                        <td></?= $mdlrab['length'] ?></td>
                                                                        <td></?= $mdlrab['width'] ?></td>
                                                                        <td></?= $mdlrab['height'] ?></td>
                                                                        <td></?= $mdlrab['volume'] ?></td>
                                                                        <td>
                                                                            </?php
                                                                            if ($mdlrab['denomination'] === "1") {
                                                                                echo "Unit";
                                                                            } elseif ($mdlrab['denomination'] === "2") {
                                                                                echo "Meter Lari";
                                                                            } elseif ($mdlrab['denomination'] === "3") {
                                                                                echo "Meter Persegi";
                                                                            } elseif ($mdlrab['denomination'] === "4") {
                                                                                echo "Set";
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td></?= $mdlrab['keterangan'] ?></td>
                                                                        <td class="uk-text-center"></?= $mdlrab['qty'] ?></td>
                                                                        <td></?= "Rp. " . number_format($mdlrab['price'], 0, ',', '.');" "; ?></td>
                                                                    </tr>
                                                                </?php }
                                                            } ?>
                                                            <tr>
                                                                <td colspan="8" class="tm-h3" style="text-transform: uppercase;">Custom Pemesanan</td>
                                                            </tr>
                                                            </?php if (!empty($projectdata[$project['id']]['customrab'])) {
                                                                foreach ($projectdata[$project['id']]['customrab'] as $customsph) { ?>
                                                                    <tr>
                                                                        <td></?= $customsph['name'] ?></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></?= "Rp. " . number_format($customsph['price'], 0, ',', '.');" "; ?></td>
                                                                    </tr>
                                                                </?php }
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </?php } ?> -->
                                        <script>
                                            // Dropdown SPH
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
                                                <?php if (!empty($projectdata[$project['id']]['paket'])) {
                                                    foreach ($projectdata[$project['id']]['autopaket'] as $autopaket) {
                                                        echo '{label:"' . $autopaket['name'] . '",idx:' . $autopaket['id'] . '},';
                                                    }
                                                } else {
                                                    foreach ($pakets as $paket) {
                                                        echo '{label:"' . $paket['name'] . '",idx:' . $paket['id'] . '},';
                                                    }
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

                                                                var thphoto = document.createElement('th');
                                                                thphoto.innerHTML = 'Foto';

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
                                                                    inputchecklist.setAttribute('id', 'checked[<?= $project['id'] ?>' + i.item.idx + emdlarray[t]['id'] + ']');
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
                                                                    if (emdlarray[t]['denomination'] === '1') {
                                                                        tdden.innerHTML = 'Unit'
                                                                    } else if (emdlarray[t]['denomination'] === '2') {
                                                                        tdden.innerHTML = 'Meter'
                                                                    } else if (emdlarray[t]['denomination'] === '3') {
                                                                        tdden.innerHTML = 'Meter Persegi'
                                                                    } else if (emdlarray[t]['denomination'] === '4') {
                                                                        tdden.innerHTML = 'Set'
                                                                    }

                                                                    var tdphoto = document.createElement('td');

                                                                    var divlightbox = document.createElement('div');
                                                                    divlightbox.setAttribute('uk-lightbox', '')

                                                                    var anchorlightbox = document.createElement('a');
                                                                    anchorlightbox.setAttribute('class', 'uk-inline')
                                                                    anchorlightbox.setAttribute('href', 'img/mdl/' + emdlarray[t]['photo']);
                                                                    anchorlightbox.setAttribute('role', 'button')

                                                                    var imglightbox = document.createElement('img');
                                                                    imglightbox.setAttribute('class', 'uk-preserve-width uk-border-circle')
                                                                    imglightbox.setAttribute('src', 'img/mdl/' + emdlarray[t]['photo']);
                                                                    imglightbox.setAttribute('alt', emdlarray[t]['photo']);
                                                                    imglightbox.setAttribute('width', '40');
                                                                    imglightbox.setAttribute('height', '40');

                                                                    var tdqty = document.createElement('td');
                                                                    tdqty.setAttribute('class', 'uk-form-controls');

                                                                    var inputqty = document.createElement('input');
                                                                    inputqty.setAttribute('class', 'uk-input uk-form-width-small');
                                                                    inputqty.setAttribute('type', 'number');
                                                                    inputqty.setAttribute('id', 'eqty[<?= $project['id'] ?>' + i.item.idx + emdlarray[t]['id'] + ']');
                                                                    inputqty.setAttribute('name', 'eqty<?= $project['id'] ?>[' + i.item.idx + '][' + emdlarray[t]['id'] + ']');
                                                                    inputqty.setAttribute('value', '0');
                                                                    inputqty.setAttribute('onchange', 'price<?= $project['id'] ?>(' + i.item.idx + emdlarray[t]['id'] + ')');

                                                                    var tdprice = document.createElement('td');
                                                                    tdprice.setAttribute('id', 'eshowprice[<?= $project['id'] ?>' + i.item.idx + emdlarray[t]['id'] + ']');
                                                                    tdprice.innerHTML = 0;

                                                                    var hiddenprice = document.createElement('div');
                                                                    hiddenprice.setAttribute('id', 'eprice[<?= $project['id'] ?>' + i.item.idx + emdlarray[t]['id'] + ']');
                                                                    hiddenprice.setAttribute('hidden', '');
                                                                    hiddenprice.innerHTML = emdlarray[t]['price'];

                                                                    anchorlightbox.appendChild(imglightbox);
                                                                    divlightbox.appendChild(anchorlightbox);
                                                                    tdphoto.appendChild(divlightbox);
                                                                    tdqty.appendChild(inputqty);
                                                                    tdchecklist.appendChild(inputchecklist);
                                                                    trbody.appendChild(tdchecklist);
                                                                    trbody.appendChild(tdname);
                                                                    trbody.appendChild(tdlength);
                                                                    trbody.appendChild(tdwidth);
                                                                    trbody.appendChild(tdheight);
                                                                    trbody.appendChild(tdvol);
                                                                    trbody.appendChild(tdden);
                                                                    trbody.appendChild(tdphoto);
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
                                                                trhead.appendChild(thphoto);
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
                                                epricetd.innerHTML = 'Rp. ' + Intl.NumberFormat('de-DE').format(eprojprice);

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
                                <?php }
                                } ?>
                            </div>
                            <!-- Detail Pemesanan Section End -->

                            <!-- SPK Section -->
                            <!-- </?php if ($project['spk'] != null) {
                                if ($project['status_spk'] === '0') { ?> -->
                            <div class="uk-margin uk-child-width-1-2" <?=$tooglespk?> uk-grid>
                                <div>
                                    <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                        <div>
                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">SPK</div>
                                        </div>
                                        <div>
                                            <?php if ($project['status_spk'] === '0') { ?>
                                                <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #ff0000; color: #ff0000; font-weight: bold; padding: 3px;">Menuggu Persetujuan DPSA</div>
                                            <?php } elseif ($project['status_spk'] === '1') { ?>
                                                <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #32CD32; color: #32CD32; font-weight: bold; padding: 3px;">Disetujui</div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-text-right">
                                    <a class="uk-link-reset uk-icon-button" id="togglespk<?= $project['id'] ?>" uk-toggle="target: .togglespk<?= $project['id'] ?>"><span class="uk-light" id="closespk<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openspk<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                </div>
                            </div>

                            <div class="togglespk<?= $project['id'] ?>" hidden>
                                <div class="uk-form-horizontal">
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label">Tanggal Upload SPK</label>
                                        <div class="uk-form-controls">:
                                            <div class="uk-inline">
                                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                    <input class="uk-input uk-form-width-medium" <?php if (!empty($project['tanggal_spk'])) {$tglspk = date_create($project['tanggal_spk']); echo "value='" . date_format($tglspk, 'm/d/Y') . "'";} ?> id="tanggalspk<?= $project['id'] ?>" name="tanggalspk<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                <?php } else { ?>
                                                    <span class=""><?php if (!empty($project['tanggal_spk'])) { $tglspk = date_create($project['tanggal_spk']);echo date_format($tglspk, 'm/d/Y');} else { echo date('m/d/Y'); } ?></span>
                                                    <input class="uk-input uk-form-width-medium" <?php if (!empty($project['tanggal_spk'])) {$tglspk = date_create($project['tanggal_spk']); echo "value='" . date_format($tglspk, 'm/d/Y') . "'";} ?> id="tanggalspk<?= $project['id'] ?>" name="tanggalspk<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" hidden/>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label">NO SPK</label>
                                            <div class="uk-form-controls">:
                                                <input type="text" class="uk-input uk-width-1-3" id="nospk" name="nospk" value="<?php if (!empty($project['no_spk'])) { echo $project['no_spk'];} ?>" placeholder="NO SPK" />
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label uk-margin-remove-top">NO. SPK</label>
                                            <div class="uk-form-controls">: <?php if (!empty($project['no_spk'])) {  echo $project['no_spk']; } ?> </a></div>
                                            <input type="hidden" class="uk-input uk-width-1-3" id="nospk" name="nospk" value="<?php if (!empty($project['no_spk'])) { echo $project['no_spk']; } ?>" />
                                        </div>
                                    <?php } ?>

                                    <div class="uk-margin-small">
                                        <label class="uk-form-label uk-margin-remove-top">File SPK</label>
                                        <div class="uk-form-controls">: <?php if (!empty($project['spk'])) { ?><a href="/img/spk/<?= $project['spk'] ?>"><span uk-icon="file-pdf"></span><?= $project['spk'] ?></a> <?php } ?>&nbsp;&nbsp;<?php if ($projectdata[$project['id']]['verspk'] > 1) { echo "<a href=".base_url("version?project=".$project['id']."&type=4").">+".($projectdata[$project['id']]['verspk']-1)."&nbsp;ver</a>"; } ?></div>
                                    </div>
                                </div>

                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                    <div class="uk-margin" id="image-container-createspk-<?= $project['id'] ?>">
                                        <div id="image-containerspk-<?= $project['id'] ?>" class="uk-form-controls">
                                            <input id="photocreatespk<?= $project['id'] ?>" name="spk" hidden />
                                            <div id="js-upload-createspk-<?= $project['id'] ?>" class="js-upload-createspk-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                <span uk-icon="icon: cloud-upload"></span>
                                                <span class="uk-text-middle">Tarik dan lepas file SPK disini atau</span>
                                                <div uk-form-custom>
                                                    <input type="file">
                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                </div>
                                            </div>
                                            <progress id="js-progressbar-createspk-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- </?php } elseif ($project['status_spk'] === '1') { ?> -->
                            <!-- <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                        <div>
                                            <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                <div>
                                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">SPK</div>
                                                </div>
                                                <div>
                                                    <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #32CD32; color: #32CD32; font-weight: bold; padding: 3px;">Disetujui</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-text-right">
                                            <a class="uk-link-reset uk-icon-button" id="togglespk??= $project['id'] ?>" uk-toggle="target: .togglespk</?= $project['id'] ?>"><span class="uk-light" id="closespk<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openspk<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                        </div>
                                    </div>

                                    <div class="togglespk</?= $project['id'] ?>" hidden>
                                        <div class="uk-form-horizontal">
                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">Tanggal Upload SPK</label>
                                                <div class="uk-form-controls">: </?= date('d M Y, H:i', strtotime($project['updated_at'])); ?></div>
                                            </div>

                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">File SPK</label>
                                                <div class="uk-form-controls">: <a href="/img/spk/</?= $project['spk'] ?>"><span uk-icon="file-pdf"></span></?= $project['spk'] ?></a></div>
                                            </div>

                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">NO. SPK</label>
                                                <div class="uk-form-controls">: </?php if (!empty($project['no_spk'])) { $project['no_spk'];} ?> </a></div>
                                                <input type="hidden" class="uk-input uk-width-1-3" id="nospk" name="nospk" value="</?php if (!empty($project['no_spk'])) { $project['no_spk']; } ?>" />
                                            </div>
                                        </div>
                                    </div> -->
                            <!-- </?php } ?> -->

                            <script type="text/javascript">
                                var bar = document.getElementById('js-progressbar-createspk-<?= $project['id'] ?>');

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

                                        var imgContainer = document.getElementById('image-container-createspk-<?= $project['id'] ?>');

                                        var displayContainer = document.createElement('div');
                                        displayContainer.setAttribute('id', 'display-container-createspk-<?= $project['id'] ?>');
                                        displayContainer.setAttribute('class', 'uk-inline uk-width-1-1');

                                        var displayImg = document.createElement('div');
                                        displayImg.setAttribute('class', 'uk-placeholder uk-text-center');

                                        var textfont = document.createElement('h6');

                                        var linkrev = document.createElement('span')
                                        linkrev.setAttribute('uk-icon', 'file-pdf');

                                        var link = document.createElement('a');
                                        link.setAttribute('href', 'img/spk/' + filename);
                                        link.setAttribute('target', '_blank');

                                        var closeContainer = document.createElement('div');
                                        closeContainer.setAttribute('class', 'uk-position-small uk-position-right');

                                        var closeButton = document.createElement('a');
                                        closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                        closeButton.setAttribute('onClick', 'removeImgCreatespk<?= $project['id'] ?>()');
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

                                        document.getElementById('js-upload-createspk-<?= $project['id'] ?>').setAttribute('hidden', '');
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

                                            document.getElementById('display-container-createspk-<?= $project['id'] ?>').remove();
                                            document.getElementById('photocreatespk<?= $project['id'] ?>').value = '';

                                            alert(pesan);

                                            document.getElementById('js-upload-createspk-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                        }
                                    });
                                };

                                // Dropdown SPK
                                document.getElementById('togglespk<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closespk<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closespk<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openspk<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openspk<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closespk<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                            </script>
                            <!-- SPK Section End -->

                            <!-- Production Section -->
                            <?php if ($project['status_spk'] == 1) { ?>
                                <div class="uk-margin uk-child-width-1-2 uk-flex-middle" <?=$togleproduction?> uk-grid>
                                    <div>
                                        <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Produksi</div>
                                    </div>
                                    <div class="uk-text-right">
                                        <a class="uk-link-reset uk-icon-button" id="toggleproduction<?= $project['id'] ?>" uk-toggle="target: .toggleproduction<?= $project['id'] ?>"><span class="uk-light" id="closeproduction<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openproduction<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                    </div>
                                </div>

                                <div class="toggleproduction<?= $project['id'] ?>" hidden>
                                    <div class="uk-form-horizontal">
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label">Tanggal Batas Produksi</label>
                                            <div class="uk-form-controls">:
                                                <div class="uk-inline">
                                                    <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?>
                                                        <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                        <input class="uk-input uk-form-width-medium" <?php if (!empty($project['batas_produksi'])) { $batasproduksi = date_create($project['batas_produksi']); echo "value='" . date_format($batasproduksi, 'm/d/Y') . "'"; } ?> id="batasproduksi<?= $project['id'] ?>" name="batasproduksi<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                    <?php } else { ?>
                                                        <span class=""><?php if (!empty($project['batas_produksi'])) { $batasproduksi = date_create($project['batas_produksi']); echo date_format($batasproduksi, 'm/d/Y'); } else {echo date('m/d/Y');} ?></span>
                                                        <input class="uk-input uk-form-width-medium" <?php if (!empty($project['batas_produksi'])) { $batasproduksi = date_create($project['batas_produksi']); echo "value='" . date_format($batasproduksi, 'm/d/Y') . "'"; } ?> id="batasproduksi<?= $project['id'] ?>" name="batasproduksi<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" / hidden>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- MDL PRODUCTION PROGRESS -->
                                    <?php if(!empty($projectdata[$project['id']]['production'])){ ?>
                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left uk-margin uk-margin-top-large" style="text-transform: uppercase;">Progress Produksi Produk</label>
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
                                                        <th class="uk-text-center">PIC Produksi</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($projectdata[$project['id']]['production'] as $production) { ?>
                                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid) || ($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                            <?php 
                                                                if($authorize->hasPermission('ppic.project.edit', $uid)){
                                                                    $dispermission = "disabled";
                                                                    $dispermitppic = "";
                                                                }else{
                                                                    $dispermission = "";
                                                                    $dispermitppic = "disabled";
                                                                } 
                                                            ?>
                                                            <tr>
                                                                <!-- production edit permission -->
                                                                <td><?= $production['name'] ?></td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['gambar_kerja']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="gambarkerja<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?= $dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_awal']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="mesinawal<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['tukang']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="tukang<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_lanjutan']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="mesinlanjutan<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['finishing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="finishing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['packing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="packing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['pengiriman']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="pengiriman<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermitppic?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="setting<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1" <?=$dispermitppic?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <!-- end production edit permission -->
    
                                                                <!-- ppic edit production employe -->
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="picpro[<?= $production['id'] ?>]" <?=$dispermitppic?>>
                                                                            <option value="">Pilih PIC</option>
                                                                            <?php if (!empty($picpro)) {
                                                                                foreach ($picpro as $propic) { ?>
                                                                                    <option value="<?= $propic->id ?>" <?php if ($production['userid'] === $propic->id) { echo 'selected'; } ?>><?= $propic->name ?></option>
                                                                                <?php }
                                                                            } else { ?>
                                                                                <option value="" disabled> Tambahkan pegawai produksi terlebih dahulu </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <!-- end ppic edit production employe -->
    
                                                                <td class="uk-text-center">
                                                                    <div><?= $production['percentages'] ?> %</div>
                                                                </td>
                                                            </tr>
                                                        <?php } elseif (!$authorize->hasPermission('production.project.edit', $uid)) { ?>
                                                            <tr>
                                                                <td><?= $production['name'] ?></td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['gambar_kerja']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_awal']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['tukang']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['mesin_lanjutan']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['finishing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['packing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['pengiriman']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="picpro[<?= $production['id'] ?>]" disabled>
                                                                            <option value="">Pilih PIC</option>
                                                                            <?php if (!empty($picpro)) {
                                                                                foreach ($picpro as $propic) { ?>
                                                                                    <option value="<?= $propic->id ?>" <?php if ($production['userid'] === $propic->id) { echo 'selected'; } ?>><?= $propic->name ?></option>
                                                                                <?php }
                                                                            } else { ?>
                                                                                <option value="" disabled> Tambahkan pegawai produksi terlebih dahulu </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <div><?= $production['percentages'] ?> %</div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                    <!--END MDL PRODUCTION PROGRESS  -->

                                    <!-- QUANTITY PRODUCT DELIVER -->
                                    <?php if(!empty($projectdata[$project['id']]['pengiriman'])){?>
                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Jumlah Produk Terkirim</label>
                                        <div class="uk-child-width-1-3@s uk-margin" uk-grid>
                                            <?php foreach($projectdata[$project['id']]['pengiriman'] as $pengiriman){ ?>
                                                <div>
                                                    <div class="uk-card uk-card-default uk-card-small uk-card-body">
                                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;"><?=$pengiriman['name']?> : <?= $pengiriman['pengiriman'] ?></label>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php }?>
                                    <!-- END QUANTITY PRODUCT DELIVER -->

                                    <!-- CUSTOM RAB PRODUCTION -->
                                   
                                    <?php if(!empty($projectdata[$project['id']]['productioncustrab'])){ ?>
                                        <hr class="uk-divider-icon">
                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left uk-margin uk-margin-large-top" style="text-transform: uppercase;">Progress Produksi Produk Kustom</label>
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
                                                        <th class="uk-text-center">PIC Produksi</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($projectdata[$project['id']]['productioncustrab'] as $productioncustrab) {  ?>
                                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid) || ($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                            <?php 
                                                                if($authorize->hasPermission('ppic.project.edit', $uid)){
                                                                    $dispermission = "disabled";
                                                                    $dispermitppic = "";
                                                                }else{
                                                                    $dispermission = "";
                                                                    $dispermitppic = "disabled";
                                                                } 
                                                            ?>
                                                            <tr>
                                                                <!-- production edit permission -->
                                                                <td><?= $productioncustrab['name'] ?></td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['gambar_kerja']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabgambarkerja<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?= $dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['mesin_awal']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabmesinawal<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['tukang']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabtukang<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['mesin_lanjutan']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabmesinlanjutan<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['finishing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabfinishing<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['packing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabpacking<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermission?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['pengiriman']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabpengiriman<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermitppic?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['setting']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } else { ?>
                                                                        <input class="uk-checkbox" type="checkbox" name="custrabsetting<?= $project['id']; ?>[<?= $productioncustrab['id'] ?>]" value="1" <?=$dispermitppic?>>
                                                                    <?php } ?>
                                                                </td>
                                                                <!-- end production edit permission -->
    
                                                                <!-- ppic edit production employe -->
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="custrabpicpro[<?= $productioncustrab['id'] ?>]" <?=$dispermitppic?>>
                                                                            <option value="">Pilih PIC</option>
                                                                            <?php if (!empty($picpro)) {
                                                                                foreach ($picpro as $propic) { ?>
                                                                                    <option value="<?= $propic->id ?>" <?php if ($productioncustrab['userid'] === $propic->id) { echo 'selected'; } ?>><?= $propic->name ?></option>
                                                                                <?php }
                                                                            } else { ?>
                                                                                <option value="" disabled> Tambahkan pegawai produksi terlebih dahulu </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <!-- end ppic edit production employe -->
    
                                                                <td class="uk-text-center">
                                                                    <div><?= $productioncustrab['percentages'] ?> %</div>
                                                                </td>
                                                            </tr>
                                                        <?php } elseif (!$authorize->hasPermission('production.project.edit', $uid)) { ?>
                                                            <tr>
                                                                <td><?= $productioncustrab['name'] ?></td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['gambar_kerja']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['mesin_awal']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['tukang']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['mesin_lanjutan']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['finishing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['packing']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['pengiriman']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <?php if (strtoupper($productioncustrab['setting']) == '1') { ?>
                                                                        <div uk-icon="check"></div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="picpro[<?= $productioncustrab['id'] ?>]" disabled>
                                                                            <option value="">Pilih PIC</option>
                                                                            <?php if (!empty($picpro)) {
                                                                                foreach ($picpro as $propic) { ?>
                                                                                    <option value="<?= $propic->id ?>" <?php if ($productioncustrab['userid'] === $propic->id) { echo 'selected'; } ?>><?= $propic->name ?></option>
                                                                                <?php }
                                                                            } else { ?>
                                                                                <option value="" disabled> Tambahkan pegawai produksi terlebih dahulu </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="uk-text-center">
                                                                    <div><?= $productioncustrab['percentages'] ?> %</div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?> 
                                    <!-- END OF CUSTOM RAB PRODUCTION -->

                                    <!-- QUANTITY CUSTOM PRODUCT DELIVER -->
                                    <!-- </?php if(!empty($projectdata[$project['id']]['pengirimanprodukcustom'])){?>
                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Jumlah Produk Kustom Terkirim</label>
                                        <div class="uk-child-width-1-3@s uk-margin uk-margin-large-bottom" uk-grid>
                                            </?php foreach($projectdata[$project['id']]['pengirimanprodukcustom'] as $pengirimancustrab){ ?>
                                                <div>
                                                    <div class="uk-card uk-card-default uk-card-small uk-card-body">
                                                        <label class="uk-h5 uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;"></?=$pengirimancustrab['name']?> : </?=$pengirimancustrab['pengiriman']?></label>
                                                    </div>
                                                </div>
                                            </?php } ?>
                                        </div>
                                    </?php }?> -->
                                    <!-- END QUANTITY CUSTOM PRODUCT DELIVER -->

                                    <!-- Bukti Pengiriman -->
                                    <div class="uk-margin" id="image-container-createbuktipengiriman-<?= $project['id'] ?> <?=$bastview?>">
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate" style="text-transform: uppercase;"<?=$bastview?>>Bukti Pengiriman</label>
                                        <div class="uk-margin uk-child-width-1-3 uk-child-width-1-6@m uk-grid-match uk-flex-middle uk-grid-divider" uk-grid uk-lightbox="animation: slide">
                                            <?php foreach ($projectdata[$project['id']]['buktipengiriman'] as $sendproof) { ?>
                                                <div <?=$bastview?>>
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
                                                        var publishupdate = "<?= $sendproof['created_at'] ?>";
                                                        var thatdate = publishupdate.split(/[- :]/);
                                                        thatdate[1]--;
                                                        var publishthatdate = new Date(...thatdate);
                                                        var publishyear = publishthatdate.getFullYear();
                                                        var publishmonth = publishthatdate.getMonth();
                                                        var publishdate = publishthatdate.getDate();
                                                        var publishday = publishthatdate.getDay();

                                                        switch (publishday) {
                                                            case 0:
                                                                publishday = "Minggu";
                                                                break;
                                                            case 1:
                                                                publishday = "Senin";
                                                                break;
                                                            case 2:
                                                                publishday = "Selasa";
                                                                break;
                                                            case 3:
                                                                publishday = "Rabu";
                                                                break;
                                                            case 4:
                                                                publishday = "Kamis";
                                                                break;
                                                            case 5:
                                                                publishday = "Jum'at";
                                                                break;
                                                            case 6:
                                                                publishday = "Sabtu";
                                                                break;
                                                        }
                                                        switch (publishmonth) {
                                                            case 0:
                                                                publishmonth = "Januari";
                                                                break;
                                                            case 1:
                                                                publishmonth = "Februari";
                                                                break;
                                                            case 2:
                                                                publishmonth = "Maret";
                                                                break;
                                                            case 3:
                                                                publishmonth = "April";
                                                                break;
                                                            case 4:
                                                                publishmonth = "Mei";
                                                                break;
                                                            case 5:
                                                                publishmonth = "Juni";
                                                                break;
                                                            case 6:
                                                                publishmonth = "Juli";
                                                                break;
                                                            case 7:
                                                                publishmonth = "Agustus";
                                                                break;
                                                            case 8:
                                                                publishmonth = "September";
                                                                break;
                                                            case 9:
                                                                publishmonth = "Oktober";
                                                                break;
                                                            case 10:
                                                                publishmonth = "November";
                                                                break;
                                                            case 11:
                                                                publishmonth = "Desember";
                                                                break;
                                                        }

                                                        var publishfulldate = publishday + ", " + publishdate + " " + publishmonth + " " + publishyear;
                                                        document.getElementById("pengiriman<?= $sendproof['id'] ?>").innerHTML = publishfulldate;
                                                    </script>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?>
                                            <div id="image-containerbuktipengiriman-<?= $project['id'] ?>" class="uk-form-controls">
                                                <!-- <input id="photocreatebuktipengiriman</?= $project['id'] ?>" name="buktipengiriman" hidden /> -->
                                                <div id="js-upload-createbuktipengiriman-<?= $project['id'] ?>" class="js-upload-createbuktipengiriman-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                    <span uk-icon="icon: cloud-upload"></span>
                                                    <span class="uk-text-middle">Tarik dan lepas bukti pengiriman disini atau</span>
                                                    <div uk-form-custom>
                                                        <input type="file" multiple>
                                                        <span class="uk-link uk-preserve-color">pilih satu</span>
                                                    </div>
                                                </div>
                                                <progress id="js-progressbar-createbuktipengiriman-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div id="list-foto-<?= $project['id'] ?>" ></div>

                                    <script type="text/javascript">
                                        // Upload Bukti Pembayaran
                                        var bar = document.getElementById('js-progressbar-createbuktipengiriman-<?= $project['id'] ?>');
                                        var createCount = 0;

                                        UIkit.upload('.js-upload-createbuktipengiriman-<?= $project['id'] ?>', {
                                            url: 'upload/buktipengiriman',
                                            multiple: true,
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
                                                createCount++;
                                                
                                                for (i in filename) {
                                                    // if (document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>')) {
                                                    //     document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>').remove();
                                                    // };

                                                    // document.getElementById('photocreatebuktipengiriman</?= $project['id'] ?>').value = filename;

                                                    var imgContainer = document.getElementById('list-foto-<?= $project['id'] ?>');

                                                    // var gridcontainer = document.createElement('div');
                                                    // gridcontainer.setAttribute('class', 'uk-child-width-1-2 uk-child-width-1-5@m');
                                                    // gridcontainer.setAttribute('uk-grid', '');

                                                    var displayContainer = document.createElement('div');
                                                    displayContainer.setAttribute('id', 'display-container-createbuktipengiriman-<?= $project['id'] ?>'+createCount);
                                                    displayContainer.setAttribute('class', 'uk-inline uk-width-1-2 uk-width-1-5@m uk-padding-small');

                                                    var displayImg = document.createElement('div');
                                                    displayImg.setAttribute('uk-lightbox', 'animation: fade');
                                                    displayImg.setAttribute('class', 'uk-inline');

                                                    var link = document.createElement('a');
                                                    link.setAttribute('href', 'img/bukti/pengiriman/' + filename);

                                                    var image = document.createElement('img');
                                                    image.setAttribute('src', 'img/bukti/pengiriman/' + filename);
                                                    image.setAttribute('width', '300');
                                                    image.setAttribute('height', '300');

                                                    var inputhidden = document.createElement('input');
                                                    inputhidden.setAttribute('hidden', '');
                                                    inputhidden.setAttribute('id', 'buktipengiriman-<?= $project['id'] ?>-'+createCount);
                                                    inputhidden.setAttribute('name', 'buktipengiriman-<?= $project['id'] ?>['+createCount+']');
                                                    inputhidden.setAttribute('value', filename);

                                                    var createNote = document.createElement('div');
                                                    createNote.setAttribute('class', 'uk-margin');

                                                    var createNoteInput = document.createElement('input');
                                                    createNoteInput.setAttribute('type', 'text');
                                                    createNoteInput.setAttribute('class', 'uk-input uk-form-width-large');
                                                    createNoteInput.setAttribute('placeholder', 'Keterangan (optional)');
                                                    createNoteInput.setAttribute('id', 'note-<?= $project['id'] ?>-'+createCount);
                                                    createNoteInput.setAttribute('name', 'note-<?= $project['id'] ?>['+createCount+']');

                                                    var closeContainer = document.createElement('div');
                                                    closeContainer.setAttribute('class', 'uk-position-small uk-position-right');

                                                    var closeButton = document.createElement('a');
                                                    closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                                    closeButton.setAttribute('onClick', 'removeImgCreatebuktipengiriman<?= $project['id'] ?>('+createCount+')');
                                                    closeButton.setAttribute('uk-icon', 'close');

                                                    createNote.appendChild(createNoteInput);
                                                    displayContainer.appendChild(displayImg);
                                                    closeContainer.appendChild(closeButton);
                                                    displayContainer.appendChild(inputhidden);
                                                    displayContainer.appendChild(createNote);
                                                    displayContainer.appendChild(closeContainer);
                                                    link.appendChild(image);
                                                    displayImg.appendChild(link);
                                                    // gridcontainer.appendChild(displayContainer);
                                                }
                                                imgContainer.appendChild(displayContainer);

                                                // document.getElementById('js-upload-createbuktipengiriman-</?= $project['id'] ?>').setAttribute('hidden', '');
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

                                        function removeImgCreatebuktipengiriman<?= $project['id'] ?>(i) {
                                            $.ajax({
                                                type: 'post',
                                                url: 'upload/removebuktipengiriman',
                                                data: {
                                                    'buktipengiriman': document.getElementById('buktipengiriman-<?= $project['id'] ?>-'+i).value
                                                },
                                                dataType: 'json',

                                                error: function() {
                                                    console.log('error', arguments);
                                                },

                                                success: function() {
                                                    console.log('success', arguments);

                                                    var pesan = arguments[0][1];

                                                    document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>'+i).remove();
                                                    // document.getElementById('buktipengiriman'+i).value = '';

                                                    alert(pesan);

                                                    // document.getElementById('js-upload-createbuktipengiriman-</?= $project['id'] ?>').removeAttribute('hidden', '');
                                                }
                                            });
                                        };
                                    </script>
                                    <!-- End Of Bukti Pengiriman -->

                                    <!-- Serah Terima -->
                                    <div class="uk-margin" id="image-container-createspk-<?= $project['id'] ?>" <?=$bastview?>>
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">SERAH TERIMA </label><?php if ($projectdata[$project['id']]['versertrim'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=5").">+".($projectdata[$project['id']]['versertrim']-1)."&nbsp;ver</a>"; } ?>
                                        <div class="uk-child-width-auto uk-text-center uk-margin-top" id="containersertrim-<?= $project['id'] ?>" uk-grid>
                                            <?php
                                            if (!empty($projectdata[$project['id']]['bast'])) {
                                                foreach ($projectdata[$project['id']]['bast'] as $bast) {
                                                    if (!empty($bast) && $bast['status'] === "0") { ?>
                                                        <div id="sertrim-file-<?= $bast['id']; ?>">
                                                            <div id="sertrim-card<?= $bast['id'] ?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                                <div class="uk-position-small uk-position-right"> <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?><a class="tm-img-remove2 uk-border-circle uk-icon" id="remove-sertrim-<?= $bast['id'] ?>" onclick="removeCardFile<?= $bast['id'] ?>()" uk-icon="close"></a><?php } ?></div>
                                                                <a href="img/sertrim/<?= $bast['file'] ?>" target="_blank"><span uk-icon="file-text"></span><?= $bast['file'] ?> </a>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function removeCardFile<?= $bast['id']; ?>() {
                                                                let text = "Hapus file Serah Terima ini?";
                                                                if (confirm(text) == true) {
                                                                    $.ajax({
                                                                        url: "project/removesertrim/<?= $bast['id'] ?>",
                                                                        method: "POST",
                                                                        data: {
                                                                            sertrim: <?= $bast['id'] ?>,
                                                                        },
                                                                        dataType: "json",
                                                                        error: function() {
                                                                            console.log('error', arguments);
                                                                        },
                                                                        success: function() {
                                                                            console.log('success', arguments);
                                                                            $("#sertrim-file-<?= $bast['id']; ?>").remove();
                                                                        },
                                                                    })
                                                                }
                                                            }
                                                        </script>
                                            <?php }
                                                }
                                            } ?>
                                        </div>
                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?>
                                            <div id="image-containersertrim-<?= $project['id'] ?>" class="uk-form-controls">
                                                <input id="photocreatesertrim<?= $project['id'] ?>" name="sertrim" hidden />
                                                <div id="js-upload-createsertrim-<?= $project['id'] ?>" class="js-upload-createsertrim-<?= $project['id'] ?> uk-placeholder uk-text-center uk-margin-remove-top">
                                                    <span uk-icon="icon: cloud-upload"></span>
                                                    <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                    <div uk-form-custom>
                                                        <input type="file">
                                                        <span class="uk-link uk-preserve-color">pilih satu</span>
                                                    </div>
                                                </div>
                                                <progress id="js-progressbar-createsertrim-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- End Of Serah Terima -->

                                    <!-- BAST -->
                                    <div class="uk-margin" id="image-container-createbast-<?= $project['id'] ?>" <?=$bastview?>>
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">BAST </label> <?php if ($projectdata[$project['id']]['verbast'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=6").">+".($projectdata[$project['id']]['verbast']-1)."&nbsp;ver</a>"; } ?>
                                        <div class="uk-form-horizontal">
                                            <div class="uk-margin-small">
                                                <label class="uk-form-label">Tanggal BAST</label>
                                                <div class="uk-form-controls">:
                                                    <div class="uk-inline">
                                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?>
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <input class="uk-input uk-form-width-medium" <?php if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                                                                                $tempo = date_create($projectdata[$project['id']]['bastfile']['tanggal_bast']);
                                                                                                                echo "value='" . date_format($tempo, 'm/d/Y') . "'";
                                                                                                            } ?> id="jatuhtempobast<?= $project['id'] ?>" name="jatuhtempobast<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                        <?php } else { ?>
                                                            <span class=""><?php if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                                                $tempo = date_create($projectdata[$project['id']]['bastfile']['tanggal_bast']);
                                                                                echo date_format($tempo, 'm/d/Y');
                                                                            } else {
                                                                                echo date('m/d/Y');
                                                                            } ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-child-width-auto uk-text-center uk-margin-top" id="containerbast-<?= $project['id'] ?>" uk-grid>
                                            <?php
                                            foreach ($projectdata[$project['id']]['bast'] as $bast) {
                                                $bastid = "";
                                                if (!empty($bast) && $bast['status'] === "1") {
                                                    $bastid = $bast['id']; ?>
                                                    <div id="bast-file-<?= $bast['id'] ?>">
                                                        <div id="bast-card<?= $bast['id'] ?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                            <div class="uk-position-small uk-position-right"><?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?><a class="tm-img-remove2 uk-border-circle uk-icon" id="removeCardFilebast<?= $bast['id']; ?>" onclick="removeCardFilebast<?= $bast['id'] ?>()" uk-icon="close"></a><?php } ?></div>
                                                            <a href="img/bast/<?= $bast['file'] ?>" target="_blank"><span uk-icon="file-text" ;></span><?= $bast['file'] ?> </a>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function removeCardFilebast<?= $bast['id']; ?>() {
                                                            let text = "Hapus file BAST ini?";
                                                            if (confirm(text) == true) {
                                                                $.ajax({
                                                                    url: "project/removesertrim/<?= $bast['id'] ?>",
                                                                    method: "POST",
                                                                    data: {
                                                                        bast: <?= $bast['id'] ?>,
                                                                    },
                                                                    dataType: "json",
                                                                    error: function() {
                                                                        console.log('error', arguments);
                                                                    },
                                                                    success: function() {
                                                                        console.log('success', arguments);
                                                                        alert('data berhasil di hapus');
                                                                        $("#bast-file-<?= $bast['id'] ?>").remove();
                                                                    },
                                                                })
                                                            }
                                                        }
                                                    </script>
                                            <?php }
                                            } ?>
                                        </div>
                                        <?php if ($authorize->hasPermission('ppic.project.edit', $uid) || $authorize->inGroup(['superuser', 'admin'], $uid)) { ?>
                                            <div id="image-containerbast-<?= $project['id'] ?>" class="uk-form-controls">
                                                <input id="photocreatebast<?= $project['id'] ?>" name="bast" hidden />
                                                <div id="js-upload-createbast-<?= $project['id'] ?>" class="js-upload-createbast-<?= $project['id'] ?> uk-placeholder uk-text-center uk-margin-remove-top">
                                                    <span uk-icon="icon: cloud-upload"></span>
                                                    <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                    <div uk-form-custom>
                                                        <input type="file">
                                                        <span class="uk-link uk-preserve-color">pilih satu</span>
                                                    </div>
                                                </div>
                                                <progress id="js-progressbar-createbast-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- End Of BAST -->

                                    <script type="text/javascript">
                                        // Serah terima
                                        UIkit.upload('#js-upload-createsertrim-<?= $project['id'] ?>', {
                                            url: 'upload/sertrim/<?= $project['id'] ?>',
                                            multiple: false,
                                            name: 'uploads',
                                            data: {
                                                'id': <?= $project['id'] ?>,
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
                                                var sertrimid = arguments[0].response.id;
                                                var filename = arguments[0].response.file;
                                                var proid = arguments[0].response.proid;

                                                var contsertrim = document.getElementById('containersertrim-<?= $project['id'] ?>');
                                                var container = document.createElement('div');
                                                container.setAttribute('id', 'sertrim-file-' + sertrimid);

                                                var cardsertrim = document.createElement('div');
                                                cardsertrim.setAttribute('class', 'uk-card uk-card-default uk-card-body uk-margin-bottom');

                                                var divclosed = document.createElement('div');
                                                divclosed.setAttribute('class', 'uk-position-small uk-position-right');

                                                var close = document.createElement('a');
                                                close.setAttribute('class', 'tm-img-remove2 uk-border-circle uk-icon');
                                                close.setAttribute('onClick', 'removeCardFile(' + sertrimid + ',' + proid + ')');
                                                close.setAttribute('uk-icon', 'close');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/sertrim/' + filename);
                                                link.setAttribute('target', '_blank');

                                                var file = document.createTextNode(filename);

                                                var icon = document.createElement('span');
                                                icon.setAttribute('uk-icon', 'file-text');

                                                contsertrim.appendChild(container);
                                                container.appendChild(cardsertrim);
                                                cardsertrim.appendChild(divclosed);
                                                divclosed.appendChild(close);
                                                cardsertrim.appendChild(link);
                                                link.appendChild(icon);
                                                link.appendChild(file);

                                                return sertrimid;
                                            },

                                            loadStart: function(e) {
                                                console.log('loadStart', arguments);

                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').removeAttribute('hidden');

                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').value = e.loaded;

                                            },

                                            progress: function(e) {
                                                console.log('progress', arguments);

                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            loadEnd: function(e) {
                                                console.log('loadEnd', arguments);

                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            completeAll: function() {
                                                console.log('completeAll', arguments);

                                                setTimeout(function() {
                                                    document.getElementById('js-progressbar-createsertrim-<?= $project['id'] ?>').setAttribute('hidden', 'hidden');
                                                    alert('<?= lang('Proses selesai, File Serah Terima berhasil di unggah.') ?>');
                                                }, 1000);
                                            }

                                        });

                                        function removeCardFile(id, proid) {
                                            let text = "Hapus file Serah Terima ini?";
                                            if (confirm(text) == true) {
                                                $.ajax({
                                                    url: "project/removesertrim/" + id,
                                                    method: "POST",
                                                    data: {
                                                        bast: id,
                                                    },
                                                    dataType: "json",
                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },
                                                    success: function() {
                                                        console.log('success', arguments);
                                                        $("#sertrim-file-" + id).remove();
                                                    },
                                                })
                                            }
                                        }
                                        //   End Of Serah Terima

                                        // Bast
                                        UIkit.upload('.js-upload-createbast-<?= $project['id'] ?>', {
                                            url: 'upload/bast/<?= $project['id'] ?>',
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

                                                var id = arguments[0].response.id;
                                                var filename = arguments[0].response.file;
                                                var proid = arguments[0].response.proid;

                                                console.log(id, filename, proid);

                                                if (document.getElementById('bast-file-' + id)) {
                                                    document.getElementById('bast-file-' + id).remove();
                                                };

                                                var contbast = document.getElementById('containerbast-<?= $project['id'] ?>');

                                                var container = document.createElement('div');
                                                container.setAttribute('id', 'bast-file-' + id);

                                                var cardbast = document.createElement('div');
                                                cardbast.setAttribute('class', 'uk-card uk-card-default uk-card-body uk-margin-bottom');

                                                var divclosed = document.createElement('div');
                                                divclosed.setAttribute('class', 'uk-position-small uk-position-right');

                                                var close = document.createElement('a');
                                                close.setAttribute('id', 'remove-bast-' + id);
                                                close.setAttribute('class', 'tm-img-remove2 uk-border-circle uk-icon');
                                                close.setAttribute('onClick', 'removeCardFilebast(' + id + ',' + proid + ')');
                                                close.setAttribute('uk-icon', 'close');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/bast/' + filename);
                                                link.setAttribute('target', '_blank');

                                                var file = document.createTextNode(filename);

                                                var icon = document.createElement('span');
                                                icon.setAttribute('uk-icon', 'file-text');

                                                contbast.appendChild(container);
                                                container.appendChild(cardbast);
                                                cardbast.appendChild(divclosed);
                                                divclosed.appendChild(close);
                                                cardbast.appendChild(link);
                                                link.appendChild(icon);
                                                link.appendChild(file);
                                            },

                                            loadStart: function(e) {
                                                console.log('loadStart', arguments);

                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').removeAttribute('hidden');

                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').value = e.loaded;

                                            },

                                            progress: function(e) {
                                                console.log('progress', arguments);

                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            loadEnd: function(e) {
                                                console.log('loadEnd', arguments);

                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').max = e.total;
                                                document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').value = e.loaded;
                                            },

                                            completeAll: function() {
                                                console.log('completeAll', arguments);

                                                setTimeout(function() {
                                                    document.getElementById('js-progressbar-createbast-<?= $project['id'] ?>').setAttribute('hidden', 'hidden');
                                                    alert('<?= lang('Proses selesai, File BAST berhasil di unggah.') ?>');
                                                }, 1000);
                                            }

                                        });

                                        function removeCardFilebast(id, proid) {
                                            let text = "Hapus BAST Terima ini?";
                                            if (confirm(text) == true) {
                                                $.ajax({
                                                    url: "project/removesertrim/" + id,
                                                    method: "POST",
                                                    data: {
                                                        bast: id,
                                                    },
                                                    dataType: "json",
                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },
                                                    success: function() {
                                                        console.log('success', arguments);
                                                        $("#bast-file-" + id).remove();
                                                    },
                                                })
                                            }
                                        }
                                    </script>
                                </div>
                                <script type="text/javascript">
                                    // Dropdown Production
                                    document.getElementById('toggleproduction<?= $project['id'] ?>').addEventListener('click', function() {
                                        if (document.getElementById('closeproduction<?= $project['id'] ?>').hasAttribute('hidden')) {
                                            document.getElementById('closeproduction<?= $project['id'] ?>').removeAttribute('hidden');
                                            document.getElementById('openproduction<?= $project['id'] ?>').setAttribute('hidden', '');
                                        } else {
                                            document.getElementById('openproduction<?= $project['id'] ?>').removeAttribute('hidden');
                                            document.getElementById('closeproduction<?= $project['id'] ?>').setAttribute('hidden', '');
                                        }
                                    });
                                </script>
                            <?php } ?>
                            <!-- Production Section End -->

                            <!-- Finance Section -->
                            <div class="uk-margin uk-child-width-1-2 uk-flex-middle" <?=$toglefinance?> uk-grid>
                                <div>
                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Keuangan</div>
                                </div>
                                <div class="uk-text-right">
                                    <a class="uk-link-reset uk-icon-button" id="togglefinance<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice<?= $project['id'] ?>"><span class="uk-light" id="closefinance<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openfinance<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                </div>
                            </div>

                            <div class="toggleinvoice<?= $project['id'] ?>" hidden>
                                <?php if (empty($projectdata[$project['id']]['finnance'])) { ?>
                                    <p>* Mohon tambahkan pegawai keuangan terlebih dahulu untuk dapat melakukan upload invoice</p>
                                <?php } ?>
                                <div class="uk-margin-small uk-child-width-1-2@m" uk-grid>
                                    <!-- Invoice I -->
                                    <div>
                                        <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="uk-h6 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-decoration: underline;">Invoice I</div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset" id="toggleinvoice1<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice1<?= $project['id'] ?>"><span id="closeinvoice1<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openinvoice1<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>

                                        <div class="toggleinvoice1<?= $project['id'] ?>" hidden>

                                            <div class="uk-form-horizontal">
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">No Invoice I</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input id="inputinv1<?=$project['id']?>" class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice I" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {echo "value='" . $projectdata[$project['id']]['invoice1']['no_inv'] . "'"; } ?> name="noinv1<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice I" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['no_inv'] . "'"; } ?> name="noinv1<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice1<?= $project['id'] ?>" name="dateinvoice1<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {$tempo = date_create($projectdata[$project['id']]['invoice1']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice1<?= $project['id'] ?>" name="dateinvoice1<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {$tempo = date_create($projectdata[$project['id']]['invoice1']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> placeholder="<?= date('m/d/Y') ?>" disabled />
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Referensi</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice1<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice1']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice1']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                } ?>
                                                                <?php
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice1<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice1']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice1']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                } ?>
                                                                <?php
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PPH 23</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice1']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice1<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice1']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice1<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice1']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice1<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice1'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice1']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice1<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PIC</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice1<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice1']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice1']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice1<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice1']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice1']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">File Invoice <?php if ($projectdata[$project['id']]['verinvoice1'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=7").">+".($projectdata[$project['id']]['verinvoice1']-1)."&nbsp;ver</a>"; } ?></label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice1'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice1']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice1']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice1']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice1']['id'] ?>" target="_blank" download><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice1']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                    <?php if (!empty($projectdata[$project['id']]['finnance'] && !empty($projectdata[$project['id']]['invoice1']))) { ?>
                                                        <div class="uk-margin-small">
                                                            <p class="uk-margin-left-remove" uk-margin>
                                                            <div class="js-upload-<?= $projectdata[$project['id']]['invoice1']['id'] ?>" uk-form-custom>
                                                                <input type="file" multiple>
                                                                <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                                <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice I</button>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    <?php } elseif(!empty($projectdata[$project['id']]['invoice1'])) { ?>
                                                        <div class="uk-margin-small">
                                                            <p class="uk-margin-left-remove" uk-margin>
                                                            <div class="js-upload-<?= $projectdata[$project['id']]['invoice1']['id'] ?>" uk-form-custom>
                                                                <button class="uk-button uk-button-default" type="button" tabindex="-1" disabled>Upload invoice I</button>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>

                                                <script>
                                                    <?php if(!empty( $projectdata[$project['id']]['invoice1'])){ ?>
                                                        var bar = document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>');
                                                        UIkit.upload('.js-upload-<?= $projectdata[$project['id']]['invoice1']['id'] ?>', {
                                                            url: 'upload/invoice/<?= $project['id'] ?>',
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

                                                                var id = arguments[0].response.id;
                                                                var filename = arguments[0].response.file;
                                                                var proid = arguments[0].response.proid;

                                                                console.log(id, filename, proid);

                                                                if (document.getElementById('inv' + id)) {
                                                                    document.getElementById('inv' + id).remove();
                                                                };

                                                                var setcontinv = document.getElementById('continv<?= $projectdata[$project['id']]['invoice1']['id'] ?>');

                                                                var container = document.createElement('a');
                                                                container.setAttribute('id', 'inv' + id);

                                                                var icon = document.createElement('span');
                                                                icon.setAttribute('uk-icon', 'file-text');

                                                                var file = document.createTextNode(filename);

                                                                setcontinv.appendChild(container);
                                                                container.appendChild(icon);
                                                                container.appendChild(file);
                                                            },

                                                            loadStart: function(e) {
                                                                console.log('loadStart', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').removeAttribute('hidden');

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').value = e.loaded;
                                                            },

                                                            progress: function(e) {
                                                                console.log('progress', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').value = e.loaded;
                                                            },

                                                            loadEnd: function(e) {
                                                                console.log('loadEnd', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').value = e.loaded;
                                                            },

                                                            completeAll: function() {
                                                                console.log('completeAll', arguments);

                                                                setTimeout(function() {
                                                                    document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>').setAttribute('hidden', 'hidden');
                                                                    alert('<?= lang('Proses selesai, File invoice berhasil di unggah.') ?>');
                                                                }, 1000);

                                                                alert('Upload Selesai');
                                                            }

                                                        });
                                                    <?php } ?>
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice I End -->

                                    <!-- Invoice II -->
                                    <div>
                                        <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="uk-h6 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-decoration: underline;">Invoice II</div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset" id="toggleinvoice2<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice2<?= $project['id'] ?>"><span id="closeinvoice2<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openinvoice2<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>

                                        <div class="toggleinvoice2<?= $project['id'] ?>" hidden>
                                            <div class="uk-form-horizontal">
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">No Invoice II</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice II" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {echo "value='" . $projectdata[$project['id']]['invoice2']['no_inv'] . "'";} ?> name="noinv2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice II" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {echo "value='" . $projectdata[$project['id']]['invoice2']['no_inv'] . "'";} ?> name="noinv2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice2<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {$tempo = date_create($projectdata[$project['id']]['invoice2']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'";} ?> name="dateinvoice2<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice2<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { $tempo = date_create($projectdata[$project['id']]['invoice2']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> name="dateinvoice2<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Referensi</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice2<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice2']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice2']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice2<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice2']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice2']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PPH 23</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {echo "value='" . $projectdata[$project['id']]['invoice2']['pph23'] . "'"; } ?> name="pphinvoice2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { echo "value='" . $projectdata[$project['id']]['invoice2']['pph23'] . "'"; } ?> name="pphinvoice2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { echo "value='" . $projectdata[$project['id']]['invoice2']['email'] . "'";} ?> name="emailinvoice2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { echo "value='" . $projectdata[$project['id']]['invoice2']['email'] . "'";} ?> name="emailinvoice2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PIC</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice2<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice2']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice2']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice2<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice2']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice2']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">File Invoice <?php if ($projectdata[$project['id']]['verinvoice2'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=8").">+".($projectdata[$project['id']]['verinvoice2']-1)."&nbsp;ver</a>"; } ?></label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice2'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice2']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice2']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice2']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice2']['id'] ?>" target="_blank" download><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice2']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <?php if (!empty($projectdata[$project['id']]['invoice2'])){?>
                                                    <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                        <?php if (!empty($projectdata[$project['id']]['finnance'])) { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice2']['id'] ?>" uk-form-custom>
                                                                    <input type="file" multiple>
                                                                    <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice II</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice2']['id'] ?>" uk-form-custom>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1" disabled>Upload invoice II</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <script>
                                                        var bar = document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>');
                                                        UIkit.upload('.js-upload-<?= $projectdata[$project['id']]['invoice2']['id'] ?>', {
                                                            url: 'upload/invoice2/<?= $project['id'] ?>',
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

                                                                var id = arguments[0].response.id;
                                                                var filename = arguments[0].response.file;
                                                                var proid = arguments[0].response.proid;

                                                                console.log(id, filename, proid);

                                                                if (document.getElementById('inv' + id)) {
                                                                    document.getElementById('inv' + id).remove();
                                                                };

                                                                var setcontinv = document.getElementById('continv<?= $projectdata[$project['id']]['invoice2']['id'] ?>');

                                                                var container = document.createElement('a');
                                                                container.setAttribute('id', 'inv' + id);

                                                                var icon = document.createElement('span');
                                                                icon.setAttribute('uk-icon', 'file-text');

                                                                var file = document.createTextNode(filename);

                                                                setcontinv.appendChild(container);
                                                                container.appendChild(icon);
                                                                container.appendChild(file);
                                                            },

                                                            loadStart: function(e) {
                                                                console.log('loadStart', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').removeAttribute('hidden');

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').value = e.loaded;
                                                            },

                                                            progress: function(e) {
                                                                console.log('progress', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').value = e.loaded;
                                                            },

                                                            loadEnd: function(e) {
                                                                console.log('loadEnd', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').value = e.loaded;
                                                            },

                                                            completeAll: function() {
                                                                console.log('completeAll', arguments);

                                                                setTimeout(function() {
                                                                    document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>').setAttribute('hidden', 'hidden');
                                                                    alert('<?= lang('Proses selesai, File invoice berhasil di unggah.') ?>');
                                                                }, 1000);

                                                                alert('Upload Selesai');
                                                            }

                                                        });
                                                    </script>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice II End -->

                                    <!-- Invoice III -->
                                    <div>
                                        <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="uk-h6 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-decoration: underline;">Invoice III</div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset" id="toggleinvoice3<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice3<?= $project['id'] ?>"><span id="closeinvoice3<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openinvoice3<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>

                                        <div class="toggleinvoice3<?= $project['id'] ?>" hidden>
                                            <div class="uk-form-horizontal">
                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">No Invoice III</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice III" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { echo "value='" . $projectdata[$project['id']]['invoice3']['no_inv'] . "'";} ?> name="noinv3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice III" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { echo "value='" . $projectdata[$project['id']]['invoice3']['no_inv'] . "'"; } ?> name="noinv3<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice3<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { $tempo = date_create($projectdata[$project['id']]['invoice3']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'";} ?> name="dateinvoice3<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice3<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { $tempo = date_create($projectdata[$project['id']]['invoice3']['jatuhtempo']);echo "value='" . date_format($tempo, 'm/d/Y') . "'";} ?> name="dateinvoice3<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Referensi</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice3<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice3']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice3']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice3<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice3']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice3']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PPH 23</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {echo "value='" . $projectdata[$project['id']]['invoice3']['pph23'] . "'";} ?> name="pphinvoice3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {    echo "value='" . $projectdata[$project['id']]['invoice3']['pph23'] . "'";} ?> name="pphinvoice3<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { echo "value='" . $projectdata[$project['id']]['invoice3']['email'] . "'"; } ?> name="emailinvoice3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice3'])) { echo "value='" . $projectdata[$project['id']]['invoice3']['email'] . "'"; } ?> name="emailinvoice3<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PIC</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice3<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice3']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice3<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice3']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">File Invoice <?php if ($projectdata[$project['id']]['verinvoice3'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=9").">+".($projectdata[$project['id']]['verinvoice3']-1)."&nbsp;ver</a>"; } ?></label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice3'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice3']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice3']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice3']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice3']['id'] ?>" target="_blank" download><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice3']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <?php if(!empty($projectdata[$project['id']]['invoice3'])) { ?>
                                                    <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                        <?php if (!empty($projectdata[$project['id']]['finnance'])) { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice3']['id'] ?>" uk-form-custom>
                                                                    <input type="file" multiple>
                                                                    <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice III</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice3']['id'] ?>" uk-form-custom>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1" disabled>Upload invoice III</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <script>
                                                        var bar = document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>');
                                                        UIkit.upload('.js-upload-<?= $projectdata[$project['id']]['invoice3']['id'] ?>', {
                                                            url: 'upload/invoice3/<?= $project['id'] ?>',
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

                                                                var id = arguments[0].response.id;
                                                                var filename = arguments[0].response.file;
                                                                var proid = arguments[0].response.proid;

                                                                console.log(id, filename, proid);

                                                                if (document.getElementById('inv' + id)) {
                                                                    document.getElementById('inv' + id).remove();
                                                                };

                                                                var setcontinv = document.getElementById('continv<?= $projectdata[$project['id']]['invoice3']['id'] ?>');

                                                                var container = document.createElement('a');
                                                                container.setAttribute('id', 'inv' + id);

                                                                var icon = document.createElement('span');
                                                                icon.setAttribute('uk-icon', 'file-text');

                                                                var file = document.createTextNode(filename);

                                                                setcontinv.appendChild(container);
                                                                container.appendChild(icon);
                                                                container.appendChild(file);
                                                            },

                                                            loadStart: function(e) {
                                                                console.log('loadStart', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').removeAttribute('hidden');

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').value = e.loaded;
                                                            },

                                                            progress: function(e) {
                                                                console.log('progress', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').value = e.loaded;
                                                            },

                                                            loadEnd: function(e) {
                                                                console.log('loadEnd', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').value = e.loaded;
                                                            },

                                                            completeAll: function() {
                                                                console.log('completeAll', arguments);

                                                                setTimeout(function() {
                                                                    document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>').setAttribute('hidden', 'hidden');
                                                                    alert('<?= lang('Proses selesai, File invoice berhasil di unggah.') ?>');
                                                                }, 1000);

                                                                alert('Upload Selesai');
                                                            }

                                                        });
                                                    </script>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice III End -->

                                    <!-- Invoice IV -->
                                    <div>
                                        <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                            <div>
                                                <div class="uk-h6 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-decoration: underline;">Invoice IV</div>
                                            </div>
                                            <div class="uk-text-right">
                                                <a class="uk-link-reset" id="toggleinvoice4<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice4<?= $project['id'] ?>"><span id="closeinvoice4<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openinvoice4<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                            </div>
                                        </div>

                                        <div class="toggleinvoice4<?= $project['id'] ?>" hidden>
                                            <div class="uk-form-horizontal">

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">No Invoice IV</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice IV" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {echo "value='" . $projectdata[$project['id']]['invoice4']['no_inv'] . "'"; } ?> name="noinv4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice IV" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { echo "value='" . $projectdata[$project['id']]['invoice4']['no_inv'] . "'"; } ?> name="noinv2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice4<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { $tempo = date_create($projectdata[$project['id']]['invoice4']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> name="dateinvoice4<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice4<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { $tempo = date_create($projectdata[$project['id']]['invoice4']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> name="dateinvoice4<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Referensi</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice4<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice4']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice4']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="referensiinvoice4<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice4']['referensi'])) {
                                                                    foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                        if ($projectdata[$project['id']]['invoice4']['referensi'] === $referensi['id']) {
                                                                            echo '<option value="' . $referensi['id'] . '" selected >' . $referensi['name'] . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih Referensi</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['referensi'] as $referensi) {
                                                                    if ($referensi['id'] === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $referensi['id'] . '" ' . $selected . '>' . $referensi['name'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PPH 23</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {echo "value='" . $projectdata[$project['id']]['invoice4']['pph23'] . "'";} ?> name="pphinvoice4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { echo "value='" . $projectdata[$project['id']]['invoice4']['pph23'] . "'";} ?> name="pphinvoice4<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { echo "value='" . $projectdata[$project['id']]['invoice4']['email'] . "'";} ?> name="emailinvoice4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice4'])) { echo "value='" . $projectdata[$project['id']]['invoice4']['email'] . "'";} ?> name="emailinvoice4<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">PIC</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice4<?= $project['id'] ?>">
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice4']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice4']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="uk-select uk-form-width-medium" name="picinvoice4<?= $project['id'] ?>" disabled>
                                                                <?php
                                                                if (!empty($projectdata[$project['id']]['invoice4']['pic'])) {
                                                                    foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                        if ($projectdata[$project['id']]['invoice4']['pic'] === $pic->id) {
                                                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo '<option value="" selected disabled>Pilih PIC</option>';
                                                                }
                                                                foreach ($projectdata[$project['id']]['pic'] as $pic) {
                                                                    if ($pic->id === "0") {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                                                } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">File Invoice <?php if ($projectdata[$project['id']]['verinvoice4'] > 1) { echo "&nbsp<a class='uk-margin-default-left' href=".base_url("version?project=".$project['id']."&type=10").">+".($projectdata[$project['id']]['verinvoice4']-1)."&nbsp;ver</a>"; } ?></label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice4'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice4']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice4']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice4']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice4']['id'] ?>" target="_blank" download><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice4']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <?php if(!empty($projectdata[$project['id']]['invoice4'])){ ?>
                                                    <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                        <?php if (!empty($projectdata[$project['id']]['finnance'])) { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice4']['id'] ?>" uk-form-custom>
                                                                    <input type="file" multiple>
                                                                    <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice IV</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="uk-margin-small">
                                                                <p class="uk-margin-left-remove" uk-margin>
                                                                <div class="js-upload-<?= $projectdata[$project['id']]['invoice4']['id'] ?>" uk-form-custom>
                                                                    <button class="uk-button uk-button-default" type="button" tabindex="-1" disabled>Upload invoice IV</button>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <script>
                                                        var bar = document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>');
                                                        UIkit.upload('.js-upload-<?= $projectdata[$project['id']]['invoice4']['id'] ?>', {
                                                            url: 'upload/invoice4/<?= $project['id'] ?>',
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

                                                                var id = arguments[0].response.id;
                                                                var filename = arguments[0].response.file;
                                                                var proid = arguments[0].response.proid;

                                                                console.log(id, filename, proid);

                                                                if (document.getElementById('inv' + id)) {
                                                                    document.getElementById('inv' + id).remove();
                                                                };

                                                                var setcontinv = document.getElementById('continv<?= $projectdata[$project['id']]['invoice4']['id'] ?>');

                                                                var container = document.createElement('a');
                                                                container.setAttribute('id', 'inv' + id);

                                                                var icon = document.createElement('span');
                                                                icon.setAttribute('uk-icon', 'file-text');

                                                                var file = document.createTextNode(filename);

                                                                setcontinv.appendChild(container);
                                                                container.appendChild(icon);
                                                                container.appendChild(file);
                                                            },

                                                            loadStart: function(e) {
                                                                console.log('loadStart', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').removeAttribute('hidden');

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').value = e.loaded;
                                                            },

                                                            progress: function(e) {
                                                                console.log('progress', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').value = e.loaded;
                                                            },

                                                            loadEnd: function(e) {
                                                                console.log('loadEnd', arguments);

                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').max = e.total;
                                                                document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').value = e.loaded;
                                                            },

                                                            completeAll: function() {
                                                                console.log('completeAll', arguments);

                                                                setTimeout(function() {
                                                                    document.getElementById('js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>').setAttribute('hidden', 'hidden');
                                                                    alert('<?= lang('Proses selesai, File invoice berhasil di unggah.') ?>');
                                                                }, 1000);

                                                                alert('Upload Selesai');
                                                            }

                                                        });
                                                    </script>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice IV End -->

                                    <!-- Invoice Generate Button -->
                                    <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                        <div class="uk-width-1-1">
                                            <label class="uk-h5 uk-margin uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">CETAK INVOICE</label>
                                            <p class="uk-margin" uk-margin>
                                                <?php
                                                // Invoice I
                                                if (!empty($project)) {
                                                    if ($project['status_spk'] === "1") {
                                                        echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel1/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice I</a>";
                                                    }
                                                }

                                                // Invoice II
                                                if (!empty($project)) {
                                                    if (!empty($projectdata[$project['id']]['sertrim'])) {
                                                        if (isset($projectdata[$project['id']]['sertrim']['status']) && ($progress >= "60" || $progress >= 60) && $projectdata[$project['id']]['sertrim']['status'] === "0") {
                                                            echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel2/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice II</a>";
                                                        }
                                                    }
                                                }

                                                // Invoice III
                                                if (!empty($projectdata[$project['id']]['bastfile']) && !empty($projectdata[$project['id']]['sertrim'])) {
                                                    if (!empty($projectdata[$project['id']]['bastfile']['status']) && ($progress >= "95" || $progress >= 95)  && $projectdata[$project['id']]['bastfile']['status'] === "1" && !empty($projectdata[$project['id']]['bastfile']['file']) && !empty($projectdata[$project['id']]['sertrim']['status'] === "0")) {
                                                        echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel3/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice III</a>";
                                                        $status = "Retensi";
                                                    }
                                                }

                                                // Invoice IV
                                                if (!empty($projectdata[$project['id']]['bastfile']) && !empty($projectdata[$project['id']]['sertrim']) && ($progress >= "95" || $progress >= 95)) {
                                                    if (!empty($projectdata[$project['id']]['bastfile']['tanggal_bast'])) {
                                                        if ($projectdata[$project['id']]['bastfile']['status'] === "1" && $projectdata[$project['id']]['now'] >=  $projectdata[$project['id']]['dateline']) {
                                                            echo "<a id='btninv" . $project['id'] . "' class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel4/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice IV</a>";
                                                            $progress   = "100";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <!-- End Of Invoice Generate Button -->
                                </div>

                                <!-- Payment Record Section -->
                                <div class="uk-margin">
                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Catatan Pembayaran</div>

                                    <div class="uk-child-1-2 uk-margin uk-margin-left" uk-grid>
                                        <div>
                                            <label class="uk-form-label" for="tanggal">Tanggal Pembayaran</label>
                                            <div class="uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                    <input class="uk-input uk-form-width-medium" id="datepayment<?= $project['id'] ?>" name="datepayment<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                <?php }else{ ?> 
                                                    <input class="uk-input uk-form-width-medium" id="datepayment<?= $project['id'] ?>" name="datepayment<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
                                                <?php } ?> 
                                            </div>
                                        </div>

                                        <div>
                                            <label class="uk-form-label" for="nominal">Nominal Pembayaran</label>
                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                <input class="uk-input uk-form-width-medium" type="text" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" id="qtypayment<?= $project['id'] ?>" data-type="qtypayment<?= $project['id'] ?>" name="qtypayment<?= $project['id'] ?>" placeholder="Rp0,-" />
                                            <?php }else{ ?>
                                                <input class="uk-input uk-form-width-medium" type="text" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" id="qtypayment<?= $project['id'] ?>" data-type="qtypayment<?= $project['id'] ?>" name="qtypayment<?= $project['id'] ?>" placeholder="Rp0,-" / disabled>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <script>
                                        $("input[data-type='qtypayment<?= $project['id'] ?>']").on({
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
                                                    right_side += "";
                                                }

                                                right_side = right_side.substring(0, 0);

                                                input_val = "Rp" + left_side + "." + right_side;

                                            } else {

                                                input_val = formatNumber(input_val);
                                                input_val = "Rp" + input_val;

                                                if (blur === "blur") {
                                                    input_val += "";
                                                }
                                            }

                                            input.val(input_val);

                                            var updated_len = input_val.length;
                                            caret_pos = updated_len - original_len + caret_pos;
                                            input[0].setSelectionRange(caret_pos, caret_pos);
                                        }
                                    </script>


                                    <?php if ($projectdata[$project['id']]['pembayaran']) { ?>
                                        <div class="uk-overflow-auto uk-margin-left">
                                            <table class="uk-table uk-table-middle uk-table-hover uk-table-divider">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($projectdata[$project['id']]['pembayaran'] as $payment) { ?>
                                                        <?php
                                                        $dateTimeObj = new DateTime($payment['date'], new DateTimeZone('Asia/Jakarta'));
                                                        $dateFormatted =
                                                            IntlDateFormatter::formatObject(
                                                                $dateTimeObj,
                                                                'eeee, d MMMM y',
                                                                'id'
                                                            );
                                                        $dateact = ucwords($dateFormatted);
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <div class="uk-inline">
                                                                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                                    <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                        <input class="uk-input uk-form-width-large" id="updatepayment<?= $project['id'] ?><?= $payment['id'] ?>" name="updatepayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" value="<?= $payment['date'] ?>" />
                                                                    <?php }else{ ?>
                                                                        <input class="uk-input uk-form-width-large" id="updatepayment<?= $project['id'] ?><?= $payment['id'] ?>" name="updatepayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" value="<?= $payment['date'] ?>" disabled/>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                    <input class="uk-input uk-form-width-large" type="text" data-type="upqtypayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" id="upqtypayment<?= $project['id'] ?><?= $payment['id'] ?>" name="upqtypayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" value="<?= 'Rp' . number_format((int)$payment['qty'], 0, ',', ','); ' '; ?>" />
                                                                <?php }else{ ?>
                                                                    <input class="uk-input uk-form-width-large" type="text" data-type="upqtypayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" id="upqtypayment<?= $project['id'] ?><?= $payment['id'] ?>" name="upqtypayment<?= $project['id'] ?>[<?= $payment['id'] ?>]" value="<?= 'Rp' . number_format((int)$payment['qty'], 0, ',', ','); ' '; ?>" disabled/>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <script type="text/javascript">
                                                            var rupiah<?= $project['id'] ?><?= $payment['id'] ?> = document.getElementById('upqtypayment<?= $project['id'] ?><?= $payment['id'] ?>');
                                                            rupiah<?= $project['id'] ?><?= $payment['id'] ?>.addEventListener('keyup', function(e){
                                                                rupiah<?= $project['id'] ?><?= $payment['id'] ?>.value = formatRupiah(this.value, 'Rp. ');
                                                            });
                                                    
                                                            function formatRupiah(angka, prefix){
                                                                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                                                                split   		= number_string.split(','),
                                                                sisa     		= split[0].length % 3,
                                                                rupiah     		= split[0].substr(0, sisa),
                                                                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
                                                    
                                                                if(ribuan){
                                                                    separator = sisa ? '.' : '';
                                                                    rupiah += separator + ribuan.join('.');
                                                                }
                                                    
                                                                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                                                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
                                                            }
                                                        </script>
                                                        <script>
                                                            $(function() {
                                                                $("#updatepayment<?= $project['id'] ?><?= $payment['id'] ?>").datepicker({
                                                                    dateFormat: "yy-mm-dd",
                                                                });
                                                            });
                                                        </script>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                                <script>
                                    $(function() {
                                        $("#datepayment<?= $project['id'] ?>").datepicker({
                                            "setDate": new Date(),
                                            dateFormat: "yy-mm-dd",
                                        });
                                    });
                                </script>
                                <!-- Payment Record Section End -->
                            </div>
                            <script type="text/javascript">
                                // Dropdown Finance
                                document.getElementById('togglefinance<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closefinance<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closefinance<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openfinance<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openfinance<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closefinance<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });

                                // Invoice 1
                                // Dropdown
                                document.getElementById('toggleinvoice1<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closeinvoice1<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closeinvoice1<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openinvoice1<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openinvoice1<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closeinvoice1<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                                // Date Picker Invoice
                                $(function() {
                                    $("#dateinvoice1<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Invoice 2
                                // Dropdown
                                document.getElementById('toggleinvoice2<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closeinvoice2<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closeinvoice2<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openinvoice2<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openinvoice2<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closeinvoice2<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                                // Date Picker Invoice
                                $(function() {
                                    $("#dateinvoice2<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Invoice 3
                                // Dropdown
                                document.getElementById('toggleinvoice3<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closeinvoice3<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closeinvoice3<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openinvoice3<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openinvoice3<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closeinvoice3<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                                // Date Picker Invoice
                                $(function() {
                                    $("#dateinvoice3<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Invoice 4
                                // Dropdown
                                document.getElementById('toggleinvoice4<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closeinvoice4<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closeinvoice4<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openinvoice4<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openinvoice4<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closeinvoice4<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                                // Date Picker Invoice
                                $(function() {
                                    $("#dateinvoice4<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Date Picker Jatuh Tempo Bast
                                $(function() {
                                    $("#jatuhtempobast<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Date Picker Tanggal SPK
                                $(function() {
                                    $("#tanggalspk<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });

                                // Date Picker Batas Produksi
                                $(function() {
                                    $("#batasproduksi<?= $project['id'] ?>").datepicker({
                                        dateFormat: "yy-mm-dd",
                                    });
                                });
                            </script>
                            <!-- Finance Section End -->

                            <!-- Bukti Pembayarn Section -->
                            <div class="uk-margin uk-child-width-1-2 uk-flex-middle" <?=$toglebukti?> uk-grid>
                                <div>
                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Bukti Pembayaran</div>
                                </div>
                                <div class="uk-text-right">
                                    <a class="uk-link-reset uk-icon-button" id="togglebuktipembayaran<?= $project['id'] ?>" uk-toggle="target: .togglebuktipembayaran<?= $project['id'] ?>"><span class="uk-light" id="closebuktipembayaran<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openbuktipembayaran<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                </div>
                            </div>

                            <div class="togglebuktipembayaran<?= $project['id'] ?>" hidden>
                                <div class="uk-child-width-auto uk-grid-match uk-flex-middle" uk-grid uk-lightbox="animation: slide">
                                    <?php foreach ($projectdata[$project['id']]['buktipembayaran'] as $payproof) { ?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-body"><a href="/img/bukti/pembayaran/<?= $payproof['file'] ?>" data-caption="<?= $payproof['file'] ?>"><span uk-icon="file-pdf"></span><?= $payproof['file'] ?></a></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <script>
                                // Dropdown
                                document.getElementById('togglebuktipembayaran<?= $project['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('closebuktipembayaran<?= $project['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('closebuktipembayaran<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('openbuktipembayaran<?= $project['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('openbuktipembayaran<?= $project['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('closebuktipembayaran<?= $project['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                            </script>
                            <!-- Bukti Pembayarn Section End -->

                            <div class="uk-modal-footer uk-text-right">
                                <?php if ($authorize->hasPermission('admin.project.delete', $uid)) { ?>
                                    <a class="uk-button uk-button-danger" href="project/delete/<?= $project['id'] ?>" onclick="return confirm('<?= 'Anda yakin ingin menghapus data ' . $project['name'] . '?' ?>')" type="button">Hapus</a>
                                <?php } ?>
                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid)|| $authorize->hasPermission('finance.project.edit', $uid) || $authorize->hasPermission('ppic.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                    <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- Modal Update Proyek End -->
<?php } ?>
<?php if (!empty($input)) { ?>
    <script>
        $(document).ready(function() {
            var editmodal = document.getElementById('modalupdatepro<?= $input ?>');
            UIkit.modal(editmodal).show();
        });
    </script>
<?php } ?>

<?= $this->endSection() ?>