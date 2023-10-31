<?= $this->extend('layout') ?>
<?= $this->section('main') ?>
<?php if ($ismobile === false) { ?>
    <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
        <div>
            <h1>Hak Akses</h1>
        </div>
        <div>
            <button class="uk-button uk-button-large uk-button-secondary">Tambah Hak Akses</button>
        </div>
    </div>
<?php } else { ?>
    <h1 class="uk-text-center">Hak Akses</h1>
    <div class="uk-margin uk-text-center"><button class="uk-button uk-button-large uk-button-secondary">Tambah Hak Akses</button></div>
<?php } ?>
<div class="uk-child-width-1-3@m" uk-grid>
    <?php foreach ($groups as $group) { ?>
        <div>
            <div class="uk-card uk-card-default uk-card-hover">
                <div class="uk-card-body">
                    <h3><?=$group->name;?></h3>
                </div>
                <div class="uk-card-footer">
                    <div class="uk-child-width-auto" uk-grid>
                        <div><a class="uk-button uk-button-primary" uk-icon="pencil"></a></div>
                        <div><a class="uk-button uk-button-danger" uk-icon="trash"></a></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?= $this->endSection() ?>