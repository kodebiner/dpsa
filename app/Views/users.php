<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Page Heading -->
<?php if ($ismobile === false) { ?>
    <div class="tm-card-header uk-margin-remove-left">
        <div uk-grid class="uk-flex-middle">
            <div class="uk-width-1-2@m">
                <h3 class="tm-h3"><?= lang('Global.usersList') ?></h3>
            </div>

            <!-- Button Trigger Modal Add -->
            <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
                <div class="uk-width-1-2@m uk-text-right@m">
                    <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata"><?= lang('Global.Adduser') ?></button>
                </div>
            <?php } ?>
            <!-- End Of Button Trigger Modal Add -->
        </div>
    </div>
<?php } else { ?>
    <h3 class="tm-h3 uk-text-center"><?= lang('Global.usersList') ?></h3>
    <div class="uk-child-width-auto uk-flex-center" uk-grid>
        <div>
            <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
        </div>
        <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
            <div>
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata"><?= lang('Global.Adduser') ?></button>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<!-- End Of Page Heading -->

<?= view('Views/Auth/_message_block') ?>

<!-- Modal Add -->
<?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
    <div uk-modal class="uk-flex-top" id="tambahdata">
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <div class="uk-modal-content">
                <div class="uk-modal-header">
                    <h5 class="uk-modal-title" id="tambahdata"><?= lang('Global.Adduser') ?></h5>
                </div>
                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="users/create" method="post">
                        <?= csrf_field() ?>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="username"><?= lang('Auth.username') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input <?php if (session('errors.username')) : ?>tm-form-invalid<?php endif ?>" id="username" name="username" placeholder="<?= lang('Auth.username') ?>" autofocus required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="email"><?= lang('Auth.email') ?></label>
                            <div class="uk-form-controls">
                                <input type="email" name="email" id="email" placeholder="<?= lang('Auth.email') ?>" required class="uk-input <?php if (session('errors.email')) : ?>tm-form-invalid<?php endif ?>" />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="firstname"><?= lang('Global.firstname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input <?php if (session('errors.firstname')) : ?>tm-form-invalid<?php endif ?>" id="firstname" name="firstname" placeholder="<?= lang('Global.firstname') ?>" autofocus required />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="lastname"><?= lang('Global.lastname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input <?php if (session('errors.lastname')) : ?>tm-form-invalid<?php endif ?>" id="lastname" name="lastname" placeholder="<?= lang('Global.lastname') ?>" autofocus required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="password"><?= lang('Auth.password') ?></label>
                            <div class="uk-form-controls">
                                <input type="password" name="password" id="password" required class="uk-input <?php if (session('errors.password')) : ?>tm-form-invalid<?php endif ?>" />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                            <div class="uk-form-controls">
                                <input type="password" name="pass_confirm" id="pass_confirm" required class="uk-input <?php if (session('errors.repeatPassword')) : ?>tm-form-invalid<?php endif ?>" />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="role"><?= lang('Global.access') ?></label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="role" required>
                                    <option value="" selected disabled>Role</option>
                                    <?php
                                    foreach ($roles as $role) {
                                        if ($authorize->inGroup('admin', $uid) === true) {
                                            $position = array('owner', 'superuser', 'guests');
                                            if ((!in_array($role->name, $position))) {
                                                echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                            }
                                        } elseif ($authorize->inGroup('owner', $uid) === true) {
                                            $position = array('superuser', 'guests');
                                            if ((!in_array($role->name, $position))) {
                                                echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                            }
                                        } elseif ($authorize->inGroup('superuser', $uid) === true) {
                                            echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div class="uk-margin">
                            <button type="submit" class="uk-button uk-button-primary"><?= lang('Global.save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- End Of Modal Add -->

<!-- Table Of Content -->
<?php if ($ismobile === false) { ?>
    <form class="uk-margin" id="searchform" action="users" method="GET">
        <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
            <div>
                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                    <div><?= lang('Global.search') ?>:</div>
                    <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                    <div>
                        <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                            <option value="0"><?= lang('Global.allAccess') ?></option>
                            <?php
                            foreach ($roles as $role) {
                                if (isset($input['rolesearch']) && ($input['rolesearch'] === $role->id)) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                if ($authorize->inGroup('admin', $uid) === true) {
                                    $position = array('owner', 'superuser', 'guests');
                                    if ((!in_array($role->name, $position))) {
                                        echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                    }
                                } elseif ($authorize->inGroup('owner', $uid) === true) {
                                    $position = array('superuser', 'guests');
                                    if ((!in_array($role->name, $position))) {
                                        echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                    }
                                } elseif ($authorize->inGroup('superuser', $uid) === true) {
                                    echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                    <div><?= lang('Global.display') ?></div>
                    <div>
                        <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                            <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                            <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                            <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                            <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                        </select>
                    </div>
                    <div><?= lang('Global.perPage') ?></div>
                </div>
            </div>
        </div>
    </form>
<?php } else { ?>
    <div id="filter" class="uk-margin" hidden>
        <form id="searchform" action="users" method="GET">
            <div class="uk-margin-small uk-flex uk-flex-center">
                <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
            </div>
            <div class="uk-margin-small uk-flex uk-flex-center">
                <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                    <option value="0"><?= lang('Global.allAccess') ?></option>
                    <?php
                    foreach ($roles as $role) {
                        if (isset($input['rolesearch']) && ($input['rolesearch'] === $role->id)) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        if ($authorize->inGroup('admin', $uid) === true) {
                            $position = array('owner', 'superuser', 'guests');
                            if ((!in_array($role->name, $position))) {
                                echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                            }
                        } elseif ($authorize->inGroup('owner', $uid) === true) {
                            $position = array('superuser', 'guests');
                            if ((!in_array($role->name, $position))) {
                                echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                            }
                        } elseif ($authorize->inGroup('superuser', $uid) === true) {
                            echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                <div><?= lang('Global.display') ?></div>
                <div>
                    <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                        <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                        <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                        <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                        <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                    </select>
                </div>
                <div><?= lang('Global.perPage') ?></div>
            </div>
        </form>
    </div>
<?php } ?>

<script>
    document.getElementById('rolesearch').addEventListener("change", submitform);
    document.getElementById('search').addEventListener("change", submitform);
    document.getElementById('perpage').addEventListener("change", submitform);

    function submitform() {
        document.getElementById('searchform').submit();
    };
</script>

<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-middle uk-table-large uk-table-hover uk-table-divider">
        <thead>
            <tr>
                <th><?= lang('Global.name') ?></th>
                <th><?= lang('Auth.username') ?></th>
                <th><?= lang('Auth.email') ?></th>
                <th><?= lang('Global.access') ?></th>
                <th class="uk-width-small uk-text-center"><?= lang('Global.action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <?php if (($authorize->inGroup('superuser', $uid) === true) && ($user->role === 'guests')) { ?>
                    <tr>
                        <td><?= $user->firstname; ?> <?= $user->lastname; ?></td>
                        <td><?= $user->username; ?></td>
                        <td><?= $user->email; ?></td>
                        <td><?= $user->role; ?></td>
                        <td class="uk-child-width-auto uk-grid-small uk-flex-center" uk-grid>
                            <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
                                <div>
                                    <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                                </div>
                            <?php } ?>
                            <?php if ($authorize->hasPermission('admin.user.delete', $uid)) { ?>
                                <div>
                                    <a uk-icon="trash" class="uk-icon-button-delete" href="users/delete/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } elseif ($user->role !== 'guests') { ?>
                    <tr>
                        <td><?= $user->firstname; ?> <?= $user->lastname; ?></td>
                        <td><?= $user->username; ?></td>
                        <td><?= $user->email; ?></td>
                        <td><?= $user->role; ?></td>
                        <td class="uk-child-width-auto uk-grid-small uk-flex-center" uk-grid>
                            <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
                                <div>
                                    <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                                </div>
                            <?php } ?>
                            <?php if ($authorize->hasPermission('admin.user.delete', $uid)) { ?>
                                <div>
                                    <a uk-icon="trash" class="uk-icon-button-delete" href="users/delete/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<?= $pager ?>
<!-- End Of Table Content -->

<!-- Modal Edit -->
<?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
    <?php foreach ($users as $user) { ?>
        <div uk-modal class="uk-flex-top" id="editdata<?= $user->id ?>">
            <div class="uk-modal-dialog uk-margin-auto-vertical">
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <h5 class="uk-modal-title" id="editdata"><?= lang('Global.editUser') ?></h5>
                    </div>

                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" role="form" action="users/update/<?= $user->id ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $user->id; ?>">
                            <input type="hidden" name="group_id" value="<?= $user->group_id; ?>">

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="username"><?= lang('Auth.username') ?></label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="username" name="username" value="<?= $user->username; ?>" autofocus />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="firstname"><?= lang('Global.firstname') ?></label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="firstname" name="firstname" value="<?= $user->firstname; ?>" autofocus />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="lastname"><?= lang('Global.lastname') ?></label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="lastname" name="lastname" value="<?= $user->lastname; ?>" autofocus />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="email"><?= lang('Auth.email') ?></label>
                                <div class="uk-form-controls">
                                    <input type="email" class="uk-input" id="email" name="email" value="<?= $user->email; ?>" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <button class="uk-button uk-button-default" type="button" uk-toggle="target: .reset-password"><?= lang('Auth.resetPassword') ?></button>
                            </div>

                            <div class="uk-margin reset-password" hidden>
                                <label class="uk-form-label" for="password"><?= lang('Auth.password') ?></label>
                                <div class="uk-form-controls">
                                    <input type="password" class="uk-input" name="password" id="password" placeholder="<?= lang('Auth.password') ?>" autocomplete="off" />
                                </div>
                            </div>

                            <div class="uk-margin reset-password" hidden>
                                <label class="uk-form-label" for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                <div class="uk-form-controls">
                                    <input type="password" class="uk-input" name="pass_confirm" id="pass_confirm" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="role"><?= lang('Global.access') ?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" name="role" required>
                                        <?php
                                        foreach ($roles as $role) {
                                            if ($user->group_id === $role->id) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            if ($authorize->inGroup('admin', $uid) === true) {
                                                $position = array('owner', 'superuser', 'guests');
                                                if ((!in_array($role->name, $position))) {
                                                    echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                                }
                                            } elseif ($authorize->inGroup('owner', $uid) === true) {
                                                $position = array('superuser', 'guests');
                                                if ((!in_array($role->name, $position))) {
                                                    echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                                }
                                            } elseif ($authorize->inGroup('superuser', $uid) === true) {
                                                echo '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="uk-margin">
                                <button type="submit" class="uk-button uk-button-primary"><?= lang('Global.save') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<!-- End Of Modal Edit -->
<?= $this->endSection() ?>