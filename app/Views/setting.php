<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<link rel="stylesheet" href="css/select2.min.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<?= $this->endSection() ?>
<?= $this->section('main') ?>

<?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
    <!-- Page Heading -->
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div class="uk-text-center">
                <h3 class="tm-h3">Pengaturan Umum</h3>
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center">Pengaturan Umum</h3>
    <?php } ?>
    <!-- End Of Page Heading -->

    <?= view('Views/Auth/_message_block') ?>

    <div class="uk-margin" uk-grid>
        <!-- Referensi Section -->
        <div class="uk-width-2-3">
            <div class="uk-card uk-card-default uk-card-body">
                <div class="uk-h4">Referensi Invoice</div>
                <?php if ($ismobile === false) { ?>
                    <!-- Filter Section -->
                    <form class="uk-margin" id="searchform" action="setting" method="GET">
                        <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                            <div>
                                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                    <div>Cari :</div>
                                    <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
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
                    <!-- Filter Section -->
                <?php } else { ?>
                    <!-- Button Filter -->
                    <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                    <!-- Button Filter End -->

                    <!-- Filter Section -->
                    <div id="filter" class="uk-margin" hidden>
                        <form id="searchform" action="mdl" method="GET">
                            <div class="uk-margin-small uk-flex uk-flex-center">
                                <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
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
                    <!-- Filter Section End -->
                <?php } ?>

                <!-- Filter Script -->
                <script>
                    document.getElementById('search').addEventListener("change", submitform);
                    document.getElementById('perpage').addEventListener("change", submitform);

                    function submitform() {
                        document.getElementById('searchform').submit();
                    };
                </script>
                <!-- Filter Script End -->

                <!-- Table Of Content -->
                <div class="uk-overflow-auto uk-margin">
                    <table class="uk-table uk-table-middle uk-table-large uk-table-hover uk-table-divider">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Bank</th>
                                <th>Rekening</th>
                                <th class="uk-text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($references as $ref) { ?>
                                <tr>
                                    <td><?= $ref['name'] ?></td>
                                    <td><?= $ref['bank'] ?></td>
                                    <td><?= $ref['no_rek'] ?></td>
                                    <td class="uk-text-center">
                                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                            <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
                                                <div>
                                                    <a class="uk-icon-button" href="#modalupdatereferensi<?= $ref['id'] ?>" uk-icon="pencil" uk-toggle></a>
                                                </div>
                                            <?php } ?>
                                            <?php if ($authorize->hasPermission('admin.user.delete', $uid)) { ?>
                                                <div>
                                                    <a class="uk-icon-button-delete" href="setting/deletereferensi/<?= $ref['id'] ?>" uk-icon="trash" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <!-- Button Trigger Modal Add Referensi -->
                    <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
                        <div>
                            <button type="button" class="uk-button uk-button-primary" uk-toggle="target: #modaladdreferensi">Tambah Referensi</button>
                        </div>
                    <?php } ?>
                    <!-- Button Trigger Modal Add Referensi End -->
                </div>
                <!-- Table Of Content End -->
            </div>
        </div>
        <!-- Referensi Section End -->

        <!-- Gconfig Section -->
        <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
            <div class="uk-width-1-3">
                <div class="uk-card uk-card-default uk-card-body">
                    <form class="uk-form-horizontal" role="form" method="post" action="setting/gconfig">

                        <div class="uk-margin uk-flex-middle">
                            <label class="uk-form-label" for="direktur">Direktur</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-form-width-medium" id="direktur" name="direktur" type="text" value="<?=$gconfig['direktur']?>" required>
                            </div>
                        </div>

                        <div class="uk-margin uk-flex-middle">
                            <label class="uk-form-label" for="alamat">Alamat</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-form-width-medium" id="alamat" name="alamat" type="text" value="<?=$gconfig['alamat']?>" required>
                            </div>
                        </div>

                        <div class="uk-margin uk-flex-middle">
                            <label class="uk-form-label" for="npwp">NPWP</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-form-width-medium" id="npwp" name="npwp" type="number" value="<?= $gconfig['npwp'] ?>" required> 
                            </div>
                        </div>

                        <div class="uk-margin uk-flex-middle">
                            <label class="uk-form-label" for="ppn">PPN</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-form-width-medium" id="ppn" name="ppn" min="0" max="100" type="number" value="<?= $gconfig['ppn'] ?>" placeholder="<?= $gconfig['ppn'] ?>%" required> 
                            </div>
                        </div>

                        <!-- Button Save -->
                        <div class="uk-card-footer uk-text-right uk-padding-remove-right">
                            <button class="uk-button uk-button-success" type="submit">Simpan PPN</button>
                        </div>
                        <!-- Button Save End -->
                    </form>
                </div>
            </div>
        <?php } ?>
        <!-- Gconfig Section End -->
    </div>

    <!-- Modal Add Referensi -->
    <?php if ($authorize->hasPermission('admin.user.create', $uid)) { ?>
        <div class="uk-modal-container" id="modaladdreferensi" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Tambah Referensi</h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="setting/createreferensi" method="post">
                        <?= csrf_field() ?>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Nama</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" placeholder="Contoh: Lorem Ipsum" required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="bank">Bank</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="bank" name="bank" placeholder="Contoh: Bank Rakyat Indonesia" required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="no_rek">Nomor Rekening</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="no_rek" name="no_rek" placeholder="Contoh: 123456789" required />
                            </div>
                        </div>

                        <div class="uk-modal-footer">
                            <div class="uk-text-right">
                                <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Modal Add Referensi End -->

    <!-- Modal Update Referensi -->
    <?php if ($authorize->hasPermission('admin.user.edit', $uid)) { ?>
        <?php foreach ($references as $refer) { ?>
            <div class="uk-modal-container" id="modalupdatereferensi<?= $refer['id'] ?>" uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                    <div class="uk-modal-content">
                        <div class="uk-modal-header">
                            <h2 class="uk-modal-title">Ubah Referensi</h2>
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                        </div>

                        <div class="uk-modal-body">
                            <form class="uk-form-stacked" role="form" action="setting/updatereferensi/<?= $refer['id'] ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="name">Nama</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="name" name="name" value="<?= $refer['name']; ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="bank">Bank</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="bank" name="bank" value="<?= $refer['bank']; ?>" required />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="no_rek">Nomor Rekening</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="no_rek" name="no_rek" value="<?= $refer['no_rek']; ?>" required />
                                    </div>
                                </div>

                                <div class="uk-modal-footer">
                                    <div class="uk-flex-right">
                                        <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- Modal Update Referensi End -->
<?php } ?>
<?= $this->endSection() ?>