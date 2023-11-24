<?= $this->extend('layout') ?>

<?= $this->section('main') ?>
<div class="uk-container uk-container-large">

    <!-- Page Heading -->
    <?php if ($ismobile) { ?>
        <h1 class="tm-h1 uk-text-center uk-margin-remove"><?= lang("Global.projectList") ?><br/><?=$client->firstname?> <?=$client->lastname?></h1>
    <div class="uk-margin uk-text-center">
        <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
    </div>
    <div id="filter" class="uk-margin" hidden>
        <form id="searchform" action="<?=$uri?>" method="GET">
            <div class="uk-margin-small uk-flex uk-flex-center">
                <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="'.$input['search'].'"' : '') ?> />
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
    <script>
        document.getElementById('filterbutton').addEventListener('click', toggleiconfilter);

        function toggleiconfilter() {
            var close = document.getElementById('filterclose').hasAttribute('hidden');
            if (close === true) {
                document.getElementById('filteropen').setAttribute('hidden', '');
                document.getElementById('filterclose').removeAttribute('hidden');
            } else {
                document.getElementById('filteropen').removeAttribute('hidden');
                document.getElementById('filterclose').setAttribute('hidden', '');
            }
        }
    </script>
    <?php } else { ?>
        <h1 class="tm-h1 uk-margin-remove"><?= lang("Global.projectList") ?> <?=$client->firstname?> <?=$client->lastname?></h1>
    <?php } ?>
    <hr class="uk-divider-icon">
    <!-- end of Page Heading -->

    <!-- Content -->
    <?php if (!$ismobile) { ?>
        <form class="uk-margin" id="searchform" action="<?=$uri?>" method="GET">
            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                <div>
                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                        <div><?= lang('Global.search') ?>:</div>
                        <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="'.$input['search'].'"' : '') ?> /></div>
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
    <?php } ?>
    <?php if (!empty($projects)) { ?>
        <?php foreach ($projects as $project) { ?>
            <?php if ($project['status'] === "1") {
                $status = "Proses Desain";
                $progress = '5';
            } elseif ($project['status'] === "2") {
                $status = "Menunggu Approval Desain";
                $progress = '10';
            } elseif ($project['status'] === "3") {
                $status = "Pengajuan RAB";
                $progress = '20';
            } elseif ($project['status'] === "4") {
                $status = "Dalam Proses Produksi";
                if ($project['production'] === "0") {
                    $progress = '30';
                } else {
                    $qty = round($project['production'] / 100 * 65, 2);
                    $progress = $qty;
                }
            } elseif ($project['status'] === "5") {
                $status = "Setting";
                $progress = '95';
            } ?>
            <?php if($ismobile) { ?>
                <div class="uk-margin uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title"><?=$project['name']?></h3>
                            </div>
                            <div class="uk-width-auto">
                                <button id="open<?=$project['id']?>" class="uk-icon-button" uk-icon="chevron-down" uk-toggle="target: #body<?=$project['id']?>"></button>
                                <button id="close<?=$project['id']?>" class="uk-icon-button" uk-icon="chevron-up" uk-toggle="target: #body<?=$project['id']?>" hidden></button>
                            </div>
                        </div>
                        <progress class="uk-progress" value="<?=$progress?>" max="100"></progress>
                    </div>
                    <div id="body<?=$project['id']?>" class="uk-card-body" hidden>
                        <div class="uk-grid-divider" uk-grid>
                            <div class="uk-width-1-1">
                                <h4>Brief</h4>
                                <p><?=$project['brief']?></p>
                            </div>
                            <div class="uk-width-1-2">
                                <h4 class="uk-text-center">Status Proyek</h4>
                                <div class="uk-text-center"><?=$status?></div>
                            </div>
                            <?php if ($project['status'] === '4') { ?>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Progress Produksi</h4>
                                    <div class="uk-text-center"><?=$project['production']?>%</div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <script>
                    document.getElementById('open<?=$project['id']?>').addEventListener('click', buttontoggle<?=$project['id']?>);
                    document.getElementById('close<?=$project['id']?>').addEventListener('click', buttontoggle<?=$project['id']?>);

                    function buttontoggle<?=$project['id']?>() {
                        if (document.getElementById('close<?=$project['id']?>').hasAttribute('hidden')) {
                            document.getElementById('open<?=$project['id']?>').setAttribute('hidden', '');
                            document.getElementById('close<?=$project['id']?>').removeAttribute('hidden');
                        } else {
                            document.getElementById('open<?=$project['id']?>').removeAttribute('hidden');
                            document.getElementById('close<?=$project['id']?>').setAttribute('hidden', '');
                        }
                    }
                </script>
            <?php } else { ?>
                <div class="uk-margin uk-card uk-card-default uk-card-hover">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title"><?=$project['name']?></h3>
                        <progress class="uk-progress" value="<?=$progress?>" max="100"></progress>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-grid-divider" uk-grid>
                            <div class="uk-width-1-1">
                                <h4>Brief</h4>
                                <p><?=$project['brief']?></p>
                            </div>
                            <div class="uk-width-1-2">
                                <h4 class="uk-text-center">Status Proyek</h4>
                                <div class="uk-text-center"><?=$status?></div>
                            </div>
                            <?php if ($project['status'] === '4') { ?>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Progress Produksi</h4>
                                    <div class="uk-text-center"><?=$project['production']?>%</div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <div class="uk-text-center uk-text-italic"><?=lang('Global.noData')?></div>
    <?php } ?>
    <!-- end of Content -->

    <?=$pager?>
    <script>
        document.getElementById('search').addEventListener("change", submitform);
        document.getElementById('perpage').addEventListener("change", submitform);

        function submitform() {
            document.getElementById('searchform').submit();
        };
    </script>
</div>
<?= $this->endSection() ?>