
<?= $this->extend('layout') ?>
<?= $this->section('main') ?>

<!-- Page Heading -->
<div class="tm-card-header uk-margin-remove-left">
    <div uk-grid class="uk-flex-middle">
        <div class="uk-width-1-2@m">
            <h3 class="tm-h3"><?=lang('Global.mdlList')?></h3>
        </div>

        <!-- Button Trigger Modal Add -->
        <div class="uk-width-1-2@m uk-text-right@m">
            <button class="uk-button uk-button-primary uk-border-rounded" href="#modaladd" aria-label="Project" uk-toggle>Add Product</button>
        </div>
        <!-- End Of Button Trigger Modal Add -->
    </div>
</div>
<!-- End Of Page Heading -->

<!-- Table Of Content -->
<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>no</th>
                <th>name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($mdls as $mdl) { ?>
                <tr>
                    <td><?= $i++?></td>
                    <td><?= $mdl['name'] ?></td>
                    <td><?= $mdl['price'] ?></td>
                    <td><a class="uk-icon-button" href="#modalupdate<?=$mdl['id']?>" uk-icon="pencil" uk-toggle></a></td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<!-- End Table Of Content -->

<!-- add project modal -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Add Project</h2>
        </div>

        <form class="uk-margin-left" action="mdl/create" method="post">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="Name" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: database"></span>
                    <input class="uk-input uk-form-width-large" name="price" placeholder="price" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-danger uk-modal-close" type="button">Delete</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>
           
        </form>

    </div>
</div>
<!-- end add project modal -->

<!-- update project modal -->
<?php foreach ($mdls as $mdl) { ?>
<div id="modalupdate<?=$mdl['id']?>" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Update Mdl</h2>
        </div>

        <form class="uk-margin-left" action="mdl/update/<?=$mdl['id']?>" method="post">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="<?=$mdl['name']?>" value="<?=$mdl['name']?>" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: database"></span>
                    <input class="uk-input uk-form-width-large" name="price" placeholder="<?=$mdl['price']?>" value="<?=$mdl['price']?>" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer">
                <div class="uk-flex">
                    <div class="uk-width-1-2@m uk-text-left">
                        <a uk-icon="trash" class="uk-icon-button-delete uk-button-danger" methode="post" href="mdl/delete/<?=$mdl['id']?>" onclick="return confirm('<?=lang('Global.deleteConfirm')?>')"></a>
                    </div>
                    <div class="uk-width-1-2@m uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                    </div>
                </div>
                
               
            </div>
           
        </form>

    </div>
</div>
<?php } ?>
<!-- end update project modal -->

<?= $this->endSection() ?>