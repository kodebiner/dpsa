<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="css/code.jquery.com_ui_1.13.2_themes_base_jquery-ui.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <div class="uk-container uk-container-large">
        <?php if ($this->data['authorize']->hasPermission('admin.project.read', $uid)) { ?>

            <!-- Page Heading -->
            <?php if ($ismobile === true) { ?>
            <h3 class="tm-h1 uk-text-center uk-margin-remove">DAFTAR KLIEN</h3>
            <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                <div class="uk-text-center uk-margin">
                    <button class="uk-button uk-button-primary uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
                </div>
                <div class="uk-text-center uk-margin">
                    <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
                </div>

                <div id="filter" class="uk-margin" hidden>
                    <form id="searchform" action="project" method="GET">
                        <div class="uk-margin-small uk-flex uk-flex-center">
                            <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="Cari" <?= (isset($inputpage['search']) ? 'value="' . $inputpage['search'] . '"' : '') ?> />
                        </div>
                        <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                            <div>Tampilan</div>
                            <div>
                                <select class="uk-select uk-form-width-xsmall" id="perpage" name="perpage">
                                    <option value="10" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '10') ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '25') ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '50') ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= (isset($inputpage['perpage']) && ($inputpage['perpage'] === '100') ? 'selected' : '') ?>>100</option>
                                </select>
                            </div>
                            <div>Per Halaman</div>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <?php } else { ?>
                <div class="uk-margin uk-child-width-auto uk-flex-between" uk-grid>
                    <div>
                        <h3 class="tm-h1 uk-text-center uk-margin-remove">DAFTAR KLIEN</h3>
                    </div>
                    <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                        <div>
                            <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>Tambah Proyek</button>
                        </div>
                    <?php } ?>
                </div>
                <?= view('Views/Auth/_message_block') ?>
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

            <hr class="uk-divider-icon uk-margin-remove-top">
            <!-- End Of Page Heading -->

            <!-- Content -->
            <?php if ($ismobile === true) { ?>
                <?php if (!empty($clients)) { ?>
                    <?php foreach ($clients as $client) { ?>
                        <a href="project/listprojectclient/<?= $client['id'] ?>">
                            <div class="uk-margin uk-card uk-card-default uk-card-body">
                                <div class="uk-grid-small uk-flex-middle" uk-grid>
                                    <div class="uk-width-auto"><span uk-icon="folder"></span></div>
                                    <div class="uk-width-expand"><?= $client['rsname'] ?></div>
                                    <div class="uk-width-auto uk-margin-auto-left"><span uk-icon="chevron-right"></span></div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div class="uk-width-1-1 uk-text-center uk-text-italic">Belum Ada Klien</div>
                <?php } ?>
            <?php } else { ?>
                <form class="uk-margin" id="searchform" action="project" method="GET">
                    <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                        <div>
                            <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                <div><?= lang('Global.search') ?>:</div>
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

                <?php if (!empty($clients)) { ?>
                    <div class="uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid uk-height-match="target: > div > a > .uk-card-body">
                        <?php foreach ($clients as $client) { ?>
                            <div>
                                <a href="project/listprojectclient/<?= $client['id'] ?>" class="uk-link-reset">
                                    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-flex uk-flex-middle">
                                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                                            <div class="uk-width-auto"><span uk-icon="icon: folder; ratio: 1.5;"></span></div>
                                            <div class="uk-width-expand">
                                                <h3 class="uk-text-uppercase"><?= $client['rsname'] ?></h3>
                                            </div>
                                            <div class="uk-width-auto"><span uk-icon="icon: chevron-right; ratio: 1.5;"></span></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="uk-width-1-1 uk-text-center uk-text-italic"><?= lang('Global.noData') ?></div>
                <?php } ?>
            <?php } ?>

            <?= $pagerpro ?>
            
        <?php } ?>
    </div>
    <!-- end of Content -->
    
    <script>
        document.getElementById('search').addEventListener("change", submitform);
        document.getElementById('perpage').addEventListener("change", submitform);
        
        function submitform() {
            document.getElementById('searchform').submit();
        };
    </script>

    <!-- Modal Add Proyek -->
    <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
        <div class="uk-modal-container" id="modaladd" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical" uk-overflow-auto>
                <div class="uk-modal-content">
                    <div class="uk-modal-header">
                        <button class="uk-modal-close-default uk-icon-button-delete" type="button" uk-close></button>
                        <h2 class="uk-modal-title">Tambah Proyek</h2>
                    </div>
                    <div class="uk-modal-body">
                        <form class="uk-form-stacked" action="project/create" method="post">

                            <div class="uk-margin">
                                <label class="uk-form-label" for="company">Nama Proyek</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" name="name" placeholder="Nama Proyek" type="text" aria-label="Not clickable icon" required>
                                </div>
                            </div>

                            <!-- Add Client Auto Complete -->
                            <div class="uk-margin" id="pusat">
                                <label class="uk-form-label" for="company">Perusahaan</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="company" name="company" placeholder="Masukkan nama perusahaan yang terdaftar sebagai klien..." required>
                                    <input id="compid" name="company" value="" hidden>
                                </div>
                            </div>
                            <!-- End Of Add Client -->

                            <!-- Design Section -->
                            <!-- Select With Design Or Without Design Section -->
                            <label class="uk-form-label" for="designtype">Dengan Desain atau Tanpa Desain</label>
                            <label class="switch  uk-margin-bottom">
                                <input id="designtype" name="designtype" type="checkbox" value="0">
                                <span class="slider round"></span>
                            </label>
                            <script>
                                $(document).ready(function() {
                                    $("input[id='designtype']").val(0);
                                    $("input[id='designtype']").change(function() {
                                        if ($(this).is(':checked')) {
                                            $("input[id='designtype']").val(1);
                                            $("div[id='imgdesigncreate']").attr("hidden", false);
                                            $("div[id='imgdesigncreate']").attr("required", false);
                                        } else {
                                            $(this).val();
                                            $("div[id='imgdesigncreate']").attr("hidden", true);
                                            $("div[id='imgdesigncreate']").attr("required", true);
                                        }
                                    });
                                });
                            </script>
                            <!-- Select With Design Or Without Design Section End -->

                            <!-- Upload Design Section -->
                            <div class="uk-margin" id="imgdesigncreate" hidden>
                                <label class="uk-form-label" for="photocreate">Unggah file DED / Layout</label>
                                <div class="uk-placeholder" id="placedesign" hidden>
                                    <div uk-grid>
                                        <div class="uk-text-left uk-width-3-4">
                                            <div id="updesign">
                                            </div>
                                        </div>
                                        <div class="uk-text-right uk-width-1-4">
                                            <div id="closeddesign">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="image-container" class="uk-form-controls">
                                    <input id="designcreated" name="design" hidden />
                                    <div id="js-upload-createdesign" class="js-upload-createdesign uk-placeholder uk-text-center">
                                        <span uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle">Tarik dan lepas file disini atau</span>
                                        <div uk-form-custom>
                                            <input type="file">
                                            <span class="uk-link uk-preserve-color">pilih satu</span>
                                        </div>
                                    </div>
                                    <progress id="js-progressbar-createdesign" class="uk-progress" value="0" max="100" hidden></progress>
                                </div>
                            </div>

                            <script type="text/javascript">
                                var bar = document.getElementById('js-progressbar-createdesign');

                                UIkit.upload('.js-upload-createdesign', {
                                    url: 'upload/layout',
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
                                        console.log(filename);

                                        if (document.getElementById('display-container-create')) {
                                            document.getElementById('display-container-create').remove();
                                        };

                                        document.getElementById('designcreated').value = filename;

                                        document.getElementById('placedesign').removeAttribute('hidden');

                                        var uprev = document.getElementById('updesign');
                                        var closed = document.getElementById('closeddesign');

                                        var divuprev = document.createElement('h6');
                                        divuprev.setAttribute('class', 'uk-margin-remove');
                                        divuprev.setAttribute('id', 'design');
                                        divuprev.setAttribute('value', filename);


                                        var linkrev = document.createElement('a');
                                        linkrev.setAttribute('href', 'img/design/' + filename);
                                        linkrev.setAttribute('uk-icon', 'file-text');

                                        var link = document.createElement('a');
                                        link.setAttribute('href', 'img/design/' + filename);
                                        link.setAttribute('target', '_blank');

                                        var linktext = document.createTextNode(filename);

                                        var divclosed = document.createElement('a');
                                        divclosed.setAttribute('uk-icon', 'icon: close');
                                        divclosed.setAttribute('onClick', 'removedesign()');
                                        divclosed.setAttribute('id', 'closedes');

                                        uprev.appendChild(divuprev);
                                        divuprev.appendChild(linkrev);
                                        divuprev.appendChild(link);
                                        link.appendChild(linktext);
                                        closed.appendChild(divclosed);

                                        document.getElementById('js-upload-createdesign').setAttribute('hidden', '');
                                    },

                                    loadStart: function(e) {
                                        console.log('loadStart', arguments);

                                        document.getElementById('js-progressbar-createdesign').removeAttribute('hidden');

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;

                                    },

                                    progress: function(e) {
                                        console.log('progress', arguments);

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;
                                    },

                                    loadEnd: function(e) {
                                        console.log('loadEnd', arguments);

                                        document.getElementById('js-progressbar-createdesign').max = e.total;
                                        document.getElementById('js-progressbar-createdesign').value = e.loaded;
                                    },

                                    completeAll: function() {
                                        console.log('completeAll', arguments);

                                        setTimeout(function() {
                                            document.getElementById('js-progressbar-createdesign').setAttribute('hidden', 'hidden');
                                            alert('Proses unggah data desain selesai');
                                        }, 1000);
                                    }

                                });

                                function removedesign() {
                                    $.ajax({
                                        type: 'post',
                                        url: 'upload/removelayout',
                                        data: {
                                            'design': document.getElementById('designcreated').value,
                                        },
                                        dataType: 'json',

                                        error: function() {
                                            console.log(document.getElementById('design').value);
                                            console.log('error', arguments);
                                        },

                                        success: function() {
                                            console.log('success', arguments);

                                            var pesan = arguments[0][1];

                                            document.getElementById('design').remove();
                                            document.getElementById('closedes').remove();
                                            document.getElementById('placedesign').setAttribute('hidden', '');
                                            document.getElementById('designcreated').value = '';

                                            document.getElementById('js-upload-createdesign').removeAttribute('hidden', '');
                                            alert(pesan);
                                        }
                                    });
                                };
                            </script>
                            <!-- Upload Design End -->
                            <!-- Design Section End -->

                            <!-- Marketing (PIC) -->
                            <div class="uk-margin-small">
                                <label class="uk-form-label">PIC Marketing</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select uk-form-width-medium" name="marketing" required>
                                        <option value="" selected disabled>Pilih Marketing</option>
                                        <?php
                                        foreach ($marketings as $pic) {
                                            if ($pic->id === "0") {
                                                $selected = 'selected';
                                            } else {
                                                $selected = "";
                                            }
                                            echo '<option value="' . $pic->id . '" ' . $selected . '>' . $pic->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Markerting (PIC) -->

                            <div class="uk-modal-footer uk-text-right">
                                <?php if ($authorize->hasPermission('admin.project.create', $uid)) { ?>
                                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            // Auto Complete Company
            $(function() {
                var company = [
                    <?php if (!empty($company)) {
                        foreach ($company as $comp) {
                            if ($comp['parentid'] === "0") {
                                $rsklasification = $comp['rsname'] . " (pusat)";
                                echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                            } else {
                                $rsklasification = $comp['rsname'] . " (cabang)";
                                echo '{label:"' . $rsklasification . '",idx:' . (int)$comp['id'] . '},';
                            }
                        }
                    } ?>
                ];
                console.log(company);
                $("#company").autocomplete({
                    source: company,
                    select: function(e, i) {
                        $("input[id='compid']").val(i.item.idx); // save selected id to hidden input
                    },
                    minLength: 2
                });
            });
        </script>
    <?php } ?>
    <!-- Modal Add Proyek End -->
<?= $this->endSection() ?>