<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if (in_groups('superuser',$uid)) { ?>
    <!-- Page Heading -->
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div uk-grid class="uk-flex-middle uk-child-width-1-2@m">
                <div>
                    <h3 class="tm-h3">Daftar Aktivitas Pengguna</h3>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center">Daftar Aktivitas Pengguna</h3>
        <div class="uk-child-width-auto uk-flex-center" uk-grid>
            <!-- Button Filter -->
            <div>
                <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
            </div>
            <!-- Button Filter End -->
        </div>
    <?php } ?>
    <!-- End Of Page Heading -->

    <!-- Form Filter Input -->
    <?php if ($ismobile === false) { ?>
        <form class="uk-margin" id="searchform" action="users/log" method="GET">
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
    <?php } else { ?>
        <div id="filter" class="uk-margin" hidden>
            <form id="searchform" action="users/log" method="GET">
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
    <?php } ?>

    <!-- Script Form Filter -->
    <script>
        document.getElementById('search').addEventListener("change", submitform);
        document.getElementById('perpage').addEventListener("change", submitform);

        function submitform() {
            document.getElementById('searchform').submit();
        };
    </script>
    <!-- Script Form Filter End -->
    <!-- Form Filter Input End -->

    <?= view('Views/Auth/_message_block') ?>

    <!-- Table Of Content -->
    <div class="uk-overflow-auto uk-margin">
        <table class="uk-table uk-table-middle uk-table-hover uk-table-divider">
            <thead>
                <tr>
                    <th class="uk-width-1-6">Nama</th>
                    <th>Aktivitas</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)){
                    foreach ($users as $user) { ?>
                        <?php 
                        $dateTimeObj = new DateTime($user->created, new DateTimeZone('Asia/Jakarta'));
                        $dateFormatted =
                            IntlDateFormatter::formatObject(
                                $dateTimeObj,
                                'eeee, d MMMM y - HH:mm:ss',
                                'id'
                            );
                        $dateact = ucwords($dateFormatted);    
                        ?>
                        <tr>
                            <td><?= $user->username ?></td>
                            <td><?= $user->record ?></td>
                            <td><?= $dateact ?></td>
                        </tr>
                    <?php } 
                }?>
            </tbody>
        </table>
    </div>
    <?= $pager ?>
<?php } ?>
<?= $this->endSection() ?>