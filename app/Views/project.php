<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="uk-child-width-expand@m uk-text-right uk-margin-bottom" uk-grid>
    <div>
        <div class="uk-margin">
            <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Add Proyek</button>
        </div>
    </div>
</div>

<!-- add project modal -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Add Project</h2>
        </div>

        <form class="uk-margin-left" action="project/create" method="post">

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="Name" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" aria-label="Brief"></textarea>
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-form-controls uk-form-width-large">
                    <select class="uk-select" name="status" id="status">
                        <option value="" selected disabled>Pilih Progres</option>
                        <option value="1">Proses Desain</option>
                        <option value="2">Menunggu Approval</option>
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
                    <input class="uk-input uk-form-width-large" name="qty" placeholder="qty" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-form-controls uk-form-width-large">
                    <select class="uk-select" name="client" id="client">
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

<!-- <div class="uk-child-width-1-2@s" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-card-hover uk-card-body">

            <div class="uk-text-center" uk-grid>
                <div class="uk-width-1-2 uk-text-left">
                    <h3 class="tm-h1">Rumah Sakit Bayangkari</h3>
                </div>
                <div class="uk-width-1-2 uk-text-right">
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdatepro" uk-icon="pencil" uk-toggle></a>
                </div>
            </div>
            <hr>

            <div class="uk-panel">
                <p class="tm-h3">Brief</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab dolores dignissimos inventore doloremque nam soluta dolorem expedita ratione. Quos reiciendis corrupti vitae? Eius amet eaque sequi dolorem aspernatur perspiciatis et.</p>
                <hr class="uk-divider-icon">
                <div class="uk-grid-divider uk-child-width-1-3@s" uk-grid>
                    <div>
                        <div class="uk-inline">
                            <h3 class="tm-h4"><span uk-icon="icon: user; ratio: 1"></span> Client</h3>
                            <p>Rumah Sakit Bayangkari</p>
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <h3 class="tm-h4"><span uk-icon="icon: list; ratio: 1"></span> Quantity</h3>
                            <p>50/100</p>
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <h3 class="tm-h4"><span uk-icon="icon: future; ratio: 1"></span> Progress</h3>
                            <p>50%</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> -->


<?php foreach ($projects as $project) { ?>
    <div class="uk-child-width-1-1@m uk-grid-match uk-margin" uk-grid>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body">

                <div class="uk-text-center" uk-grid>
                    <div class="uk-width-1-2 uk-text-left">
                        <h3 class="tm-h1"><?= $project['name'] ?></h3>
                    </div>
                    <div class="uk-width-1-2 uk-text-right">
                        <a class="uk-icon-button  uk-margin-small-right" href="#modalupdatepro<?= $project['id'] ?>" uk-icon="pencil" uk-toggle></a>
                    </div>
                </div>
                <hr>

                <div class="uk-panel">
                    <p class="tm-h3">Brief</p>
                    <p><?= $project['brief'] ?></p>
                    <hr class="uk-divider-icon">
                    <div class="uk-grid-divider uk-child-width-1-3@s" uk-grid>
                        <div>
                            <div class="uk-inline">
                                <h3 class="tm-h4"><span uk-icon="icon: user; ratio: 1"></span> Client</h3>
                                <p>
                                    <?php foreach ($clients as $user) {
                                        if ($user['id'] == $project['clientid']) {
                                            $clientname = $user['username'];
                                            foreach ($parent as $idParent) {
                                                if ($user['id'] === $idParent) {
                                                    $name[] = [
                                                        'id'    => $user['id'],
                                                        'name'  => $user['username'],
                                                    ];
                                                }
                                            }
                                            if ($user['parent'] != "") {
                                                foreach ($name as $parentname) {
                                                    if ($user['parent'] === $parentname['id']) {
                                                        $client = "Cabang " . $parentname['name'];
                                                    }
                                                }
                                            } else {
                                                $client = $clientname . " pusat";
                                            }
                                        }
                                    } ?>
                                    <?= $client ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="">
                                <h3 class="tm-h4"><span uk-icon="icon: list; ratio: 1"></span> Quantity</h3>
                                <p><?= $project['production'] ?>/100</p>
                            </div>
                        </div>
                        <div>
                            <div class="">
                                <h3 class="tm-h4"><span uk-icon="icon: future; ratio: 1"></span> Progress</h3>
                                <p>
                                    <?php $persentasi = round($project['production'] / 100 * 100, 2);
                                    echo "$persentasi %"; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- update project modal -->
    <div id="modalupdatepro<?= $project['id'] ?>" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>

            <div class="uk-modal-header uk-margin">
                <h2 class="uk-modal-title">Update Project</h2>
            </div>

            <form class="uk-margin-left" action="project/update/<?= $project['id'] ?>" method="post">

                <div class="uk-margin">
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                        <input class="uk-input uk-form-width-large" name="name" value="<?= $project['name'] ?>" placeholder="Name" type="text" aria-label="Not clickable icon">
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-inline">
                        <input class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" value="<?= $project['brief'] ?>" aria-label="Brief"></input>
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-form-controls uk-form-width-large">
                        <select class="uk-select" name="status" id="status<?= $project['id'] ?>">
                            <option value="" selected disabled>Pilih Progres</option>
                            <option value="1">Proses Desain</option>
                            <option value="2">Menunggu Approval</option>
                            <option value="3">Pengajuan RAB</option>
                            <option value="4">Dalam Proses Produksi</option>
                            <option value="5">Setting</option>
                        </select>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $("#status<?= $project['id'] ?>").change(function() {
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
                        <input class="uk-input uk-form-width-large" name="qty" value="<?= $project['production'] ?>" placeholder="qty" type="number" aria-label="Not clickable icon">
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-form-controls uk-form-width-large">
                        <select class="uk-select" name="client" id="client">
                            <?php foreach ($clients as $user) {
                                if ($user['id'] == $project['clientid']) {
                                    $clientname = $user['username'];
                                    foreach ($parent as $idParent) {
                                        if ($user['id'] === $idParent) {
                                            $name[] = [
                                                'id'    => $user['id'],
                                                'name'  => $user['username'],
                                            ];
                                        }
                                    }
                                    if ($user['parent'] != "") {
                                        foreach ($name as $parentname) {
                                            if ($user['parent'] === $parentname['id']) {
                                                $client =  $parentname['name'] . " cabang";
                                            }
                                        }
                                    } else {
                                        $client = $clientname . " pusat";
                                    }
                                }
                            } ?>
                            <option value="" selected disabled>  <?= $client ?></option>
                            <?php foreach ($clients as $client) {
                                if ($client['role'] === "client pusat") {
                                    $klien = $client['username'] . " pusat";
                                } else {
                                    $klien = $client['username'] . " cabang";
                                } ?>
                                <option value="<?= $client['id'] ?>"> <?= $klien  ?></option>
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
    <!-- end update project modal -->
<?php } ?>

<!-- update progress modal -->
<div id="updaterab" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title"> Update Progress</h2>
        </div>

        <form class="uk-margin-left" action="bar/update/1" method="post">

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: pencil"></span>
                    <input class="uk-input" id="qty" name="qty" placeholder="Quantity" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <a class="uk-button uk-button-danger" href="project/delete" type="button">Delete</a>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end update progress modal -->

<?= $this->endSection() ?>