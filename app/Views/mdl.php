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
                    <h3 class="tm-h3">Daftar MDL</h3>
                </div>

                <!-- Button Trigger Modal Add -->
                <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                    <div class="uk-text-right">
                        <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Kategori</button>
                    </div>
                <?php } ?>
                <!-- End Of Button Trigger Modal Add -->
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center">Daftar MDL</h3>
        <div class="uk-child-width-auto uk-flex-center" uk-grid>
            <!-- Button Trigger Modal Add -->
            <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
                <div>
                    <button type="button" class="uk-button uk-button-primary uk-preserve-color" uk-toggle="target: #modaladd">Tambah Kategori</button>
                </div>
            <?php } ?>
            <!-- Button Trigger Modal Add End -->

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
        <form class="uk-margin" id="searchform" action="mdl" method="GET">
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
                        <td>
                            <select class="uk-select uk-form-width-xsmall" aria-label="Parent" id="parentList<?= $parent['id'] ?>" >
                                <?php
                                for ($x = 1; $x <= $countparents; $x++) {
                                    if ($x == $parent['ordering']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <!-- <td><a class="uk-link-reset" id="togglepaket</?= $parent['id'] ?>" uk-toggle="target: .togglepaket</?= $parent['id'] ?>"><span id="closepaket</?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket</?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td> -->
                        <td><a class="uk-link-reset"><span id="closepaket<?= $parent['id'] ?>" uk-icon="chevron-down" hidden></span><span id="openpaket<?= $parent['id'] ?>" uk-icon="chevron-right"></span></a></td>
                        
                        <td colspan="9" class="tm-h3" style="text-transform: uppercase;"><?= $parent['name'] ?></td>
                        <td class="uk-text-center">
                            <div class="uk-grid-small uk-flex-center uk-flex-middle" uk-grid>
                                <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                                    <div>
                                        <a class="uk-icon-button" href="#modalupdatepaket<?= $parent['id'] ?>" uk-icon="pencil" uk-toggle></a>
                                    </div>
                                <?php } ?>
                                <?php if ($authorize->hasPermission('admin.mdl.delete', $uid)) { ?>
                                    <div>
                                        <a class="uk-icon-button-delete" href="paket/delete/<?= $parent['id'] ?>" uk-icon="trash" onclick="return confirm('Anda yakin ingin menghapus data ini?')"></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>

                    <script>
                        // Reposiition Parent List
                        $('#parentList<?= $parent['id'] ?>').change(function() {
                            $.ajax({
                                type: 'POST',
                                url: "mdl/newreorderingparent",
                                data: {
                                    id: <?= $parent['id'] ?>,
                                    order: $("#parentList<?= $parent['id'] ?>").val()
                                },
                                dataType: "json",
                                error: function(parentOrder) {
                                    console.log('error', arguments);
                                },
                                success: function(parentOrder) {
                                    console.log(parentOrder);
                                    location.reload();
                                }
                            });
                        });
                        
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
                                            
                                            var tdOrdering = document.createElement('td');
                                            var selectOrder = document.createElement('select');
                                            selectOrder.setAttribute('id','paketList' + Paket[i].id );
                                            selectOrder.setAttribute('class','uk-select uk-form-width-xsmall uk-margin-left');
                                            selectOrder.setAttribute('Aria-label', 'Paket');

                                            for ($x = 1; $x <= countPaket; $x++) {
                                                var option = document.createElement("option");
                                                if ($x == Paket[i].ordering) {
                                                    option.selected = true;
                                                } else {
                                                    option.selected = false;
                                                }
                                                option.value    = $x;
                                                option.text     = $x;
                                                selectOrder.appendChild(option);
                                            }

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
                                            // linkIcon.setAttribute('uk-toggle','target: .togglemdl'+ Paket[i].id);
                                            // closeButton.setAttribute('onClick', 'removeImgCreatemdl("+ paketId + + itemMdl.id +")');
                                            
                                            var spanOpen = document.createElement('span');
                                            spanOpen.setAttribute('id','open'+ Paket[i].id);
                                            spanOpen.setAttribute('uk-icon','chevron-right');
                                            spanOpen.setAttribute('onClick','togglemdl('+ Paket[i].id +')');

                                            var spanClose = document.createElement('span');
                                            spanClose.setAttribute('id','close'+ Paket[i].id);
                                            spanClose.setAttribute('uk-icon','chevron-down');
                                            spanClose.setAttribute('hidden', true);
                                            spanClose.setAttribute('onClick','closetogglemdl('+ Paket[i].id +')');
                                            
                                            var tdButton = document.createElement('td');
                                            tdButton.setAttribute('class','uk-text-center');

                                            divButton = document.createElement('div');
                                            divButton.setAttribute('class','uk-grid-small uk-flex-center uk-flex-middle');
                                            divButton.toggleAttribute('uk-grid');

                                            divCreate = document.createElement('div');

                                            linkAdd   = document.createElement('a');
                                            linkAdd.setAttribute('class','uk-icon-button-success');
                                            linkAdd.setAttribute('href','#modaladdmdl' + Paket[i].id);
                                            linkAdd.setAttribute('uk-icon','plus');
                                            linkAdd.toggleAttribute('uk-toggle');

                                            divUpdate = document.createElement('div');

                                            linkUpdate = document.createElement('a');
                                            linkUpdate.setAttribute('class','uk-icon-button');
                                            linkUpdate.setAttribute('href','#modalupdate' + Paket[i].id);
                                            linkUpdate.setAttribute('uk-icon','pencil');
                                            linkUpdate.toggleAttribute('uk-toggle');

                                            divDelete = document.createElement('div');
                                            linkDelete = document.createElement('a');
                                            linkDelete.setAttribute('class','uk-icon-button-delete');
                                            linkDelete.setAttribute('href','paket/delete/' + Paket[i].id);
                                            linkDelete.setAttribute('uk-icon','trash');
                                            linkDelete.setAttribute('onClick','return confirm("Anda yakin ingin menghapus data ini?")');

                                            var itemorder = document.getElementById('rowitem');

                                            itemorder.appendChild(trPaket);
                                            trPaket.appendChild(tdOrdering);
                                            tdOrdering.appendChild(selectOrder);
                                            trPaket.appendChild(tdIcon);
                                            tdIcon.appendChild(linkIcon);
                                            linkIcon.appendChild(spanOpen);
                                            linkIcon.appendChild(spanClose);
                                            trPaket.appendChild(tdName);
                                            tdName.appendChild(paketName);
                                            trPaket.appendChild(tdButton);
                                            tdButton.appendChild(divButton);
                                            divButton.appendChild(divCreate);
                                            divButton.appendChild(divUpdate);
                                            divButton.appendChild(divDelete);
                                            divCreate.appendChild(linkAdd);
                                            divUpdate.appendChild(linkUpdate);
                                            divDelete.appendChild(linkDelete);
                                            $("#paketid"+ Paket[i].id).insertAfter( $("#rowpaket<?= $parent['id'] ?>") );

                                            $("#modalcontainer").append('<div class="uk-modal-container" id="modalupdate'+Paket[i].id+'" role="dialog"> <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto></div></div>');

                                            // Modal Update Paket 
                                            $("#modalcontainer").append('<div class="uk-modal-container" id="modalupdate'+Paket[i].id+'" uk-modal><div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto><div class="uk-modal-content"><div class="uk-modal-header"><h2 class="uk-modal-title">Ubah Sub Kategori '+Paket[i].name+'</h2><button class="uk-modal-close-default" type="button" uk-close></button></div><div class="uk-modal-body"><form class="uk-form-stacked" role="form" action="paket/update/'+Paket[i].id+'" method="post"><div class="uk-margin-bottom"><label class="uk-form-label" for="name">Nama Sub Kategori</label><div class="uk-form-controls"><input type="text" class="uk-input" id="name" name="name" value="'+Paket[i].name+'" /></div></div><div class="uk-modal-footer"><div class="uk-flex-right"><button class="uk-button uk-button-primary" type="submit">Simpan</button></div></div></form></div></div></div></div>');

                                            // Modal Add Mdl to Paket
                                            $("#modalcontainer").append('<div class="uk-modal-container" id="modaladdmdl'+Paket[i].id+'" uk-modal><div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto><div class="uk-modal-header"><h2 class="uk-modal-title">Tambah MDL '+Paket[i].name+'</h2><button class="uk-modal-close-default" type="button" uk-close></button></div><div class="uk-modal-body"><form id="formPaket'+Paket[i].id+'" class="uk-form-stacked" role="form" action="mdl/create/'+Paket[i].id+'" method="post"><div class="uk-margin"><label class="uk-form-label" for="findmdl">Cari MDL yang sudah tersedia</label><select id="mdl-search'+Paket[i].id+'" class="js-example-data-array" multiple="multiple" style="width:100%;"></select></div><div class="uk-text-right uk-margin"><button class="uk-button uk-button-secondary" type="button" uk-toggle="target: .togglenewmdl'+Paket[i].id+'">Buat MDL Baru</button></div><div class="togglenewmdl'+Paket[i].id+'" hidden><div class="uk-margin-bottom"><label class="uk-form-label" for="name">Nama</label><div class="uk-form-controls"><input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required /></div></div> <div class="uk-margin"><label class="uk-form-label" for="denomination">Satuan</label><select class="uk-select" aria-label="Satuan" id="denomination'+Paket[i].id+'" name="denomination" required><option value="" selected disabled hidden>Pilih Satuan</option><option value="1">Unit</option><option value="2">Meter Lari</option><option value="3">Meter Persegi</option><option value="4">Set</option></select></div><div id="dimentions'+Paket[i].id+'"></div><div class="uk-margin"><label class="uk-form-label" for="price">Keterangan</label><div class="uk-margin"><textarea class="uk-textarea" type="text" name="keterangan" rows="5" placeholder="Keterangan" aria-label="Textarea"></textarea></div></div><div class="uk-margin"><label class="uk-form-label" for="price">Harga</label><div class="uk-form-controls"><input type="text" class="uk-input" id="price" name="price" placeholder="Harga" value="" data-type="currency" required /></div></div><div class="uk-margin" id="image-container-createmdl-'+Paket[i].id+'"><div id="image-containermdl-'+Paket[i].id+'" class="uk-form-controls"><label class="uk-form-label" for="photo">Foto MDL</label><input id="photocreatemdl'+Paket[i].id+'" name="photo" hidden /><div id="js-upload-createmdl-'+Paket[i].id+'" class="js-upload-createmdl-'+Paket[i].id+' uk-placeholder uk-text-center"><span uk-icon="icon: cloud-upload"></span><span class="uk-text-middle">Tarik dan lepas foto disini atau</span><div uk-form-custom><input type="file"><span class="uk-link uk-preserve-color">pilih satu</span></div></div><progress id="js-progressbar-createmdl-'+Paket[i].id+'" class="uk-progress" value="0" max="100" hidden></progress></div></div><div class="uk-modal-footer"><div class="uk-text-right"><button class="uk-button uk-button-primary" type="submit">Simpan</button></div></div></div></form></div></div></div>');

                                            // Script Select2 Paket
                                            $('#formPaket'+Paket[i].id).append("<script>$('#mdl-search"+Paket[i].id+"').select2({placeholder: 'Cari...',minimumInputLength: 3,ajax: {url: 'mdl/datapaket',dataType: 'json',type: 'GET',data: function (term) {return {search: term,paketid: "+Paket[i].id+",};},processResults: function (data) {console.log(data); console.log(data);return {results: $.map(data, function (item) {return { text: item.text, id: item.id}})};}},templateSelection: function(mdldata) {$.ajax({url: 'mdl/submitcat',type: 'POST',dataType: 'json',data: { paketid: "+Paket[i].id+", mdlid: mdldata.id},success: function(mdl) {console.log(mdl); $('#mdl-search').select2().val(null).trigger('change'); }});},});<\/script>");
                                            
                                            // Script Add MDL To Paket
                                            $('#formPaket' + Paket[i].id).append("<script> document.getElementById('denomination" + Paket[i].id + "').addEventListener('change', function() { if (this.value == '1' || this.value == '2' || this.value == '3' || this.value == '4') {  var elements = document.getElementById('contdim" + Paket[i].id + "');  if (elements) {  elements.remove();  }  var dimentions = document.getElementById('dimentions" + Paket[i].id + "');  var contdim = document.createElement('div');  contdim.setAttribute('id', 'contdim" + Paket[i].id + "');  var contlength = document.createElement('div');  contlength.setAttribute('class', 'uk-margin');  var lablength = document.createElement('label');  lablength.setAttribute('class', 'uk-form-label');  lablength.setAttribute('for', 'length');  lablength.innerHTML = 'Panjang';  var coninputl = document.createElement('div');  coninputl.setAttribute('class', 'uk-form-controls');  var inputl = document.createElement('input');  inputl.setAttribute('class', 'uk-input');  inputl.setAttribute('type', 'text');  inputl.setAttribute('id', 'length" + Paket[i].id + "');  inputl.setAttribute('name', 'length');  inputl.setAttribute('placeholder', 'Panjang');  inputl.setAttribute('required', '');  var contw = document.createElement('div');  contw.setAttribute('class', 'uk-margin');  var labw = document.createElement('label');  labw.setAttribute('class', 'uk-form-label');  labw.setAttribute('for', 'width');  labw.innerHTML = 'Lebar';  var coninputw = document.createElement('div');  coninputw.setAttribute('class', 'uk-form-controls');  var inputw = document.createElement('input');  inputw.setAttribute('class', 'uk-input');  inputw.setAttribute('type', 'text');  inputw.setAttribute('id', 'width" + Paket[i].id + "');  inputw.setAttribute('name', 'width');  inputw.setAttribute('placeholder', 'Lebar');  inputw.setAttribute('required', '');  var conth = document.createElement('div');  conth.setAttribute('class', 'uk-margin');  var labh = document.createElement('label');  labh.setAttribute('class', 'uk-form-label');  labh.setAttribute('for', 'height');  labh.innerHTML = 'Tinggi';  var coninputh = document.createElement('div');  coninputh.setAttribute('class', 'uk-form-controls');  var inputh = document.createElement('input');  inputh.setAttribute('class', 'uk-input');  inputh.setAttribute('type', 'text');  inputh.setAttribute('id', 'height" + Paket[i].id + "');  inputh.setAttribute('name', 'height');  inputh.setAttribute('placeholder', 'Tinggi');  inputh.setAttribute('required', '');  coninputl.appendChild(inputl);  contlength.appendChild(lablength);  contlength.appendChild(coninputl);  contdim.appendChild(contlength);  coninputw.appendChild(inputw);  contw.appendChild(labw);  contw.appendChild(coninputw);  contdim.appendChild(contw);  coninputh.appendChild(inputh);  conth.appendChild(labh);  conth.appendChild(coninputh);  contdim.appendChild(conth);  dimentions.appendChild(contdim); } else {  var dim = document.getElementById('contdim" + Paket[i].id + "');  if (dim) {      dim.remove();  } } }); var bar = document.getElementById('js-progressbar-createmdl-" + Paket[i].id + "'); UIkit.upload('.js-upload-createmdl-" + Paket[i].id + "', { url: 'upload/photomdl', multiple: false, name: 'uploads', param: {lorem: 'ipsum'}, method: 'POST', type: 'json', beforeSend: function() {  console.log('beforeSend', arguments); }, beforeAll: function() {  console.log('beforeAll', arguments); }, load: function() {  console.log('load', arguments); }, error: function() {  console.log('error', arguments);  var error = arguments[0].xhr.response.message.uploads;  alert(error); }, complete: function() {  console.log('complete', arguments);  var filename = arguments[0].response;  if (document.getElementById('display-container-createmdl-" + Paket[i].id + "')) {      document.getElementById('display-container-createmdl-" + Paket[i].id + "').remove();  }  document.getElementById('photocreatemdl-" + Paket[i].id + "').value = filename;  var imgContainer = document.getElementById('image-container-createmdl-" + Paket[i].id + "');  var displayContainer = document.createElement('div');  displayContainer.setAttribute('id', 'display-container-createmdl-" + Paket[i].id + "');  displayContainer.setAttribute('class', 'uk-inline');  var displayImg = document.createElement('div');  displayImg.setAttribute('uk-lightbox', 'animation: fade');  displayImg.setAttribute('class', 'uk-inline');  var link = document.createElement('a');  link.setAttribute('href', 'img/mdl/' + filename);  var image = document.createElement('img');  image.setAttribute('src', 'img/mdl/' + filename);  var closeContainer = document.createElement('div');  closeContainer.setAttribute('class', 'uk-position-small uk-position-right');  var closeButton = document.createElement('a');  closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');  closeButton.setAttribute('onClick', 'removeImgCreatemdl" + Paket[i].id + "()');  closeButton.setAttribute('uk-icon', 'close');  closeContainer.appendChild(closeButton);  displayContainer.appendChild(displayImg);  displayContainer.appendChild(closeContainer);  link.appendChild(image);  displayImg.appendChild(link);  imgContainer.appendChild(displayContainer);  document.getElementById('js-upload-createmdl-" + Paket[i].id + "').setAttribute('hidden', ''); }, loadStart: function(e) {  console.log('loadStart', arguments);  bar.removeAttribute('hidden');  bar.max = e.total;  bar.value = e.loaded; }, progress: function(e) {  console.log('progress', arguments);  bar.max = e.total;  bar.value = e.loaded; }, loadEnd: function(e) {  console.log('loadEnd', arguments);  bar.max = e.total;  bar.value = e.loaded; }, completeAll: function() {  console.log('completeAll', arguments);  setTimeout(function() {      bar.setAttribute('hidden', 'hidden');  }, 1000);  alert('Data Berhasil Terunggah');  } }); function removeImgCreatemdl" + Paket[i].id + "() {  $.ajax({ type: 'post',  url: 'upload/removephotomdl', data: {'photo': document.getElementById('photocreatemdl-" + Paket[i].id + "').value},  dataType: 'json',  error: function() { console.log('error', arguments); }, success: function() { console.log('success', arguments);  var pesan = arguments[0][1]; if (document.getElementById('display-container-createmdl-" + Paket[i].id + "')) {  document.getElementById('display-container-createmdl-" + Paket[i].id + "').remove();  } document.getElementById('photocreatemdl-" + Paket[i].id + "').value = ''; alert(pesan); document.getElementById('js-upload-createmdl-" + Paket[i].id + "').removeAttribute('hidden'); } });}<\/script>");

                                            // Script Upload
                                            $("<script>var bar = document.getElementById('js-progressbar-createmdl-" + Paket[i].id +"');UIkit.upload('.js-upload-createmdl-"+ Paket[i].id +"', {url: 'upload/photomdl',multiple: false,name: 'uploads',param: { lorem: 'ipsum'},method: 'POST',type: 'json',beforeSend: function() { console.log('beforeSend', arguments);},beforeAll: function() {console.log('beforeAll', arguments);},load: function() {console.log('load', arguments);},error: function() {console.log('error', arguments);var error = arguments[0].xhr.response.message.uploads;alert(error);},complete: function() {console.log('complete', arguments);var filename = arguments[0].response;console.log(filename);if (document.getElementById('display-container-createmdl-"+ Paket[i].id +"')) {document.getElementById('display-container-createmdl-"+ Paket[i].id +"').remove();};document.getElementById('photocreatemdl"+ Paket[i].id +"').value = filename;var imgContainer = document.getElementById('image-container-createmdl-"+ Paket[i].id +"');var displayContainer = document.createElement('div');displayContainer.setAttribute('id', 'display-container-createmdl-"+ Paket[i].id +"');displayContainer.setAttribute('class', 'uk-inline');var displayImg = document.createElement('div');displayImg.setAttribute('uk-lightbox', 'animation: fade');displayImg.setAttribute('class', 'uk-inline');var link = document.createElement('a');link.setAttribute('href', 'img/mdl/'+filename );var image = document.createElement('img');image.setAttribute('src', 'img/mdl/'+filename);var closeContainer = document.createElement('div');closeContainer.setAttribute('class', 'uk-position-small uk-position-right');var closeButton = document.createElement('a');closeButton.setAttribute('id', 'removeImgCreatemdl"+ Paket[i].id +"'); closeButton.setAttribute('onClick', 'removeImgCreatemdl("+ Paket[i].id +")');closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');closeButton.setAttribute('uk-icon', 'close');closeContainer.appendChild(closeButton);displayContainer.appendChild(displayImg);displayContainer.appendChild(closeContainer);link.appendChild(image);displayImg.appendChild(link);imgContainer.appendChild(displayContainer);document.getElementById('js-upload-createmdl-"+ Paket[i].id +"').setAttribute('hidden', '');},loadStart: function(e) {console.log('loadStart', arguments);bar.removeAttribute('hidden');bar.max = e.total;bar.value = e.loaded;},progress: function(e) {console.log('progress', arguments);bar.max = e.total;bar.value = e.loaded;},loadEnd: function(e) {console.log('loadEnd', arguments);bar.max = e.total;bar.value = e.loaded;},completeAll: function() {console.log('completeAll', arguments);setTimeout(function() {bar.setAttribute('hidden', 'hidden');}, 1000);alert('Data Berhasil Terunggah');}});</" + "script>").appendTo(document.getElementById("formPaket"+ Paket[i].id));

                                            // Sript Remove Image
                                            $('<script>function removeImgCreatemdl(x) { console.log(x); $.ajax({ type: "post",url: "upload/removephotomdl", data: {"photo": document.getElementById("photocreatemdl' + Paket[i].id +'").value}, dataType: "json", error: function() { console.log("error", arguments);},success: function() {console.log("success", arguments); var pesan = arguments[0][1]; document.getElementById("display-container-createmdl-'+ Paket[i].id +'").remove();document.getElementById("photocreatemdl' + Paket[i].id +'").value = "";alert(pesan); document.getElementById("js-upload-createmdl-' + Paket[i].id +'").removeAttribute("hidden", "");}}); }</' + 'script>').appendTo(document.getElementById("formPaket"+ Paket[i].id));
                                        
                                            // Reposiition Paket List
                                            $('#paketList'+ Paket[i].id).change(function() {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: "mdl/reorderingpaket",
                                                    data: {
                                                        id: Paket[i].id,
                                                        parent: <?= $parent['id'] ?>,
                                                        order: $("#paketList"+ Paket[i].id).val()
                                                    },
                                                    dataType: "json",
                                                    error: function(paketOrder) {
                                                        console.log('error', arguments);
                                                    },
                                                    success: function(paketOrder) {
                                                        console.log(paketOrder);
                                                        location.reload();

                                                        console.log("pketid",<?= $parent['id'] ?>);

                                                        // $.ajax({
                                                        //     type: 'post',
                                                        //     url: "mdl/requestdatapaket",
                                                        //     data: {
                                                        //         id: </?= $parent['id'] ?>,
                                                        //     },
                                                        //     dataType: "json",
                                                        //     error: function() {
                                                        //         console.log('error', arguments);
                                                        //     },
                                                        //     success: function() {
                                                        //         console.log('success', arguments);
                                                        //         var Paket       = arguments[0]['mdldata'][</?= $parent['id'] ?>]['paket'];
                                                        //         var countPaket  = Object.keys(Paket).length;
                                                        //         var idparent    = '</?= $idparent ?>';
                                                        //         var idPaket     = '</?= $idpaket ?>';
                                                        //         var parenthide  = '';
                                                        //         var pakethide   = '';
                                                        //         console.log(i);
                                                                
                                                        //         $.each(Paket, function(i, item) {

                                                        //             $('#paketid'+Paket[i].id).hide('slow', function(){ $('#paketid'+Paket[i].id).remove(); });
                                                                    
                                                        //         });

                                                        //     }
                                                        // });
                                                    }
                                                });
                                            });
                                        
                                        });

                                        // Currency Add Mdl
                                        $("input[data-type='currency']").on({
                                            keyup: function() {
                                                formatCurrency($(this));
                                            },
                                            blur: function() {
                                                formatCurrency($(this), "blur");
                                            }
                                        });

                                        function formatNumber(n) {
                                            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                        }

                                        function formatCurrency(input, blur) {

                                            var input_val = input.val();
                                            if (input_val === "") {
                                                return;
                                            }

                                            var original_len = input_val.length;

                                            var caret_pos = input.prop("selectionStart");

                                            if (input_val.indexOf(".") >= 0) {

                                                var decimal_pos = input_val.indexOf(".");

                                                var left_side = input_val.substring(0, decimal_pos);
                                                var right_side = input_val.substring(decimal_pos);

                                                left_side = formatNumber(left_side);

                                                right_side = formatNumber(right_side);

                                                if (blur === "blur") {
                                                    right_side += "00";
                                                }

                                                right_side = right_side.substring(0, 2);

                                                input_val = "Rp" + left_side + "." + right_side;

                                            } else {
                                                input_val = formatNumber(input_val);
                                                input_val = "Rp" + input_val;

                                                if (blur === "blur") {
                                                    input_val += ".00";
                                                }
                                            }

                                            input.val(input_val);

                                            var updated_len = input_val.length;
                                            caret_pos = updated_len - original_len + caret_pos;
                                            input[0].setSelectionRange(caret_pos, caret_pos);
                                        }

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
                                            // $('#paketid'+Paket[i].id).hide('slow', function(){ $('#paketid'+Paket[i].id).remove(); });
                                            // $('#paketid'+Paket[i].id).hide('slide', {direction: "right"}, 2000, function(){ $('#paketid'+Paket[i].id).remove(); });
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

                                                    $('#importMdl'+ Paket[i].id ).remove();

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

                                    console.log('success', arguments);

                                    $('#open'+ x).attr('hidden',true);
                                    $('#close'+ x).removeAttr('hidden');

                                    var mdldata     = arguments[0];
                                    var paketId     = x;
                                    var countMdl    = Object.keys(mdldata).length;

                                    // ROW IMPORT MDL
                                    var trImportMdl = document.createElement('tr');
                                    trImportMdl.setAttribute('id','importMdl'+ paketId);
                                    trImportMdl.setAttribute('class','togglemdl' + paketId);

                                    tdImport = document.createElement('td');

                                    tdImportButton = document.createElement('td');
                                    tdImportButton.setAttribute('colspan','9');

                                    divImportButton = document.createElement('div');
                                    divImportButton.setAttribute('class','uk-child-width-auto uk-grid-small uk-flex-middle');
                                    divImportButton.toggleAttribute("uk-grid");

                                    divImportMdl = document.createElement('div');
                                    buttonImport = document.createElement('button');
                                    buttonImport.setAttribute('class','uk-button uk-button-primary');
                                    buttonImport.setAttribute('uk-toggle','target: #modalimport'+ paketId);
                                    mdlImportText = document.createTextNode('Import Mdl');

                                    divDeleteMdl = document.createElement('div');
                                    buttonImportDelete = document.createElement('a');
                                    buttonImportDelete.setAttribute('class','uk-button uk-button-danger');
                                    buttonImportDelete.setAttribute('href','paket/deleteallmdl/'+ paketId);
                                    buttonImportDelete.setAttribute('onClick','return confirm("Anda yakin ingin menghapus semua data MDL?")');
                                    mdlImportTextDelete = document.createTextNode('Delete Mdl');
                                    // END ROW IMPORT MDL

                                    var itemorder = document.getElementById('rowitem');

                                    itemorder.appendChild(trImportMdl);
                                    trImportMdl.appendChild(tdImport);
                                    trImportMdl.appendChild(tdImportButton);
                                    tdImportButton.appendChild(divImportButton);
                                    divImportButton.appendChild(divImportMdl);
                                    divImportButton.appendChild(divDeleteMdl);
                                    divImportMdl.appendChild(buttonImport);
                                    divDeleteMdl.appendChild(buttonImportDelete);
                                    buttonImport.appendChild(mdlImportText);
                                    buttonImportDelete.appendChild(mdlImportTextDelete);
                                    $("#importMdl"+ paketId).insertAfter( $("#paketid"+ paketId));

                                    $.each(mdldata, function(x, itemMdl) {

                                        // ROW MDL
                                            var trMdl = document.createElement('tr');
                                            trMdl.setAttribute('id','mdlDataItem' + itemMdl.id);                                            
                                            
                                            var tdOrderingMdl = document.createElement('td');
                                            var selectOrderMdl = document.createElement('select');

                                            selectOrderMdl.setAttribute('id','mdlList'+ paketId + itemMdl.id );
                                            selectOrderMdl.setAttribute('class','uk-select uk-form-width-xsmall uk-margin-large-left');
                                            selectOrderMdl.setAttribute('Aria-label', 'Mdl');

                                            for ($y = 1; $y <= countMdl; $y++) {
                                                var optionMdl = document.createElement("option");
                                                if ($y == itemMdl.ordering) {
                                                    optionMdl.selected = true;
                                                } else {
                                                    optionMdl.selected = false;
                                                }
                                                optionMdl.value    = $y;
                                                optionMdl.text     = $y;
                                                selectOrderMdl.appendChild(optionMdl);
                                            }

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
                                                separator = sisa ? '.' : '';
                                                rupiah += separator + ribuan.join('.');
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
                                            linkUpdateMdl = document.createElement('a');
                                            linkUpdateMdl.setAttribute('class','uk-icon-button');
                                            linkUpdateMdl.setAttribute('href','#modalupdatemdl'+ paketId + itemMdl.id);
                                            linkUpdateMdl.setAttribute('uk-icon','pencil');
                                            linkUpdateMdl.toggleAttribute('uk-toggle');


                                            divDeleteMdlitem = document.createElement('div');
                                            FormDeleteMdl = document.createElement('form');
                                            FormDeleteMdl.setAttribute('class','uk-form-stacked');
                                            FormDeleteMdl.setAttribute('role','form');
                                            FormDeleteMdl.setAttribute('action','mdl/delete/' + itemMdl.id);
                                            FormDeleteMdl.setAttribute('method','post');

                                            inputDeleteMdl = document.createElement('input');
                                            inputDeleteMdl.setAttribute('type','hidden');
                                            inputDeleteMdl.setAttribute('name','paketid');
                                            inputDeleteMdl.setAttribute('value', paketId);

                                            buttonMdlDelete = document.createElement('button');
                                            buttonMdlDelete.setAttribute('type','submit');
                                            buttonMdlDelete.setAttribute('uk-icon','trash');
                                            buttonMdlDelete.setAttribute('class','uk-icon-button-delete');
                                            buttonMdlDelete.setAttribute('onclick','return confirm("Anda yakin ingin menghapus data ini?")');

                                        // END ROW MDL

                                        itemorder.appendChild(trMdl);
                                        trMdl.appendChild(tdOrderingMdl);
                                        tdOrderingMdl.appendChild(selectOrderMdl);
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
                                        divButtonMdl.appendChild(divDeleteMdlitem);
                                        divDeleteMdlitem.appendChild(FormDeleteMdl);
                                        FormDeleteMdl.appendChild(inputDeleteMdl);
                                        FormDeleteMdl.appendChild(buttonMdlDelete);
                                        $("#mdlDataItem" + itemMdl.id).insertAfter( $("#importMdl"+ paketId));

                                        // Modal Import Mdl Paket
                                        $('<div class="uk-modal-container" id="modalimport' + paketId +'" uk-modal><div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto><button class="uk-modal-close-default" type="button" uk-close></button><div class="uk-modal-header"><h2 class="uk-modal-title">Upload File MDL</h2></div><div class="uk-modal-body"><form id="formInputPaket' + paketId +'" class="uk-form-stacked" action="upload/importmdl/' + paketId +'" method="post" enctype="multipart/form-data"><div class="uk-margin" id="image-container-importmdl-' + paketId +'"><div class="uk-form-controls"><input id="fileimportmdl' + paketId +'" name="mdl" hidden /><div id="js-upload-importmdl-' + paketId +'" class="js-upload-importmdl-' + paketId +' uk-placeholder uk-text-center"><span uk-icon="icon: cloud-upload"></span><span class="uk-text-middle">Tarik dan lepas file MDL disini atau</span><div uk-form-custom><input type="file"><span class="uk-link uk-preserve-color">pilih satu</span></div></div><progress id="js-progressbar-importmdl-' + paketId +'" class="uk-progress" value="0" max="100" hidden></progress></div></div></form></div></div></div>').appendTo(document.getElementById("modalContainerImport"));
                                       
                                        // Script Import MDL
                                        $("<script>var bar = document.getElementById('js-progressbar-importmdl-" + paketId +"');UIkit.upload('.js-upload-importmdl-" + paketId +"', {url: 'upload/mdl/" + paketId + "',multiple: false,name: 'uploads',param: {lorem: 'ipsum'},method: 'POST', type: 'json', beforeSend: function() {console.log('beforeSend', arguments);},beforeAll: function() {console.log('beforeAll', arguments);}, load: function() {console.log('load', arguments);},error: function() {console.log('error', arguments);var error = arguments[0].xhr.response.message.uploads;alert(error);},complete: function() {console.log('complete', arguments);},loadStart: function(e) {console.log('loadStart', arguments);bar.removeAttribute('hidden');bar.max = e.total;bar.value = e.loaded;},progress: function(e) {console.log('progress', arguments);bar.max = e.total;bar.value = e.loaded;}, loadEnd: function(e) {console.log('loadEnd', arguments);bar.max = e.total; bar.value = e.loaded;},completeAll: function() {console.log('completeAll', arguments);setTimeout(function() { bar.setAttribute('hidden', 'hidden');}, 1000);location.reload();}});</" + "script>").appendTo(document.getElementById("formInputPaket" + paketId ));

                                        // Modal Update Mdl
                                        $('#modalcontainer').append("<div class='uk-modal-container' id='modalupdatemdl" + paketId + + itemMdl.id +"' uk-modal><div class='uk-modal-dialog uk-margin-auto-vertical' uk-overflow-auto><div class='uk-modal-header'> <h2 class='uk-modal-title'>Ubah MDL '"+ itemMdl.name +"'</h2> <button class='uk-modal-close-default' type='button' uk-close></button></div><div id='modalbodymdl"+itemMdl.id+"' class='uk-modal-body'></div></div></div>");
                                        
                                        // Modal Body Update MDL
                                        $('#modalbodymdl'+itemMdl.id).append("<form class='uk-form-stacked' id='formMdl" + paketId + + itemMdl.id +"' role='form' action='mdl/update/"+ itemMdl.id +"' method='post'><input type='text' class='uk-input' id='paketid"+itemMdl.id+"' ' name='paketid"+itemMdl.id+"' value='"+ paketId+ "' hidden/><div class='uk-margin-bottom'><label class='uk-form-label' for='name'>Nama</label><div class='uk-form-controls'><input type='text' class='uk-input' id='name' name='name' value='"+ itemMdl.name +"' /></div></div><div class='uk-margin'><label class='uk-form-label' for='denomination'> Satuan</label><select class='uk-select' aria-label='Satuan' id='denominations"+ itemMdl.id +"' name='denomination' required></select></div><div id='contupmdl"+ itemMdl.id +"'><div class='uk-margin-bottom'><label class='uk-form-label' for='length'>Panjang</label><div class='uk-form-controls'><input type='text' class='uk-input' id='length' name='length' value='"+ itemMdl.length +"' required /></div></div><div class='uk-margin-bottom'><label class='uk-form-label' for='width'>Lebar</label><div class='uk-form-controls'><input type='text' class='uk-input' id='width' name='width' value='"+ itemMdl.width +"' required /></div></div><div class='uk-margin-bottom'> <label class='uk-form-label' for='height'>Tinggi</label><div class='uk-form-controls'><input type='text' class='uk-input' id='height' name='height' value='"+ itemMdl.height +"' required /></div></div><div class='uk-margin-bottom'><label class='uk-form-label' for='volume'>Volume</label><div class='uk-form-controls'><input type='text' class='uk-input' id='volume' name='volume' value='"+ itemMdl.volume +"' required /></div></div></div><div class='uk-margin'><label class='uk-form-label' for='price'>Harga</label><div class='uk-form-controls'><input type='text' class='uk-input' id='price"+itemMdl.id+"' name='price' placeholder='Rp."+itemMdl.price+"' value='"+itemMdl.price+"' data-type='curencyupdate' novalidate/></div></div><div class='uk-margin'><label class='uk-form-label' for='price'>Keterangan</label><div class='uk-margin'> <textarea class='uk-textarea' type='text' name='keterangan' rows='5' placeholder='"+ itemMdl.keterangan +"' value='"+ itemMdl.keterangan +"' aria-label='Textarea'>"+ itemMdl.keterangan +"</textarea></div></div><div class='uk-margin' id='image-container-createmdl-"+ paketId + + itemMdl.id +"'> <div id='image-containermdl-"+ paketId + + itemMdl.id +"' class='uk-form-controls'><label class='uk-form-label' for='photo'>Foto MDL</label><input id='photocreatemdl"+ paketId + + itemMdl.id +"' name='photo' hidden /><div id='js-upload-createmdl-"+ paketId + + itemMdl.id +"' class='js-upload-createmdl-"+ paketId + + itemMdl.id +" uk-placeholder uk-text-center'><span uk-icon='icon: cloud-upload'></span><span class='uk-text-middle'>Tarik dan lepas foto disini atau</span><div uk-form-custom><input type='file'><span class='uk-link uk-preserve-color'>pilih satu</span> </div></div><progress id='js-progressbar-createmdl-"+ paketId + + itemMdl.id +"' class='uk-progress' value='0' max='100' hidden></progress></div></div> <div class='uk-modal-footer'><div class='uk-text-right'><button class='uk-button uk-button-primary' type='submit'>Simpan</button></div> </div></form>");
                                        
                                        // Script Upload
                                        $("<script>var bar = document.getElementById('js-progressbar-createmdl-" + paketId + + itemMdl.id +"');UIkit.upload('.js-upload-createmdl-"+ paketId + + itemMdl.id +"', {url: 'upload/photomdl',multiple: false,name: 'uploads',param: { lorem: 'ipsum'},method: 'POST',type: 'json',beforeSend: function() { console.log('beforeSend', arguments);},beforeAll: function() {console.log('beforeAll', arguments);},load: function() {console.log('load', arguments);},error: function() {console.log('error', arguments);var error = arguments[0].xhr.response.message.uploads;alert(error);},complete: function() {console.log('complete', arguments);var filename = arguments[0].response;console.log(filename);if (document.getElementById('display-container-createmdl-"+ paketId + + itemMdl.id+"')) {document.getElementById('display-container-createmdl-"+ paketId + + itemMdl.id+"').remove();};document.getElementById('photocreatemdl"+ paketId + + itemMdl.id+"').value = filename;var imgContainer = document.getElementById('image-container-createmdl-"+ paketId + + itemMdl.id+"');var displayContainer = document.createElement('div');displayContainer.setAttribute('id', 'display-container-createmdl-"+ paketId + + itemMdl.id+"');displayContainer.setAttribute('class', 'uk-inline');var displayImg = document.createElement('div');displayImg.setAttribute('uk-lightbox', 'animation: fade');displayImg.setAttribute('class', 'uk-inline');var link = document.createElement('a');link.setAttribute('href', 'img/mdl/'+filename );var image = document.createElement('img');image.setAttribute('src', 'img/mdl/'+filename);var closeContainer = document.createElement('div');closeContainer.setAttribute('class', 'uk-position-small uk-position-right');var closeButton = document.createElement('a');closeButton.setAttribute('id', 'removeImgCreatemdl"+ paketId + + itemMdl.id +"');closeButton.setAttribute('class', 'tm-img-remove uk-border-circle'); closeButton.setAttribute('onClick', 'removeImgCreatemdl("+ paketId + + itemMdl.id +")');closeButton.setAttribute('uk-icon', 'close');closeContainer.appendChild(closeButton);displayContainer.appendChild(displayImg);displayContainer.appendChild(closeContainer);link.appendChild(image);displayImg.appendChild(link);imgContainer.appendChild(displayContainer);document.getElementById('js-upload-createmdl-"+ paketId + + itemMdl.id +"').setAttribute('hidden', '');},loadStart: function(e) {console.log('loadStart', arguments);bar.removeAttribute('hidden');bar.max = e.total;bar.value = e.loaded;},progress: function(e) {console.log('progress', arguments);bar.max = e.total;bar.value = e.loaded;},loadEnd: function(e) {console.log('loadEnd', arguments);bar.max = e.total;bar.value = e.loaded;},completeAll: function() {console.log('completeAll', arguments);setTimeout(function() {bar.setAttribute('hidden', 'hidden');}, 1000);alert('Data Berhasil Terunggah');}});</" + "script>").appendTo(document.getElementById("formMdl"+ paketId + + itemMdl.id));
                                        
                                        // Sript Remove Image
                                        $('<script>function removeImgCreatemdl(x) { console.log(x); $.ajax({ type: "post",url: "upload/removephotomdl", data: {"photo": document.getElementById("photocreatemdl' + paketId + + itemMdl.id +'").value}, dataType: "json", error: function() { console.log("error", arguments);},success: function() {console.log("success", arguments); var pesan = arguments[0][1]; document.getElementById("display-container-createmdl-'+ paketId + + itemMdl.id +'").remove();document.getElementById("photocreatemdl' + paketId + + itemMdl.id +'").value = "";alert(pesan); document.getElementById("js-upload-createmdl-' + paketId + + itemMdl.id +'").removeAttribute("hidden", "");}}); }</' + 'script>').appendTo(document.getElementById("formMdl"+ paketId + + itemMdl.id));
                                                    
                                        
                                        // Reposiition MDL List
                                        $('#mdlList'+ paketId + + itemMdl.id ).change(function() {
                                            $.ajax({
                                                type: 'POST',
                                                url: "mdl/reorderingmdl",
                                                data: {
                                                    id: itemMdl.id,
                                                    paket: paketId,
                                                    order: $("#mdlList"+ paketId + + itemMdl.id).val()
                                                },
                                                dataType: "json",
                                                error: function(mdlOrder) {
                                                    console.log('error', arguments);
                                                },
                                                success: function(mdlOrder) {
                                                    console.log(mdlOrder);
                                                    location.reload();
                                                }
                                            });
                                        });
                                        
                                        // Converting Price
                                        var	number_string = itemMdl.price.toString(),
                                        sisa 	= number_string.length % 3,
                                        rupiah 	= number_string.substr(0, sisa),
                                        ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                                        
                                        if (ribuan) {
                                            separator = sisa ? '.' : '';
                                            rupiah += separator + ribuan.join('.');
                                        }

                                        $("#price" + itemMdl.id).val("Rp " + rupiah);

                                        // Denomination Condition
                                        var mdldenom = "";
                                        
                                        for ($y = 1; $y <= 4; $y++) {
                                            var selectMdlItem = document.getElementById("denominations"+ itemMdl.id);
                                            var optionMdlItem = document.createElement("option");

                                            if ($y == itemMdl.denomination) {
                                                optionMdlItem.selected = true;
                                            } else {
                                                optionMdlItem.selected = false;
                                            }

                                            if ($y == "1"){
                                                mdldenom = "Unit";
                                            }else if($y == "2"){
                                                mdldenom = "Meter Lari";
                                            }else if($y == "3"){
                                                mdldenom = "Meter Persegi";
                                            }else if($y == "4"){
                                                mdldenom = "Set";
                                            }
                                            optionMdlItem.value    = $y;
                                            optionMdlItem.text     = mdldenom;
                                            selectMdlItem.appendChild(optionMdlItem);
                                        }

                                        // Currency
                                        $("input[data-type='curencyupdate']").on({
                                            keyup: function() {
                                                formatCurrency($(this));
                                            },
                                            blur: function() {
                                                formatCurrency($(this), "blur");
                                            }
                                        });


                                        function formatNumber(n) {
                                            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                        }

                                        function formatCurrency(input, blur) {

                                            var input_val = input.val();

                                            if (input_val === "") {
                                                return;
                                            }

                                            var original_len = input_val.length;

                                            var caret_pos = input.prop("selectionStart");

                                            if (input_val.indexOf(".") >= 0) {

                                                var decimal_pos = input_val.indexOf(".");

                                                var left_side = input_val.substring(0, decimal_pos);
                                                var right_side = input_val.substring(decimal_pos);

                                                left_side = formatNumber(left_side);

                                                right_side = formatNumber(right_side);

                                                if (blur === "blur") {
                                                    right_side += "00";
                                                }

                                                right_side = right_side.substring(0, 2);

                                                input_val = "Rp" + left_side + "." + right_side;

                                            } else {

                                                input_val = formatNumber(input_val);
                                                input_val = "Rp" + input_val;

                                                if (blur === "blur") {
                                                    input_val += ".00";
                                                }
                                            }

                                            input.val(input_val);

                                            var updated_len = input_val.length;
                                            caret_pos = updated_len - original_len + caret_pos;
                                            input[0].setSelectionRange(caret_pos, caret_pos);
                                        }

                                    }); 
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

                <!-- ROW UNCATEGORIZE (BELUM TERKATEGORI) -->
                <tr id="mdluncaterow">
                    <td></td>
                    <td><a class="uk-link-reset" id="toggleuncate"><span id="closeuncate" uk-icon="chevron-down" hidden></span><span id="openuncate" uk-icon="chevron-right"></span></a></td>
                    <td colspan="9" class="tm-h3" style="text-transform: uppercase;">Belum Terkategori</td>
                </tr>
                <!-- END ROW UNCATEGORIZE (BELUM TERKATEGORI) -->

                <!-- SCRIPT UNCATE MDL -->
                <script>

                    $("#openuncate").click(function(){
                        $('#closeuncate').removeAttr('hidden',true);
                        $('#openuncate').attr('hidden',true);

                        $.ajax({
                            type    : 'get',
                            url     : 'mdl/requestdatamdluncate',
                            dataType : 'json',
                            error : function (){
                                console.log("error", arguments);
                            },
                            success : function (data){
                                console.log("success", arguments);

                               var mlduncate = arguments[0];

                                $.each(mlduncate, function(x, itemmdluncate){

                                    console.log(itemmdluncate.name);

                                    // ROW MDL
                                        var trMdlUncate = document.createElement('tr');
                                        trMdlUncate.setAttribute('id','mdlDataItem' + itemmdluncate.id);                                            
                                        
                                        var tdOrderingMdlUncate = document.createElement('td');

                                        var tdIconMdlUncate = document.createElement('td');

                                        var tdNameMdlUncate = document.createElement('td');
                                        tdNameMdlUncate.setAttribute('style','text-transform: uppercase; font-weight: 400;');
                                        tdNameMdlUncate.setAttribute('class','tm-h4');
                                        MdlNameUncate = document.createTextNode(itemmdluncate.name);

                                        var tdWidthMdlUncate = document.createElement('td');
                                        widthMdlUncate = document.createTextNode(itemmdluncate.width);

                                        var tdLengthMdlUncate = document.createElement('td');
                                        lengthMdlUncate = document.createTextNode(itemmdluncate.length);

                                        var tdHeightMdlUncate = document.createElement('td');
                                        heightMdlUncate = document.createTextNode(itemmdluncate.height);

                                        var tdVolumeMdlUncate = document.createElement('td');
                                        volumeMdlUncate = document.createTextNode(itemmdluncate.volume);

                                        var denomUncate = "";
                                        if (itemmdluncate.denomination == "1") {
                                            denomUncate = "Unit";
                                        } else if (itemmdluncate.denomination == "2") {
                                            denomUncate = "Meter Lari";
                                        } else if (itemmdluncate.denomination == "3") {
                                            denomUncate = "Meter Persegi";
                                        } else if (itemmdluncate.denomination == "4") {
                                            denomUncate = "Set";
                                        }
                                        var tdDenominationMdlUncate = document.createElement('td');
                                        denominationMdlUncate = document.createTextNode(denomUncate);

                                        var tdPriceMdlUncate = document.createElement('td');

                                        var	number_string = itemmdluncate.price.toString(),
                                            sisa 	= number_string.length % 3,
                                            rupiah 	= number_string.substr(0, sisa),
                                            ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                                                
                                        if (ribuan) {
                                            separator = sisa ? '.' : '';
                                            rupiah += separator + ribuan.join('.');
                                        }
                                        priceMdlUncate = document.createTextNode('Rp.' + rupiah);

                                        var tdKeteranganMdlUncate = document.createElement('td');
                                        keteranganMdlUncate = document.createTextNode(itemmdluncate.keterangan);

                                        var tdPhotoMdlUncate = document.createElement('td');

                                        divLightboxMdlUncate = document.createElement('div');
                                        divLightboxMdlUncate.toggleAttribute('uk-lightbox');

                                        linkPhotoMdlUncate = document.createElement('a');
                                        linkPhotoMdlUncate.setAttribute('class','uk-inline');
                                        linkPhotoMdlUncate.setAttribute('href','img/mdl/'+ itemmdluncate.photo);
                                        linkPhotoMdlUncate.setAttribute('role','button');

                                        imgMdlUncate = document.createElement("img");
                                        imgMdlUncate.setAttribute('id', 'img'+ itemmdluncate.id);
                                        imgMdlUncate.setAttribute('class','uk-preserve-width uk-border-circle');
                                        imgMdlUncate.setAttribute('src','img/mdl/'+ itemmdluncate.photo);
                                        imgMdlUncate.setAttribute('width','40');
                                        imgMdlUncate.setAttribute('height','40');
                                        imgMdlUncate.setAttribute('alt', itemmdluncate.photo);

                                        var tdButtonMdlUncate = document.createElement('td');
                                        tdButtonMdlUncate.setAttribute('class','uk-text-center');

                                        divButtonMdlUncate = document.createElement('div');
                                        divButtonMdlUncate.setAttribute('class','uk-grid-small uk-flex-center uk-flex-middle');
                                        divButtonMdlUncate.toggleAttribute('uk-grid');

                                        divUpdateMdlUncate = document.createElement('div');
                                        linkUpdateMdlUncate = document.createElement('a');
                                        linkUpdateMdlUncate.setAttribute('class','uk-icon-button');
                                        linkUpdateMdlUncate.setAttribute('href','#modalupdatemdl'+ itemmdluncate.id);
                                        linkUpdateMdlUncate.setAttribute('uk-icon','pencil');
                                        linkUpdateMdlUncate.toggleAttribute('uk-toggle');


                                        divDeleteMdlitemUncate = document.createElement('div');
                                        FormDeleteMdlUncate = document.createElement('form');
                                        FormDeleteMdlUncate.setAttribute('class','uk-form-stacked');
                                        FormDeleteMdlUncate.setAttribute('role','form');
                                        FormDeleteMdlUncate.setAttribute('action','mdl/deleteuncate/' + itemmdluncate.id);
                                        FormDeleteMdlUncate.setAttribute('method','post');

                                        inputDeleteMdlUncate = document.createElement('input');
                                        inputDeleteMdlUncate.setAttribute('type','hidden');
                                        inputDeleteMdlUncate.setAttribute('name','paketid');
                                        inputDeleteMdlUncate.setAttribute('value', itemmdluncate.id);

                                        buttonMdlDeleteUncate = document.createElement('button');
                                        buttonMdlDeleteUncate.setAttribute('type','submit');
                                        buttonMdlDeleteUncate.setAttribute('uk-icon','trash');
                                        buttonMdlDeleteUncate.setAttribute('class','uk-icon-button-delete');
                                        buttonMdlDeleteUncate.setAttribute('onclick','return confirm("Anda yakin ingin menghapus data ini?")');

                                    // END ROW MDL UNCATE
                                    itemorder = document.getElementById('rowitem');
                                    itemorder.appendChild(trMdlUncate);
                                    trMdlUncate.appendChild(tdOrderingMdlUncate);
                                    trMdlUncate.appendChild(tdIconMdlUncate);
                                    trMdlUncate.appendChild(tdNameMdlUncate);
                                    tdNameMdlUncate.appendChild(MdlNameUncate);
                                    trMdlUncate.appendChild(tdLengthMdlUncate);
                                    tdLengthMdlUncate.appendChild(lengthMdlUncate);
                                    trMdlUncate.appendChild(tdWidthMdlUncate);
                                    tdWidthMdlUncate.appendChild(widthMdlUncate);
                                    trMdlUncate.appendChild(tdHeightMdlUncate);
                                    tdHeightMdlUncate.appendChild(heightMdlUncate);
                                    trMdlUncate.appendChild(tdVolumeMdlUncate);
                                    tdVolumeMdlUncate.appendChild(volumeMdlUncate);
                                    trMdlUncate.appendChild(tdDenominationMdlUncate);
                                    tdDenominationMdlUncate.appendChild(denominationMdlUncate);
                                    trMdlUncate.appendChild(tdKeteranganMdlUncate);
                                    tdKeteranganMdlUncate.appendChild(keteranganMdlUncate);
                                    trMdlUncate.appendChild(tdPriceMdlUncate);
                                    tdPriceMdlUncate.appendChild(priceMdlUncate);
                                    trMdlUncate.appendChild(tdPhotoMdlUncate);
                                    tdPhotoMdlUncate.appendChild(divLightboxMdlUncate);
                                    divLightboxMdlUncate.appendChild(linkPhotoMdlUncate);
                                    linkPhotoMdlUncate.appendChild(imgMdlUncate);
                                    trMdlUncate.appendChild(tdButtonMdlUncate);
                                    tdButtonMdlUncate.appendChild(divButtonMdlUncate);
                                    divButtonMdlUncate.appendChild(divUpdateMdlUncate);
                                    divUpdateMdlUncate.appendChild(linkUpdateMdlUncate);
                                    divButtonMdlUncate.appendChild(divDeleteMdlitemUncate);
                                    divDeleteMdlitemUncate.appendChild(FormDeleteMdlUncate);
                                    FormDeleteMdlUncate.appendChild(inputDeleteMdlUncate);
                                    FormDeleteMdlUncate.appendChild(buttonMdlDeleteUncate);
                                    $("#mdlDataItem" + itemmdluncate.id).insertAfter( $("#mdluncaterow"));

                                    // Modal Update Mdl
                                    $('#modalcontainer').append("<div class='uk-modal-container' id='modalupdatemdl"+ itemmdluncate.id +"' uk-modal><div class='uk-modal-dialog uk-margin-auto-vertical' uk-overflow-auto><div class='uk-modal-header'> <h2 class='uk-modal-title'>Ubah MDL '"+ itemmdluncate.name +"'</h2> <button class='uk-modal-close-default' type='button' uk-close></button></div><div id='modalbodymdl"+itemmdluncate.id+"' class='uk-modal-body'></div></div></div>");
                                    
                                    // Modal Body Update MDL
                                    $('#modalbodymdl'+itemmdluncate.id).append("<form class='uk-form-stacked' id='formMdl"+ itemmdluncate.id +"' role='form' action='mdl/update/"+ itemmdluncate.id +"' method='post'><input type='text' class='uk-input' id='paketid"+itemmdluncate.id+"' ' name='paketid"+itemmdluncate.id+"' value='"+itemmdluncate.id+ "' hidden/><div class='uk-margin-bottom'><label class='uk-form-label' for='name'>Nama</label><div class='uk-form-controls'><input type='text' class='uk-input' id='name' name='name' value='"+ itemmdluncate.name +"' /></div></div><div class='uk-margin'><label class='uk-form-label' for='denomination'> Satuan</label><select class='uk-select' aria-label='Satuan' id='denominationsUncate"+ itemmdluncate.id +"' name='denomination' required></select></div><div id='contupmdl"+ itemmdluncate.id +"'><div class='uk-margin-bottom'><label class='uk-form-label' for='length'>Panjang</label><div class='uk-form-controls'><input type='text' class='uk-input' id='length' name='length' value='"+ itemmdluncate.length +"' required /></div></div><div class='uk-margin-bottom'><label class='uk-form-label' for='width'>Lebar</label><div class='uk-form-controls'><input type='text' class='uk-input' id='width' name='width' value='"+ itemmdluncate.width +"' required /></div></div><div class='uk-margin-bottom'> <label class='uk-form-label' for='height'>Tinggi</label><div class='uk-form-controls'><input type='text' class='uk-input' id='height' name='height' value='"+ itemmdluncate.height +"' required /></div></div><div class='uk-margin-bottom'><label class='uk-form-label' for='volume'>Volume</label><div class='uk-form-controls'><input type='text' class='uk-input' id='volume' name='volume' value='"+ itemmdluncate.volume +"' required /></div></div></div><div class='uk-margin'><label class='uk-form-label' for='price'>Harga</label><div class='uk-form-controls'><input type='text' class='uk-input' id='price"+itemmdluncate.id+"' name='price' placeholder='Rp."+itemmdluncate.price+"'  value='"+itemmdluncate.price+"' data-type='curencyupdate' /></div></div><div class='uk-margin'><label class='uk-form-label' for='price'>Keterangan</label><div class='uk-margin'> <textarea class='uk-textarea' type='text' name='keterangan' rows='5' placeholder='"+ itemmdluncate.keterangan +"' value='"+ itemmdluncate.keterangan +"' aria-label='Textarea'>"+ itemmdluncate.keterangan +"</textarea></div></div><div class='uk-modal-footer'><div class='uk-text-right'><button class='uk-button uk-button-primary' type='submit'>Simpan</button></div> </div></form>");
                                                                       
                                    // Converting Price
                                    var	number_string = itemmdluncate.price.toString(),
                                    sisa 	= number_string.length % 3,
                                    rupiah 	= number_string.substr(0, sisa),
                                    ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                                    
                                    if (ribuan) {
                                        separator = sisa ? '.' : '';
                                        rupiah += separator + ribuan.join('.');
                                    }

                                    // Denomination Condition
                                    $("#price" + itemmdluncate.id).val("Rp " + rupiah);
                                    var mdldenomUncate = "";
                                    
                                    for ($y = 1; $y <= 4; $y++) {
                                        var selectMdlItemUncate = document.getElementById("denominationsUncate"+ itemmdluncate.id);
                                        var optionMdlItemUncate = document.createElement("option");

                                        if ($y == itemmdluncate.denomination) {
                                            optionMdlItemUncate.selected = true;
                                        } else {
                                            optionMdlItemUncate.selected = false;
                                        }

                                        if ($y == "1"){
                                            mdldenomUncate = "Unit";
                                        }else if($y == "2"){
                                            mdldenomUncate = "Meter Lari";
                                        }else if($y == "3"){
                                            mdldenomUncate = "Meter Persegi";
                                        }else if($y == "4"){
                                            mdldenomUncate = "Set";
                                        }
                                        optionMdlItemUncate.value    = $y;
                                        optionMdlItemUncate.text     = mdldenomUncate;
                                        selectMdlItemUncate.appendChild(optionMdlItemUncate);
                                    }

                                    // Currency
                                    $("input[data-type='curencyupdate']").on({
                                        keyup: function() {
                                            formatCurrency($(this));
                                        },
                                        blur: function() {
                                            formatCurrency($(this), "blur");
                                        }
                                    });


                                    function formatNumber(n) {
                                        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                    }

                                    function formatCurrency(input, blur) {

                                        var input_val = input.val();

                                        if (input_val === "") {
                                            return;
                                        }

                                        var original_len = input_val.length;

                                        var caret_pos = input.prop("selectionStart");

                                        if (input_val.indexOf(".") >= 0) {

                                            var decimal_pos = input_val.indexOf(".");

                                            var left_side = input_val.substring(0, decimal_pos);
                                            var right_side = input_val.substring(decimal_pos);

                                            left_side = formatNumber(left_side);

                                            right_side = formatNumber(right_side);

                                            if (blur === "blur") {
                                                right_side += "00";
                                            }

                                            right_side = right_side.substring(0, 2);

                                            input_val = "Rp" + left_side + "." + right_side;

                                        } else {

                                            input_val = formatNumber(input_val);
                                            input_val = "Rp" + input_val;

                                            if (blur === "blur") {
                                                input_val += ".00";
                                            }
                                        }

                                        input.val(input_val);

                                        var updated_len = input_val.length;
                                        caret_pos = updated_len - original_len + caret_pos;
                                        input[0].setSelectionRange(caret_pos, caret_pos);
                                    }
                                });
                            }

                        })
                        
                    });

                    $("#closeuncate").click(function(){
                        $('#openuncate').removeAttr('hidden',true);
                        $('#closeuncate').attr('hidden',true);

                        $.ajax({
                            type    : 'get',
                            url     : 'mdl/requestdatamdluncate',
                            dataType : 'json',
                            error : function (){
                                console.log("error", arguments);
                            },
                            success : function (data){
                                console.log("success", arguments);

                               var mlduncate = arguments[0];

                                $.each(mlduncate, function(x, itemmdluncate){
                                    $('#mdlDataItem'+ itemmdluncate.id).remove();
                                });
                            }

                        })
                    });
                
                </script>
                <!-- END SCRIPT UNCATE MDL -->

            </tbody>
        </table>
        <?= $pager ?>
    </div>
    <!-- End Table Of Content -->

    <!-- Modal Add Paket -->
    <?php if ($authorize->hasPermission('admin.mdl.create', $uid)) { ?>
        <div class="uk-modal-container" id="modaladd" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Tambah Kategori</h2>
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                </div>

                <div class="uk-modal-body">
                    <form class="uk-form-stacked" role="form" action="paket/create" method="post">
                        <?= csrf_field() ?>

                        <div class="uk-margin-bottom">
                            <label class="uk-form-label" for="name">Nama</label>
                            <div class="uk-form-controls">
                                <input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required />
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label" for="parent kategori">Kategori</label>
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                <label><input class="uk-checkbox" type="checkbox" id="kategori" name="parent"> Kategori</label>
                                <label><input class="uk-checkbox" type="checkbox" id="sub kategori"> Sub Kategori</label>
                            </div>
                        </div>

                        <div class="uk-margin" id="parent" hidden>
                            <label class="uk-form-label" for="parent">Pilih Kategori</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="parent">
                                    <option value="" selected disabled>Daftar Kategori</option>
                                    <?php
                                    foreach ($parents as $parent) {
                                        if ($parent['parentid'] === '0') {
                                            echo '<option value="' . $parent['id'] . '">' . $parent['name'] . '</option>';
                                        }
                                    } ?>
                                </select>
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
        <script type="text/javascript">
            $(document).ready(function() {
                $("input[id='sub kategori']").change(function() {
                    if ($(this).is(':checked')) {
                        $("input[id='kategori']").prop("checked", false);
                        $("#parent").removeAttr("hidden");
                    } else {
                        $("#parent").attr("hidden", true);
                    }
                });
                $("input[id='kategori']").click(function() {
                    $("input[id='sub kategori']").prop("checked", false);
                    $("#parent").attr("hidden", true);
                    $("input[id='kategori']").val("");
                });
            });
        </script>
    <?php } ?>
    <!-- End Of Modal Add Paket -->

    <?php foreach ($parents as $parent) { ?>
        <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
            <!-- Modal Edit Paket -->
                <div class="uk-modal-container" id="modalupdatepaket<?= $parent['id'] ?>" uk-modal>
                    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                        <div class="uk-modal-content">
                            <div class="uk-modal-header">
                                <h2 class="uk-modal-title">Ubah Kategori <?= $parent['name'] ?></h2>
                                <button class="uk-modal-close-default" type="button" uk-close></button>
                            </div>

                            <div class="uk-modal-body">
                                <form class="uk-form-stacked" role="form" action="paket/update/<?= $parent['id'] ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="uk-margin-bottom">
                                        <label class="uk-form-label" for="name">Nama</label>
                                        <div class="uk-form-controls">
                                            <input type="text" class="uk-input" id="name" name="name" value="<?= $parent['name']; ?>" />
                                        </div>
                                    </div>

                                    <div class="uk-margin">
                                        <label class="uk-form-label" for="parent">Tipe Kategori</label>
                                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-remove-top">
                                            <?php if ($parent['parentid'] != "0") { ?>
                                                <label><input class="uk-checkbox" type="checkbox" id="edit kategori<?= $parent['id'] ?>" name="parent" value="0"> Kategori</label>
                                                <label><input class="uk-checkbox" type="checkbox" id="edit subkategori<?= $parent['id'] ?>" checked> Sub Kategori</label>
                                            <?php } else { ?>
                                                <label><input class="uk-checkbox" type="checkbox" id="edit kategori<?= $parent['id'] ?>" name="parent" value="0" checked> Kategori</label>
                                                <label><input class="uk-checkbox" type="checkbox" id="edit subkategori<?= $parent['id'] ?>"> Sub Kategori</label>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="uk-margin" id="parent<?= $parent['id'] ?>" hidden>
                                        <label class="uk-form-label" for="parent">Pilih Kategori</label>
                                        <div class="uk-form-controls">
                                            <select class="uk-select" name="parent" id="select<?= $parent['id'] ?>">
                                                <option value="" selected disabled>Daftar Kategori</option>
                                                <?php
                                                foreach ($autoparents as $autoparent) {
                                                    if ($autoparent['id'] === $parent['id']) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = "";
                                                    }
                                                    echo '<option value="' . $autoparent['id'] . '" ' . $selected . '>' . $autoparent['name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            if ($("input[id='edit subkategori<?= $parent['id'] ?>']").is(':checked')) {
                                                $("#parent<?= $parent['id'] ?>").removeAttr("hidden");
                                                $("#select<?= $parent['id'] ?>").prop("required", true);
                                            }

                                            $("input[id='edit subkategori<?= $parent['id'] ?>']").change(function() {
                                                if ($(this).is(':checked')) {
                                                    $("input[id='edit kategori<?= $parent['id'] ?>']").prop("checked", false);
                                                    $("#parent<?= $parent['id'] ?>").removeAttr("hidden");
                                                    $("#select<?= $parent['id'] ?>").prop("required", true);
                                                } else {
                                                    $("#editparent<?= $parent['id'] ?>").attr("hidden", true);
                                                    $("#select<?= $parent['id'] ?>").prop("required", false);
                                                    $("#select<?= $parent['id'] ?>").val("0");
                                                }
                                            });

                                            $("input[id='edit kategori<?= $parent['id'] ?>']").click(function() {
                                                $("input[id='edit subkategori<?= $parent['id'] ?>']").prop("checked", false);
                                                $("#parent<?= $parent['id'] ?>").attr("hidden", true);
                                                $("#select<?= $parent['id'] ?>").prop("required", false);
                                                $("#select<?= $parent['id'] ?>").val("0");
                                            });
                                        });
                                    </script>

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
            <!-- Modal Edit Paket End -->
        <?php } ?>
        <?php } ?>

        <!-- Modal Update MDL Uncategories -->
        <?php foreach ($mdldata['mdluncate'] as $mdluncate) { ?>
            <?php if ($authorize->hasPermission('admin.mdl.edit', $uid)) { ?>
                <div class="uk-modal-container" id="modalupdateuncate<?= $mdluncate['id'] ?>" uk-modal>
                    <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                        <div class="uk-modal-header">
                            <h2 class="uk-modal-title">Ubah MDL <?= $mdluncate['name'] ?></h2>
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                        </div>

                        <div class="uk-modal-body">
                            <form class="uk-form-stacked" role="form" action="mdl/update/<?= $mdluncate['id'] ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="name">Nama</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="name" name="name" value="<?= $mdluncate['name'] ?>" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="denomination"> Satuan</label>
                                    <select class="uk-select" aria-label="Satuan" id="denominations<?= $mdluncate['id'] ?>" name="denomination" required>
                                        <option value="1" <?php if ($mdluncate['denomination'] === "1") { echo 'selected'; } ?>>Unit</option>
                                        <option value="2" <?php if ($mdluncate['denomination'] === "2") { echo 'selected'; } ?>>Meter Lari</option>
                                        <option value="3" <?php if ($mdluncate['denomination'] === "3") { echo 'selected'; } ?>>Meter Persegi</option>
                                        <option value="4" <?php if ($mdluncate['denomination'] === "4") { echo 'selected'; } ?>>Set</option>
                                    </select>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        if ($("#denominations<?= $mdluncate['id'] ?>").val() == "1") {
                                            $("#contupmdl<?= $mdluncate['id'] ?>").attr("hidden", false);
                                        }

                                        $("select[id='denominations<?= $mdluncate['id'] ?>']").change(function() {
                                            if ((this.value) === "1") {
                                                $('#contupmdl<?= $mdluncate['id'] ?>').attr("hidden", true);
                                            } else {
                                                $('#contupmdl<?= $mdluncate['id'] ?>').removeAttr("hidden");
                                            }
                                        });
                                    });
                                </script>

                                <div id="contupmdl<?= $mdluncate['id'] ?>">

                                    <div class="uk-margin-bottom">
                                        <label class="uk-form-label" for="length">Panjang</label>
                                        <div class="uk-form-controls">
                                            <input type="text" class="uk-input" id="length" name="length" value="<?= $mdluncate['length'] ?>" required />
                                        </div>
                                    </div>

                                    <div class="uk-margin-bottom">
                                        <label class="uk-form-label" for="width">Lebar</label>
                                        <div class="uk-form-controls">
                                            <input type="text" class="uk-input" id="width" name="width" value="<?= $mdluncate['width'] ?>" required />
                                        </div>
                                    </div>

                                    <div class="uk-margin-bottom">
                                        <label class="uk-form-label" for="height">Tinggi</label>
                                        <div class="uk-form-controls">
                                            <input type="text" class="uk-input" id="height" name="height" value="<?= $mdluncate['height'] ?>" required />
                                        </div>
                                    </div>

                                    <div class="uk-margin-bottom">
                                        <label class="uk-form-label" for="volume">Volume</label>
                                        <div class="uk-form-controls">
                                            <input type="text" class="uk-input" id="volume" name="volume" value="<?= $mdluncate['volume'] ?>" required />
                                        </div>
                                    </div>

                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="price">Harga</label>
                                    <div class="uk-form-controls">
                                        <input type="text" class="uk-input" id="price" name="price" placeholder="<?php echo 'Rp. ' . number_format((int)$mdluncate['price'], 0, ',', '.'); ' '; ?>" value="<?= $mdluncate['price'] ?>" data-type="curencyupdate" />
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="price">Keterangan</label>
                                    <div class="uk-margin">
                                        <textarea class="uk-textarea" type="text" name="keterangan" rows="5" placeholder="<?= $mdluncate['keterangan'] ?>" value="<?= $mdluncate['keterangan'] ?>" aria-label="Textarea"><?= $mdluncate['keterangan'] ?></textarea>
                                    </div>
                                </div>

                                <script>
                                    $("input[data-type='curencyupdate']").on({
                                        keyup: function() {
                                            formatCurrency($(this));
                                        },
                                        blur: function() {
                                            formatCurrency($(this), "blur");
                                        }
                                    });


                                    function formatNumber(n) {
                                        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                    }

                                    function formatCurrency(input, blur) {

                                        var input_val = input.val();

                                        if (input_val === "") {
                                            return;
                                        }

                                        var original_len = input_val.length;

                                        var caret_pos = input.prop("selectionStart");

                                        if (input_val.indexOf(".") >= 0) {

                                            var decimal_pos = input_val.indexOf(".");

                                            var left_side = input_val.substring(0, decimal_pos);
                                            var right_side = input_val.substring(decimal_pos);

                                            left_side = formatNumber(left_side);

                                            right_side = formatNumber(right_side);

                                            if (blur === "blur") {
                                                right_side += "00";
                                            }

                                            right_side = right_side.substring(0, 2);

                                            input_val = "Rp" + left_side + "." + right_side;

                                        } else {

                                            input_val = formatNumber(input_val);
                                            input_val = "Rp" + input_val;

                                            if (blur === "blur") {
                                                input_val += ".00";
                                            }
                                        }

                                        input.val(input_val);

                                        var updated_len = input_val.length;
                                        caret_pos = updated_len - original_len + caret_pos;
                                        input[0].setSelectionRange(caret_pos, caret_pos);
                                    }
                                </script>

                                <div class="uk-modal-footer">
                                    <div class="uk-text-right">
                                        <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }
        } ?>
        <!-- MDL UNCATEGORIZE -->
<?php } ?>

<!-- This Containt Modal -->
<div id="modalcontainer">
</div>

<div id="modalContainerImport">
</div>
<!--This Containt Modal End -->


<?= $this->endSection() ?>