<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
    <style>
        .row li:nth-child(odd) {
            background: #9999FF;
            color: white;
        }

        .row li:nth-child(odd) a{
            color: white;
        }

        /* tr.row td:hover, tr.row td:focus {
            background-color: #0080ff;
        } */

        tr.row:hover, tr.row:focus {
            background-color: whitesmoke;
            opacity: 0.2;
        }

        .row li:first-child, li:last-child{
            border-radius: 8px;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <?php if ($authorize->hasPermission('marketing.project.edit', $uid) || $authorize->hasPermission('finance.project.edit', $uid) || $authorize->hasPermission('admin.project.read', $uid) ) { ?>
        <!-- Page Heading -->
        <?php if ($ismobile === false) { ?>
            <div class="tm-card-header uk-light uk-margin-remove-left">
                <div uk-grid class="uk-flex-middle uk-child-width-1-2">
                    <div>
                        <h3 class="tm-h3 uk-text-uppercase">Arsip File</h3>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <h3 class="tm-h3 uk-text-center uk-widht uk-text-uppercase">Arsip File</h3>
            <div class="uk-child-width-auto uk-flex-center" uk-grid>
                <!-- Button Filter -->
                    <div>
                        <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                    </div>
                <!-- Button Filter End -->
            </div>
        <?php } ?>
        <!-- End Of Page Heading -->

        <!-- form input -->
        <?php if ($ismobile === false) { ?>
            <form class="uk-margin" id="searchform" action="version" method="GET">
                <input type="hidden" name="project" value="<?=$id?>">
                <input type="hidden" name="type" value="<?=$typefile?>">

                <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Cari :</div>
                            <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($inputpage['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> /></div>
                        </div>
                    </div>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div><?= lang('Global.display') ?></div>
                            <div>
                                <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                    <option value="10" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                                </select>
                            </div>
                            <div><?= lang('Global.perPage') ?></div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } else { ?>
            <div id="filter" class="uk-margin" hidden>
                <form id="searchform" action="version" method="GET">
                    <input type="hidden" name="project" value="<?=$id?>">
                    <input type="hidden" name="type" value="<?=$typefile?>">
                    <div class="uk-margin-small uk-flex uk-flex-center">
                        <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($inputpage['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> />
                    </div>
                    <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                        <div><?= lang('Global.display') ?></div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div><?= lang('Global.perPage') ?></div>
                    </div>
                </form>
            </div>
            <hr class="uk-divider-icon">
        <?php } ?>

        <!-- script form -->
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>
        <!-- end script form -->

        <?= view('Views/Auth/_message_block') ?>

        <?php 
            $type = "";
            $folder = "";
            foreach($versions as $version){
                if($version['type'] === "1" ){
                    $type   = "Desain";
                    $folder = "design";
                }elseif($version['type'] === "2"){
                    $type   = "Revisi";
                    $folder = "revisi";
                }elseif($version['type'] === "3"){
                    $type   = "SPH";
                    $folder = "sph";
                }elseif($version['type'] === "4"){
                    $type   = "SPK";
                    $folder = "spk";
                }elseif($version['type'] === "5"){
                    $type   = "Sertrim";
                    $folder = "bast";
                }elseif($version['type'] === "6"){
                    $type   = "Bast";
                    $folder = "bast";
                }elseif($version['type'] === "7"){
                    $type   = "Invoice I";
                    $folder = "invoice";
                }elseif($version['type'] === "8"){
                    $type   = "Invoice II";
                    $folder = "invoice";
                }elseif($version['type'] === "9"){
                    $type   = "Invoice III";
                    $folder = "invoice";
                }elseif($version['type'] === "10"){
                    $type   = "Invoice IV";
                    $folder = "invoice";
                }elseif($version['type'] === "11"){
                    $type   = "DED / Layout";
                    $folder = "design";      
                }
            }
        ?>

        <div class="uk-card uk-card-default uk-width-1-1@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <?php if ($ismobile === false){?>
                        <div class="uk-width-expand">
                            <h3 class="uk-card-title uk-margin-remove-bottom">File <font class="uk-text-uppercase"><?= $type ?></font></h3>
                            <p class="uk-margin-remove-top uk-text-meta" ><?=$projects['name']." - ".$company['rsname']?></p>
                        </div>
                    <?php }else{ ?>
                        <div class="uk-width-expand uk-text-center">
                            <h3 class="uk-card-title uk-text-center" >File <font class="uk-text-uppercase"><?= $type ?></font></h3>
                            <p class="uk-text-meta uk-margin-remove-top"><?=$projects['name']."<br>".$company['rsname']?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="uk-card-body uk-padding-small uk-overflow-auto" style="margin-left: 20px;">
                <table class="uk-table uk-table-divider">
                    <thead>
                        <tr class="uk-text-bolder">
                            <th class="uk-table-shrink uk-text-bold">No</th>
                            <th class="uk-text-bold">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($versions as $version) { ?>
                                <tr>
                                    <td><?=$number++;?></td>
                                    <td class="uk-text-nowrap"><span uk-icon="file-text"></span><a href="img/<?=$folder?>/<?=$version['file']?>" target="_blank" download><?=$version['file']?></a></td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?= $pager ?>
            </div>
        </div>

        <!-- <div class="uk-card uk-card-default uk-width-1-1@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    </?php if ($ismobile === false){?>
                        <div class="uk-width-expand">
                            <h3 class="uk-card-title uk-margin-remove-bottom">File </?= $type ?></h3>
                            <p class="uk-text-meta uk-margin-remove-top"></?=$projects['name']." - ".$company['rsname']?></p>
                        </div>
                    </?php }else{ ?>
                        <div class="uk-width-expand uk-text-center">
                            <h3 class="uk-card-title uk-text-center">File </?= $type ?></h3>
                            <p class="uk-text-meta uk-margin-remove-top"></?=$projects['name']."<br>".$company['rsname']?></p>
                        </div>
                    </?php } ?>
                </div>
            </div>
            <div class="uk-card-body uk-padding-small" style="margin-left: 20px;">
                <ul class="uk-list uk-margin-left-large row">
                    </?php foreach($versions as $version) { ?>
                        <li class="file"><span uk-icon="file-text" class="uk-padding-small"></span><a href="img/</?=$folder?>/</?=$version['file']?>" target="_blank" download></?=$version['file']?></a></li>
                    </?php } ?>
                </ul>
            </div>
        </div> -->
        
    <?php } ?>

<?= $this->endSection() ?>