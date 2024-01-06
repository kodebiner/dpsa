<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="uk-container uk-container-large">

    <!-- Page Heading -->
    <?php if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) { ?>
        <?php if ($ismobile) { ?>
            <!-- <h1 class="tm-h1 uk-text-center uk-margin-remove"></?= lang("Global.projectList") ?><br/><//?=$client->firstname?> </?=$client->lastname?></h1> -->
            <h1 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek<br />
                <?php if (!empty($client['rsname'])) {
                    echo $client['rsname'];
                } ?>
            </h1>
            <div class="uk-margin uk-text-center">
                <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
            </div>
            <div id="filter" class="uk-margin" hidden>
                <form id="searchform" action="<?= $uri ?>" method="GET">
                    <div class="uk-margin-small uk-flex uk-flex-center">
                        <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                    </div>
                    <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                        <div>Tampilan</div>
                        <div>
                            <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                            </select>
                        </div>
                        <div>Per Halaman</div>
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
            <!-- <h1 class="tm-h1 uk-margin-remove"></?= lang("Global.projectList") ?> </?=$client->firstname?> </?=$client->lastname?></h1> -->
            <h1 class="tm-h1 uk-margin-remove">
                Daftar Proyek
                <?php if (!empty($client['rsname'])) {
                    echo $client['rsname'];
                } ?>
            </h1>
        <?php } ?>
        <hr class="uk-divider-icon">
        <!-- end of Page Heading -->

        <!-- Content -->
        <?php if (!$ismobile) { ?>
            <form class="uk-margin" id="searchform" action="<?= $uri ?>" method="GET">
                <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Cari:</div>
                            <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                        </div>
                    </div>
                    <div>
                        <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                            <div>Tampilan</div>
                            <div>
                                <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                    <option value="10" <?= (isset($input['perpage']) && ($input['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= (isset($input['perpage']) && ($input['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= (isset($input['perpage']) && ($input['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= (isset($input['perpage']) && ($input['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                                </select>
                            </div>
                            <div>Per Halaman</div>
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
                <?php if ($ismobile) { ?>
                    <div class="uk-margin uk-card uk-card-default">
                        <div class="uk-card-header">
                            <div class="uk-grid-small uk-flex-middle" uk-grid>
                                <div class="uk-width-expand">
                                    <h3 class="uk-card-title"><?= $project['name'] ?></h3>
                                </div>
                                <div class="uk-width-auto">
                                    <button id="open<?= $project['id'] ?>" class="uk-icon-button" uk-icon="chevron-down" uk-toggle="target: #body<?= $project['id'] ?>"></button>
                                    <button id="close<?= $project['id'] ?>" class="uk-icon-button" uk-icon="chevron-up" uk-toggle="target: #body<?= $project['id'] ?>" hidden></button>
                                </div>
                            </div>
                            <progress class="uk-progress" value="<?= $progress ?>" max="100"></progress>
                        </div>
                        <div id="body<?= $project['id'] ?>" class="uk-card-body" hidden>
                            <div class="uk-grid-divider" uk-grid>
                                <div class="uk-width-1-1">
                                    <h4>Detail Pesanan</h4>
                                </div>
                                <?php if (!empty($project['id'])) { ?>
                                    <div class="uk-overflow-auto uk-margin">
                                        <table class="uk-table">
                                            <thead>
                                                <tr>
                                                    <th class="">Nama</th>
                                                    <th class="uk-text-center">Panjang</th>
                                                    <th class="uk-text-center">Lebar</th>
                                                    <th class="uk-text-center">Tinggi</th>
                                                    <th class="uk-text-center">Volume</th>
                                                    <th class="uk-text-center">Satuan</th>
                                                    <th class="uk-text-center">Jumlah Pesanan</th>
                                                    <th class="uk-text-center">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($project['id'])) {
                                                    foreach ($rabs as $rab) {
                                                        if ($rab['projectid'] === $project['id']) {
                                                            foreach ($pakets as $paket) {
                                                                if ($paket['id'] === $rab['paketid']) {
                                                                    foreach ($mdls as $mdl) {
                                                                        if ($mdl['id'] === $rab['mdlid']) { ?>
                                                                            <tr>
                                                                                <td class=""><?= $mdl['name'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['length'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['width'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['height'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['volume'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['denomination'] ?></td>
                                                                                <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['price'] ?></td>
                                                                            </tr>
                                                <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Status Proyek</h4>
                                    <div class="uk-text-center"><?= $status ?></div>
                                </div>
                                <?php if ($project['status'] === '4') { ?>
                                    <div class="uk-width-1-2">
                                        <h4 class="uk-text-center">Progress Produksi</h4>
                                        <div class="uk-text-center"><?= $project['production'] ?>%</div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('open<?= $project['id'] ?>').addEventListener('click', buttontoggle<?= $project['id'] ?>);
                        document.getElementById('close<?= $project['id'] ?>').addEventListener('click', buttontoggle<?= $project['id'] ?>);

                        function buttontoggle<?= $project['id'] ?>() {
                            if (document.getElementById('close<?= $project['id'] ?>').hasAttribute('hidden')) {
                                document.getElementById('open<?= $project['id'] ?>').setAttribute('hidden', '');
                                document.getElementById('close<?= $project['id'] ?>').removeAttribute('hidden');
                            } else {
                                document.getElementById('open<?= $project['id'] ?>').removeAttribute('hidden');
                                document.getElementById('close<?= $project['id'] ?>').setAttribute('hidden', '');
                            }
                        }
                    </script>
                <?php } else { ?>
                    <div class="uk-margin uk-card uk-card-default uk-card-hover">
                        <div class="uk-card-header">
                            <h3 class="uk-card-title"><?= $project['name'] ?></h3>
                            <progress class="uk-progress" value="<?= $progress ?>" max="100"></progress>
                        </div>
                        <?php
                        $desainpro  = "";
                        $tanggal    = "";
                        $designId   = "";
                        foreach ($design as $desain) {
                            if ($desain['projectid'] === $project['id']) {
                                $desainpro      = $desain['submitted'];
                                $designId       = $desain['id'];
                                $designStatus   = $desain['status'];
                                $dateTimeObj = new DateTime($desain['updated_at'], new DateTimeZone('Asia/Jakarta'));
                                $dateFormatted =
                                    IntlDateFormatter::formatObject(
                                        $dateTimeObj,
                                        'eeee, d MMMM y, HH:mm:ss',
                                        'id'
                                    );
                                $tanggal = ucwords($dateFormatted);
                            }
                        }
                        ?>
                        <div class="uk-card-body">
                            <div class="uk-grid-divider" uk-grid>
                                <?php if (!empty($desainpro)) { ?>
                                    <div class="uk-width-1-1">
                                        <?= view('Views/Auth/_message_block') ?>
                                        <div>
                                            <h4>Desain</h4>
                                            <div class="uk-margin" uk-grid>
                                                <div class="uk-width-auto@m">
                                                    <div class="">Tanggal Desain</div>
                                                </div>
                                                <div class="uk-width-1-3@m">
                                                    <div>
                                                        <?= $tanggal ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-margin" uk-grid>
                                                <div class="uk-width-auto@m">
                                                    <div class="">Status Desain</div>
                                                </div>
                                                <div class="uk-width-1-3@m uk-text-right">
                                                    <div class="" id="st">
                                                        <?php
                                                        if ($designStatus === "0") {
                                                            echo '<div class="uk-text-light uk-width-1-2 uk-text-center" id="status'.$designId.'" style="border-style: solid; border-color: #ff0000; color: white; background-color:#ff0000;  font-weight: bold;"> Menuggu Konfirmasi </div>';
                                                        } elseif ($designStatus === "1") {
                                                            echo '<div class="uk-text-light" id="status'.$designId.'" style="border-style: solid; color: white; background-color:#32CD32;  font-weight: bold;"> Dalam proses revisi </div>';
                                                        } else {
                                                            echo '<div class="uk-text-light uk-width-1-2 uk-text-center" id="status'.$designId.'" style="border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;"> Terkonfirmasi </div>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div uk-lightbox>
                                                <a class="uk-height-medium uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light" href="img/design/<?= $desainpro ?>" data-src="img/design/<?= $desainpro ?>" uk-img></a>
                                            </div>
                                            <p uk-margin>
                                                <button class="uk-button uk-button-primary" value="2" id="acc<?= $designId ?>">Setuju</button>
                                                <button class="uk-button uk-button-secondary" uk-toggle="target: #modal-revisi<?= $project['id'] ?>">Revisi</button>
                                            </p>
                                            <script>
                                                $(document).ready(function() {
                                                    $("#acc<?= $designId ?>").click(function() {
                                                        $.ajax({
                                                            url: "home/acc/<?= $designId ?>",
                                                            method: "POST",
                                                            data: {
                                                                status: $('#acc<?= $designId ?>').val()
                                                            },
                                                            dataType: "json",
                                                            error: function() {
                                                                console.log('error', arguments);
                                                            },
                                                            success: function() {
                                                                console.log('success', arguments);
                                                                $("#status<?=$designId?>").remove();
                                                                $("#st").append("<div class='uk-text-light uk-width-1-2 uk-text-center' style='border-style: solid; color: #32CD32; border-color:#32CD32;  font-weight: bold;'>Terkonfirmasi</div>");
                                                            },
                                                        })
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>

                                    <!-- This is the modal -->
                                    <div id="modal-revisi<?= $project['id'] ?>" uk-modal>
                                        <div class="uk-modal-dialog uk-modal-body">
                                            <div class="uk-modal-header">
                                                <h2 class="uk-modal-title">Revisi Desain</h2>
                                            </div>
                                            <div class="uk-modal-body">
                                                <form class="uk-form-stacked" action="home/saverevisi/<?= $project['id'] ?>" method="post">
                                                    <?php foreach ($projectdata[$project['id']]['design'] as $design) { ?>
                                                        <?php
                                                        $dateTimeObj = new DateTime($design['updated_at'], new DateTimeZone('Asia/Jakarta'));
                                                        $dateFormatted =
                                                            IntlDateFormatter::formatObject(
                                                                $dateTimeObj,
                                                                'eeee, d MMMM y, HH:mm:ss',
                                                                'id'
                                                            );
                                                        ?>
                                                        <?php if (!empty($design['revision'])) { ?>
                                                            <img class="uk-width-1-1" src="/img/revisi/<?= $design['revision'] ?>" />
                                                            <div class="uk-text-center uk-margin-small-top">
                                                                <label class="uk-text-middle uk-align-center">Tanggal Revisi : <?= ucwords($dateFormatted) ?></label>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <div class="uk-margin" id="image-container-create-<?= $project['id'] ?>">
                                                        <label class="uk-form-label" for="photocreate">Unggah Revisi</label>
                                                        <div id="image-container-<?= $project['id'] ?>" class="uk-form-controls">
                                                            <input id="photocreate<?= $project['id'] ?>" name="revisi" hidden />
                                                            <div class="js-upload-create-<?= $project['id'] ?> uk-placeholder uk-text-center">
                                                                <span uk-icon="icon: cloud-upload"></span>
                                                                <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                                                <div uk-form-custom>
                                                                    <input type="file">
                                                                    <span class="uk-link uk-preserve-color">pilih satu</span>
                                                                </div>
                                                            </div>
                                                            <progress id="js-progressbar-create-<?= $project['id'] ?>" class="uk-progress" value="0" max="100" hidden></progress>
                                                        </div>
                                                    </div>

                                                    <script type="text/javascript">
                                                        var bar = document.getElementById('js-progressbar-create-<?= $project['id'] ?>');

                                                        UIkit.upload('.js-upload-create-<?= $project['id'] ?>', {
                                                            url: 'home/revisi',
                                                            multiple: false,
                                                            name: 'uploads',
                                                            param: {
                                                                lorem: 'ipsum'
                                                            },
                                                            method: 'POST',
                                                            type: 'json',

                                                            beforeSend: function() {
                                                                console.log('beforeSend', arguments);
                                                            },
                                                            beforeAll: function() {
                                                                console.log('beforeAll', arguments);
                                                            },
                                                            load: function() {
                                                                console.log('load', arguments);
                                                            },
                                                            error: function() {
                                                                console.log('error', arguments);
                                                                var error = arguments[0].xhr.response.message.uploads;
                                                                alert(error);
                                                            },
                                                            complete: function() {
                                                                console.log('complete', arguments);

                                                                var filename = arguments[0].response;

                                                                if (document.getElementById('display-container-create-<?= $project['id'] ?>')) {
                                                                    document.getElementById('display-container-create-<?= $project['id'] ?>').remove();
                                                                };

                                                                document.getElementById('photocreate<?= $project['id'] ?>').value = filename;

                                                                var imgContainer = document.getElementById('image-container-create-<?= $project['id'] ?>');

                                                                var displayContainer = document.createElement('div');
                                                                displayContainer.setAttribute('id', 'display-container-create-<?= $project['id'] ?>');
                                                                displayContainer.setAttribute('class', 'uk-inline');

                                                                var displayImg = document.createElement('img');
                                                                displayImg.setAttribute('src', 'img/revisi/' + filename);

                                                                var closeContainer = document.createElement('div');
                                                                closeContainer.setAttribute('class', 'uk-position-small uk-position-top-right');

                                                                var closeButton = document.createElement('a');
                                                                closeButton.setAttribute('class', 'tm-img-remove uk-border-circle');
                                                                closeButton.setAttribute('onClick', 'removeImgCreate<?= $project['id'] ?>()');
                                                                closeButton.setAttribute('uk-icon', 'close');

                                                                closeContainer.appendChild(closeButton);
                                                                displayContainer.appendChild(displayImg);
                                                                displayContainer.appendChild(closeContainer);
                                                                imgContainer.appendChild(displayContainer);

                                                                document.getElementsByClassName('js-upload-create-<?= $project['id'] ?>').setAttribute('hidden', '');
                                                            },

                                                            loadStart: function(e) {
                                                                console.log('loadStart', arguments);

                                                                bar.removeAttribute('hidden');
                                                                bar.max = e.total;
                                                                bar.value = e.loaded;
                                                            },

                                                            progress: function(e) {
                                                                console.log('progress', arguments);

                                                                bar.max = e.total;
                                                                bar.value = e.loaded;
                                                            },

                                                            loadEnd: function(e) {
                                                                console.log('loadEnd', arguments);

                                                                bar.max = e.total;
                                                                bar.value = e.loaded;
                                                            },

                                                            completeAll: function() {
                                                                console.log('completeAll', arguments);

                                                                setTimeout(function() {
                                                                    bar.setAttribute('hidden', 'hidden');
                                                                }, 1000);

                                                                alert('Data Berhasil Terunggah');
                                                            }
                                                        });

                                                        function removeImgCreate<?= $project['id'] ?>() {
                                                            $.ajax({
                                                                type: 'post',
                                                                url: 'home/removerevisi',
                                                                data: {
                                                                    'revisi': document.getElementById('photocreate<?= $project['id'] ?>').value
                                                                },
                                                                dataType: 'json',

                                                                error: function() {
                                                                    console.log('error', arguments);
                                                                },

                                                                success: function() {
                                                                    console.log('success', arguments);

                                                                    var pesan = arguments[0][1];

                                                                    document.getElementById('display-container-create-<?= $project['id'] ?>').remove();
                                                                    document.getElementById('js-progressbar-create-<?= $project['id'] ?>').remove();
                                                                    document.getElementById('photocreate<?= $project['id'] ?>').value = '';

                                                                    alert(pesan);
                                                                }
                                                            });
                                                        };
                                                    </script>
                                                    <div class="uk-modal-footer uk-text-center">
                                                        <button class="uk-button uk-button-primary" type="submit">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end of modal revisi -->
                                <?php } ?>

                                <div class="uk-width-1-1">
                                    <h4>Detail Pesanan</h4>
                                </div>
                                <?php if (!empty($project['id'])) { ?>
                                    <div class="uk-width-1-1">
                                        <table class="uk-table">
                                            <thead>
                                                <tr>
                                                    <th class="">Nama</th>
                                                    <th class="uk-text-center">Panjang</th>
                                                    <th class="uk-text-center">Lebar</th>
                                                    <th class="uk-text-center">Tinggi</th>
                                                    <th class="uk-text-center">Volume</th>
                                                    <th class="uk-text-center">Satuan</th>
                                                    <th class="uk-text-center">Jumlah Pesanan</th>
                                                    <th class="uk-text-center">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($project['id'])) {
                                                    foreach ($rabs as $rab) {
                                                        if ($rab['projectid'] === $project['id']) {
                                                            foreach ($pakets as $paket) {
                                                                if ($paket['id'] === $rab['paketid']) {
                                                                    foreach ($mdls as $mdl) {
                                                                        if ($mdl['id'] === $rab['mdlid']) { ?>
                                                                            <tr>
                                                                                <td class=""><?= $mdl['name'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['length'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['width'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['height'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['volume'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['denomination'] ?></td>
                                                                                <td class="uk-text-center"><?= $rab['qty'] ?></td>
                                                                                <td class="uk-text-center"><?= $mdl['price'] ?></td>
                                                                            </tr>
                                                <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                                <div class="uk-width-1-2">
                                    <h4 class="uk-text-center">Status Proyek</h4>
                                    <div class="uk-text-center"><?= $status ?></div>
                                </div>
                                <?php if ($project['status'] === '4') { ?>
                                    <div class="uk-width-1-2">
                                        <h4 class="uk-text-center">Progress Produksi</h4>
                                        <div class="uk-text-center"><?= $project['production'] ?>%</div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <div class="uk-text-center uk-text-italic">Data tidak Ditemukan.</div>
        <?php } ?>
        <!-- end of Content -->

        <?= $pager ?>
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>
    <?php } ?>
</div>
<?= $this->endSection() ?>