<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) { ?>
    <?php if ($ismobile === true) { ?>
        <h3 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h3>
        <div class="uk-text-center uk-margin">
            <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
        </div>
    <?php } else { ?>
        <div class="uk-margin uk-child-width-auto uk-flex-between" uk-grid>
            <div>
                <h3 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h3>
            </div>
            <div>
                <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
            </div>
        </div>
        <?= view('Views/Auth/_message_block') ?>
    <?php } ?>
    <hr class="uk-divider-icon uk-margin-remove-top">

    <div class="uk-container uk-container-large">
        <?php foreach ($projects as $project) {
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
                        <div class="uk-card-footer uk-text-center">
                            <?php if ($this->data['authorize']->hasPermission('marketing.project.edit', $this->data['uid'])) { ?>
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
                                <?php if ($this->data['authorize']->hasPermission('marketing.project.edit', $this->data['uid'])) { ?>
                                    <button class="uk-button uk-button-secondary uk-margin-small-right" type="button" uk-toggle="target: #modalupdatepro<?= $project['id'] ?>">Ubah Data</button>
                                <?php } ?>
                            </div>
                        </div>
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
        <?php }
        } ?>
        <?= $pager->links('projects', 'uikit_full') ?>
    </div>

    <!-- Modal Add Proyek -->
    <?php if ($this->data['authorize']->hasPermission('admin.project.create', $this->data['uid'])) { ?>
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
                                <input id="designtype" name="designtype" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                            <script>
                                $(document).ready(function() {
                                    $("input[id='designtype']").change(function() {
                                        if ($(this).is(':checked')) {
                                            $("input[id='designtype']").val(1);
                                            $("div[id='imgdesigncreate']").attr("hidden", false);
                                        } else {
                                            $("input[id='designtype']").val(0);
                                            $("div[id='imgdesigncreate']").attr("hidden", true);
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
                                <div id="image-container-" class="uk-form-controls">
                                    <input id="designcreated" name="design" hidden required />
                                    <div id="js-upload-createdesign-" class="js-upload-createdesign- uk-placeholder uk-text-center">
                                        <span uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                        <div uk-form-custom>
                                            <input type="file">
                                            <span class="uk-link uk-preserve-color">pilih satu</span>
                                        </div>
                                    </div>
                                    <progress id="js-progressbar-createdesign-" class="uk-progress" value="0" max="100" hidden></progress>
                                </div>
                            </div>

                            <script type="text/javascript">
                                var bar = document.getElementById('js-progressbar-createdesign-');

                                UIkit.upload('.js-upload-createdesign-', {
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

                                        if (document.getElementById('display-container-create-')) {
                                            document.getElementById('display-container-create-').remove();
                                        };

                                        document.getElementById('designcreated').value = filename;

                                        document.getElementById('placedesign').removeAttribute('hidden');

                                        var uprev = document.getElementById('updesign');
                                        var closed = document.getElementById('closeddesign');

                                        var divuprev = document.createElement('h6');
                                        divuprev.setAttribute('class', 'uk-margin-remove');
                                        divuprev.setAttribute('id', 'design');


                                        var linkrev = document.createElement('a');
                                        linkrev.setAttribute('href', 'img/revisi/' + filename);
                                        linkrev.setAttribute('uk-icon', 'file-text');

                                        var link = document.createElement('a');
                                        link.setAttribute('href', 'img/revisi/' + filename);
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

                                        document.getElementById('js-upload-createdesign-').setAttribute('hidden', '');
                                    },

                                    loadStart: function(e) {
                                        console.log('loadStart', arguments);

                                        document.getElementById('js-progressbar-createdesign-').removeAttribute('hidden');

                                        document.getElementById('js-progressbar-createdesign-').max = e.total;
                                        document.getElementById('js-progressbar-createdesign-').value = e.loaded;

                                    },

                                    progress: function(e) {
                                        console.log('progress', arguments);

                                        document.getElementById('js-progressbar-createdesign-').max = e.total;
                                        document.getElementById('js-progressbar-createdesign-').value = e.loaded;
                                    },

                                    loadEnd: function(e) {
                                        console.log('loadEnd', arguments);

                                        document.getElementById('js-progressbar-createdesign-').max = e.total;
                                        document.getElementById('js-progressbar-createdesign-').value = e.loaded;
                                    },

                                    completeAll: function() {
                                        console.log('completeAll', arguments);

                                        setTimeout(function() {
                                            document.getElementById('js-progressbar-createdesign-').setAttribute('hidden', 'hidden');
                                            alert('Proses unggah data desain selesai');
                                        }, 1000);
                                    }

                                });

                                function removedesign() {
                                    $.ajax({
                                        type: 'post',
                                        url: 'upload/removelayout',
                                        data: {
                                            'design': document.getElementById('designcreated').value
                                        },
                                        dataType: 'json',

                                        error: function() {
                                            console.log('error', arguments);
                                        },

                                        success: function() {
                                            console.log('success', arguments);

                                            var pesan = arguments[0][1];

                                            document.getElementById('design').remove();
                                            document.getElementById('closedes').remove();
                                            document.getElementById('placedesign').setAttribute('hidden', '');
                                            document.getElementById('designcreated').value = '';

                                            document.getElementById('js-upload-createdesign-').removeAttribute('hidden', '');
                                            alert(pesan);
                                        }
                                    });
                                };
                            </script>
                            <!-- Upload Design End -->

                            <div class="uk-modal-footer uk-text-right">
                                <button class="uk-button uk-button-primary" type="submit">Save</button>
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
    <?php if ($this->data['authorize']->hasPermission('marketing.project.edit', $this->data['uid'])) { ?>
        <?php foreach ($projects as $project) { ?>
            <div class="uk-modal-container" id="modalupdatepro<?= $project['id'] ?>" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <button class="uk-modal-close-default uk-icon-button-delete" type="button" uk-close></button>
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

                            <!-- Desain Section -->
                            <?php if ($this->data['authorize']->hasPermission('design.project.edit', $this->data['uid'])) { ?>
                                <?php if (empty($projectdata[$project['id']]['design'])) { ?>
                                    <div class="uk-margin-small uk-child-width-1-2" uk-grid>
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

                            <!-- Detail Pemesanan Seciton -->
                            <?php if (!empty($projectdata[$project['id']]['design'])) {
                                if ($projectdata[$project['id']]['design']['status'] === '2') { ?>
                                    <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                        <div>
                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Detail Pemesanan</div>
                                        </div>
                                        <div class="uk-text-right">
                                            <a class="uk-link-reset uk-icon-button" id="toggle<?= $project['id'] ?>" uk-toggle="target: .togglesph<?= $project['id'] ?>"><span class="uk-light" id="close<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="open<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                        </div>
                                    </div>

                                    <?php if ($project['status_spk'] != 1) { ?>
                                        <div class="uk-padding uk-padding-remove-vertical togglesph<?= $project['id'] ?>" hidden>
                                            <?php if (!empty($projectdata[$project['id']]['paket'])) { ?>
                                                <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/<?= $project['id'] ?>" target="_blank">Download SPH</a>
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
                                                                            } elseif ($mdl['denomination'] === "4") {
                                                                                echo "Set";
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td><?= $mdl['keterangan'] ?></td>
                                                                        <td class="uk-form-controls">
                                                                            <input type="number" id="eqty[<?= $project['id'] ?><?= $mdl['id'] ?>]" name="eqty<?= $project['id'] ?>[<?= $mdl['id'] ?>]" class="uk-input uk-form-width-small" value="<?= $mdl['qty'] ?>" onchange="eprice(<?= $project['id'] ?><?= $mdl['id'] ?>)" />
                                                                        </td>
                                                                        <div id="eprice[<?= $project['id'] ?><?= $mdl['id'] ?>]" hidden><?= $mdl['price'] ?></div>
                                                                        <td id="eshowprice[<?= $project['id'] ?><?= $mdl['id'] ?>]"><?= "Rp. " . number_format((int)$mdl['qty'] * (int)$mdl['price'], 0, ',', '.');
                                                                                                                                    " "; ?></td>
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
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="uk-h4">Tambah Pesanan</div>
                                            <?php } ?>

                                            <div class="uk-margin-bottom">
                                                <label class="uk-form-label" for="paket">Cari Paket</label>
                                                <div class="uk-form-controls">
                                                    <input type="text" class="uk-input" id="paketname<?= $project['id'] ?>" name="paketname<?= $project['id'] ?>" placeholder="Nama Paket">
                                                </div>
                                            </div>

                                            <div id="listmdl<?= $project['id'] ?>"></div>
                                        </div>

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
                                                                    if (emdlarray[t]['denomination'] === '1') {
                                                                        tdden.innerHTML = 'Unit'
                                                                    } else if (emdlarray[t]['denomination'] === '2') {
                                                                        tdden.innerHTML = 'Meter'
                                                                    } else if (emdlarray[t]['denomination'] === '3') {
                                                                        tdden.innerHTML = 'Meter Persegi'
                                                                    }

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
                                    <?php } else { ?>
                                        <div class="uk-padding uk-padding-remove-vertical togglesph<?= $project['id'] ?>" hidden>
                                            <a class="uk-button uk-button-primary uk-margin-small-right" href="project/sphprint/<?= $project['id'] ?>">Download SPH</a>
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
                                                        <?php foreach ($projectdata[$project['id']]['rab'] as $mdlrab) { ?>
                                                            <tr>
                                                                <td><?= $mdlrab['name'] ?></td>
                                                                <td><?= $mdlrab['length'] ?></td>
                                                                <td><?= $mdlrab['width'] ?></td>
                                                                <td><?= $mdlrab['height'] ?></td>
                                                                <td><?= $mdlrab['volume'] ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($mdlrab['denomination'] === "1") {
                                                                        echo "Unit";
                                                                    } elseif ($mdlrab['denomination'] === "2") {
                                                                        echo "Meter Lari";
                                                                    } elseif ($mdlrab['denomination'] === "3") {
                                                                        echo "Meter Persegi";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?= $mdlrab['keterangan'] ?></td>
                                                                <td><?= $mdlrab['qty'] ?></td>
                                                                <td><?= $mdlrab['price'] ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                            <?php }
                                }
                            } ?>
                            <!-- Detail Pemesanan Seciton End -->

                            <!-- SPK Section -->
                            <?php if ($project['spk'] != null) {
                                if ($project['status_spk'] === '0') { ?>
                                    <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                        <div>
                                            <div class="uk-child-width-auto uk-flex-middle" uk-grid>
                                                <div>
                                                    <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">SPK</div>
                                                </div>
                                                <div>
                                                    <div class="uk-text-light uk-text-center" style="border-style: solid; border-color: #ff0000; color: #ff0000; font-weight: bold; padding: 3px;">Menuggu Persetujuan DPSA</div>
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
                                                <label class="uk-form-label uk-margin-remove-top">Tanggal Upload SPK</label>
                                                <div class="uk-form-controls">: <?= date('d M Y, H:i', strtotime($project['updated_at'])); ?></div>
                                            </div>

                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">File SPK</label>
                                                <div class="uk-form-controls">: <a href="/img/spk/<?= $project['spk'] ?>"><span uk-icon="file-pdf"></span><?= $project['spk'] ?></a></div>
                                            </div>
                                        </div>

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
                                    </div>
                                <?php } elseif ($project['status_spk'] === '1') { ?>
                                    <div class="uk-margin-small uk-child-width-1-2" uk-grid>
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
                                            <a class="uk-link-reset uk-icon-button" id="togglespk<?= $project['id'] ?>" uk-toggle="target: .togglespk<?= $project['id'] ?>"><span class="uk-light" id="closespk<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openspk<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                        </div>
                                    </div>

                                    <div class="togglespk<?= $project['id'] ?>" hidden>
                                        <div class="uk-form-horizontal">
                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">Tanggal Upload SPK</label>
                                                <div class="uk-form-controls">: <?= date('d M Y, H:i', strtotime($project['updated_at'])); ?></div>
                                            </div>

                                            <div class="uk-margin-small">
                                                <label class="uk-form-label uk-margin-remove-top">File SPK</label>
                                                <div class="uk-form-controls">: <a href="/img/spk/<?= $project['spk'] ?>"><span uk-icon="file-pdf"></span><?= $project['spk'] ?></a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

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
                            <?php } ?>
                            <!-- SPK Section End -->

                            <!-- Production Section -->
                            <?php if ($this->data['authorize']->hasPermission('production.project.edit', $this->data['uid'])) { ?>
                                <?php if ($project['status_spk'] == 1) { ?>
                                    <div class="uk-margin-small uk-child-width-1-2" uk-grid>
                                        <div>
                                            <div class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" style="text-transform: uppercase;">Production</div>
                                        </div>
                                        <div class="uk-text-right">
                                            <a class="uk-link-reset uk-icon-button" id="toggleproduction<?= $project['id'] ?>" uk-toggle="target: .toggleproduction<?= $project['id'] ?>"><span class="uk-light" id="closeproduction<?= $project['id'] ?>" uk-icon="chevron-down" hidden></span><span class="uk-light" id="openproduction<?= $project['id'] ?>" uk-icon="chevron-right"></span></a>
                                        </div>
                                    </div>

                                    <div class="toggleproduction<?= $project['id'] ?>" hidden>
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
                                                        <th class="uk-text-center">Setting</th>
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
                                                                <?php if (strtoupper($production['setting']) == '1') { ?>
                                                                    <div uk-icon="check"></div>
                                                                <?php } else { ?>
                                                                    <input class="uk-checkbox" type="checkbox" name="setting<?= $project['id']; ?>[<?= $production['id'] ?>]" value="1">
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Serah Terima -->
                                        <div class="uk-margin" id="image-container-createspk-<?= $project['id'] ?>">
                                            <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">Upload Serah Terima</label>
                                            <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-text-center uk-margin-top" id="containersertrim-<?= $project['id'] ?>" uk-grid>
                                                <?php
                                                foreach ($projectdata[$project['id']]['bast'] as $bast) {
                                                    $bastid = "";
                                                    if (!empty($bast) && $bast['status'] === "0") {
                                                        $bastid = $bast['id']; ?>
                                                        <div id="sertrim-file-<?= $bast['id']; ?>">
                                                            <div id="sertrim-card<?=$bast['id']?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                                <div class="uk-position-small uk-position-right"><a class="tm-img-remove2 uk-border-circle uk-icon" id="remove-sertrim-<?= $bastid ?>" onclick="removeCardFile<?= $bast['id'] ?>()" uk-icon="close"></a></div>
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
                                                } ?>
                                            </div>
                                            <div class="uk-placeholder" id="placesertrim<?= $project['id'] ?>" hidden>
                                                <div uk-grid>
                                                    <div class="uk-text-left uk-width-3-4">
                                                        <div id="upsertrim<?= $project['id'] ?>"></div>
                                                    </div>
                                                    <div class="uk-text-right uk-width-1-4">
                                                        <div id="closesertrim<?= $project['id'] ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
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
                                        </div>
                                        <!-- End Of Serah Terima -->

                                        <!-- BAST -->
                                        <div class="uk-margin" id="image-container-createbast-<?= $project['id'] ?>">
                                            <label class="uk-h5 uk-margin-remove uk-text-bold uk-text-emphasis uk-text-left" for="photocreate">Upload BAST</label>
                                            <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-text-center uk-margin-top" uk-grid>
                                                <?php
                                                foreach ($projectdata[$project['id']]['bast'] as $bast) {
                                                    $bastid = "";
                                                    if (!empty($bast) && $bast['status'] === "1") {
                                                        $bastid = $bast['id']; ?>
                                                        <div id="bast-file-<?= $bast['id']; ?>">
                                                            <div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
                                                                <div class="uk-position-small uk-position-right"><a class="tm-img-remove2 uk-border-circle uk-icon" id="remove-bast-<?= $bastid ?>" onclick="removeCardFile<?= $bast['id'] ?>()" uk-icon="close"></a></div>
                                                                <a href="img/bast/<?= $bast['file'] ?>" target="_blank"><span uk-icon="file-text" ;></span><?= $bast['file'] ?> </a>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function removeCardFile<?= $bast['id']; ?>() {
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
                                                                            $("#bast-file-<?= $bast['id']; ?>").remove();
                                                                        },
                                                                    })
                                                                }
                                                            }
                                                        </script>
                                                <?php }
                                                } ?>
                                            </div>
                                            <div class="uk-placeholder" id="placebast<?= $project['id'] ?>" hidden>
                                                <div uk-grid>
                                                    <div class="uk-text-left uk-width-3-4">
                                                        <div id="upbast<?= $project['id'] ?>"></div>
                                                    </div>
                                                    <div class="uk-text-right uk-width-1-4">
                                                        <div id="closebast<?= $project['id'] ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                    var filename = arguments[0].response;

                                                    // location.reload(true);

                                                    var contsertrim = document.getElementById('containersertrim-<?= $project['id'] ?>');

                                                    var container = document.createElement('div');
                                                    container.setAttribute('id','sertrim-file-<?= $bast['id']; ?>');

                                                    var cardsertrim = document.createElement('div');
                                                    cardsertrim.setAttribute('class','uk-card uk-card-default uk-card-body uk-margin-bottom');

                                                    var divclosed = document.createElement('div');
                                                    divclosed.setAttribute('class','uk-position-small uk-position-right');

                                                    var close = document.createElement('a');
                                                    close.setAttribute('id','remove-sertrim-<?= $bastid ?>');
                                                    close.setAttribute('class','tm-img-remove2 uk-border-circle uk-icon');
                                                    close.setAttribute('onClick','removeCardFile<?= $bast['id'] ?>()');
                                                    close.setAttribute('uk-icon','close');

                                                    var link = document.createElement('a');
                                                    link.setAttribute('href','img/sertrim/'+filename);
                                                    link.setAttribute('target','_blank');

                                                    var file = document.createTextNode(filename);

                                                    var icon = document.createElement('span');
                                                    icon.setAttribute('uk-icon','file-text');

                                                    contsertrim.appendChild(container);
                                                    container.appendChild(cardsertrim);
                                                    cardsertrim.appendChild(divclosed);
                                                    divclosed.appendChild(close);
                                                    cardsertrim.appendChild(link);
                                                    link.appendChild(icon);
                                                    link.appendChild(file);
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
                                                        alert('<?= lang('Proses selesai, Data berhasil di unggah.') ?>');
                                                    }, 1000);
                                                }

                                            });

                                            function removeImgCreatesertrim<?= $project['id'] ?>() {
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'upload/removesertrim',
                                                    data: {
                                                        'sertrim': document.getElementById('photocreatesertrim<?= $project['id'] ?>').value
                                                    },
                                                    dataType: 'json',

                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },

                                                    success: function() {
                                                        console.log('success', arguments);

                                                        var pesan = arguments[0][1];

                                                        document.getElementById('sertrim<?= $project['id'] ?>').remove();
                                                        document.getElementById('closedsertrim<?= $project['id'] ?>').remove();
                                                        document.getElementById('placesertrim<?= $project['id'] ?>').setAttribute('hidden', '');
                                                        document.getElementById('photocreatesertrim<?= $project['id'] ?>').value = '';

                                                        document.getElementById('js-upload-createsertrim-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                        alert(pesan);
                                                    }
                                                });
                                            };
                                            //   End Of Serah Terima

                                            // Bast
                                            UIkit.upload('.js-upload-createbast-<?= $project['id'] ?>', {
                                                url: 'upload/bast',
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

                                                    if (document.getElementById('display-container-createbast-<?= $project['id'] ?>')) {
                                                        document.getElementById('display-container-createbast-<?= $project['id'] ?>').remove();
                                                    };

                                                    document.getElementById('photocreatebast<?= $project['id'] ?>').value = filename;

                                                    document.getElementById('placebast<?= $project['id'] ?>').removeAttribute('hidden');

                                                    var uprev = document.getElementById('upbast<?= $project['id'] ?>');
                                                    var closed = document.getElementById('closebast<?= $project['id'] ?>');

                                                    var divuprev = document.createElement('h6');
                                                    divuprev.setAttribute('class', 'uk-margin-remove');
                                                    divuprev.setAttribute('id', 'bast<?= $project['id'] ?>');

                                                    var linkrev = document.createElement('a');
                                                    linkrev.setAttribute('href', 'img/bast/' + filename);
                                                    linkrev.setAttribute('uk-icon', 'file-text');

                                                    var link = document.createElement('a');
                                                    link.setAttribute('href', 'img/bast/' + filename);
                                                    link.setAttribute('target', '_blank');

                                                    var linktext = document.createTextNode(filename);

                                                    var divclosed = document.createElement('a');
                                                    divclosed.setAttribute('uk-icon', 'icon: close');
                                                    divclosed.setAttribute('onClick', 'removeImgCreatebast<?= $project['id'] ?>()');
                                                    divclosed.setAttribute('id', 'closedbast<?= $project['id'] ?>');

                                                    uprev.appendChild(divuprev);
                                                    divuprev.appendChild(linkrev);
                                                    divuprev.appendChild(link);
                                                    link.appendChild(linktext);
                                                    closed.appendChild(divclosed);

                                                    document.getElementById('js-upload-createbast-<?= $project['id'] ?>').setAttribute('hidden', '');
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
                                                        alert('<?= lang('Proses selesai, Silahkan Unggah Data.') ?>');
                                                    }, 1000);
                                                }

                                            });

                                            function removeImgCreatebast<?= $project['id'] ?>() {
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'upload/removebast',
                                                    data: {
                                                        'bast': document.getElementById('photocreatebast<?= $project['id'] ?>').value
                                                    },
                                                    dataType: 'json',

                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },

                                                    success: function() {
                                                        console.log('success', arguments);

                                                        var pesan = arguments[0][1];

                                                        document.getElementById('bast<?= $project['id'] ?>').remove();
                                                        document.getElementById('closedbast<?= $project['id'] ?>').remove();
                                                        document.getElementById('placebast<?= $project['id'] ?>').setAttribute('hidden', '');
                                                        document.getElementById('photocreatebast<?= $project['id'] ?>').value = '';

                                                        document.getElementById('js-upload-createbast-<?= $project['id'] ?>').removeAttribute('hidden', '');
                                                        alert(pesan);
                                                    }
                                                });
                                            };
                                            //   End Of BAST
                                        </script>
                                    </div>
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
            <?php } ?>
            <!-- Production Section End -->

            <div class="uk-modal-footer uk-text-right">
                <?php if ($this->data['authorize']->hasPermission('admin.project.delete', $this->data['uid'])) { ?>
                    <a class="uk-button uk-button-danger" href="project/delete/<?= $project['id'] ?>" onclick="return confirm('<?= 'Anda yakin ingin menghapus data ' . $project['name'] . '?' ?>')" type="button">Hapus</a>
                <?php } ?>
                <?php if ($this->data['authorize']->hasPermission('marketing.project.edit', $this->data['uid'])) { ?>
                    <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                <?php } ?>
            </div>
            </form>
                </div>
            </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- Modal Update Proyek End -->
<?php } ?>

<?= $this->endSection() ?>