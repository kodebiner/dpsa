
<?= $this->extend('layout') ?>
<?= $this->section('extraScript') ?>
    <script src="js/jquery-3.7.0.js"></script>
<?= $this->endSection() ?>
<?= $this->section('main') ?>

<!-- Page Heading -->
<div class="tm-card-header uk-margin-remove-left">
    <div uk-grid class="uk-flex-middle uk-child-width-1-2">
        <div>
            <div class="tm-h3">Daftar MDL</div>
        </div>

        <!-- Button Trigger Modal Add -->
        <div class="uk-text-right">
            <button class="uk-button uk-button-primary" href="#modaladd" uk-toggle>Tambah MDL</button>
        </div>
        <!-- End Of Button Trigger Modal Add -->
    </div>
</div>
<!-- End Of Page Heading -->

<?= view('Views/Auth/_message_block') ?>

<!-- Table Of Content -->
<div class="uk-overflow-auto">
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Panjang</th>
                <th>Lebar</th>
                <th>Tinggi</th>
                <th>Volume</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($mdls as $mdl) { ?>
                <tr>
                    <td><?= $i++?></td>
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
                        }
                        ?>
                    </td>
                    <td><?= $mdl['price'] ?></td>
                    <td><a class="uk-icon-button" href="#modalupdate<?=$mdl['id']?>" uk-icon="pencil" uk-toggle></a></td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<!-- End Table Of Content -->

<!-- Modal Add MDL -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog" uk-overflow-auto>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Tambah MDL</h2>
            <button class="uk-modal-close-default" type="button" uk-close></button>
        </div>

        <div class="uk-modal-body">
            <form class="uk-form-stacked" role="form" action="mdl/create" method="post">
                <?= csrf_field() ?>
                
                <div class="uk-margin-bottom">
                    <label class="uk-form-label" for="name">Nama</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" id="name" name="name" placeholder="Nama" required />
                    </div>
                </div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="denomination">Satuan</label>
                    <select class="uk-select" aria-label="Satuan" id="denomination" name="denomination" required>
                        <option value="" selected disabled hidden>Pilih Satuan</option>
                        <option value="1">Unit</option>
                        <option value="2">Meter Lari</option>
                        <option value="3">Meter Persegi</option>
                    </select>
                </div>

                <script>
                    document.getElementById('denomination').addEventListener('change', function() {
                        if (this.value == "2" || this.value == "3") {
                            var elements = document.getElementById('contdim');
                            if (elements){
                                elements.remove();
                            }
                            var dimentions  = document.getElementById('dimentions');

                            var contdim     = document.createElement('div');
                            contdim.setAttribute('id', 'contdim');

                            var contlength  = document.createElement('div');
                            contlength.setAttribute('class', 'uk-margin');

                            var lablength   = document.createElement('label');
                            lablength.setAttribute('class', 'uk-form-label');
                            lablength.setAttribute('for', 'length');
                            lablength.innerHTML = "Panjang";

                            var coninputl   = document.createElement('div');
                            coninputl.setAttribute('class', 'uk-form-controls');

                            var inputl      = document.createElement('input');
                            inputl.setAttribute('class', 'uk-input');
                            inputl.setAttribute('type', 'number');
                            inputl.setAttribute('id', 'length');
                            inputl.setAttribute('name', 'length');
                            inputl.setAttribute('placeholder', 'Panjang');
                            inputl.setAttribute('required', '');

                            var contw  = document.createElement('div');
                            contw.setAttribute('class', 'uk-margin');

                            var labw   = document.createElement('label');
                            labw.setAttribute('class', 'uk-form-label');
                            labw.setAttribute('for', 'width');
                            labw.innerHTML = "Lebar";

                            var coninputw   = document.createElement('div');
                            coninputw.setAttribute('class', 'uk-form-controls');

                            var inputw      = document.createElement('input');
                            inputw.setAttribute('class', 'uk-input');
                            inputw.setAttribute('type', 'number');
                            inputw.setAttribute('id', 'width');
                            inputw.setAttribute('name', 'width');
                            inputw.setAttribute('placeholder', 'Lebar');
                            inputw.setAttribute('required', '');

                            var conth  = document.createElement('div');
                            conth.setAttribute('class', 'uk-margin');

                            var labh   = document.createElement('label');
                            labh.setAttribute('class', 'uk-form-label');
                            labh.setAttribute('for', 'height');
                            labh.innerHTML = "Tinggi";

                            var coninputh   = document.createElement('div');
                            coninputh.setAttribute('class', 'uk-form-controls');

                            var inputh      = document.createElement('input');
                            inputh.setAttribute('class', 'uk-input');
                            inputh.setAttribute('type', 'number');
                            inputh.setAttribute('id', 'height');
                            inputh.setAttribute('name', 'height');
                            inputh.setAttribute('placeholder', 'Tinggi');
                            inputh.setAttribute('required', '');

                            var contv  = document.createElement('div');
                            contv.setAttribute('class', 'uk-margin');

                            var labv   = document.createElement('label');
                            labv.setAttribute('class', 'uk-form-label');
                            labv.setAttribute('for', 'volume');
                            labv.innerHTML = "Volume";

                            var coninputv   = document.createElement('div');
                            coninputv.setAttribute('class', 'uk-form-controls');

                            var inputv      = document.createElement('input');
                            inputv.setAttribute('class', 'uk-input');
                            inputv.setAttribute('type', 'number');
                            inputv.setAttribute('id', 'volume');
                            inputv.setAttribute('name', 'volume');
                            inputv.setAttribute('placeholder', 'Volume');
                            inputv.setAttribute('required', '');

                            coninputl.appendChild(inputl);
                            coninputw.appendChild(inputw);
                            coninputh.appendChild(inputh);
                            coninputv.appendChild(inputv);
                            contv.appendChild(labv);
                            contv.appendChild(coninputv);
                            conth.appendChild(labh);
                            conth.appendChild(coninputh);
                            contw.appendChild(labw);
                            contw.appendChild(coninputw);
                            contlength.appendChild(lablength);
                            contlength.appendChild(coninputl);
                            contdim.appendChild(contlength);
                            contdim.appendChild(contw);
                            contdim.appendChild(conth);
                            contdim.appendChild(contv);
                            dimentions.appendChild(contdim);
                        } else {
                            var dimentions = document.getElementById('contdim');
                            if (dimentions) {
                                dimentions.remove();
                            }
                        }
                    });
                </script>

                <div id="dimentions"></div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="price">Harga</label>
                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" id="price" name="price" placeholder="Harga" required />
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
<!-- Modal Add MDL End -->

<!-- Modal Edit MDL -->
<?php foreach ($mdls as $mdl) { ?>
    <div id="modalupdate<?= $mdl['id'] ?>" uk-modal>
        <div class="uk-modal-dialog" uk-overflow-auto>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Ubah MDL</h2>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>

            <div class="uk-modal-body">
                <form class="uk-form-stacked" role="form" action="mdl/update/<?=$mdl['id']?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="uk-margin-bottom">
                        <label class="uk-form-label" for="name">Nama</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="name" name="name" value="<?= $mdl['name'] ?>"/>
                        </div>
                    </div>
                    
                    <!-- Belum Selesai -->
                        <div class="uk-margin">
                            <label class="uk-form-label" for="denomination">Satuan</label>
                            <select class="uk-select" aria-label="Satuan" id="denominations" name="denomination" required>
                                <option value="1" <?php if ($mdl['denomination'] === "1") {echo 'selected';} ?>>Unit</option>
                                <option value="2" <?php if ($mdl['denomination'] === "2") {echo 'selected';} ?>>Meter Lari</option>
                                <option value="3" <?php if ($mdl['denomination'] === "3") {echo 'selected';} ?>>Meter Persegi</option>
                            </select>
                        </div>

                        <script>
                            document.getElementById('denominations').addEventListener('change', function() {
                                if (this.value == "2" || this.value == "3") {
                                    var elements = document.getElementById('contdim<?= $mdl['id'] ?>');
                                    if (elements){
                                        elements.remove();
                                    }
                                    var dimentions  = document.getElementById('updatedimentions<?= $mdl['id'] ?>');

                                    var contdim     = document.createElement('div');
                                    contdim.setAttribute('id', 'contdim<?= $mdl['id'] ?>');

                                    var contlength  = document.createElement('div');
                                    contlength.setAttribute('class', 'uk-margin');

                                    var lablength   = document.createElement('label');
                                    lablength.setAttribute('class', 'uk-form-label');
                                    lablength.setAttribute('for', 'length');
                                    lablength.innerHTML = "Panjang";

                                    var coninputl   = document.createElement('div');
                                    coninputl.setAttribute('class', 'uk-form-controls');

                                    var inputl      = document.createElement('input');
                                    inputl.setAttribute('class', 'uk-input');
                                    inputl.setAttribute('type', 'number');
                                    inputl.setAttribute('id', 'length');
                                    inputl.setAttribute('name', 'length');
                                    inputl.setAttribute('value', <?= $mdl['length'] ?>);
                                    inputl.setAttribute('placeholder', 'Panjang');
                                    inputl.setAttribute('required', '');

                                    var contw  = document.createElement('div');
                                    contw.setAttribute('class', 'uk-margin');

                                    var labw   = document.createElement('label');
                                    labw.setAttribute('class', 'uk-form-label');
                                    labw.setAttribute('for', 'width');
                                    labw.innerHTML = "Lebar";

                                    var coninputw   = document.createElement('div');
                                    coninputw.setAttribute('class', 'uk-form-controls');

                                    var inputw      = document.createElement('input');
                                    inputw.setAttribute('class', 'uk-input');
                                    inputw.setAttribute('type', 'number');
                                    inputw.setAttribute('id', 'width');
                                    inputw.setAttribute('name', 'width');
                                    inputl.setAttribute('value', <?= $mdl['width'] ?>);
                                    inputw.setAttribute('placeholder', 'Lebar');
                                    inputw.setAttribute('required', '');

                                    var conth  = document.createElement('div');
                                    conth.setAttribute('class', 'uk-margin');

                                    var labh   = document.createElement('label');
                                    labh.setAttribute('class', 'uk-form-label');
                                    labh.setAttribute('for', 'height');
                                    labh.innerHTML = "Tinggi";

                                    var coninputh   = document.createElement('div');
                                    coninputh.setAttribute('class', 'uk-form-controls');

                                    var inputh      = document.createElement('input');
                                    inputh.setAttribute('class', 'uk-input');
                                    inputh.setAttribute('type', 'number');
                                    inputh.setAttribute('id', 'height');
                                    inputh.setAttribute('name', 'height');
                                    inputl.setAttribute('value', <?= $mdl['height'] ?>);
                                    inputh.setAttribute('placeholder', 'Tinggi');
                                    inputh.setAttribute('required', '');

                                    var contv  = document.createElement('div');
                                    contv.setAttribute('class', 'uk-margin');

                                    var labv   = document.createElement('label');
                                    labv.setAttribute('class', 'uk-form-label');
                                    labv.setAttribute('for', 'volume');
                                    labv.innerHTML = "Volume";

                                    var coninputv   = document.createElement('div');
                                    coninputv.setAttribute('class', 'uk-form-controls');

                                    var inputv      = document.createElement('input');
                                    inputv.setAttribute('class', 'uk-input');
                                    inputv.setAttribute('type', 'number');
                                    inputv.setAttribute('id', 'volume');
                                    inputv.setAttribute('name', 'volume');
                                    inputl.setAttribute('value', <?= $mdl['volume'] ?>);
                                    inputv.setAttribute('placeholder', 'Volume');
                                    inputv.setAttribute('required', '');

                                    coninputl.appendChild(inputl);
                                    coninputw.appendChild(inputw);
                                    coninputh.appendChild(inputh);
                                    coninputv.appendChild(inputv);
                                    contv.appendChild(labv);
                                    contv.appendChild(coninputv);
                                    conth.appendChild(labh);
                                    conth.appendChild(coninputh);
                                    contw.appendChild(labw);
                                    contw.appendChild(coninputw);
                                    contlength.appendChild(lablength);
                                    contlength.appendChild(coninputl);
                                    contdim.appendChild(contlength);
                                    contdim.appendChild(contw);
                                    contdim.appendChild(conth);
                                    contdim.appendChild(contv);
                                    dimentions.appendChild(contdim);
                                } else {
                                    var dimentions  = document.getElementById('contdim<?= $mdl['id'] ?>');
                                    if (dimentions) {
                                        dimentions.remove();
                                    }
                                    var dim         = document.getElementById('contupmdl<?= $mdl['id'] ?>');
                                    if (dim) {
                                        dim.remove();
                                    }
                                }
                            });
                        </script>

                        <div id="contupmdl<?= $mdl['id'] ?>">
                            <?php if (($mdl['denomination'] === "2") || ($mdl['denomination'] === "3")) { ?>
                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="length">Panjang</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="length" name="length" value="<?= $mdl['length'] ?>"/>
                                    </div>
                                </div>
                                
                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="width">Lebar</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="width" name="width" value="<?= $mdl['width'] ?>"/>
                                    </div>
                                </div>
                                
                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="height">Tinggi</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="height" name="height" value="<?= $mdl['height'] ?>"/>
                                    </div>
                                </div>
                                
                                <div class="uk-margin-bottom">
                                    <label class="uk-form-label" for="volume">Volume</label>
                                    <div class="uk-form-controls">
                                        <input type="number" class="uk-input" id="volume" name="volume" value="<?= $mdl['volume'] ?>"/>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div id="updatedimentions<?= $mdl['id'] ?>"></div>
                    <!-- Belum Selesai -->
                    
                    <div class="uk-margin">
                        <label class="uk-form-label" for="price">Harga</label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" id="price" name="price" value="<?= $mdl['price'] ?>"/>
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
<!-- Modal Edit MDL End -->

<!-- update project modal -->
<!-- <?php foreach ($mdls as $mdl) { ?>
<div id="modalupdate<?=$mdl['id']?>" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Update Mdl</h2>
        </div>

        <form class="uk-margin-left" action="mdl/update/<?=$mdl['id']?>" method="post">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="<?=$mdl['name']?>" value="<?=$mdl['name']?>" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: database"></span>
                    <input class="uk-input uk-form-width-large" name="price" placeholder="<?=$mdl['price']?>" value="<?=$mdl['price']?>" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer">
                <div class="uk-flex">
                    <div class="uk-width-1-2@m uk-text-left">
                        <a uk-icon="trash" class="uk-icon-button-delete uk-button-danger" methode="post" href="mdl/delete/<?=$mdl['id']?>" onclick="return confirm('<?=lang('Global.deleteConfirm')?>')"></a>
                    </div>
                    <div class="uk-width-1-2@m uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                    </div>
                </div>
                
               
            </div>
           
        </form>

    </div>
</div>
<?php } ?> -->
<!-- end update project modal -->

<?= $this->endSection() ?>