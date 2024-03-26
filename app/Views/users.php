<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
    <!-- Page Heading -->
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div uk-grid class="uk-flex-middle">
                <div class="uk-width-1-2@m">
                    <h3 class="tm-h3">Daftar Pengguna</h3>
                </div>

                <!-- Button Trigger Modal Add -->
                <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
                    <div class="uk-width-1-2@m uk-text-right@m">
                        <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Pengguna</button>
                    </div>
                <?php } ?>
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
                    <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Pengguna</button>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- End Of Page Heading -->
    <?= view('Views/Auth/_message_block') ?>

    <!-- Modal Add -->
    <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
        <div uk-modal class="uk-flex-top" id="tambahdata">
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <h5 class="uk-modal-title" id="tambahdata">Tambah Pengguna</h5>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" role="form" action="users/create" method="post">
                            <?= csrf_field() ?>
                            <input id="compid" name="compid" hidden />

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="username">Nama Pengguna</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input <?php if (session('errors.username')) : ?>tm-form-invalid<?php endif ?>" id="username" name="username" placeholder="Nama" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="email">Email</label>
                                <div class="uk-form-controls">
                                    <input type="email" name="email" id="email" placeholder="Email" class="uk-input <?php if (session('errors.email')) : ?>tm-form-invalid<?php endif ?>" />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="firstname">Nama Depan</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input <?php if (session('errors.firstname')) : ?>tm-form-invalid<?php endif ?>" id="firstname" name="firstname" placeholder="Nama Depan" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="lastname">Nama Belakang</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input <?php if (session('errors.lastname')) : ?>tm-form-invalid<?php endif ?>" id="lastname" name="lastname" placeholder="Nama Belakang" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="password">Kata Sandi</label>
                                <div class="uk-form-controls">
                                    <input type="password" name="password" id="password" required class="uk-input <?php if (session('errors.password')) : ?>tm-form-invalid<?php endif ?>" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="pass_confirm">Konfirmasi Kata Sandi</label>
                                <div class="uk-form-controls">
                                    <input type="password" name="pass_confirm" id="pass_confirm" required class="uk-input <?php if (session('errors.repeatPassword')) : ?>tm-form-invalid<?php endif ?>" />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="role">Akses</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="role" name="role" required>
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

                            <div class="uk-margin-bottom" id="marketingcode" hidden>
                                <label class="uk-form-label" for="kodemarketing">Kode Marketing</label>
                                <div class="uk-form-controls">
                                    <input type="text" id="kodemarketing" class="uk-input" id="kodemarketing" name="kodemarketing" placeholder="Kode Marketing" autofocus required />
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $("select[id='role']").change(function() {
                                        if ($('#role').find(":selected").text() === "client pusat") {
                                            $("#pusat").removeAttr("hidden");
                                            $("#kliencabang").attr("hidden", true);
                                            $("#cabang").attr("required", false);
                                            $("#marketingcode").attr("hidden", true);
                                            $("#kodemarketing").attr("required", false);
                                        } else if ($('#role').find(":selected").text() === "client cabang") {
                                            $("#kliencabang").removeAttr("hidden");
                                            $("#pusat").attr("hidden", true);
                                            $("#company").attr("required", false);
                                            $("#marketingcode").attr("hidden", true);
                                            $("#kodemarketing").attr("required", false);
                                        }else if ($('#role').find(":selected").text() === "marketing"){
                                            $("#marketingcode").removeAttr("hidden");
                                            $("#kodemarketing").attr("required", true);
                                            $("#pusat").attr("hidden", true);
                                            $("#kliencabang").attr("hidden", true);
                                            $("#company").val("");
                                            $("#company").attr("required", false);
                                            $("#cabang").attr("required", false);
                                            $("#compid").val("");
                                        } else {
                                            $("#pusat").attr("hidden", true);
                                            $("#kliencabang").attr("hidden", true);
                                            $("#company").val("");
                                            $("#company").attr("required", false);
                                            $("#cabang").attr("required", false);
                                            $("#compid").val("");
                                            $("#marketingcode").attr("hidden", true);
                                            $("#kodemarketing").attr("required", false);
                                        }
                                    });
                                });
                            </script>

                            <div class="uk-margin" id="pusat" hidden>
                                <label class="uk-form-label" for="company">Perusahaan</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="company" name="company" placeholder="Masukkan nama perusahaan yang terdaftar sebagai pusat..." required>
                                </div>

                                <script type="text/javascript">
                                    $(function() {
                                        var company = [
                                            <?php if (!empty($Companys)) {
                                                foreach ($Companys as $comp) {
                                                    if ($comp['parentid'] === "0") {
                                                        $rsklasification = $comp['rsname'] . " (pusat)";
                                                        echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                    }
                                                }
                                            } ?>
                                        ];
                                        $("#company").autocomplete({
                                            source: company,
                                            select: function(e, i) {
                                                $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                                            },
                                            minLength: 2
                                        });
                                    });
                                </script>
                            </div>

                            <div class="uk-margin" id="kliencabang" hidden>
                                <label class="uk-form-label" for="cabang">Perusahaan Cabang</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="cabang" name="cabang" placeholder="Masukkan nama perusahaan yang terdaftar sebagai cabang..." required>
                                </div>

                                <script type="text/javascript">
                                    $(function() {
                                        var cabang = [
                                            <?php if (!empty($Companys)) {
                                                foreach ($Companys as $comp) {
                                                    if ($comp['parentid'] != "0") {
                                                        $rsklasification = $comp['rsname'] . " (cabang)";
                                                        echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                    }
                                                }
                                            } ?>
                                        ];
                                        $("#cabang").autocomplete({
                                            source: cabang,
                                            select: function(e, i) {
                                                $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                                            },
                                            minLength: 2
                                        });
                                    });
                                </script>
                            </div>

                            <hr>

                            <div class="uk-margin">
                                <button type="submit" class="uk-button uk-button-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Of Modal Add -->

    <!-- form input -->
    <?php if ($ismobile === false) { ?>
        <form class="uk-margin" id="searchform" action="users" method="GET">
            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                <div>
                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                        <div>Cari:</div>
                        <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                        <div>
                            <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                                <option value="0">Semua Akses</option>
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
                        <div>Tampilan</div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div>Per Halaman</div>
                    </div>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <div id="filter" class="uk-margin" hidden>
            <form id="searchform" action="users/client" method="GET">
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                </div>
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                        <option value="0">Semua Akses</option>
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
                    <div>Tampilan</div>
                    <div>
                        <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                            <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                            <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                            <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                            <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                        </select>
                    </div>
                    <div>Per Halaman</div>
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
                    <th class="uk-width-large">Nama Pengguna</th>
                    <th class="uk-width-medium">Email</th>
                    <th class="uk-width-medium">Akses</th>
                    <th class="uk-width-medium">Perusahaan</th>
                    <th class="uk-text-center uk-width-medium">Status</th>
                    <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
                        <th class="uk-text-center uk-width-large">Ubah</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) {
                    $client = "";
                    $pusat = "";
                    if (!empty($Companys)) {
                        foreach ($Companys as $comp) {
                            if ($user->parent === $comp['id'] && $comp['parentid'] === "0") {
                                $client = $comp['rsname'];
                            } elseif ($user->parent === $comp['id'] && $comp['parentid'] != "0") {
                                foreach ($Companys as $parent) {
                                    if ($comp['parentid'] === $parent['id'] && $comp['parentid'] != "0") {
                                        $pusat = $parent['rsname'];
                                    }
                                }
                                $client = $comp['rsname'];
                            } elseif ($user->parent === null) {
                                $client = " DPSA ";
                            }
                        }
                    } else {
                        $client = " DPSA ";
                    } ?>

                    <tr>
                        <td class=""><?= $user->username; ?></td>
                        <td class=""><?= $user->email; ?></td>
                        <td class=""><?= $user->role; ?></td>
                        <td class=""><?= $client; ?></td>
                        <td class="uk-text-center">
                            <?php
                            if ($user->status == "0") {
                                echo '<div class="uk-text-light" style="border-style: solid; border-color: #ff0000; color: white; background-color:#ff0000;  font-weight: bold;"> Non Aktif </div>';
                            } else {
                                echo '<div class="uk-text-light" style="border-style: solid; color: white; background-color:#32CD32;  font-weight: bold;"> Aktif </div>';
                            }
                            ?>
                        </td>
                        <td class="uk-child-width-auto uk-flex-center uk-grid-row-small uk-grid-column-small" uk-grid>
                            <!-- Button Trigger Modal Edit -->
                            <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
                                <div>
                                    <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $user->id ?>"></a>
                                </div>
                            <?php } ?>
                            <!-- End Of Button Trigger Modal Edit -->

                            <!-- Button Delete -->
                            <?php if ($authorize->hasPermission('admin.user.delete', $uid)) { ?>
                                <div>
                                    <a uk-icon="trash" class="uk-icon-button-delete" href="users/delete/<?= $user->id ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                                </div>
                            <?php } ?>
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
    <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
        <?php foreach ($users as $user) { ?>
            <div uk-modal class="uk-flex-top" id="editdata<?= $user->id ?>">
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <div class="uk-modal-content">
                        <div class="uk-modal-header">
                            <h5 class="uk-modal-title" id="editdata">Ubah Data Pengguna</h5>
                        </div>

                        <div class="uk-modal-body">
                            <form class="uk-form-stacked" role="form" action="users/update/<?= $user->id ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id<?= $user->id ?>" value="<?= $user->id; ?>">
                                <input type="hidden" name="group_id" value="<?= $user->group_id; ?>">
                                <input type="hidden" name="status" id="statusval" value="<?= $user->status ?>">
                                <input id="compid<?= $user->id ?>" name="compid" value="<?= $user->parent ?>" hidden />

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="username">Nama Pengguna</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="username" name="username" value="<?= $user->username ?>" placeholder="<?= $user->username; ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="firstname">Nama Depan</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="firstname" name="firstname" value="<?=$user->firstname?>" placeholder="<?= $user->firstname; ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="lastname">Nama Belakang</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="lastname<?= $user->id; ?>" name="lastname" value="<?=$user->lastname?>" placeholder="<?= $user->lastname; ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="email">Email</label>
                                    <div class="uk-form-controls">
                                        <input type="email" class="uk-input" id="email" name="email" value="<?= $user->email ?>" placeholder=" <?= $user->email; ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <button class="uk-button uk-button-default" type="button" uk-toggle="target: .reset-password">Ubah Kata Sandi</button>
                                </div>

                                <div class="uk-margin reset-password" hidden>
                                    <label class="uk-form-label" for="password">Kata Sandi</label>
                                    <div class="uk-form-controls">
                                        <input type="password" class="uk-input" name="password" id="password" placeholder="Kata Sandi" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="uk-margin reset-password" hidden>
                                    <label class="uk-form-label" for="pass_confirm">Konfirmasi Kata Sandi</label>
                                    <div class="uk-form-controls">
                                        <input type="password" class="uk-input" name="pass_confirm" id="pass_confirm" placeholder="Konfirmasi Kata Ssndi" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="role">Akses</label>
                                    <div class="uk-form-controls">
                                        <select class="uk-select" id="role<?= $user->id; ?>" name="role" required>
                                            <option value="" selected disabled>Role</option>
                                            <?php
                                            foreach ($roles as $role) {
                                                $selected = "";
                                                if ($role->id === $user->group_id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = "";
                                                }
                                                if ($authorize->inGroup('admin', $uid) === true) {
                                                    $position = array('owner', 'superuser', 'guests');
                                                    if ((!in_array($role->name, $position))) {
                                                        echo '<option value="' . $role->id . '"' . $selected . '>' . $role->name . '</option>';
                                                    }
                                                } elseif ($authorize->inGroup('owner', $uid) === true) {
                                                    $position = array('superuser', 'guests');
                                                    if ((!in_array($role->name, $position))) {
                                                        echo '<option value="' . $role->name . '"' . $selected . '>' . $role->name . '</option>';
                                                    }
                                                } elseif ($authorize->inGroup('superuser', $uid) === true) {
                                                    echo '<option value="' . $role->id . '"' . $selected . '>' . $role->name . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="uk-margin-bottom" id="marketingcode<?= $user->id; ?>" hidden>
                                    <label class="uk-form-label" for="kodemarketing">Kode Marketing</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="kodemarketing<?= $user->id; ?>" value="<?= $user->kodemarketing; ?>" name="kodemarketing" placeholder="Kode Marketing" autofocus required />
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        $("select[id='role<?= $user->id; ?>']").change(function() {
                                            if ($('#role<?= $user->id; ?>').find(":selected").text() === "client pusat") {
                                                $("#pusat<?= $user->id; ?>").removeAttr("hidden");
                                                $("#kliencabang<?= $user->id; ?>").attr("hidden", true);
                                                $("input[id='cabang<?= $user->id; ?>']").attr("required", false);
                                                $("#marketingcode<?= $user->id; ?>").attr("hidden",true);
                                                $("#kodemarketing<?= $user->id; ?>").attr("required",false);
                                                $("#kodemarketing<?= $user->id; ?>").val("");
                                            } else if ($('#role<?= $user->id; ?>').find(":selected").text() === "client cabang") {
                                                $("#kliencabang<?= $user->id; ?>").removeAttr("hidden");
                                                $("#pusat<?= $user->id; ?>").attr("hidden", true);
                                                $("#marketingcode<?= $user->id; ?>").attr("hidden",true);
                                                $("#kodemarketing<?= $user->id; ?>").attr("required",false);
                                                $("#kodemarketing<?= $user->id; ?>").val("");
                                            }else if($('#role<?= $user->id; ?>').find(":selected").text() === "marketing")  {
                                                $("#marketingcode<?= $user->id; ?>").removeAttr("hidden");
                                                $("#kodemarketing<?= $user->id; ?>").attr("required",true);
                                            }else {
                                                $("#pusat<?= $user->id; ?>").attr("hidden", true);
                                                $("#company<?= $user->id; ?>").val("");
                                                $("#company<?= $user->id; ?>").attr("required", false);
                                                $("#compid<?= $user->id ?>").val(null);
                                                $("#pusat<?= $user->id; ?>").attr("required", false);
                                                $("#kliencabang<?= $user->id; ?>").attr("hidden", true);
                                                $("#marketingcode<?= $user->id; ?>").attr("hidden",true);
                                                $("#kodemarketing<?= $user->id; ?>").attr("required",false);
                                                $("#kodemarketing<?= $user->id; ?>").val("");
                                            }
                                        });

                                        if ($('#role<?= $user->id; ?>').find(":selected").text() === "marketing") {
                                            $("#marketingcode<?= $user->id; ?>").removeAttr("hidden");
                                            $("#kodemarketing<?= $user->id; ?>").attr("required",true);
                                        }
                                    });
                                </script>

                                <?php
                                $client = "";
                                $pusat = "";
                                if (!empty($Companys)) {
                                    foreach ($Companys as $comp) {
                                        if ($user->parent === $comp['id'] && $comp['parentid'] === "0") {
                                            $client = $comp['rsname'] . " Pusat";
                                        } elseif ($user->parent === $comp['id'] && $comp['parentid'] != "0") {
                                            foreach ($Companys as $parent) {
                                                if ($comp['parentid'] === $parent['id'] && $comp['parentid'] != "0") {
                                                    $pusat = $parent['rsname'];
                                                }
                                            }
                                            $client = $comp['rsname'] . " Cabang " . $pusat;
                                        } elseif ($user->parent === null) {
                                            $client = " DPSA ";
                                        }
                                    }
                                } ?>

                                <div class="uk-margin" id="pusat<?= $user->id; ?>" hidden>
                                    <label class="uk-form-label" for="company">Perusahaan</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="company<?= $user->id; ?>" name="company" placeholder="<?= $client; ?>">
                                    </div>

                                    <script type="text/javascript">
                                        $(function() {
                                            var company = [
                                                <?php if (!empty($Companys)) {
                                                    foreach ($Companys as $comp) {
                                                        if ($comp['parentid'] === "0") {
                                                            $rsklasification = $comp['rsname'] . " (pusat)";
                                                            echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                        }
                                                    }
                                                } ?>
                                            ];
                                            $("#company<?= $user->id; ?>").autocomplete({
                                                source: company,
                                                select: function(e, i) {
                                                    $("input[id='compid<?= $user->id ?>']").val(i.item.idx); // save selected id to hidden input
                                                },
                                                minLength: 2
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="uk-margin" id="kliencabang<?= $user->id; ?>" hidden>
                                    <label class="uk-form-label" for="cabang">Perusahaan Cabang</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="cabang<?= $user->id; ?>" name="cabang" placeholder="<?= $client; ?>">
                                    </div>

                                    <script type="text/javascript">
                                        $(function() {
                                            var cabang = [
                                                <?php if (!empty($Companys)) {
                                                    foreach ($Companys as $comp) {
                                                        if ($comp['parentid'] != "0") {
                                                            $rsklasification = $comp['rsname'] . " (cabang)";
                                                            echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                                                        }
                                                    }
                                                } ?>
                                            ];
                                            $("#cabang<?= $user->id; ?>").autocomplete({
                                                source: cabang,
                                                select: function(e, i) {
                                                    $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                                                },
                                                minLength: 2
                                            });
                                        });
                                    </script>
                                </div>

                                <label class="uk-form-label" for="status">Status</label>
                                <label class="switch">
                                    <?php if ($user->status != "0") { ?>
                                        <input id="status<?= $user->id ?>" type="checkbox" checked>
                                    <?php } else { ?>
                                        <input id="status<?= $user->id ?>" type="checkbox">
                                    <?php } ?>
                                    <span class="slider round"></span>
                                </label>
                                <script>
                                    $(document).ready(function() {
                                        $("input[id='status<?= $user->id ?>']").change(function() {
                                            if ($(this).is(':checked')) {
                                                $("input[id='statusval']").val("1");
                                            } else {
                                                $("input[id='statusval']").val("0");
                                            }
                                        });
                                    });
                                </script>

                                <hr>

                                <div class="uk-margin">
                                    <button type="submit" class="uk-button uk-button-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>
<!-- End Of Modal Edit -->
<?= $this->endSection() ?>