<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>


<!-- Page Heading -->
<?php if ($ismobile === false) { ?>
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
<?php } else { ?>
    <h3 class="tm-h3 uk-text-center"><?= lang('Global.clientList') ?></h3>
    <div class="uk-child-width-auto uk-flex-center" uk-grid>
        <div>
            <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
        </div>
        <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
            <div>
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata"><?= lang('Global.Addclient') ?></button>
            </div>
        <?php } ?>
    </div>
<?php } ?>

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
                                if ($authorize->inGroup(['superuser', 'admin'], $uid) === true) {
                                    $position = array("client pusat", "client cabang");
                                    if ((in_array($role->name, $position))) {
                                        echo '<label><input class="uk-checkbox" name="parent" id="' . $role->name . '" value="' . $role->id . '" type="checkbox"> ' . $role->name . '</label>';
                                    }
                                }
                            } ?>
                        </div>
                    </div>

                    <div class="uk-margin" id="parent" hidden>
                        <label class="uk-form-label" for="child">Choose <?= lang('Global.center') ?></label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="child">
                                <option value="" selected disabled>Center List</option>
                                <?php
                                foreach ($users as $user) {
                                    if ($user->role != 'client cabang') {
                                        echo '<option value="' . $user->id . '">' . $user->username . '</option>';
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

<!-- form input -->
<?php if ($ismobile === false) { ?>
    <form class="uk-margin" id="searchform" action="users/client" method="GET">
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
        <form id="searchform" action="users/client" method="GET">
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
<!-- form input -->

<!-- script form -->
<script>
    document.getElementById('rolesearch').addEventListener("change", submitform);
    document.getElementById('search').addEventListener("change", submitform);
    document.getElementById('perpage').addEventListener("change", submitform);

    function submitform() {
        document.getElementById('searchform').submit();
    };
</script>
<!-- end script form -->

<!-- Table Of Content -->
<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-justify uk-table-middle uk-table-divider" id="example" style="width:100%">
        <thead>
            <tr>
                <th class="uk-width-large"><?= lang('Global.name') ?></th>
                <th class="uk-width-medium"><?= lang('Global.email') ?></th>
                <th class="uk-text-center uk-width-medium"><?= lang('Global.access') ?></th>
                <th class="uk-text-center uk-width-medium"><?= lang('Global.center') ?></th>
                <th class="uk-text-center uk-width-large"><?= lang('Global.action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) {
                $client = "";
                if ($user->parent != "" && !empty($user->parent)) {
                    foreach ($parent as $parentid) {
                        if ($user->parent === $parentid['id']) {
                            $client = $parentid['name'];
                        }
                    }
                } else {
                    $client = "-";
                } ?>

                <tr>
                    <td class=""><?= $user->username; ?></td>
                    <td class=""><?= $user->email; ?></td>
                    <td class="uk-text-center"><?= $user->role; ?></td>
                    <td class="uk-text-center"><?= $client; ?></td>
                    <td class="uk-child-width-auto uk-flex-center uk-grid-row-small uk-grid-column-small" uk-grid>
                        <!-- Button Trigger Modal Edit -->
                        <div>
                            <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                        </div>
                        <!-- End Of Button Trigger Modal Edit -->

                        <!-- Button Delete -->
                        <div>
                            <a uk-icon="trash" class="uk-icon-button-delete" href="users/deleteclient/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                        </div>
                        <!-- End Of Button Delete -->
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
                    <h5 class="uk-modal-title" id="editdata"><?= lang('Global.editClient') ?></h5>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="users/update/<?= $user->id ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id<?= $user->id ?>" value="<?= $user->id; ?>">
                        <input type="hidden" name="group_id" value="<?= $user->group_id; ?>">

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="username"><?= lang('Auth.username') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="username" name="username" placeholder="<?= $user->username; ?>" />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="firstname"><?= lang('Global.firstname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="firstname" name="firstname" placeholder="<?= $user->firstname; ?>" />
                            </div>
                        </div>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="lastname"><?= lang('Global.lastname') ?></label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="lastname<?= $user->id; ?>" name="lastname" placeholder="<?= $user->lastname; ?>" />
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
                            <label class="uk-form-label" for="pass_confirm"><?= lang('Global.access') ?></label>
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                <?php foreach ($roles as $role) {
                                    $position = array("client pusat", "client cabang");
                                    if ((in_array($role->name, $position))) {
                                        if ($user->role == $role->name) {
                                            $checked = "checked";
                                        } else {
                                            $checked = "";
                                        }
                                        echo '<label><input class="uk-checkbox" name="parent" id="editrole' . $role->name . $user->id . '" value="' . $role->id . '" type="checkbox" ' . $checked . '> ' . $role->name . '</label>';
                                    }
                                } ?>
                            </div>
                        </div>

                        <div class="uk-margin" id="editparent<?= $user->id ?>" hidden>
                            <label class="uk-form-label" for="child">Choose <?= lang('Global.center') ?></label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="child">
                                    <?php
                                    foreach ($users as $parent) {
                                        if ($parent->role != 'client cabang') { ?>
                                            <option value="<?= $parent->id ?>" <?php if ($parent->id === $user->parent) {echo 'selected';} ?>><?= $parent->username ?></option>
                                        <?php }
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

<?php foreach ($users as $user) { ?>
    <script>
        $(document).ready(function() {

            if ($("input[id='editroleclient cabang<?= $user->id ?>']").is(':checked')) {
                $("#editparent<?= $user->id ?>").removeAttr("hidden");
            }

            $("input[id='editroleclient cabang<?= $user->id ?>']").change(function() {
                if ($(this).is(':checked')) {
                    $("input[id='editroleclient pusat<?= $user->id ?>']").prop("checked", false);
                    $("#editparent<?= $user->id ?>").removeAttr("hidden");
                } else {
                    $("#editparent<?= $user->id ?>").attr("hidden", true);
                }
            });

            $("input[id='editroleclient pusat<?= $user->id ?>']").click(function() {
                $("input[id='editroleclient cabang<?= $user->id ?>']").prop("checked", false);
                $("#editparent<?= $user->id ?>").attr("hidden", true);
            });

        });
    </script>
<?php } ?>
<?= $this->endSection() ?>