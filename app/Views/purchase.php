<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <script src="js/code.jquery.com_jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
    <script src="js/code.jquery.com_ui_1.13.2_jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php if (in_groups('superuser',$uid)) { ?>
    <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
        <!-- Page Heading -->
        <?php if ($ismobile === false) { ?>
            <div class="tm-card-header uk-light uk-margin-remove-left">
                <div uk-grid class="uk-flex-middle uk-child-width-1-2">
                    <div>
                        <h3 class="tm-h3">Pesan Item</h3>
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
            <h3 class="tm-h3 uk-text-center">Pesan Item</h3>
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
            <form class="uk-margin" id="searchform" action="pesanan" method="GET">
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
                <form id="searchform" action="pesanan" method="GET">
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
        
        <!-- Table Of Content -->
        <div class="uk-overflow-auto uk-margin">
            <table class="uk-table uk-table-middle uk-table-large uk-table-hover uk-table-divider">
                <thead>
                    <tr>
                        <th>Detail</th>
                        <th>Nama</th>
                        <th>Panjang</th>
                        <th>Lebar</th>
                        <th>Tinggi</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th class="uk-width-medium">Keterangan</th>
                        <th class="uk-width-small">Harga</th>
                        <th class="uk-width-small">photo</th>
                        <th class="uk-text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($parents as $parent) { ?>
                        <tr>
                            <td><a class="uk-link-reset" id="togglepaket<?= $parent['id'] ?>" uk-toggle="target: .togglepaket<?= $parent['id'] ?>"><span id="closepaket<?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket<?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td>
                            <td colspan="9" class="tm-h3" style="text-transform: uppercase;"><?= $parent['name'] ?></td>
                        </tr>
                        <?php
                        $paketCount = count($mdldata[$parent['id']]['paket']);
                        foreach ($mdldata[$parent['id']]['paket'] as $paket) {
                        ?>
                            <?php
                            if ($idparent === $parent['id']) {
                                $parenthide = '';
                            } else {
                                $parenthide = 'hidden';
                            }

                            if ($idpaket === $paket['id']) {
                                $pakethide = '';
                            } else {
                                $pakethide = 'hidden';
                            }
                            ?>
                            <tr class="togglepaket<?= $parent['id'] ?>" <?=$parenthide?>>
                                <td class="uk-text-right"><a class="uk-link-reset" id="toggle<?= $paket['id'] ?>" uk-toggle="target: .togglemdl<?= $paket['id'] ?>"><span id="close<?= $paket['id'] ?>" uk-icon="chevron-down" hidden></span><span id="open<?= $paket['id'] ?>" uk-icon="chevron-right"></span></a></td>
                                <td colspan="9" class="tm-h4" style="text-transform: uppercase; font-weight: 400;"><?= $paket['name'] ?></td>
                            </tr>
                            <?php
                            $mdlCount = count($paket['mdl']);
                            foreach ($paket['mdl'] as $mdl) { ?>
                                <tr class="togglemdl<?= $paket['id'] ?>" id="togglemdl<?= $mdl['id'] ?>" <?=$pakethide?>>
                                    <td></td>
                                    <td><?= $mdl['name'] ?></td>
                                    <td><?= $mdl['length'] ?></td>
                                    <td><?= $mdl['width'] ?></td>
                                    <td><?= $mdl['height'] ?></td>
                                    <td><?= $mdl['volume'] ?></td>
                                    <td>
                                        <?php
                                        if ($mdl['denomination'] === "1") {
                                            echo "Unit";
                                        } elseif ($mdl['denomination'] === "2") {
                                            echo "Meter Lari";
                                        } elseif ($mdl['denomination'] === "3") {
                                            echo "Meter Persegi";
                                        } elseif ($mdl['denomination'] === "4") {
                                            echo "Set";
                                        }
                                        ?>
                                    </td>
                                    <td class=""><?= $mdl['keterangan'] ?></td>
                                    <td><?= "Rp. " . number_format((int)$mdl['price'], 0, ',', '.');" "; ?></td>
                                    <td class="">
                                        <div uk-lightbox="">
                                            <a class="uk-inline" href="img/mdl/<?=$mdl['photo']?>" role="button">
                                                <img class="uk-preserve-width uk-border-circle" id="img18" src="img/mdl/<?=$mdl['photo']?>" width="40" height="40" alt="<?=$mdl['photo']?>">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="uk-text-center">
                                        <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                            <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                                                <div id="buttonadd<?=$mdl['id'] ?>">
                                                    <a class="uk-icon-button addtocart" id="addtocart<?=$mdl['id'] ?>" uk-icon="cart"></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <script>
                                    // Add To Cart Function
                                    $('#addtocart<?= $mdl['id'] ?>').click(function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: "pesanan/createpurchase",
                                            data: {
                                                id: <?= $mdl['id'] ?>,
                                                paket: <?= $paket['id'] ?>,
                                            },
                                            dataType: "json",
                                            error: function() {
                                                console.log('error', arguments);
                                            },
                                            success: function() {
                                                // console.log('success', arguments);

                                                $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', false);
                                                $('#addtocart<?= $mdl['id'] ?>').attr('hidden',true);
                                                $('#buttonadd<?= $mdl['id'] ?>').append("<p id='info<?= $mdl['id'] ?>' class='info'>Item telah masuk daftar pesanan</p>");

                                                var item = document.createElement('tr');
                                                item.setAttribute('id','item<?= $mdl['id'] ?>');
                                                item.setAttribute('class','itemrowmdl');

                                                var name = document.createElement('td');
                                                name.setAttribute('id','name');
                                                itemname = document.createTextNode(arguments[0][0]['name']);

                                                var length = document.createElement('td');
                                                length.setAttribute('id','length');
                                                length.setAttribute('class','uk-text-center'); 
                                                itemlength = document.createTextNode(arguments[0][0]['length']);

                                                var width = document.createElement('td');
                                                width.setAttribute('id','width');
                                                width.setAttribute('class','uk-text-center'); 
                                                itemwidth = document.createTextNode(arguments[0][0]['width']);

                                                var height = document.createElement('td');
                                                height.setAttribute('id','height');
                                                height.setAttribute('class','uk-text-center'); 
                                                itemheight = document.createTextNode(arguments[0][0]['heigth']); 

                                                var volume = document.createElement('td');
                                                volume.setAttribute('id','volume');
                                                volume.setAttribute('class','uk-text-center'); 
                                                itemvolume = document.createTextNode(arguments[0][0]['volume']);  

                                                var denom = "";
                                                if (arguments[0][0]['denomination'] === "1") {
                                                    denom = "Unit";
                                                } else if (arguments[0][0]['denomination'] === "2") {
                                                    denom = "Meter Lari";
                                                } else if (arguments[0][0]['denomination'] === "3") {
                                                    denom = "Meter Persegi";
                                                } else if (arguments[0][0]['denomination'] === "4") {
                                                    denom = "Set";
                                                }

                                                var denomination = document.createElement('td');
                                                denomination.setAttribute('id','denomination'); 
                                                denomination.setAttribute('class','uk-text-center'); 
                                                itemdenomination = document.createTextNode(denom);  
                                                
                                                var keterangan = document.createElement('td');
                                                keterangan.setAttribute('id','keterangan');
                                                itemketerangan = document.createTextNode(arguments[0][0]['keterangan']);  

                                                var photo = document.createElement('td');
                                                photo.setAttribute('id','photo'); 
                                                itemphoto = document.createTextNode(arguments[0][0]['photo']); 

                                                var lightbox = document.createElement('div');
                                                lightbox.toggleAttribute('uk-lightbox');

                                                var linkphoto = document.createElement('a');
                                                linkphoto.setAttribute('class','uk-inline');
                                                linkphoto.setAttribute('href','img/mdl/'+ arguments[0][0]['photo']);
                                                linkphoto.setAttribute('role','button');

                                                var imgphoto = document.createElement('img');
                                                imgphoto.setAttribute('class','uk-preserve-width uk-border-circle');
                                                imgphoto.setAttribute('src','img/mdl/'+ arguments[0][0]['photo']);
                                                imgphoto.setAttribute('width','40');
                                                imgphoto.setAttribute('height','40');
                                                imgphoto.setAttribute('alt', arguments[0][0]['photo']);
                                                
                                                var price = document.createElement('td');
                                                price.setAttribute('id','price'); 
                                                itemprice = document.createTextNode("Rp. " + arguments[0][0]['price'] + ",-"); 

                                                var trinput = document.createElement('td');
                                                var divinput = document.createElement('div');
                                                divinput.setAttribute('class','uk-margin');

                                                var input = document.createElement('input');
                                                input.setAttribute('class','uk-input uk-form-width-small uk-text-center');
                                                input.setAttribute('name','qty['+arguments[0][0]['mdl']+']');
                                                input.setAttribute('placeholder','1');
                                                input.setAttribute('type','number');
                                                input.setAttribute('min','1');
                                                input.setAttribute('aria-label','X-Small');
                                                
                                                var tdtrash = document.createElement('td');
                                                var divtrash = document.createElement('div');
                                                var linktrash = document.createElement('a');
                                                linktrash.setAttribute('uk-icon','trash');
                                                linktrash.setAttribute('class','uk-icon-button-delete');
                                                linktrash.setAttribute('onclick','removemdl'+ arguments[0][0]['mdl']+'()');
                                                var itemorder = document.getElementById('itemorder');

                                                itemorder.appendChild(item);
                                                item.appendChild(name);
                                                item.appendChild(length);
                                                item.appendChild(width);
                                                item.appendChild(height);
                                                item.appendChild(volume);
                                                item.appendChild(denomination);
                                                item.appendChild(keterangan);
                                                item.appendChild(photo);
                                                item.appendChild(price);
                                                item.appendChild(trinput);
                                                item.appendChild(tdtrash);
                                                name.appendChild(itemname);
                                                length.appendChild(itemlength);
                                                width.appendChild(itemwidth);
                                                height.appendChild(itemheight);
                                                volume.appendChild(itemvolume);
                                                denomination.appendChild(itemdenomination);
                                                keterangan.appendChild(itemketerangan);
                                                photo.appendChild(lightbox);
                                                lightbox.appendChild(linkphoto);
                                                linkphoto.appendChild(imgphoto);
                                                price.appendChild(itemprice);
                                                trinput.appendChild(divinput);
                                                divinput.appendChild(input);
                                                tdtrash.appendChild(divtrash);
                                                divtrash.appendChild(linktrash);

                                            }
                                        });
                                    });
                                </script>
                            <?php
                            }
                            ?>
                            <script>
                                // Dropdown MDL
                                document.getElementById('toggle<?= $paket['id'] ?>').addEventListener('click', function() {
                                    if (document.getElementById('close<?= $paket['id'] ?>').hasAttribute('hidden')) {
                                        document.getElementById('close<?= $paket['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('open<?= $paket['id'] ?>').setAttribute('hidden', '');
                                    } else {
                                        document.getElementById('open<?= $paket['id'] ?>').removeAttribute('hidden');
                                        document.getElementById('close<?= $paket['id'] ?>').setAttribute('hidden', '');
                                    }
                                });
                            </script>
                        <?php } ?>
                        
                        <script>
                            // Dropdown Paket
                            document.getElementById('togglepaket<?= $parent['id'] ?>').addEventListener('click', function() {
                                if (document.getElementById('closepaket<?= $parent['id'] ?>').hasAttribute('hidden')) {
                                    document.getElementById('closepaket<?= $parent['id'] ?>').removeAttribute('hidden');
                                    document.getElementById('openpaket<?= $parent['id'] ?>').setAttribute('hidden', '');
                                } else {
                                    document.getElementById('openpaket<?= $parent['id'] ?>').removeAttribute('hidden');
                                    document.getElementById('closepaket<?= $parent['id'] ?>').setAttribute('hidden', '');
                                }
                            });
                        </script>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- End Table Of Content -->

        <!-- Detail Pesanan Pembelian -->
        <div class="uk-card uk-card-default uk-margin-large-top uk-width-1-1@m" id="detailpesanan<?=$this->data['account']->parentid?>" hidden>
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">Detail Pesanan Klien</h3>
                    </div>
                </div>
            </div>
            <form method="post" action="pesanan/insertpurchase">
                <div class="uk-card-body uk-overflow-auto uk-margin">
                    <table class="uk-table uk-table-striped">
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
                                <th id="qty">Jumlah Pesanan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="itemorder">
                        </tbody>
                    </table>
                </div>
                <div class="uk-card-footer uk-text-right">
                    <button type="submit" class="uk-button uk-button-primary">Buat Pesanan</button>
                    <a onclick="resetitem()" class="uk-button uk-button-danger">Batal</a>
                </div>
            </form>
        </div>

        <script>
            <?php foreach ($mdls as $mdl){ ?>
                function removemdl<?= $mdl['id']; ?>() {
                    $('#item<?= $mdl['id'] ?>').remove();
                    $('#addtocart<?=$mdl['id'] ?>').attr('hidden', false);
                    $('#info<?= $mdl['id'] ?>').remove();

                    if ($(".itemrowmdl").length) {
                        $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', false);
                    } else {
                        $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', true);
                    }
                }

                function resetitem(){
                    $('.itemrowmdl').remove();
                    $('.info').remove();
                    $('.addtocart').attr('hidden', false);
                    $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', true);
                }
            <?php } ?>
        </script>
        <!-- End Detail Pesanan Pembelian -->
    
    <?php }
} ?>
<?= $this->endSection() ?>