<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
<script src="js/cdn.datatables.net_1.13.4_js_jquery.dataTables.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Page Heading -->
<div class="tm-card-header uk-light uk-margin-remove-left">
    <div uk-grid class="uk-flex-middle">
        <div class="uk-width-1-2@m">
            <h3 class="tm-h3"><?= lang('Global.usersList') ?></h3>
        </div>

        <!-- Button Trigger Modal Add -->
        <div class="uk-width-1-2@m uk-text-right@m">
            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata"><?= lang('Global.Adduser') ?></button>
        </div>
        <!-- End Of Button Trigger Modal Add -->
    </div>
</div>
<!-- End Of Page Heading -->

<?= view('Views/Auth/_message_block') ?>

<!-- Modal Add -->
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
<!-- End Of Modal Add -->

<!-- Table Of Content -->
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
                <tr>
                    <td><?= $user->firstname; ?> <?= $user->lastname; ?></td>
                    <td><?= $user->username; ?></td>
                    <td><?= $user->email; ?></td>
                    <td><?= $user->role; ?></td>
                    <td class="uk-child-width-auto uk-grid-small uk-flex-center" uk-grid>
                        <div>
                            <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                        </div>
                        <div>
                            <a uk-icon="trash" class="uk-icon-button-delete" href="users/delete/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?= $pager ?>
<!-- End Of Table Content -->

<!-- Modal Edit -->
<?php foreach ($users as $user) { ?>
    <div uk-modal class="uk-flex-top" id="editdata<?= $user->id ?>">
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <div class="uk-modal-content">
                <div class="uk-modal-header">
                    <h5 class="uk-modal-title" id="editdata"><?= lang('Global.updateData') ?></h5>
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
                                                echo '<option value="' . $role->id . '" '.$selected.'>' . $role->name . '</option>';
                                            }
                                        } elseif ($authorize->inGroup('owner', $uid) === true) {
                                            $position = array('superuser', 'guests');
                                            if ((!in_array($role->name, $position))) {
                                                echo '<option value="' . $role->id . '" '.$selected.'>' . $role->name . '</option>';
                                            }
                                        } elseif ($authorize->inGroup('superuser', $uid) === true) {
                                            echo '<option value="' . $role->id . '" '.$selected.'>' . $role->name . '</option>';
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
<!-- End Of Modal Edit -->

<!-- Search Engine Script -->
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<!-- Search Engine Script End -->
<?= $this->endSection() ?>