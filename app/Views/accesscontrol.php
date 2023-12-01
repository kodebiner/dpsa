<?= $this->extend('layout') ?>
<?= $this->section('main') ?>
<?php if ($ismobile === false) { ?>
    <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
        <div>
            <h1>Hak Akses</h1>
        </div>
        <div>
            <a class="uk-button uk-button-large uk-button-secondary" href="#modaladd" uk-toggle>Tambah Hak Akses</a>
        </div>

        <div id="modaladd" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Add Permissions</h2>
                </div>
                <form method="post" action="users/create/access-control">
                    <div class="uk-modal-body">
                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="group" placeholder="Groups" aria-label="Input">
                        </div>

                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="description" placeholder="Description" aria-label="Input">
                        </div>

                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <?php foreach ($permissions as $permission) { ?>
                                <label><input class="uk-checkbox" name="permission[<?= $permission['id'] ?>]" value="<?= $permission['id'] ?>" type="checkbox"> <?= $permission['name'] ?></label>
                            <?php } ?>
                        </div>
                        <div class="uk-modal-footer uk-text-right">
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                            <button class="uk-button uk-button-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <?= view('Views/Auth/_message_block') ?>
<?php } else { ?>
    <h1 class="uk-text-center">Hak Akses</h1>
    <div class="uk-margin uk-text-center"><button class="uk-button uk-button-large uk-button-secondary">Tambah Hak Akses</button></div>
<?php } ?>
<div class="uk-child-width-1-3@m" uk-grid uk-height-match="target: > div > .uk-card > .uk-card-body">
    <?php foreach ($groups as $group) { ?>
        <div>
            <div class="uk-card uk-card-default uk-card-hover">
                <div class="uk-card-header">
                    <h3 class="uk-card-title"><?= $group->name; ?></h3>
                </div>
                <div class="uk-card-body">
                    <div class="uk-child-width-auto uk-grid-collapse" uk-grid>
                        <?php
                        $permissionlists = $GroupModel->getPermissionsForGroup($group->id);
                        foreach ($permissionlists as $permissionlist) {
                            echo '<div>';
                            echo '<span class="uk-badge uk-margin-small-right">' . $permissionlist->name . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php
                if (!in_array($group->name, $grouparr)) { ?>
                    <div class="uk-card-footer">
                        <div class="uk-child-width-auto" uk-grid>
                            <div><a class="uk-button uk-button-primary" uk-icon="pencil" href="#modalupdate<?= $group->id; ?>" uk-toggle></a></div>
                            <div><a class="uk-button uk-button-danger" href="users/delete/access-control/<?= $group->id; ?>" onClick="return confirm('<?= lang('Global.deleteConfirm') ?>')" uk-icon="trash"></a></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- update permission modal -->
        <div id="modalupdate<?= $group->id; ?>" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <button class="uk-modal-close-default" type="button" uk-close></button>

                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Update Permissions</h2>
                </div>

                <div class="uk-modal-body">
                    <form method="post" action="users/update/access/<?= $group->id; ?>">

                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="name" value="<?= $group->name; ?>" aria-label="Input">
                            <input class="uk-input" type="number" name="groupid" value="<?= $group->id; ?>" aria-label="Input" hidden>
                        </div>

                        <div class="uk-margin">
                            <input class="uk-input" type="text" name="description" value="<?= $group->description; ?>" aria-label="Input">
                        </div>

                        <div class="uk-margin">
                            <?php
                            foreach ($permissions as $permission) {
                                $permarr = [];
                                $grouppermissions = $GroupModel->getPermissionsForGroup($group->id);
                                foreach ($grouppermissions as $grouppermission) {
                                    $permarr[] = $grouppermission->id;
                                }
                                if (in_array($permission['id'], $permarr)) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                            ?>
                                <div>
                                    <label><input class="uk-checkbox" name="permission[]" value="<?= $permission['id'] ?>" type="checkbox" <?= $checked ?>> <?= $permission['description'] ?></label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="uk-modal-footer uk-text-right">
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                            <button class="uk-button uk-button-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end update permission modal  -->

    <?php } ?>
</div>
<?= $this->endSection() ?>