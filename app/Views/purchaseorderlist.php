<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
        <!-- Page Heading -->
        <?php if ($ismobile === false) { ?>
            <div class="tm-card-header uk-light uk-margin-remove-left">
                <div uk-grid class="uk-flex-middle uk-child-width-1-2">
                    <div>
                        <h3 class="tm-h3">Pesanan Diterima</h3>
                    </div>

                    <!-- Button Trigger Modal Add -->
                    <!-- </?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                        <div class="uk-text-right">
                            <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Buat Pesanan</button>
                        </div>
                    </?php } ?> -->
                    <!-- End Of Button Trigger Modal Add -->
                </div>
            </div>
        <?php } else { ?>
            <h3 class="tm-h3 uk-text-center">Pesanan Diterima</h3>
            <div class="uk-child-width-auto uk-flex-center" uk-grid>

                <!-- Button Filter -->
                <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
                    <div>
                        <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                    </div>
                <?php } ?>
                <!-- Button Filter End -->
            </div>
        <?php } ?>
        <!-- End Of Page Heading -->

        <!-- form input -->
        <?php if ($ismobile === false) { ?>
            <form class="uk-margin" id="searchform" action="pesanmasuk" method="GET">
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
                <form id="searchform" action="pesanmasuk" method="GET">
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
        foreach($companys as $company){ ?>
            <?php var_dump($items[$company['id']]['purdet'])?>
            <?php  $empty ="";
                if (!empty($items[$company['id']]['purdet'])){ ?>
                    <div id="order<?=$company['id']?>" class="uk-grid-column-small uk-grid-row-large uk-child-width-1-1@s uk-margin" uk-grid>
                        <div>
                            <div class="uk-card uk-card-default uk-width-1-1@m">
                                <div class="uk-card-header">
                                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                                        <div class="uk-width-expand">
                                            <h3 class="uk-card-title uk-margin-remove-bottom"><?=$company['rsname']?></h3>
                                        </div>
                                        <div class="uk-width-auto">
                                            <button id="openorder<?=$company['id']?>" class="uk-icon-button" uk-icon="chevron-up" uk-toggle="target: #body<?=$company['id']?>" hidden></button>
                                            <button id="closeorder<?=$company['id']?>" class="uk-icon-button" uk-icon="chevron-down" uk-toggle="target: #body<?=$company['id']?>"></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="body<?=$company['id']?>" class="uk-card-body uk-overflow-auto" hidden>
                                    <table class="uk-table uk-table-divider">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th class="uk-text-center">Panjang</th>
                                                <th class="uk-text-center">Lebar</th>
                                                <th class="uk-text-center">Tinggi</th>
                                                <th class="uk-text-center">Volume</th>
                                                <th id="denomination" class="uk-text-center">Satuan</th>
                                                <th>Keterangan</th>
                                                <th id="photo">Photo</th>
                                                <th id="price">Harga</th>
                                                <th class="uk-text-center" id="qty">Jumlah Pesanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items[$company['id']]['purdet'] as $item){ ?>
                                            <tr>
                                                <td><?=$item['name']?></td>
                                                <td class="uk-text-center"><?=$item['length']?></td>
                                                <td class="uk-text-center"><?=$item['width']?></td>
                                                <td class="uk-text-center"><?=$item['height']?></td>
                                                <td class="uk-text-center"><?=$item['volume']?></td>
                                                <td class="uk-text-center"><?=$item['denomination']?></td>
                                                <td><?=$item['keterangan']?></td>
                                                <td>
                                                    <div uk-lightbox>
                                                        <a class="uk-inline" href="img/mdl/<?=$item['photo']?>" role="button">
                                                            <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$item['photo']?>" width="40" height="40" alt="1714891963_3160b300a8c8e0a30db2.jpg">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?= "Rp " . number_format($item['price'],0,',','.').",-";?></td>
                                                <td class="uk-text-center"><?=$item['qty']?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php if(!$ismobile){?>
                                        <div class="uk-text-right" uk-margin>
                                            <button class="uk-button uk-button-primary" href="#modal-confirm<?=$company['id']?>" uk-toggle>Konfirmasi</button>
                                            <a id="deletepesanan<?=$company['id']?>" class="uk-button uk-button-danger">Hapus</a>
                                        </div>
                                    <?php }else{ ?>
                                        <button class="uk-button uk-button-primary" href="#modal-confirm<?=$company['id']?>" uk-toggle>Konfirmasi</button>
                                        <a id="deletepesanan<?=$company['id']?>" class="uk-button uk-button-danger">Hapus</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('openorder<?=$company['id']?>').addEventListener('click', opentoggle<?=$company['id']?>);
                        document.getElementById('closeorder<?=$company['id']?>').addEventListener('click', closetoggle<?=$company['id']?>);

                        function opentoggle<?=$company['id']?>() {
                            document.getElementById('openorder<?=$company['id']?>').setAttribute('hidden', '');
                            document.getElementById('closeorder<?=$company['id']?>').removeAttribute('hidden');
                        };

                        function closetoggle<?=$company['id']?>() {
                            document.getElementById('openorder<?=$company['id']?>').removeAttribute('hidden');
                            document.getElementById('closeorder<?=$company['id']?>').setAttribute('hidden', '');
                        };

                        // Delete
                        $('#deletepesanan<?=$company['id']?>').click(function() {
                            let text = "Anda yakin ingin menghapus pesanan ini?";
                            if (confirm(text) == true) {
                                $.ajax({
                                    type: 'POST',
                                    url: "pesanmasuk/delete",
                                    data: {
                                        id: <?=$company['id']?>,
                                    },
                                    dataType: "json",
                                    error: function() {
                                        console.log('error', arguments);
                                    },
                                    success: function() {
                                        console.log('success', arguments);
                                        $('#order<?=$company['id']?>').remove();
                                        alert("Pesanan berhasil dihapus");
                                    }
                                });
                            }
                        });
                    </script>
             <?php }else{ 
                if(empty($items)){
                    $empty = '<div class="uk-width-1-1 uk-text-center uk-text-italic">Belum Ada Pesanan Masuk</div>';
                }
              }?>
        <?php } ?>
        <!-- </?= $empty ?> -->
        <?= $pager ?>
    <?php } ?>

    <!-- Confirm Modal -->
    <?php foreach($companys as $company){ ?>
        <div id="modal-confirm<?=$company['id'] ?>" uk-modal>
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>

                <div class="uk-modal-header" style="background-color: #39f;">
                    <h5 class="uk-card-title uk-light"> Konfirmasi Pesanan <br> RS <?=$company['rsname'] ?></h5>
                </div>

                <form action="pesanmasuk/confirm/<?=$company['id']?>" method="post">
                    <div class="uk-modal-body" uk-overflow-auto>
                        <fieldset class="uk-fieldset">

                            <div class="uk-margin">
                                <!-- <label for="proyek" class="uk-text-bolder">Nama Proyek</label> -->
                                <input class="uk-input" type="text" name="name" placeholder="Buat Nama Proyek.." aria-label="Input" required>
                            </div>

                            <table class="uk-table uk-table-divider">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th id="denomination" class="uk-text-center">Satuan</th>
                                        <th id="price">Harga</th>
                                        <th class="uk-text-center" id="qty">Jumlah Pesanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($items[$company['id']]['purdet'])){ ?>
                                    <?php foreach ($items[$company['id']]['purdet'] as $item){ ?>
                                    <tr>
                                        <td><?=$item['name']?></td>
                                        <td class="uk-text-center"><?=$item['denomination']?></td>
                                        <td><?= "Rp " . number_format($item['price'],0,',','.').",-";?></td>
                                        <td class="uk-text-center"><?=$item['qty']?></td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>

                        </fieldset>
                    </div>
                    
                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-danger uk-modal-close" type="button">Batal</button>
                        <button class="uk-button uk-button-primary" type="submit">Buat Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
    <!--End Confirm Modal -->

    <?php if (!empty($input)) {
        if ($ismobile == false) { ?>
            <script>
                $(document).ready(function() {
                    var unhidecontent = document.getElementById('body<?=$input?>');
                    unhidecontent.removeAttribute('hidden');

                    var opendropdown = document.getElementById('openorder<?=$input?>');
                    opendropdown.removeAttribute('hidden');

                    var closedropdown = document.getElementById('closeorder<?=$input?>');
                    closedropdown.setAttribute('hidden', '');
                });
                    
                window.addEventListener('load', () => setTimeout(() => {
                    document.querySelector('#body<?= $input ?>').scrollIntoView()
                }))
            </script>
        <?php } else { ?>
            <script>
                $(document).ready(function() {
                    var unhidebody = document.getElementById('body<?=$input?>');
                    unhidebody.removeAttribute('hidden');

                    var openmobile = document.getElementById('openorder<?=$input?>');
                    openmobile.removeAttribute('hidden');

                    var closemobile = document.getElementById('closeorder<?=$input?>');
                    closemobile.setAttribute('hidden', '');
                });
                
                window.addEventListener('load', () => setTimeout(() => {
                    document.querySelector('#body<?= $input ?>').scrollIntoView()
                }))
            </script>
        <?php }
    } ?>

<?= $this->endSection() ?>