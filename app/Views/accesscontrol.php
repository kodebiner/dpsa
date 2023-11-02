<?= $this->extend('layout') ?>
<?= $this->section('main') ?>
<?php if ($ismobile === false) { ?>
    <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
        <div>
            <h1>Hak Akses</h1>
        </div>
        <div>
            <a class="uk-button uk-button-large uk-button-secondary"  href="#modal-sections" uk-toggle>Tambah Hak Akses</a>
        </div>
            <div id="modal-sections" uk-modal>
                <div class="uk-modal-dialog">
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Add Permissions</h2>
                    </div>
                    <div class="uk-modal-body">
                    <form method="post" href="users/create/access-control">
                        <fieldset class="uk-fieldset">

                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="group" placeholder="Groups" aria-label="Input">
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="description" placeholder="Description" aria-label="Input">
                            </div>
                            
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <?php foreach ($permissions as $permission){?>
                                    <label><input class="uk-checkbox" name="permission[<?=$permission->id?>]" value="<?=$permission->id?>" type="checkbox"> <?=$permission->name?></label>
                               <?php } ?>
                            </div>

                        </fieldset>
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                        <button class="uk-button uk-button-primary" type="submit">Save</button>
                    </div>
                </form>
                </div>
            </div>
    </div>
<?php } else { ?>
    <h1 class="uk-text-center">Hak Akses</h1>
    <div class="uk-margin uk-text-center"><button class="uk-button uk-button-large uk-button-secondary">Tambah Hak Akses</button></div>
<?php } ?>
<div class="uk-child-width-1-3@m" uk-grid>
    <?php foreach ($groups as $group) { ?>
        <div>
            <div class="uk-card uk-card-default uk-card-hover">
                <div class="uk-card-body">
                    <h3><?=$group->name;?></h3>
                </div>
                <div class="uk-card-footer">
                    <div class="uk-child-width-auto" uk-grid>
                        <div><a class="uk-button uk-button-primary" uk-icon="pencil"></a></div>
                        <div><a class="uk-button uk-button-danger" uk-icon="trash"></a></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?= $this->endSection() ?>