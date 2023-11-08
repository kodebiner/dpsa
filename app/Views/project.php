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

            <div class="uk-margin" id="editparent">
                <div class="uk-form-controls uk-form-width-large">
                    <select class="uk-select" name="parent">
                        <option value="" selected disabled>Pilih Progres</option>
                        <option value="1">Proses Desain</option>
                        <option value="2">Menunggu Approval</option>
                        <option value="3">Pengajuan RAB</option>
                        <option value="4">Dalam Proses Produksi</option>
                        <option value="5">Setting</option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="qty" placeholder="qty" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div uk-form-custom="target: > * > span:first-child">
                    <select name="client" aria-label="Custom controls">
                        <option value="" disabled>Client</option>
                        <?php foreach ($clients as $client) {?>
                            <option value="<?= $client['id'] ?>"> <?= $client['username'] ?></option>
                        <?php } ?>
                    </select>
                    <button class="uk-button uk-button-default" type="button" tabindex="-1">
                        <span></span>
                        <span uk-icon="icon: chevron-down"></span>
                    </button>
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


<?php foreach ($projects as $project) { ?>
    <div class="uk-child-width-1-1@m uk-grid-match uk-margin" uk-grid>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body">

                <div class="uk-text-center" uk-grid>

                    <div class="uk-width-1-2 uk-text-left">
                        <h3 class="tm-h1">Project <?= $project['name'] ?></h3>
                    </div>

                    <div class="uk-width-1-2 uk-text-right">
                        <a class="uk-icon-button  uk-margin-small-right" href="#modalupdatepro<?= $project['id'] ?>" uk-icon="pencil" uk-toggle></a>
                    </div>

                </div>
                <hr>

                <div class="uk-panel">
                    <p class="uk-h3"> Brief <?= $project['name'] ?> </p>
                    <p><?= $project['brief'] ?></p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>


                <?php foreach ($rabs as $rab) {
                    if ($rab['projectid'] === $project['id']) {
                        foreach ($mdls as $mdl) {
                            if ($mdl['id'] === $rab['mdlid']) { ?>
                                <div class="uk-text-center uk-width-1-1" uk-grid>

                                    <div class="uk-width-1-2 uk-text-left">
                                        <span class="tm-h2"><?= $mdl['name'] ?></span>
                                        <a class="uk-icon-button-default" href="#updaterab" uk-icon="file-edit" uk-toggle></a>
                                    </div>

                                    <div class="uk-width-1-2 uk-text-right">
                                        <span class="tm-h5">
                                            <?php $persent = round($rab['qty_complete'] / $rab['qty'] * 100) ?>
                                            Progress <?= $persent ?> %
                                        </span>
                                    </div>

                                </div>
                        <?php }
                        } ?>
                <?php
                    }
                } ?>
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
                        <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" value="<?= $project['brief'] ?>" aria-label="Brief"></textarea>
                    </div>
                </div>

                <div class="uk-margin">
                    <div uk-form-custom="target: > * > span:first-child">
                        <select name="client" aria-label="Custom controls">
                            <?php if ($project['clientid'] === $client['id']) {
                                echo "<option value=" . $project['clientid'] . ">" . $client['username'] . "</option>";
                            } ?>
                            <?php foreach ($clients as $client) { ?>
                                <option value="<?= $client['id'] ?>"> <?= $client['username'] ?></option>
                            <?php } ?>
                        </select>
                        <button class="uk-button uk-button-default" type="button" tabindex="-1">
                            <span></span>
                            <span uk-icon="icon: chevron-down"></span>
                        </button>
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