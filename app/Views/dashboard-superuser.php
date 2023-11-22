<?= $this->extend('layout') ?>

<?= $this->section('main') ?>
<div class="uk-container uk-container-large">
<!-- Page Heading -->
<?php if ($ismobile === true) { ?>
    <h3 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h3>
    <div class="uk-margin uk-text-center">
        <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
    </div>
    <div id="filter" class="uk-margin" hidden>
        <form id="searchform" action="" method="GET">
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
    <h3 class="tm-h1 uk-margin-remove">Daftar Proyek</h3>
<?php } ?>
<hr class="uk-divider-icon uk-margin-remove-top">
<!-- end of Page Heading -->

<!-- Content -->
<?php if ($ismobile === true) { ?>
    <?php foreach ($clients as $client) { ?>
        <a>
            <div class="uk-margin uk-card uk-card-default uk-card-body">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto"><span uk-icon="folder"></span></div>
                    <div class="uk-width-expand"><?=$client['firstname']?> <?=$client['lastname']?></div>
                    <div class="uk-width-auto uk-margin-auto-left"><span uk-icon="chevron-right"></span></div>
                </div>
            </div>
        </a>
    <?php } ?>
<?php } else { ?>
    <form class="uk-margin" id="searchform" action="users" method="GET">
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
    <div class="uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid uk-height-match="target: > div > a > .uk-card-body">
        <?php foreach ($clients as $client) { ?>
            <div>
                <a class="uk-link-reset">
                    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-flex uk-flex-middle">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto"><span uk-icon="icon: folder; ratio: 1.5;"></span></div>
                            <div class="uk-width-expand">
                                <h3 class="uk-text-uppercase"><?=$client['firstname']?> <?=$client['lastname']?></h3>
                            </div>
                            <div class="uk-width-auto"><span uk-icon="icon: chevron-right; ratio: 1.5;"></span></div>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<?=$pager?>
<script>
    document.getElementById('search').addEventListener("change", submitform);
    document.getElementById('perpage').addEventListener("change", submitform);

    function submitform() {
        document.getElementById('searchform').submit();
    };
</script>
<!-- end of Content -->
</div>
<?= $this->endSection() ?>