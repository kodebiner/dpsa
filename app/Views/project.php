<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>

<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
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
                $progress = round($projectdata[$project['id']]['progress'] + $progress);
                $status   = "Retensi";
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
                                    <h4 class="uk-text-center match-height">Status</h3>
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
                            <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
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
                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('admin.project.create', $uid) || $authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
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
        <?= $pager->links('projects', 'uikit_full') ?>
    </div>

    <!-- Modal Add Proyek -->
    <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
        <div class="uk-modal-container" id="modaladd" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <button class="uk-modal-close-default uk-icon-button-delete" type="button" uk-close></button>
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

                            <!-- Add Client Auto Complete -->
                            <div class="uk-margin" id="pusat">
                                <label class="uk-form-label" for="company">Perusahaan</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="company" name="company" placeholder="Masukkan nama perusahaan yang terdaftar sebagai klien..." required>
                                    <input id="compid" name="company" value="" hidden>
                                </div>
                            </div>
                            <!-- End Of Add Client -->

                            <!-- Design Section -->
                            <!-- Select With Design Or Without Design Section -->
                            <label class="uk-form-label" for="designtype">Dengan Desain atau Tanpa Desain</label>
                            <label class="switch  uk-margin-bottom">
                                <input id="designtype" name="designtype" type="checkbox" value="0">
                                <span class="slider round"></span>
                            </label>
                            <script>
                                $(document).ready(function() {
                                    $("input[id='designtype']").val(0);
                                    $("input[id='designtype']").change(function() {
                                        if ($(this).is(':checked')) {
                                            $("input[id='designtype']").val(1);
                                            $("div[id='imgdesigncreate']").attr("hidden", false);
                                            $("div[id='imgdesigncreate']").attr("required", false);
                                        } else {
                                            $(this).val();
                                            $("div[id='imgdesigncreate']").attr("hidden", true);
                                            $("div[id='imgdesigncreate']").attr("required", true);
                                        }
                                    });
                                });
                            </script>
                            <!-- Select With Design Or Without Design Section End -->

                            <!-- Upload Design Section -->
                            <div class="uk-margin" id="imgdesigncreate" hidden>
                                <label class="uk-form-label" for="photocreate">Unggah file DED / Layout</label>
                                <div class="uk-placeholder" id="placedesign" hidden>
                                    <div uk-grid>
                                        <div class="uk-text-left uk-width-3-4">
                                            <div id="updesign">
                                            </div>
                                        </div>
                                        <div class="uk-text-right uk-width-1-4">
                                            <div id="closeddesign">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="image-container" class="uk-form-controls">
                                    <input id="designcreated" name="design" hidden />
                                    <div id="js-upload-createdesign" class="js-upload-createdesign uk-placeholder uk-text-center">
                                        <span uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                        <div uk-form-custom>
                                            <input type="file">
                                            <span class="uk-link uk-preserve-color">pilih satu</span>
                                        </div>
                                    </div>
                                    <progress id="js-progressbar-createdesign" class="uk-progress" value="0" max="100" hidden></progress>
                                </div>
                            </div>

                            <script type="text/javascript">
                                var bar = document.getElementById('js-progressbar-createdesign');

                                UIkit.upload('.js-upload-createdesign', {
                                    url: 'upload/layout',
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

                                        if (document.getElementById('display-container-create')) {
                                            document.getElementById('display-container-create').remove();
                                        };

                                        document.getElementById('designcreated').value = filename;

                                        document.getElementById('placedesign').removeAttribute('hidden');

                                        var uprev = document.getElementById('updesign');
                                        var closed = document.getElementById('closeddesign');

                                        var divuprev = document.createElement('h6');
                                        divuprev.setAttribute('class', 'uk-margin-remove');
                                        divuprev.setAttribute('id', 'design');
                                        divuprev.setAttribute('value', filename);


                                        var linkrev = document.createElement('a');
                                        linkrev.setAttribute('href', 'img/design/' + filename);
                                        linkrev.setAttribute('uk-icon', 'file-text');

                                        var link = document.createElement('a');
                                        link.setAttribute('href', 'img/design/' + filename);
                                        link.setAttribute('target', '_blank');

                                        var linktext = document.createTextNode(filename);

                                        var divclosed = document.createElement('a');
                                        divclosed.setAttribute('uk-icon', 'icon: close');
                                        divclosed.setAttribute('onClick', 'removedesign()');
                                        divclosed.setAttribute('id', 'closedes');

                                        uprev.appendChild(divuprev);
                                        divuprev.appendChild(linkrev);
                                        divuprev.appendChild(link);
                                        link.appendChild(linktext);
                                        closed.appendChild(divclosed);

                                        document.getElementById('js-upload-createdesign').setAttribute('hidden', '');
                                    },

                                    loadStart: function(e) {
                                        console.log('loadStart', arguments);

                                        document.getElementById('js-progressbar-createdesign').removeAttribute('hidden');

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;

                                    },

                                    progress: function(e) {
                                        console.log('progress', arguments);

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;
                                    },

                                    loadEnd: function(e) {
                                        console.log('loadEnd', arguments);

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;
                                    },

                                    completeAll: function() {
                                        console.log('completeAll', arguments);

                                        setTimeout(function() {
                                            document.getElementById('js-progressbar-createdesign').setAttribute('hidden', 'hidden');
                                            alert('Proses unggah data desain selesai');
                                        }, 1000);
                                    }

                                });

                                function removedesign() {
                                    $.ajax({
                                        type: 'post',
                                        url: 'upload/removelayout',
                                        data: {
                                            'design': document.getElementById('designcreated').value,
                                        },
                                        dataType: 'json',

                                        error: function() {
                                            console.log(document.getElementById('design').value);
                                            console.log('error', arguments);
                                        },

                                        success: function() {
                                            console.log('success', arguments);

                                            var pesan = arguments[0][1];

                                            document.getElementById('design').remove();
                                            document.getElementById('closedes').remove();
                                            document.getElementById('placedesign').setAttribute('hidden', '');
                                            document.getElementById('designcreated').value = '';

                                            document.getElementById('js-upload-createdesign').removeAttribute('hidden', '');
                                            alert(pesan);
                                        }
                                    });
                                };
                            </script>
                            <!-- Upload Design End -->
                            <!-- Design Section End -->

                            <!-- Marketing (PIC) -->
                            <div class="uk-margin-small">
                                <label class="uk-form-label">PIC Marketing</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select uk-form-width-medium" name="marketing" required>
                                        <option value="" selected disabled>Pilih Marketing</option>
                                        <?php
                                        foreach ($marketings as $pic) {
                                            if ($pic->id === "0") {
                                                $selected = 'selected';
                                            } else {
                                                $selected = "";
                                            }
                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Markerting (PIC) -->

                            <div class="uk-modal-footer uk-text-right">
                                <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
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
    <?php } ?>
    <!-- Modal Add Proyek End -->

    <!-- Modal Update Proyek -->
    <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('admin.project.create', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid)  || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
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
                $status   = "Retensi";
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
                                            <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
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
                                                    <div class="uk-form-controls">: <a href="/img/design/<?= $projectdata[$project['id']]['design']['submitted'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['submitted'] ?></a></div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label uk-margin-remove-top">File Revisi</label>
                                                    <div class="uk-form-controls">: <a href="/img/revisi/<?= $projectdata[$project['id']]['design']['revision'] ?>"><span uk-icon="file-pdf"></span><?= $projectdata[$project['id']]['design']['revision'] ?></a></div>
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
                            <!-- Desain Section End -->

                            <!-- Detail Pemesanan Section -->
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

                                    <!-- </?php if ($project['status_spk'] != 1) { ?> -->
                                    <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                        <div class="uk-padding uk-padding-remove-vertical togglesph<?= $project['id'] ?>" hidden>

                                            <div class="uk-margin-bottom">
                                                <label class="uk-form-label" for="paket">Nomor SPH</label>
                                                <div class="uk-form-controls">
                                                    <input type="text" class="uk-input" id="nosph<?= $project['id'] ?>" name="nosph<?= $project['id'] ?>" <?php if (!empty($project['no_sph'])) { $nosph = $project['no_sph']; echo "value='$nosph'"; } ?> placeholder="Nomor SPH">
                                                </div>
                                            </div>

                                            <!-- SPH -->
                                            <div class="uk-margin" id="image-container-createsph-<?= $project['id'] ?>">
                                                <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">UPLOAD SPH</label>
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
                                                                    },
                                                                })
                                                            }
                                                        }
                                                    </script>
                                                </div>
                                                <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?>
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
                                            <!-- end SPH -->

                                            <?php if (!empty($projectdata[$project['id']]['paket'])) { ?>
                                                <!-- <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/</?= $project['id'] ?>" target="_blank">Download SPH</a> -->
                                                <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphview/<?= $project['id'] ?>" target="_blank">Download SPH</a>
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
                                                                <th>Keterangan</th>
                                                                <th>Jumlah Pesanan</th>
                                                                <th>Harga</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($projectdata[$project['id']]['paket'] as $paket) { ?>
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
                                                                        <td class="uk-form-controls">
                                                                            <input type="number" id="eqty[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]" name="eqty<?= $project['id'] ?>[<?= $paket['id'] ?>][<?= $mdl['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $mdl['qty'] ?>" onchange="eprice(<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>)" />
                                                                        </td>
                                                                        <div id="eprice[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]" hidden><?= $mdl['price'] ?></div>
                                                                        <td id="eshowprice[<?= $project['id'] ?><?= $paket['id'] ?><?= $mdl['id'] ?>]"><?= "Rp. " . number_format((int)$mdl['qty'] * (int)$mdl['price'], 0, ',', '.');
                                                                                                                                                        " "; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
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

                                                            <tr>
                                                                <td colspan="9" class="tm-h3" style="text-transform: uppercase;">Biaya Pengiriman</td>
                                                                <td>
                                                                    <input type="text" class="uk-input uk-form-width-small" id="shippingcost" name="shippingcost" value="<?php if (!empty($projectdata[$project['id']]['shippingcost'])) { echo $projectdata[$project['id']]['shippingcost']['price']; } ?>" />
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
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>
                                                                            <input type="number" id="pricecustrab[<?= $project['id'] ?><?= $customrab['id'] ?>]" name="pricecustrab<?= $project['id'] ?>[<?= $customrab['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $customrab['price'] ?>" />
                                                                        </td>
                                                                    </tr>
                                                            <?php }
                                                            } ?>
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
                                                        <input type="text" class="uk-input" id="customname<?= $project['id'] ?>[0]" name="customname<?= $project['id'] ?>[0]" placeholder="Nama" />
                                                    </div>
                                                    <div id="createPrice<?= $project['id'] ?>0">
                                                        <input type="number" class="uk-input" id="customprice<?= $project['id'] ?>[0]" name="customprice<?= $project['id'] ?>[0]" placeholder="Harga" />
                                                    </div>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                var createCount = 0;

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
                                                    createNameInput.setAttribute('class', 'uk-input');
                                                    createNameInput.setAttribute('placeholder', 'Nama');
                                                    createNameInput.setAttribute('id', 'customname' + x + '[' + createCount + ']');
                                                    createNameInput.setAttribute('name', 'customname' + x + '[' + createCount + ']');

                                                    const createPrice = document.createElement('div');
                                                    createPrice.setAttribute('id', 'createPrice' + x + '' + createCount);

                                                    const createPriceInput = document.createElement('input');
                                                    createPriceInput.setAttribute('type', 'number');
                                                    createPriceInput.setAttribute('class', 'uk-input');
                                                    createPriceInput.setAttribute('placeholder', 'Harga');
                                                    createPriceInput.setAttribute('id', 'customprice' + x + '[' + createCount + ']');
                                                    createPriceInput.setAttribute('name', 'customprice' + x + '[' + createCount + ']');

                                                    const createRemove = document.createElement('div');
                                                    createRemove.setAttribute('id', 'remove' + x + '' + createCount);
                                                    createRemove.setAttribute('class', 'uk-text-center uk-text-bold uk-text-danger uk-flex uk-flex-middle');

                                                    const createRemoveButton = document.createElement('a');
                                                    createRemoveButton.setAttribute('onclick', 'createRemove' + x + '(' + createCount + ')');
                                                    createRemoveButton.setAttribute('class', 'uk-link-reset');
                                                    createRemoveButton.innerHTML = 'X';

                                                    createName.appendChild(createNameInput);
                                                    newCreateCustomRab.appendChild(createName);
                                                    createPrice.appendChild(createPriceInput);
                                                    newCreateCustomRab.appendChild(createPrice);
                                                    createRemove.appendChild(createRemoveButton);
                                                    newCreateCustomRab.appendChild(createRemove);
                                                    createCustomRab.appendChild(newCreateCustomRab);
                                                };

                                                function createRemove<?= $project['id'] ?>(i) {
                                                    const createRemoveElement = document.getElementById('create<?= $project['id'] ?>' + i);
                                                    createRemoveElement.remove();
                                                };
                                            </script>
                                        </div>
                                    <?php } ?>
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
                            <!-- Detail Pemesanan Section End -->

                            <!-- SPK Section -->
                            <!-- </?php if ($project['spk'] != null) {
                                if ($project['status_spk'] === '0') { ?> -->
                            <div class="uk-margin uk-child-width-1-2" uk-grid>
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
                                                    <input class="uk-input uk-form-width-medium" <?php if (!empty($project['tanggal_spk'])) { $tglspk = date_create($project['tanggal_spk']); echo "value='" . date_format($tglspk, 'm/d/Y') . "'"; } ?> id="tanggalspk<?= $project['id'] ?>" name="tanggalspk<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                <?php } else { ?>
                                                    <span class=""><?php if (!empty($project['tanggal_spk'])) { $tglspk = date_create($project['tanggal_spk']); echo date_format($tglspk, 'm/d/Y'); } else { echo date('m/d/Y'); } ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label">NO SPK</label>
                                            <div class="uk-form-controls">:
                                                <input type="text" class="uk-input uk-width-1-3" id="nospk" name="nospk" value="<?php if (!empty($project['no_spk'])) { echo $project['no_spk']; } ?>" placeholder="NO SPK" />
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label uk-margin-remove-top">NO. SPK</label>
                                            <div class="uk-form-controls">: <?php if (!empty($project['no_spk'])) { echo $project['no_spk']; } ?> </a></div>
                                            <input type="hidden" class="uk-input uk-width-1-3" id="nospk" name="nospk" value="<?php if (!empty($project['no_spk'])) { $project['no_spk']; } ?>" />
                                        </div>
                                    <?php } ?>

                                    <div class="uk-margin-small">
                                        <label class="uk-form-label uk-margin-remove-top">File SPK</label>
                                        <div class="uk-form-controls">: <?php if (!empty($project['spk'])) { ?><a href="/img/spk/<?= $project['spk'] ?>"><span uk-icon="file-pdf"></span><?= $project['spk'] ?></a> <?php } ?></div>
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
                            <!-- </?php } ?> -->
                            <!-- SPK Section End -->

                            <!-- Production Section -->
                            <?php if ($project['status_spk'] == 1) { ?>
                                <div class="uk-margin uk-child-width-1-2 uk-flex-middle" uk-grid>
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
                                                    <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?>
                                                        <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                        <input class="uk-input uk-form-width-medium" <?php if (!empty($project['batas_produksi'])) { $batasproduksi = date_create($project['batas_produksi']); echo "value='" . date_format($batasproduksi, 'm/d/Y') . "'"; } ?> id="batasproduksi<?= $project['id'] ?>" name="batasproduksi<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                    <?php } else { ?>
                                                        <span class=""><?php if (!empty($project['batas_produksi'])) { $batasproduksi = date_create($project['batas_produksi']); echo date_format($batasproduksi, 'm/d/Y'); } else { echo date('m/d/Y'); } ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    <?php if (($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                        <th class="uk-text-center">PIC Produksi</th>
                                                    <?php } ?>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($projectdata[$project['id']]['production'] as $production) { ?>
                                                    <?php if ($authorize->hasPermission('production.project.edit', $uid) && ($uid === $production['userid']) || ($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                        <tr>
                                                            <td><?= $production['name'] ?></td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['gambar_kerja']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="gambarkerja<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['mesin_awal']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="mesinawal<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['tukang']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="tukang<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['mesin_lanjutan']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="mesinlanjutan<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['finishing']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="finishing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['packing']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="packing<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['pengiriman']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="pengiriman<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <td class="uk-text-center">
                                                                <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="setting<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                            <?php if (($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="picpro[<?= $production['id'] ?>]">
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
                                                            <?php } ?>
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
                                                            <?php if (($authorize->inGroup('admin', $uid)) || ($authorize->inGroup('owner', $uid)) || ($authorize->inGroup('superuser', $uid))) { ?>
                                                                <td class="uk-text-center">
                                                                    <div class="uk-margin">
                                                                        <select class="uk-select" name="picpro[<?= $production['id'] ?>]">
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
                                                            <?php } ?>
                                                            <td class="uk-text-center">
                                                                <div><?= $production['percentages'] ?> %</div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Bukti Pengiriman -->
                                    <div class="uk-margin" id="image-container-createbuktipengiriman-<?= $project['id'] ?>">
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate" style="text-transform: uppercase;">Bukti Pengiriman</label>
                                        <div class="uk-margin uk-child-width-1-3 uk-child-width-1-6@m uk-grid-match uk-flex-middle uk-grid-divider" uk-grid uk-lightbox="animation: slide">
                                            <?php foreach ($projectdata[$project['id']]['buktipengiriman'] as $sendproof) { ?>
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

                                        <div id="image-containerbuktipengiriman-<?= $project['id'] ?>" class="uk-form-controls">
                                            <input id="photocreatebuktipengiriman<?= $project['id'] ?>" name="buktipengiriman" hidden />
                                            <div id="js-upload-createbuktipengiriman-<?= $project['id'] ?>" class="js-upload-createbuktipengiriman-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                <span uk-icon="icon: cloud-upload"></span>
                                                <span class="uk-text-middle">Tarik dan lepas bukti pengiriman disini atau</span>
                                                <div uk-form-custom>
                                                    <input type="file">
                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                </div>
                                            </div>
                                            <progress id="js-progressbar-createbuktipengiriman-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        // Upload Bukti Pembayaran
                                        var bar = document.getElementById('js-progressbar-createbuktipengiriman-<?= $project['id'] ?>');

                                        UIkit.upload('.js-upload-createbuktipengiriman-<?= $project['id'] ?>', {
                                            url: 'upload/buktipengiriman',
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

                                                if (document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>')) {
                                                    document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>').remove();
                                                };

                                                document.getElementById('photocreatebuktipengiriman<?= $project['id'] ?>').value = filename;

                                                var imgContainer = document.getElementById('image-container-createbuktipengiriman-<?= $project['id'] ?>');

                                                var displayContainer = document.createElement('div');
                                                displayContainer.setAttribute('id', 'display-container-createbuktipengiriman-<?= $project['id'] ?>');
                                                displayContainer.setAttribute('class', 'uk-inline uk-width-1-2 uk-width-1-5@m uk-margin');

                                                var displayImg = document.createElement('div');
                                                displayImg.setAttribute('uk-lightbox', 'animation: fade');
                                                displayImg.setAttribute('class', 'uk-inline');

                                                var link = document.createElement('a');
                                                link.setAttribute('href', 'img/bukti/pengiriman/' + filename);

                                                var image = document.createElement('img');
                                                image.setAttribute('src', 'img/bukti/pengiriman/' + filename);

                                                var createNote = document.createElement('div');
                                                createNote.setAttribute('class', 'uk-margin');

                                                var createNoteInput = document.createElement('input');
                                                createNoteInput.setAttribute('type', 'text');
                                                createNoteInput.setAttribute('class', 'uk-input uk-form-width-large');
                                                createNoteInput.setAttribute('placeholder', 'Keterangan (optional)');
                                                createNoteInput.setAttribute('id', 'note');
                                                createNoteInput.setAttribute('name', 'note');

                                                var closeContainer = document.createElement('div');
                                                closeContainer.setAttribute('class', 'uk-position-small uk-position-right');

                                                var closeButton = document.createElement('a');
                                                closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                                closeButton.setAttribute('onClick', 'removeImgCreatebuktipengiriman<?= $project['id'] ?>()');
                                                closeButton.setAttribute('uk-icon', 'close');

                                                createNote.appendChild(createNoteInput);
                                                displayContainer.appendChild(displayImg);
                                                closeContainer.appendChild(closeButton);
                                                displayContainer.appendChild(createNote);
                                                displayContainer.appendChild(closeContainer);
                                                link.appendChild(image);
                                                displayImg.appendChild(link);
                                                imgContainer.appendChild(displayContainer);

                                                document.getElementById('js-upload-createbuktipengiriman-<?= $project['id'] ?>').setAttribute('hidden', '');
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

                                        function removeImgCreatebuktipengiriman<?= $project['id'] ?>() {
                                            $.ajax({
                                                type: 'post',
                                                url: 'upload/removebuktipengiriman',
                                                data: {
                                                    'buktipengiriman': document.getElementById('photocreatebuktipengiriman<?= $project['id'] ?>').value
                                                },
                                                dataType: 'json',

                                                error: function() {
                                                    console.log('error', arguments);
                                                },

                                                success: function() {
                                                    console.log('success', arguments);

                                                    var pesan = arguments[0][1];

                                                    document.getElementById('display-container-createbuktipengiriman-<?= $project['id'] ?>').remove();
                                                    document.getElementById('photocreatebuktipengiriman<?= $project['id'] ?>').value = '';

                                                    alert(pesan);

                                                    document.getElementById('js-upload-createbuktipengiriman-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                }
                                            });
                                        };
                                    </script>
                                    <!-- End Of Bukti Pengiriman -->

                                    <!-- Serah Terima -->
                                    <div class="uk-margin" id="image-container-createspk-<?= $project['id'] ?>">
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">SERAH TERIMA</label>
                                        <div class="uk-child-width-auto uk-text-center uk-margin-top" id="containersertrim-<?= $project['id'] ?>" uk-grid>
                                            <?php
                                            if (!empty($projectdata[$project['id']]['bast'])) {
                                                foreach ($projectdata[$project['id']]['bast'] as $bast) {
                                                    if (!empty($bast) && $bast['status'] === "0") { ?>
                                                        <div id="sertrim-file-<?= $bast['id']; ?>">
                                                            <div id="sertrim-card<?= $bast['id'] ?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                                <div class="uk-position-small uk-position-right"> <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?><a class="tm-img-remove2 uk-border-circle uk-icon" id="remove-sertrim-<?= $bast['id'] ?>" onclick="removeCardFile<?= $bast['id'] ?>()" uk-icon="close"></a><?php } ?></div>
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
                                        <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?>
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
                                    <div class="uk-margin" id="image-container-createbast-<?= $project['id'] ?>">
                                        <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">BAST</label>
                                        <div class="uk-form-horizontal">
                                            <div class="uk-margin-small">
                                                <label class="uk-form-label">Tanggal BAST</label>
                                                <div class="uk-form-controls">:
                                                    <div class="uk-inline">
                                                        <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?>
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <input class="uk-input uk-form-width-medium" <?php if (!empty($projectdata[$project['id']]['bastfile'])) { $tempo = date_create($projectdata[$project['id']]['bastfile']['tanggal_bast']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> id="jatuhtempobast<?= $project['id'] ?>" name="jatuhtempobast<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                        <?php } else { ?>
                                                            <span class=""><?php if (!empty($projectdata[$project['id']]['bastfile'])) { $tempo = date_create($projectdata[$project['id']]['bastfile']['tanggal_bast']); echo date_format($tempo, 'm/d/Y');} else {echo date('m/d/Y');} ?></span>
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
                                                            <div class="uk-position-small uk-position-right"><?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?><a class="tm-img-remove2 uk-border-circle uk-icon" id="removeCardFilebast<?= $bast['id']; ?>" onclick="removeCardFilebast<?= $bast['id'] ?>()" uk-icon="close"></a><?php } ?></div>
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
                                        <?php if ($authorize->hasPermission('production.project.edit', $uid)) { ?>
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
                            <div class="uk-margin uk-child-width-1-2 uk-flex-middle" uk-grid>
                                <div>
                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Keuangan</div>
                                </div>
                                <div class="uk-text-right">
                                    <a class="uk-link-reset uk-icon-button" id="togglefinance<?= $project['id'] ?>" uk-toggle="target: .toggleinvoice<?= $project['id'] ?>"><span class="uk-light" id="closefinance<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openfinance<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                </div>
                            </div>

                            <div class="toggleinvoice<?= $project['id'] ?>" hidden>
                                <div class="uk-margin-small uk-child-width-1-2" uk-grid>

                                    <!-- Invoice Generate Button -->
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
                                                if (isset($projectdata[$project['id']]['sertrim']['status']) && $progress >= "60" || $progress >= 60 && $projectdata[$project['id']]['sertrim']['status'] === "0") {
                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel2/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice II</a>";
                                                }
                                            }

                                            // Invoice III
                                            if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                if (isset($projectdata[$project['id']]['bastfile']['status']) && $progress >= "95" || $progress >= 95  && $projectdata[$project['id']]['bastfile']['status'] === "1" && !empty($projectdata[$project['id']]['bast']['file'])) {
                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel3/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice III</a>";
                                                    $status = "Retensi";
                                                }
                                            }

                                            // Invoice IV
                                            if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                if (!empty($projectdata[$project['id']]['bastfile']['tanggal_bast'])) {
                                                    if ($projectdata[$project['id']]['bastfile']['status'] === "1" && $projectdata[$project['id']]['now'] >=  $projectdata[$project['id']]['dateline'] &&  $progress >= 95) {
                                                        echo "<a id='btninv" . $project['id'] . "' class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel4/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice IV</a>";
                                                        $progress   = "100";
                                                    }
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <!-- <div class="uk-width-1-1">
                                        <label class="uk-h5 uk-margin uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">CETAK INVOICE</label>
                                        <p class="uk-margin" uk-margin>
                                            </?php
                                            // Invoice I
                                            if (!empty($project)) {
                                                if ($project['status_spk'] === "1") {
                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice I</a>";
                                                }
                                            }

                                            // Invoice II
                                            if (!empty($project)) {
                                                if (isset($projectdata[$project['id']]['sertrim']['status']) && $progress >= "60" || $progress >= 60 && $projectdata[$project['id']]['sertrim']['status'] === "0") {
                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice II</a>";
                                                }
                                            }

                                            // Invoice III
                                            if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                if (isset($projectdata[$project['id']]['bastfile']['status']) && $progress >= "95" || $progress >= 95  && $projectdata[$project['id']]['bastfile']['status'] === "1" && !empty($projectdata[$project['id']]['bast']['file'])) {
                                                    echo "<a class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice III</a>";
                                                    $status = "Retensi";
                                                }
                                            }

                                            // Invoice IV
                                            if (!empty($projectdata[$project['id']]['bastfile'])) {
                                                if (!empty($projectdata[$project['id']]['bastfile']['tanggal_bast'])) {
                                                    if ($projectdata[$project['id']]['bastfile']['status'] === "1" && $projectdata[$project['id']]['now'] >=  $projectdata[$project['id']]['dateline'] &&  $progress >= 95) {
                                                        echo "<a id='btninv" . $project['id'] . "' class='uk-button uk-button-primary uk-margin-right' href='project/invoiceexcel/" . $project['id'] . "'><span class='uk-margin-small-right uk-icon' uk-icon='icon:  file-text; ratio: 1.2'></span>Invoice IV</a>";
                                                        $progress   = "100";
                                                    }
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div> -->
                                    <!-- End Of Invoice Generate Button -->

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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice I" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['no_inv'] . "'"; } ?> name="noinv1<?= $project['id'] ?>">
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
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice1<?= $project['id'] ?>" name="dateinvoice1<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { $tempo = date_create($projectdata[$project['id']]['invoice1']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice1<?= $project['id'] ?>" name="dateinvoice1<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { $tempo = date_create($projectdata[$project['id']]['invoice1']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> placeholder="<?= date('m/d/Y') ?>" disabled />
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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['pph23'] . "'"; } ?> name="pphinvoice1<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['pph23'] . "'"; } ?> name="pphinvoice1<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['email'] . "'"; } ?> name="emailinvoice1<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice1'])) { echo "value='" . $projectdata[$project['id']]['invoice1']['email'] . "'"; } ?> name="emailinvoice1<?= $project['id'] ?>" disabled>
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
                                                    <label class="uk-form-label">File Invoice</label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice1'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice1']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice1']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice1']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice1']['id'] ?>"><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice1']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <p class="uk-margin-left-remove" uk-margin>
                                                    <div class="js-upload-<?= $projectdata[$project['id']]['invoice1']['id'] ?>" uk-form-custom>
                                                        <input type="file" multiple>
                                                        <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice1']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice I</button>
                                                    </div>
                                                    </p>
                                                </div>

                                                <script>
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
                                                            container.setAttribute('id', 'inv' + proid);

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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice II" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { echo "value='" . $projectdata[$project['id']]['invoice2']['no_inv'] . "'"; } ?> name="noinv2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice II" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { echo "value='" . $projectdata[$project['id']]['invoice2']['no_inv'] . "'"; } ?> name="noinv2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice2<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice2'])) { $tempo = date_create($projectdata[$project['id']]['invoice2']['jatuhtempo']); echo "value='" . date_format($tempo, 'm/d/Y') . "'"; } ?> name="dateinvoice2<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice2']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice2']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice2']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice2<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice2'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice2']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice2<?= $project['id'] ?>" disabled>
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
                                                    <label class="uk-form-label">File Invoice</label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice2'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice2']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice2']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice2']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice2']['id'] ?>"><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice2']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <p class="uk-margin-left-remove" uk-margin>
                                                    <div class="js-upload-<?= $projectdata[$project['id']]['invoice2']['id'] ?>" uk-form-custom>
                                                        <input type="file" multiple>
                                                        <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice2']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice II</button>
                                                    </div>
                                                    </p>
                                                </div>

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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice III" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                        echo "value='" . $projectdata[$project['id']]['invoice3']['no_inv'] . "'";
                                                                                                                                                    } ?> name="noinv3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice III" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                        echo "value='" . $projectdata[$project['id']]['invoice3']['no_inv'] . "'";
                                                                                                                                                    } ?> name="noinv3<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice3<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                        $tempo = date_create($projectdata[$project['id']]['invoice3']['jatuhtempo']);
                                                                                                                                                        echo "value='" . date_format($tempo, 'm/d/Y') . "'";
                                                                                                                                                    } ?> name="dateinvoice3<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice3<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                        $tempo = date_create($projectdata[$project['id']]['invoice3']['jatuhtempo']);
                                                                                                                                                        echo "value='" . date_format($tempo, 'm/d/Y') . "'";
                                                                                                                                                    } ?> name="dateinvoice3<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice3']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice3']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice3<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice3']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice3<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice3'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice3']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice3<?= $project['id'] ?>" disabled>
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
                                                    <label class="uk-form-label">File Invoice</label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice3'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice3']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice3']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice3']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice3']['id'] ?>"><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice3']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <p class="uk-margin-left-remove" uk-margin>
                                                    <div class="js-upload-<?= $projectdata[$project['id']]['invoice3']['id'] ?>" uk-form-custom>
                                                        <input type="file" multiple>
                                                        <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice3']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice III</button>
                                                    </div>
                                                    </p>
                                                </div>

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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice IV" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                        echo "value='" . $projectdata[$project['id']]['invoice4']['no_inv'] . "'";
                                                                                                                                                    } ?> name="noinv4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="No Invoice IV" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                        echo "value='" . $projectdata[$project['id']]['invoice4']['no_inv'] . "'";
                                                                                                                                                    } ?> name="noinv2<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Jatuh Tempo</label>
                                                    <div class="uk-form-controls">:
                                                        <div class="uk-inline">
                                                            <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                                            <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice4<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                        $tempo = date_create($projectdata[$project['id']]['invoice4']['jatuhtempo']);
                                                                                                                                                        echo "value='" . date_format($tempo, 'm/d/Y') . "'";
                                                                                                                                                    } ?> name="dateinvoice4<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" />
                                                            <?php } else { ?>
                                                                <input class="uk-input uk-form-width-medium" id="dateinvoice4<?= $project['id'] ?>" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                        $tempo = date_create($projectdata[$project['id']]['invoice4']['jatuhtempo']);
                                                                                                                                                        echo "value='" . date_format($tempo, 'm/d/Y') . "'";
                                                                                                                                                    } ?> name="dateinvoice4<?= $project['id'] ?>" placeholder="<?= date('m/d/Y') ?>" disabled />
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
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice4']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="text" placeholder="PPH 23" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice4']['pph23'] . "'";
                                                                                                                                            } ?> name="pphinvoice4<?= $project['id'] ?>" disabled>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <label class="uk-form-label">Email</label>
                                                    <div class="uk-form-controls">:
                                                        <?php if ($authorize->hasPermission('finance.project.edit', $uid)) { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice4']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice4<?= $project['id'] ?>">
                                                        <?php } else { ?>
                                                            <input class="uk-input uk-form-width-medium" type="email" placeholder="Email" <?php if (!empty($projectdata[$project['id']]['invoice4'])) {
                                                                                                                                                echo "value='" . $projectdata[$project['id']]['invoice4']['email'] . "'";
                                                                                                                                            } ?> name="emailinvoice4<?= $project['id'] ?>" disabled>
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
                                                    <label class="uk-form-label">File Invoice</label>
                                                    <?php if (!empty($projectdata[$project['id']]['invoice4'])) { ?>
                                                        <div class="uk-form-controls" id="continv<?= $projectdata[$project['id']]['invoice4']['id'] ?>">:
                                                            <?php if (!empty($projectdata[$project['id']]['invoice4']['file'])) { ?>
                                                                <a href="img/invoice/<?= $projectdata[$project['id']]['invoice4']['file'] ?>" id="inv<?= $projectdata[$project['id']]['invoice4']['id'] ?>"><span uk-icon="file-text" ;></span><?= $projectdata[$project['id']]['invoice4']['file'] ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="uk-margin-small">
                                                    <p class="uk-margin-left-remove" uk-margin>
                                                    <div class="js-upload-<?= $projectdata[$project['id']]['invoice4']['id'] ?>" uk-form-custom>
                                                        <input type="file" multiple>
                                                        <progress id="js-progressbar-<?= $projectdata[$project['id']]['invoice4']['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        <button class="uk-button uk-button-default" type="button" tabindex="-1">Upload invoice IV</button>
                                                    </div>
                                                    </p>
                                                </div>

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
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice IV End -->
                                </div>
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
                            <div class="uk-margin uk-child-width-1-2 uk-flex-middle" uk-grid>
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
                                <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('production.project.edit', $uid) || $authorize->hasPermission('design.project.edit', $uid) || $authorize->hasPermission('marketing.project.edit', $uid)) { ?>
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
            var editmodal = document.getElementById('modalupdatepro<?=$input?>');
            UIkit.modal(editmodal).show();
        });
    </script>
<?php } ?>

<?= $this->endSection() ?>