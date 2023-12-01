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
                <h3 class="tm-h3">Daftar Client</h3>
            </div>

            <!-- Button Trigger Modal Add -->
            <div class="uk-width-1-2@m uk-text-right@m">
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Client</button>
            </div>
            <!-- End Of Button Trigger Modal Add -->
        </div>
    </div>
<?php } else { ?>
    <h3 class="tm-h3 uk-text-center">Daftar Client</h3>
    <div class="uk-child-width-auto uk-flex-center" uk-grid>
        <div>
            <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
        </div>
        <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
            <div>
                <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #tambahdata">Tambah Client</button>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<!-- End Of Page Heading -->
<?= view('Views/Auth/_message_block') ?>

<!-- Modal Add -->
<div uk-modal class="uk-flex-top" id="tambahdata">
    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
        <div class="uk-modal-content">
            <div class="uk-modal-header">
                <h5 class="uk-modal-title" id="tambahdata"><?= lang('Global.Addclient') ?></h5>
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
                        <label class="uk-form-label" for="lastname">NPWP</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="npwp" name="npwp" placeholder="NPWP" autofocus required />
                        </div>
                    </div>

                    <div class="uk-margin-bottom">
                        <label class="uk-form-label" for="lastname">No Telphone</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="notelp" name="notelp" placeholder="No Telphone" autofocus required />
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
                                    if ($role['parentid'] != '') {
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
    <form class="uk-margin" id="searchform" action="client" method="GET">
        <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
            <div>
                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                    <div><?= lang('Global.search') ?>:</div>
                    <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                    <div>
                        <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                            <option value="0"><?= lang('Global.allAccess') ?></option>
                            <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Pusat</option>
                            <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Cabang</option>
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
        <form id="searchform" action="client" method="GET">
            <div class="uk-margin-small uk-flex uk-flex-center">
                <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
            </div>
            <div class="uk-margin-small uk-flex uk-flex-center">
                <select class="uk-select uk-form-width-medium" id="rolesearch" name="rolesearch">
                    <option value="0"><?= lang('Global.allAccess') ?></option>
                    <option value="1" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '1') ? 'selected' : '') ?>>Pusat</option>
                    <option value="2" <?= (isset($input['rolesearch']) && ($input['rolesearch'] === '2') ? 'selected' : '') ?>>Cabang</option>
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
                <th class="uk-width-large">Rumah Sakit</th>
                <th class="uk-width-medium">Nama PT</th>
                <th class="uk-width-medium">No Telepon</th>
                <th class="uk-text-center uk-width-medium">Alamat</th>
                <th class="uk-text-center uk-width-medium">Keterangan</th>
                <th class="uk-text-center uk-width-medium">Status</th>
                <th class="uk-text-center uk-width-large">Ubah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($company as $comp) {
                $client = "";
                if ($comp['parent'] != "" && !empty($comp['parent'])) {
                    foreach ($parent as $parentid) {
                        if ($comp['parent'] === $parentid['id']) {
                            $client = "Cabang ".$parentid['name'];
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
                                echo '<div class="uk-text-danger" style="border-style: solid; border-color: #ff0000;"> Non Aktif </div>';
                            } else {
                                echo '<div class="uk-text-primary" style="border-style: solid; border-color: #007Ec8;"> Aktif </div>';
                            }
                        ?>
                    </td>
                    <td class="uk-child-width-auto uk-flex-center uk-grid-row-small uk-grid-column-small" uk-grid>
                        <!-- Button Trigger Modal Edit -->
                        <div>
                            <a class="uk-icon-button" uk-icon="pencil" uk-toggle="target: #editdata<?= $comp['id'] ?>"></a>
                        </div>
                        <!-- End Of Button Trigger Modal Edit -->

                        <!-- Button Delete -->
                        <div>
                            <a uk-icon="trash" class="uk-icon-button-delete" href="users/deleteclient/<?= $comp['id'] ?>" onclick="return confirm('<?= lang('Global.deleteConfirm') ?>')"></a>
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
<?php foreach ($company as $com){ ?>
<div uk-modal class="uk-flex-top" id="tambahdata">
    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
        <div class="uk-modal-content">
            <div class="uk-modal-header">
                <h5 class="uk-modal-title" id="tambahdata"><?= lang('Global.Addclient') ?></h5>
            </div>
            <div class="uk-modal-body">
                <form class="uk-form-stacked" role="form" action="client/update" method="post">
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
                        <label class="uk-form-label" for="lastname">NPWP</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="npwp" name="npwp" placeholder="NPWP" autofocus required />
                        </div>
                    </div>

                    <div class="uk-margin-bottom">
                        <label class="uk-form-label" for="lastname">No Telphone</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="notelp" name="notelp" placeholder="No Telphone" autofocus required />
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
                                    if ($role['parentid'] != '') {
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
                        <button type="submit" class="uk-button uk-button-primary"><?= lang('Global.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- End Of Modal Edit -->


<?= $this->endSection() ?>