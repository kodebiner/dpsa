<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if ($ismobile === true) { ?>
    <h3 class="tm-h1 uk-text-center uk-margin-remove"><?= lang("Global.projectList") ?></h3>
    <div class="uk-text-center uk-margin">
        <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle><?= lang("Global.Addproject") ?></button>
    </div>
<?php } else { ?>
    <div class="uk-margin uk-child-width-auto uk-flex-between" uk-grid>
        <div>
            <h3 class="tm-h1 uk-text-center uk-margin-remove"><?= lang("Global.projectList") ?></h3>
        </div>
        <div>
            <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle><?= lang("Global.Addproject") ?></button>
        </div>
    </div>
<?php } ?>
<hr class="uk-divider-icon uk-margin-remove-top">
<!-- add project modal -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog" uk-overflow-auto>
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title"><?=lang('Global.Addproject')?></h2>
        </div>

        <form class="uk-margin-left" action="project/create" method="post">

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon" required>
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" aria-label="Brief" required></textarea>
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-form-controls uk-form-width-large">
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

            <script>
                $(document).ready(function() {
                    $("#status").change(function() {
                        if ((this.value) == 4) {
                            $("#proqty").removeAttr("hidden");
                        } else {
                            $("#proqty").attr("hidden", true);
                        }
                    });
                });
            </script>

            <div class="uk-margin" id="proqty" hidden>
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="qty" placeholder="Prosentase Produksi" type="number" max="100" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-form-controls uk-form-width-large">
                    <select class="uk-select" name="client" id="client" required>
                        <option value="" selected disabled>Pilih Client</option>
                        <?php foreach ($clients as $client) { ?>
                            <option value="<?= $client['id'] ?>"> <?= $client['username'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end add project modal -->

<div class="uk-container uk-container-large">
    <?php foreach ($projects as $project) { ?>
        <?php if ($ismobile === true) { ?>
            <div class="uk-card uk-card-default uk-width-1-1 uk-margin">
                <div class="uk-card-header">
                    <div class="uk-flex-middle uk-grid-small" uk-grid>
                        <div class="uk-width-3-4">
                            <h3 class="uk-card-title">
                                <?php foreach ($clients as $client) {
                                    if ($client['id'] === $project['clientid']) {
                                        if ($client['parent'] != null) {
                                            echo $client['username'] . " (cabang)";
                                        } else {
                                            echo $client['username'] . " (pusat)";
                                        }
                                    }
                                }
                                ?>
                                <span> - <?= $project['name'] ?></span>
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
                                <h4 class="match-height">Brief</h4>
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
                    <div class="uk-card-footer uk-text-center">
                        <a class="uk-button uk-button-secondary" href="#modalupdatepro<?= $project['id'] ?>" uk-toggle><?= lang('Global.updateData') ?></a>
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
                <div class="uk-card-header">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <span uk-icon="icon: home; ratio: 1.5"></span>
                        </div>
                        <div class="uk-width-expand">
                            <h3 class="tm-h1">
                                <?php foreach ($clients as $client) {
                                    if ($client['id'] === $project['clientid']) {
                                        if ($client['parent'] != null) {
                                            echo $client['username'] . " cabang";
                                        } else {
                                            echo $client['username'] . " pusat";
                                        }
                                    }
                                }
                                ?>
                                <span class="tm-h2"> - <?= $project['name'] ?></span>
                            </h3>
                        </div>
                        <div class="uk-text-right uk-width-auto">
                            <a class="uk-button uk-button-secondary uk-margin-small-right" href="#modalupdatepro<?= $project['id'] ?>" uk-toggle><?= lang('Global.updateData') ?></a>
                        </div>
                    </div>
                </div>
                <div class="uk-card-body">
                    <h4 class="tm-h1">Brief</h4>
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

        <!-- update project modal -->
        <div id="modalupdatepro<?= $project['id'] ?>" uk-modal>
            <div class="uk-modal-dialog" uk-overflow-auto>
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header uk-margin">
                    <h2 class="uk-modal-title"><?=lang('Global.editProject')?></h2>
                </div>
                <div class="uk-modal-body">
                    <form class="uk-margin-left" action="project/update/<?= $project['id'] ?>" method="post">
                        <div class="uk-margin">
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                                <input class="uk-input uk-form-width-large" name="name" value="<?= $project['name'] ?>" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline">
                                <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" aria-label="Brief"><?= $project['brief'] ?></textarea>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-form-controls uk-form-width-large">
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
                        <script>
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
                        </script>
                        <div class="uk-margin" id="proqty<?= $project['id'] ?>" hidden>
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                                <input class="uk-input uk-form-width-large" name="qty" value="<?= $project['production'] ?>" placeholder="Prosentase Produksi" type="number" max="100" min="0" aria-label="Not clickable icon">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-form-controls uk-form-width-large">
                                <select class="uk-select" name="client" id="client">
                                    <?php foreach ($clients as $user) {
                                        $client = "";
                                        if ($user['parent'] != "" && !empty($user['parent'])) {
                                            foreach ($parent as $parentid) {
                                                if ($user['parent'] === $parentid['id']) {
                                                    $client = $parentid['name'];
                                                }
                                            }
                                        } else {
                                            $client = "-";
                                        } ?>
                                    <?php if (!empty($client)) {
                                            echo $client;
                                        }
                                    } ?>
                                    <option value="" selected disabled>
                                        Pilih Client
                                    </option>
                                    <?php foreach ($clients as $client) {
                                        if ($client['role'] === "client pusat") {
                                            $klien = $client['username'] . " pusat";
                                        } else {
                                            $klien = $client['username'] . " cabang";
                                        } ?>
                                        <option value="<?= $client['id'] ?>" <?= ($project['clientid'] === $client['id'] ? 'selected' : '') ?>> <?= $klien  ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-modal-footer uk-text-right">
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                            <a class="uk-button uk-button-danger" href="project/delete/<?= $project['id'] ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')" type="button">Delete</a>
                            <button class="uk-button uk-button-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end update project modal -->
    <?php } ?>
    <?= $pager->links('projects', 'uikit_full') ?>
</div>

<?= $this->endSection() ?>