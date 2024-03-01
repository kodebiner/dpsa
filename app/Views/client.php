<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>


<!-- Page Heading -->
<?php if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) { ?>
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div uk-grid class="uk-flex-middle">
                <div class="uk-width-1-2@m">
                    <h3 class="tm-h3">Daftar Klien</h3>
                </div>

                <!-- Button Trigger Modal Add -->
                <?php if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) { ?>
                    <div class="uk-width-1-2@m uk-text-right@m">
                        <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Klien</button>
                    </div>
                <?php } ?>
                <!-- End Of Button Trigger Modal Add -->
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center">Daftar Klien</h3>
        <div class="uk-child-width-auto uk-flex-center" uk-grid>
            <div>
                <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
            </div>
            <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
                <div>
                    <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Klien</button>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- End Of Page Heading -->
    <?= view('Views/Auth/_message_block') ?>

    <!-- Modal Add -->
    <?php if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) { ?>
        <div uk-modal class="uk-flex-top" id="tambahdata">
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <h5 class="uk-modal-title" id="tambahdata">Tambah Klien</h5>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" role="form" action="client/create" method="post">
                            <?= csrf_field() ?>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="rsname">Nama Rumah Sakit</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="rsname" name="rsname" placeholder="Nama Rumah Sakit" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="ptname">Nama PT</label>
                                <div class="uk-form-controls">
                                    <input type="text" name="ptname" id="ptname" placeholder="Nama PT" required class="uk-input" />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="address">Alamat</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="address" name="address" placeholder="Alamat" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="npwp">NPWP</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="npwp" name="npwp" placeholder="NPWP" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="notelp">No Telphone</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="notelp" name="notelp" placeholder="No Telphone" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="norek">Bank</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="bank" name="bank" placeholder="bank" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="norek">No.Rek</label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" id="norek" name="norek" placeholder="No Rekening" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin-bottom">
                                <label class="uk-form-label" for="koders">Kode RS</label>
                                <div class="uk-form-controls">
                                    <input type="koders" class="uk-input" id="koders" name="koders" placeholder="Kode RS" autofocus required />
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="pass_confirm"><?= lang('Global.access') ?></label>
                                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                    <label><input class="uk-checkbox" name="parent" id="client pusat" type="checkbox"> Pusat</label>
                                    <label><input class="uk-checkbox" id="client cabang" type="checkbox"> Cabang</label>
                                </div>
                            </div>

                            <div class="uk-margin" id="parent" hidden>
                                <label class="uk-form-label" for="parent">Pilih Pusat</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" name="parent">
                                        <option value="" selected disabled>Center List</option>
                                        <?php
                                        foreach ($roles as $role) {
                                            if ($role['parentid'] === '0') {
                                                echo '<option value="' . $role['id'] . '">' . $role['rsname'] . '</option>';
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
                                        $("input[id='client pusat']").val("");
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
    <!-- End Of Modal Add -->

    <!-- form input -->
    <?php if ($ismobile === false) { ?>
        <form class="uk-margin" id="searchform" action="client" method="GET">
            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                <div>
                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                        <div>Cari:</div>
                        <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                        <div>
                            <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                                <option value="0">Semua Akses</option>
                                <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Pusat</option>
                                <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Cabang</option>
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
            <form id="searchform" action="client" method="GET">
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                </div>
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                        <option value="0">Semua Akses</option>
                        <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Pusat</option>
                        <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Cabang</option>
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
                    <th class="uk-width-large">Rumah Sakit</th>
                    <th class="uk-width-medium">Nama PT</th>
                    <th class="uk-width-medium">No Telepon</th>
                    <th class="uk-text-center uk-width-medium">Alamat</th>
                    <th class="uk-text-center uk-width-medium">Keterangan</th>
                    <th class="uk-text-center uk-width-medium">Status</th>
                    <?php if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) { ?>
                        <th class="uk-text-center uk-width-large">Ubah</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($company as $comp) {
                    $client = "";
                    if ($comp['parent'] != "" && !empty($comp['parent'])) {
                        foreach ($parent as $parentid) {
                            if ($comp['parent'] === $parentid['id']) {
                                $client = "Cabang " . $parentid['name'];
                            }
                        }
                    } else {
                        $client = "Pusat";
                    } ?>

                    <tr>
                        <td class=""><?= $comp['rs']; ?></td>
                        <td class=""><?= $comp['pt']; ?></td>
                        <td class=""><?= $comp['phone']; ?></td>
                        <td class=""><?= $comp['address']; ?></td>
                        <td class="uk-text-center"><?= $client; ?></td>
                        <td class="uk-text-center">
                            <?php
                            if ($comp['status'] === "0") {
                                echo '<div class="uk-text-light" style="border-style: solid; border-color: #ff0000; color: white; background-color:#ff0000;  font-weight: bold;"> Non Aktif </div>';
                            } else {
                                echo '<div class="uk-text-light" style="border-style: solid; color: white; background-color:#32CD32;  font-weight: bold;"> Aktif </div>';
                            }
                            ?>
                        </td>
                        <?php if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) { ?>
                            <td class="uk-child-width-auto uk-flex-center uk-grid-row-small uk-grid-column-small" uk-grid>
                                <!-- Button Trigger Modal Edit -->
                                <div>
                                    <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $comp['id'] ?>"></a>
                                </div>
                                <!-- End Of Button Trigger Modal Edit -->

                                <!-- Button Delete -->
                                <?php if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) { ?>
                                    <div>
                                        <a uk-icon="trash" class="uk-icon-button-delete" href="client/delete/<?= $comp['id'] ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
                                    </div>
                                <?php } ?>
                                <!-- End Of Button Delete -->
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?= $pager ?>
    <!-- End Of Table Content -->

    <!-- Modal Edit -->
    <?php if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) { ?>
        <?php foreach ($company as $comp) { ?>
            <div uk-modal class="uk-flex-top" id="editdata<?= $comp['id'] ?>">
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <div class="uk-modal-content">
                        <div class="uk-modal-header">
                            <h5 class="uk-modal-title">Ubah Data Client</h5>
                        </div>
                        <div class="uk-modal-body">
                            <form class="uk-form-stacked" role="form" action="client/update/<?= $comp['id'] ?>" method="post">

                                <?= csrf_field() ?>
                                <input type="hidden" name="id<?= $comp['id'] ?>" value="<?= $comp['id'] ?>">

                                <input type="hidden" name="status" id="statusval" value="<?= $comp['status'] ?>">

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="rsname">Nama Rumah Sakit</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="rsname" name="rsname" value="<?= $comp['rs'] ?>" placeholder="<?= $comp['rs'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="ptname">Nama PT</label>
                                    <div class="uk-form-controls">
                                        <input type="text" name="ptname" id="ptname" placeholder="<?= $comp['pt'] ?>" value="<?= $comp['pt'] ?>" class="uk-input" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="address">Alamat</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="address" name="address" value="<?= $comp['address'] ?>" placeholder="<?= $comp['address'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="lastname">NPWP</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="npwp" name="npwp" value="<?= $comp['npwp'] ?>" placeholder="<?= $comp['npwp'] ?>" required/>
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="lastname">No Telphone</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="notelp" name="notelp" value="<?= $comp['phone'] ?>" placeholder="<?= $comp['phone'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="norek">Bank</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="bank" name="bank" value="<?= $comp['bank'] ?>" placeholder="<?= $comp['bank'] ?>" autofocus required />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="norek">No.Rek</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="norek" name="norek" value="<?= $comp['no_rek'] ?>" placeholder="<?= $comp['no_rek'] ?>" autofocus required />
                                    </div>
                                </div>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="koders">Kode RS</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="koders" name="koders" value="<?= $comp['rscode'] ?>" placeholder="<?= $comp['rscode'] ?>" autofocus required />
                                    </div>
                                </div>

                                <div class="uk-margin-small">
                                    <label class="uk-form-label">PIC Klien</label>
                                    <div class="uk-form-controls">
                                        <select class="uk-select uk-form-width-medium" name="picklien" required>
                                            <?php 
                                            if(!empty($comp['pic'])){
                                                foreach ($users as $user) {
                                                    if($user->id === $comp['pic']){
                                                        echo '<option value="' . $user->id . '" selected >' . $user->name . '</option>';
                                                    }
                                                }
                                            }else{
                                                echo '<option value="" selected disabled>Pilih PIC Klien</option>';
                                            } 
                                            foreach ($users as $user) {
                                                if ($user->id === $comp['pic']) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = "";
                                                }
                                                echo '<option value="' . $user->id . '" ' . $selected . '>' . $user->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="parent">Keterangan</label>
                                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                        <?php if ($comp['parent'] != "0") { ?>
                                            <label><input class="uk-checkbox" name="parent" value="0" id="editroleclient pusat<?= $comp['id'] ?>" type="checkbox"> Pusat</label>
                                            <label><input class="uk-checkbox" id="editroleclient cabang<?= $comp['id'] ?>" type="checkbox" checked> Cabang</label>
                                        <?php } else { ?>
                                            <label><input class="uk-checkbox" name="parent" value="0" id="editroleclient pusat<?= $comp['id'] ?>" type="checkbox" checked> Pusat</label>
                                            <label><input class="uk-checkbox" id="editroleclient cabang<?= $comp['id'] ?>" type="checkbox"> Cabang</label>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="uk-margin" id="parent<?= $comp['id'] ?>" hidden>
                                    <label class="uk-form-label" for="parent">Pilih Pusat</label>
                                    <div class="uk-form-controls">
                                        <select class="uk-select" name="parent" id="select<?= $comp['id'] ?>">
                                            <option value="" selected disabled>Daftar Pusat</option>
                                            <?php
                                            foreach ($roles as $role) {
                                                if ($role['parentid'] == "0" && $role['id'] != $comp['id']) {
                                                    if ($role['id'] === $comp['parent']) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = "";
                                                    }
                                                    echo '<option value="' . $role['id'] . '" ' . $selected . '>' . $role['rsname'] . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <label class="uk-form-label" for="status">Status</label>
                                <label class="switch">
                                    <?php if ($comp['status'] != "0") { ?>
                                        <input id="status<?= $comp['id'] ?>" type="checkbox" checked>
                                    <?php } else { ?>
                                        <input id="status<?= $comp['id'] ?>" type="checkbox">
                                    <?php } ?>
                                    <span class="slider round"></span>
                                </label>


                                <script>
                                    $(document).ready(function() {
                                        if ($("input[id='editroleclient cabang<?= $comp['id'] ?>']").is(':checked')) {
                                            $("#parent<?= $comp['id'] ?>").removeAttr("hidden");
                                            $("#select<?= $comp['id'] ?>").prop("required", true);
                                        }

                                        $("input[id='editroleclient cabang<?= $comp['id'] ?>']").change(function() {
                                            if ($(this).is(':checked')) {
                                                $("input[id='editroleclient pusat<?= $comp['id'] ?>']").prop("checked", false);
                                                $("#parent<?= $comp['id'] ?>").removeAttr("hidden");
                                                $("#select<?= $comp['id'] ?>").prop("required", true);
                                            } else {
                                                $("#editparent<?= $comp['id'] ?>").attr("hidden", true);
                                                $("#select<?= $comp['id'] ?>").prop("required", false);
                                                $("#select<?= $comp['id'] ?>").val("0");
                                            }
                                        });

                                        $("input[id='editroleclient pusat<?= $comp['id'] ?>']").click(function() {
                                            $("input[id='editroleclient cabang<?= $comp['id'] ?>']").prop("checked", false);
                                            $("#parent<?= $comp['id'] ?>").attr("hidden", true);
                                            $("#select<?= $comp['id'] ?>").prop("required", false);
                                            $("#select<?= $comp['id'] ?>").val("0");
                                        });

                                        $("input[id='status<?= $comp['id'] ?>']").change(function() {
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
                                    <button type="submit" value="submit" class="uk-button uk-button-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- End Of Modal Edit -->
<?php } ?>


<?= $this->endSection() ?>