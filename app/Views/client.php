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
            <h3 class="tm-h3"><?= lang('Global.clientList') ?></h3>
        </div>

        <!-- Button Trigger Modal Add -->
        <div class="uk-width-1-2@m uk-text-right@m">
            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata"><?= lang('Global.Addclient') ?></button>
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
                <h5 class="uk-modal-title" id="tambahdata"><?= lang('Global.Addclient') ?></h5>
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
                        <label class="uk-form-label" for="pass_confirm"><?= lang('Global.access') ?></label>
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                            <?php foreach ($roles as $role) {
                                if ($authorize->inGroup('superuser', $uid) === true) {
                                    $position = array("client pusat", "client cabang");
                                    if ((in_array($role->name, $position))) {
                                        echo '<label><input class="uk-checkbox" name="role" id="' . $role->name . '" value="' . $role->id . '" type="checkbox"> ' . $role->name . '</label>';
                                    }
                                }
                            } ?>
                        </div>
                    </div>

                    <div class="uk-margin" id="parent" hidden>
                        <label class="uk-form-label" for="parent"><?= lang('Global.access') ?></label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="parent">
                                <option value="" selected disabled>Role</option>
                                <?php
                                foreach ($users as $user) {
                                        if ($authorize->inGroup('superuser', $uid) === true) {
                                            if ($user->role !='client cabang') {
                                                echo '<option value="' . $user->id . '">' . $user->username . '</option>';
                                            }
                                        }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $("input[id='client cabang']").change(function() {
                                if ($(this).is(':checked')) {
                                    $("input[id='client pusat']").prop("checked", false);
                                    $("#parent").removeAttr("hidden");
                                } else {
                                    $("#parent").attr("hidden", true);
                                }
                            });
                            $("input[id='client pusat']").click(function() {
                                $("input[id='client cabang']").prop("checked", false);
                                $("#parent").attr("hidden", true);
                            });
                        });
                    </script>

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
    <table class="uk-table uk-table-justify uk-table-middle uk-table-divider" id="example" style="width:100%">
        <thead>
            <tr>
                <th class="uk-text-center uk-width-small">No</th>
                <th class="uk-width-large"><?= lang('Global.name') ?></th>
                <th class="uk-width-medium"><?= lang('Global.email') ?></th>
                <th class="uk-width-medium"><?= lang('Global.access') ?></th>
                <th class="uk-width-medium"><?= lang('Global.center') ?></th>
                <th class="uk-text-center uk-width-large"><?= lang('Global.action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td class="uk-text-center"><?= $i++; ?></td>
                    <td class=""><?= $user->username; ?></td>
                    <td class=""><?= $user->email; ?></td>
                    <td class=""><?= $user->role; ?></td>

                    <?php
                    $userid[] = $user->id;
                    if ($user->parent != "") {
                        $center = "Cabang ";
                    } else {
                        $center = "Pusat";
                    } ?>
                    <td class=""><?= $center; ?></td>
                    <td class="uk-child-width-auto uk-flex-center uk-grid-row-small uk-grid-column-small" uk-grid>
                        <!-- Button Trigger Modal Edit -->
                        <div>
                            <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                        </div>
                        <!-- End Of Button Trigger Modal Edit -->

                        <!-- Button Delete -->
                        <div>
                            <a uk-icon="trash" class="uk-icon-button-delete" href="users/delete/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                        </div>
                        <!-- End Of Button Delete -->
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
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
                                <input type="text" class="uk-input" id="username" name="username" placeholder="<?= $user->username; ?>" autofocus />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="firstname"><?= lang('Global.firstname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="firstname" name="firstname" placeholder="<?= $user->firstname; ?>" autofocus />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="lastname"><?= lang('Global.lastname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="lastname" name="lastname" placeholder="<?= $user->lastname; ?>" autofocus />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="email"><?= lang('Auth.email') ?></label>
                            <div class="uk-form-controls">
                                <input type="email" class="uk-input" id="email" name="email" placeholder="<?= $user->email; ?>" />
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
                                    <option value="" selected disabled>Role</option>
                                    <?php foreach ($roles as $role) {
                                        if ($authorize->inGroup('Admin', $uid) === true) {
                                            if ($role->name == 'client') {
                                                echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                                            }
                                        }
                                    } ?>
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