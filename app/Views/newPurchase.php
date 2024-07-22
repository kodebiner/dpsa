<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
    <link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
    <link rel="stylesheet" href="css/select2.min.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>
<?= $this->endSection() ?>
<?= $this->section('main') ?>

<?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>

    <!-- Page Heading -->
        <?php if ($ismobile === false) { ?>
            <div class="tm-card-header uk-light uk-margin-remove-left">
                <div uk-grid class="uk-flex-middle uk-child-width-1-2">
                    <div>
                        <h3 class="tm-h3">Pesan Item</h3>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <h3 class="tm-h3 uk-text-center">Pesan Item</h3>
            <div class="uk-child-width-auto uk-flex-center" uk-grid>

                <!-- Button Filter -->
                <?php if ($authorize->hasPermission('client.read', $uid)) { ?>
                    <div>
                        <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                    </div>
                <?php } ?>
                <!-- Button Filter End -->
            </div>
        <?php } ?>
        <!-- End Of Page Heading -->

        <!-- Detail Pesanan Pembelian -->
        <?php if(!isset($_SESSION['item_purchase']) || empty($_SESSION['item_purchase'])){
            $hiddenDetailOrder = "hidden";
        }else{
            $hiddenDetailOrder = "";
        }?>

        <div class="uk-card uk-card-default uk-margin-large-top uk-width-1-1@m" id="detailpesanan<?=$this->data['account']->parentid?>" <?=$hiddenDetailOrder?>>
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
                            <?php if(!empty($_SESSION['item_purchase'])){ ?>
                                <?php foreach($_SESSION['item_purchase'] as $item){ ?>
                                    <tr id="item<?=$item['mdl']?>" class="itemrowmdl">
                                        <td><?=$item['name']?></td>
                                        <td><?=$item['length']?></td>
                                        <td><?=$item['width']?></td>
                                        <td><?=$item['heigth']?></td>
                                        <td><?=$item['volume']?></td>
                                        <?php
                                            if($item['denomination'] === "1"){
                                                $denomitem = "Unit";
                                            }elseif($item['denomination'] === "2"){
                                                $denomitem = "Meter Lari";
                                            }elseif($item['denomination'] === "3"){
                                                $denomitem = "Meter Persegi";
                                            }elseif($item['denomination'] === "4"){
                                                $denomitem = "Set";
                                            }
                                        ?>
                                        <td><?=$denomitem?></td>
                                        <td><?=$item['keterangan']?></td>
                                        <td><?=$item['photo']?></td>
                                        <td><?= "Rp. " . number_format((int)$item['price'], 0, ',', '.');" "; ?></td>
                                        <td><input class="uk-input uk-text-center" name="qty[<?=$item['mdl']?>]" id="input<?=$item['mdl']?>" value="<?=$item['qty']?>" type="number" placeholder="0"></td>
                                        <td>
                                            <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                                <?php if ($authorize->hasPermission('client.read', $uid)) { ?>
                                                    <div id="buttonadd<?=$item['mdl'] ?>">
                                                        <a class="uk-icon-button-delete" id="removemdl<?=$item['mdl'] ?>" onclick="removemdl(<?=$item['mdl'] ?>)" uk-icon="trash"></a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <script>
                                        $(document).ready(function(){
                                            $('#input<?=$item['mdl']?>').on('input', function() {
                                                var input<?=$item['mdl']?> = document.getElementById('input<?=$item['mdl']?>');

                                                $.ajax({
                                                    type: 'POST',
                                                    url: "pesanan/createQtySession",
                                                    data: {
                                                        id: <?=$item['mdl']?>,
                                                        qtyVal: input<?=$item['mdl']?>.value,
                                                    },
                                                    dataType: "json",
                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },
                                                    success: function() {
                                                        console.log('success', arguments);
                                                    }
                                                });

                                            });
                                        });
                                    </script>
                                <?php } ?>
                            <?php } ?>
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

            // Remove Detail Pesanan Function
            function removemdl(mdlid) {
               
                $.ajax({
                    type: 'POST',
                    url: "pesanan/unsetSessionItem",
                    dataType: "json",
                    data:{
                        id : mdlid,
                    },
                    error: function(data) {
                        console.log('error', arguments);
                    },
                    success: function(data) {
                        console.log('success', arguments);
                    }
                });

                $('#item'+ mdlid).remove();
                $('#info'+ mdlid).remove();

                if($('#addtocart'+ mdlid).length){
                    $('#addtocart'+ mdlid).attr('hidden', false);
                }else{
                    $('#buttonadd'+mdlid).append('<a class="uk-icon-button uk-icon" onClick="addCart('+mdlid+')" uk-icon="cart" id="addtocart'+mdlid+'"></a>');
                    
                }

                // if( document.getElementById('addtocart'+mdlid).onclick != null ){
                //     console.log('exist');
                // }else{
                //     console.log('undifined');
                //     $('#addtocart'+mdlid).click(function(){alert('olla')});
                // }
                

                if ($(".itemrowmdl").length) {
                    $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', false);
                } else {
                    $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', true);
                }
            }

            // Reset Item Function
            function resetitem(){
                $('.itemrowmdl').remove();
                $('.info').remove();
                $('.addtocart').attr('hidden', false);
                $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', true);
                $.ajax({
                    type: 'POST',
                    url: "pesanan/unsetSession",
                    dataType: "json",
                    error: function(data) {
                        console.log('error', arguments);
                    },
                    success: function(data) {
                        console.log('success', arguments);
                        location.replace('<?= base_url('/pesanan')?>');
                        // alert("Pesanan Di Batalkan");
                        UIkit.alert(element, options);
                        // window.location.reload(</?php base_url('/pesanan')?>);
                    }
                });
            }
        </script>
        <!-- End Detail Pesanan Pembelian -->

        <!-- form input -->
        <?php if ($ismobile === false) { ?>
            <form class="uk-margin" id="searchform" action="pesanan" method="GET">
                <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Cari :</div>
                            <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> /></div>
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
                <form id="searchform" action="pesanan" method="GET">
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
        
        <!-- Table Of Content -->
        <div class="uk-overflow-auto uk-margin">
            <table class="uk-table uk-table-middle uk-table-large uk-table-hover uk-table-divider">
                <thead>
                    <tr>
                        <th>No. Urut</th>
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
                <tbody id="rowitem">
                    <?php $i = 1; ?>
                    <?php foreach ($parents as $parent) { ?>
                        <tr id="rowpaket<?= $parent['id'] ?>">
                            <td><a class="uk-link-reset"><span id="closepaket<?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket<?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td>
                            <td colspan="9" class="tm-h3" style="text-transform: uppercase;"><?= $parent['name'] ?></td>
                        </tr>

                        <script>

                            function addCart(mdlReadded){
                                $.ajax({
                                    type: 'POST',
                                    url: "pesanan/createpurchase",
                                    data: {
                                        id: mdlReadded,
                                    },
                                    dataType: "json",
                                    error: function() {
                                        console.log('error', arguments);
                                    },
                                    success: function() {
                                        console.log('success', arguments);

                                        console.log(arguments[0][0]['name']);

                                        $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', false);
                                        $('#buttonadd'+ arguments[0][0]['mdl']).append("<p id='info"+ arguments[0][0]['mdl'] +"' class='info'>Item telah masuk daftar pesanan</p>");
                                        $('#addtocart'+ arguments[0][0]['mdl']).remove();
                                        $('#addtocart'+ arguments[0][0]['mdl']).attr('hidden',true);

                                        var item = document.createElement('tr');
                                        item.setAttribute('id','item'+ arguments[0][0]['mdl']);
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
                                        itemheight = document.createTextNode(arguments[0][0]['height']); 

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
                                        input.setAttribute('name','qty['+arguments[0][0]['mdl'] +']');
                                        input.setAttribute('id','input'+arguments[0][0]['mdl'] );
                                        input.setAttribute('placeholder','1');
                                        input.setAttribute('type','number');
                                        input.setAttribute('value','1');
                                        input.setAttribute('min','1');
                                        input.setAttribute('aria-label','X-Small');
                                        
                                        var tdtrash = document.createElement('td');
                                        var divtrash = document.createElement('div');
                                        var linktrash = document.createElement('a');
                                        linktrash.setAttribute('uk-icon','trash');
                                        linktrash.setAttribute('class','uk-icon-button-delete');
                                        linktrash.setAttribute('onclick','removemdl('+ arguments[0][0]['mdl'] +')');
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

                                        $('#input'+ arguments[0][0]['mdl']).change(function() {
                                            var input = document.getElementById('input'+arguments[0][0]['mdl']);
                                            $.ajax({
                                                type: 'POST',
                                                url: "pesanan/createQtySession",
                                                data: {
                                                    id: arguments[0][0]['mdl'],
                                                    qtyVal: input.value,
                                                },
                                                dataType: "json",
                                                error: function() {
                                                    console.log('error', arguments);
                                                },
                                                success: function() {
                                                    console.log('success', arguments);
                                                }
                                            });
                                        });
                                        
                                    }
                                });

                            }
                            
                            $(function () {
                                // Function Showing Paket
                                $('#openpaket<?= $parent['id'] ?>').on('click', function () {
                                    $.ajax({
                                        type: 'post',
                                        url: "mdl/requestdatapaket",
                                        data: {
                                            id: <?= $parent['id'] ?>,
                                        },
                                        dataType: "json",
                                        error: function() {
                                            console.log('error', arguments);
                                        },
                                        success: function() {
                                            console.log('success', arguments);
                                            var Paket       = arguments[0]['mdldata'][<?= $parent['id'] ?>]['paket'];
                                            var countPaket  = Object.keys(Paket).length;
                                            var idparent    = '<?= $idparent ?>';
                                            var idPaket     = '<?= $idpaket ?>';
                                            var parenthide  = '';
                                            var pakethide   = '';

                                            console.log(arguments[0]['mdldata'][<?= $parent['id'] ?>]);

                                            $('#openpaket<?= $parent['id'] ?>').attr('hidden',true);
                                            $('#closepaket<?= $parent['id'] ?>').removeAttr('hidden');

                                            $.each(Paket, function(i, item) {
                                                
                                                if ( idparent = <?= $parent['id'] ?>) {
                                                    var parenthide = '';
                                                } else {
                                                    var parenthide = 'hidden';
                                                }
                                                
                                                if (idPaket = Paket[i].id) {
                                                    var pakethide = '';
                                                } else {
                                                    var pakethide = 'hidden';
                                                }
                                                
                                                var trPaket = document.createElement('tr');
                                                trPaket.setAttribute('id','paketid' + Paket[i].id);        

                                                var tdName = document.createElement('td');
                                                tdName.setAttribute('style','text-transform: uppercase; font-weight: 400;');
                                                tdName.setAttribute('colspan','9');
                                                tdName.setAttribute('class','tm-h4');
                                                paketName = document.createTextNode(Paket[i].name);

                                                var tdIcon = document.createElement('td');
                                                tdIcon.setAttribute('class','uk-text-right');

                                                var linkIcon = document.createElement('a');
                                                linkIcon.setAttribute('id','toggle'+ Paket[i].id);
                                                linkIcon.setAttribute('class','uk-link-reset');
                                                
                                                var spanOpen = document.createElement('span');
                                                spanOpen.setAttribute('id','open'+ Paket[i].id);
                                                spanOpen.setAttribute('uk-icon','chevron-right');
                                                spanOpen.setAttribute('onClick','togglemdl('+ Paket[i].id +')');

                                                var spanClose = document.createElement('span');
                                                spanClose.setAttribute('id','close'+ Paket[i].id);
                                                spanClose.setAttribute('uk-icon','chevron-down');
                                                spanClose.setAttribute('hidden', true);
                                                spanClose.setAttribute('onClick','closetogglemdl('+ Paket[i].id +')');

                                                var itemorder = document.getElementById('rowitem');

                                                itemorder.appendChild(trPaket);
                                                trPaket.appendChild(tdIcon);
                                                tdIcon.appendChild(linkIcon);
                                                linkIcon.appendChild(spanOpen);
                                                linkIcon.appendChild(spanClose);
                                                trPaket.appendChild(tdName);
                                                tdName.appendChild(paketName);
                                                $("#paketid"+ Paket[i].id).insertAfter( $("#rowpaket<?= $parent['id'] ?>") );
                                            
                                            });

                                        }
                                    });
                                });

                                // Function Remove Row Paket
                                $('#closepaket<?= $parent['id'] ?>').on('click', function () {
                                    
                                    $('#closepaket<?= $parent['id'] ?>').attr('hidden',true);
                                    $('#openpaket<?= $parent['id'] ?>').removeAttr('hidden');

                                    $.ajax({
                                        type: 'post',
                                        url: "mdl/requestdatapaket",
                                        data: {
                                            id: <?= $parent['id'] ?>,
                                        },
                                        dataType: "json",
                                        error: function() {
                                            console.log('error', arguments);
                                        },
                                        success: function() {
                                            console.log('success', arguments);
                                            var Paket       = arguments[0]['mdldata'][<?= $parent['id'] ?>]['paket'];
                                            var countPaket  = Object.keys(Paket).length;
                                            var idparent    = '<?= $idparent ?>';
                                            var idPaket     = '<?= $idpaket ?>';
                                            var parenthide  = '';
                                            var pakethide   = '';
                                            
                                            $.each(Paket, function(i, item) {

                                                $('#paketid'+ Paket[i].id).remove();
                                                $.ajax({
                                                    type: 'post',
                                                    url: "mdl/requestmdldata",
                                                    data: {
                                                        id: Paket[i].id,
                                                    },
                                                    dataType: "json",
                                                    error: function() {
                                                        console.log('error', arguments);
                                                    },
                                                    success: function() {
                                                        console.log('success', arguments);

                                                        var mdldata     = arguments[0];
                                                        var paketId     = Paket[i].id;
                                                        var countMdl    = Object.keys(mdldata).length;

                                                        $.each(mdldata, function(paketId, itemMdl) {
                                                            $('#mdlDataItem'+ itemMdl.id).remove();
                                                        }); 
                                                    }
                                                });
                                            });

                                        }
                                    });

                                });
                            });

                            // Function Showing Mdl
                            function togglemdl(x){
                                $.ajax({
                                    type: 'post',
                                    url: "mdl/requestmdldata",
                                    data: {
                                        id: x,
                                    },
                                    dataType: "json",
                                    error: function() {
                                        console.log('error', arguments);
                                    },
                                    success: function() {

                                        <?php if(!empty($_SESSION['item_purchase'])) { ?>
                                            var itemInSession = <?php echo json_encode($_SESSION['item_purchase'] ); ?>;
                                        <?php }else{ ?>
                                            var itemInSession = [];
                                        <?php } ?>

                                        let itemAddedId = [];

                                        $.each(itemInSession, function(index, item) {
                                            itemAddedId.push(item.mdl);
                                        });

                                        console.log('success', arguments);

                                        $('#open'+ x).attr('hidden',true);
                                        $('#close'+ x).removeAttr('hidden');

                                        var mdldata     = arguments[0];
                                        var paketId     = x;
                                        var countMdl    = Object.keys(mdldata).length;

                                        console.log('qtymdl',countMdl);

                                        var itemorder = document.getElementById('rowitem');

                                        if (jQuery.isEmptyObject(mdldata)){
                                            alert('Data MDL Belum Tersedia');
                                        } else{
                                            $.each(mdldata, function(x, itemMdl) {
                                                // ROW MDL
                                                    var trMdl = document.createElement('tr');
                                                    trMdl.setAttribute('id','mdlDataItem' + itemMdl.id);

                                                    var tdIconMdl = document.createElement('td');

                                                    var tdNameMdl = document.createElement('td');
                                                    tdNameMdl.setAttribute('style','text-transform: uppercase; font-weight: 400;');
                                                    tdNameMdl.setAttribute('class','tm-h4');
                                                    MdlName = document.createTextNode(itemMdl.name);

                                                    var tdWidthMdl = document.createElement('td');
                                                    widthMdl = document.createTextNode(itemMdl.width);

                                                    var tdLengthMdl = document.createElement('td');
                                                    lengthMdl = document.createTextNode(itemMdl.length);

                                                    var tdHeightMdl = document.createElement('td');
                                                    heightMdl = document.createTextNode(itemMdl.height);

                                                    var tdVolumeMdl = document.createElement('td');
                                                    volumeMdl = document.createTextNode(itemMdl.volume);

                                                    var denom = "";
                                                    if (itemMdl.denomination == "1") {
                                                        denom = "Unit";
                                                    } else if (itemMdl.denomination == "2") {
                                                        denom = "Meter Lari";
                                                    } else if (itemMdl.denomination == "3") {
                                                        denom = "Meter Persegi";
                                                    } else if (itemMdl.denomination == "4") {
                                                        denom = "Set";
                                                    }
                                                    var tdDenominationMdl = document.createElement('td');
                                                    denominationMdl = document.createTextNode(denom);

                                                    var tdPriceMdl = document.createElement('td');

                                                    var	number_string = itemMdl.price.toString(),
                                                        sisa 	= number_string.length % 3,
                                                        rupiah 	= number_string.substr(0, sisa),
                                                        ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                                                            
                                                    if (ribuan) {
                                                        separator = sisa ? ',' : '';
                                                        rupiah += separator + ribuan.join(',');
                                                    }
                                                    priceMdl = document.createTextNode('Rp.' + rupiah);

                                                    var tdKeteranganMdl = document.createElement('td');
                                                    keteranganMdl = document.createTextNode(itemMdl.keterangan);

                                                    var tdPhotoMdl = document.createElement('td');

                                                    divLightboxMdl = document.createElement('div');
                                                    divLightboxMdl.toggleAttribute('uk-lightbox');

                                                    linkPhotoMdl = document.createElement('a');
                                                    linkPhotoMdl.setAttribute('class','uk-inline');
                                                    linkPhotoMdl.setAttribute('href','img/mdl/'+ itemMdl.photo);
                                                    linkPhotoMdl.setAttribute('role','button');

                                                    imgMdl = document.createElement("img");
                                                    imgMdl.setAttribute('id', 'img'+ itemMdl.id);
                                                    imgMdl.setAttribute('class','uk-preserve-width uk-border-circle');
                                                    imgMdl.setAttribute('src','img/mdl/'+ itemMdl.photo);
                                                    imgMdl.setAttribute('width','40');
                                                    imgMdl.setAttribute('height','40');
                                                    imgMdl.setAttribute('alt', itemMdl.photo);

                                                    var tdButtonMdl = document.createElement('td');
                                                    tdButtonMdl.setAttribute('class','uk-text-center');

                                                    divButtonMdl = document.createElement('div');
                                                    divButtonMdl.setAttribute('class','uk-grid-small uk-flex-center uk-flex-middle');
                                                    divButtonMdl.toggleAttribute('uk-grid');

                                                    divUpdateMdl = document.createElement('div');
                                                    divUpdateMdl.setAttribute('id','buttonadd'+ itemMdl.id);

                                                    if(itemAddedId.includes(itemMdl.id)){
                                                        linkUpdateMdl = document.createElement('p');
                                                        linkUpdateMdl.setAttribute('id','info'+ itemMdl.id +'');
                                                        linkUpdateMdl.setAttribute('class','info');
                                                        addeditem = document.createTextNode("Item telah masuk daftar pesanan");
                                                    }else{
                                                        linkUpdateMdl = document.createElement('a');
                                                        linkUpdateMdl.setAttribute('id','addtocart'+ itemMdl.id);
                                                        linkUpdateMdl.setAttribute('class','uk-icon-button');
                                                        linkUpdateMdl.setAttribute('uk-icon','cart');
                                                        linkUpdateMdl.toggleAttribute('uk-toggle');
                                                    }

                                                // END ROW MDL

                                                itemorder.appendChild(trMdl);
                                                trMdl.appendChild(tdIconMdl);
                                                trMdl.appendChild(tdNameMdl);
                                                tdNameMdl.appendChild(MdlName);
                                                trMdl.appendChild(tdLengthMdl);
                                                tdLengthMdl.appendChild(lengthMdl);
                                                trMdl.appendChild(tdWidthMdl);
                                                tdWidthMdl.appendChild(widthMdl);
                                                trMdl.appendChild(tdHeightMdl);
                                                tdHeightMdl.appendChild(heightMdl);
                                                trMdl.appendChild(tdVolumeMdl);
                                                tdVolumeMdl.appendChild(volumeMdl);
                                                trMdl.appendChild(tdDenominationMdl);
                                                tdDenominationMdl.appendChild(denominationMdl);
                                                trMdl.appendChild(tdKeteranganMdl);
                                                tdKeteranganMdl.appendChild(keteranganMdl);
                                                trMdl.appendChild(tdPriceMdl);
                                                tdPriceMdl.appendChild(priceMdl);
                                                trMdl.appendChild(tdPhotoMdl);
                                                tdPhotoMdl.appendChild(divLightboxMdl);
                                                divLightboxMdl.appendChild(linkPhotoMdl);
                                                linkPhotoMdl.appendChild(imgMdl);
                                                trMdl.appendChild(tdButtonMdl);
                                                tdButtonMdl.appendChild(divButtonMdl);
                                                divButtonMdl.appendChild(divUpdateMdl);
                                                divUpdateMdl.appendChild(linkUpdateMdl);
                                                if(itemAddedId.includes(itemMdl.id)){
                                                    linkUpdateMdl.appendChild(addeditem);
                                                }
                                                $("#mdlDataItem" + itemMdl.id).insertAfter( $("#paketid"+ paketId));

                                                // Add To Cart Function
                                                $('#addtocart'+ itemMdl.id).on('click', function() {
                                                // $('#addtocart'+ itemMdl.id).click(function() {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: "pesanan/createpurchase",
                                                        data: {
                                                            id: itemMdl.id,
                                                            // paket: x,
                                                        },
                                                        dataType: "json",
                                                        error: function() {
                                                            console.log('error', arguments);
                                                        },
                                                        success: function() {
                                                            console.log('success', arguments);

                                                            $('#detailpesanan<?=$this->data['account']->parentid?>').attr('hidden', false);
                                                            $('#addtocart'+ itemMdl.id).attr('hidden',true);
                                                            $('#buttonadd'+ itemMdl.id).append("<p id='info"+ itemMdl.id +"' class='info'>Item telah masuk daftar pesanan</p>");

                                                            var item = document.createElement('tr');
                                                            item.setAttribute('id','item'+ itemMdl.id);
                                                            item.setAttribute('class','itemrowmdl');

                                                            var name = document.createElement('td');
                                                            name.setAttribute('id','name');
                                                            itemname = document.createTextNode(itemMdl.name);

                                                            var length = document.createElement('td');
                                                            length.setAttribute('id','length');
                                                            length.setAttribute('class','uk-text-center'); 
                                                            itemlength = document.createTextNode(itemMdl.length);

                                                            var width = document.createElement('td');
                                                            width.setAttribute('id','width');
                                                            width.setAttribute('class','uk-text-center'); 
                                                            itemwidth = document.createTextNode(itemMdl.width);

                                                            var height = document.createElement('td');
                                                            height.setAttribute('id','height');
                                                            height.setAttribute('class','uk-text-center'); 
                                                            itemheight = document.createTextNode(itemMdl.height); 

                                                            var volume = document.createElement('td');
                                                            volume.setAttribute('id','volume');
                                                            volume.setAttribute('class','uk-text-center'); 
                                                            itemvolume = document.createTextNode(itemMdl.volume);  

                                                            var denom = "";
                                                            if (itemMdl.denomination === "1") {
                                                                denom = "Unit";
                                                            } else if (itemMdl.denomination === "2") {
                                                                denom = "Meter Lari";
                                                            } else if (itemMdl.denomination === "3") {
                                                                denom = "Meter Persegi";
                                                            } else if (itemMdl.denomination === "4") {
                                                                denom = "Set";
                                                            }

                                                            var denomination = document.createElement('td');
                                                            denomination.setAttribute('id','denomination'); 
                                                            denomination.setAttribute('class','uk-text-center'); 
                                                            itemdenomination = document.createTextNode(denom);  
                                                            
                                                            var keterangan = document.createElement('td');
                                                            keterangan.setAttribute('id','keterangan');
                                                            itemketerangan = document.createTextNode(itemMdl.keterangan);  

                                                            var photo = document.createElement('td');
                                                            photo.setAttribute('id','photo'); 
                                                            itemphoto = document.createTextNode(itemMdl.photo); 

                                                            var lightbox = document.createElement('div');
                                                            lightbox.toggleAttribute('uk-lightbox');

                                                            var linkphoto = document.createElement('a');
                                                            linkphoto.setAttribute('class','uk-inline');
                                                            linkphoto.setAttribute('href','img/mdl/'+ itemMdl.photo);
                                                            linkphoto.setAttribute('role','button');

                                                            var imgphoto = document.createElement('img');
                                                            imgphoto.setAttribute('class','uk-preserve-width uk-border-circle');
                                                            imgphoto.setAttribute('src','img/mdl/'+ itemMdl.photo);
                                                            imgphoto.setAttribute('width','40');
                                                            imgphoto.setAttribute('height','40');
                                                            imgphoto.setAttribute('alt', itemMdl.photo);
                                                            
                                                            var price = document.createElement('td');
                                                            price.setAttribute('id','price'); 
                                                            itemprice = document.createTextNode("Rp. " + itemMdl.price + ",-"); 

                                                            var trinput = document.createElement('td');
                                                            var divinput = document.createElement('div');
                                                            divinput.setAttribute('class','uk-margin');

                                                            var input = document.createElement('input');
                                                            input.setAttribute('class','uk-input uk-form-width-small uk-text-center');
                                                            input.setAttribute('name','qty['+itemMdl.id +']');
                                                            input.setAttribute('id','input'+itemMdl.id );
                                                            input.setAttribute('placeholder','1');
                                                            input.setAttribute('type','number');
                                                            input.setAttribute('value','1');
                                                            input.setAttribute('min','1');
                                                            input.setAttribute('aria-label','X-Small');
                                                            
                                                            var tdtrash = document.createElement('td');
                                                            var divtrash = document.createElement('div');
                                                            var linktrash = document.createElement('a');
                                                            linktrash.setAttribute('uk-icon','trash');
                                                            linktrash.setAttribute('class','uk-icon-button-delete');
                                                            linktrash.setAttribute('onclick','removemdl('+ itemMdl.id +')');
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

                                                            $('#input'+ itemMdl.id).change(function() {
                                                                var input = document.getElementById('input'+itemMdl.id);
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: "pesanan/createQtySession",
                                                                    data: {
                                                                        id: itemMdl.id,
                                                                        qtyVal: input.value,
                                                                    },
                                                                    dataType: "json",
                                                                    error: function() {
                                                                        console.log('error', arguments);
                                                                    },
                                                                    success: function() {
                                                                        console.log('success', arguments);
                                                                    }
                                                                });
                                                            });
                                                            
                                                        }
                                                    });
                                                });

                                            }); 
                                        }

                                    }
                                });
                            }
                            
                            // Function Remove Row MDL
                            function closetogglemdl(x){
                                $('#close'+ x).attr('hidden',true);
                                $('#open'+ x).removeAttr('hidden');

                                $.ajax({
                                    type: 'post',
                                    url: "mdl/requestmdldata",
                                    data: {
                                        id: x,
                                    },
                                    dataType: "json",
                                    error: function() {
                                        console.log('error', arguments);
                                    },
                                    success: function() {
                                        console.log('success', arguments);

                                        $('#importMdl'+ x ).remove();

                                        var mdldata     = arguments[0];
                                        var paketId     = x;
                                        var countMdl    = Object.keys(mdldata).length;

                                        $.each(mdldata, function(x, itemMdl) {
                                            $('#mdlDataItem'+ itemMdl.id).remove();
                                        }); 
                                    }
                                });
                            }
                        </script>
                    <?php } ?>

                </tbody>
            </table>
            <?= $pager ?>
        </div>
        <!-- End Table Of Content -->
<?php } ?>

<div id="addItemScript">

</div>


<?= $this->endSection() ?>