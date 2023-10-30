
<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="uk-child-width-expand@m uk-text-right uk-margin-bottom" uk-grid>
    <div>
        <div class="uk-margin">
            <button class="uk-button uk-button-primary uk-border-rounded uk-margin-small-right" href="#modaladd" aria-label="Project" uk-toggle>+ Proyek</button>
        </div>
    </div>
</div>

<!-- add project modal -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Add Project</h2>
        </div>

        <form class="uk-margin-left" action="project/create" method="post">

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="Name" type="text" aria-label="Not clickable icon">
                </div>
            </div>
            
            <div class="uk-margin uk-text-left uk-form-width-large">
                <div class="uk-search uk-search-default uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" placeholder="Search MDL ..." id="mdl" name="mdl" aria-label="Not clickable icon" style="border-radius: 5px;">
                </div>
                <script type="text/javascript">
                    $(function() {
                        var mdlList = [
                            {label: 'MDL List', idx: '0'},
                            <?php
                                foreach ($mdls as $mdl) {
                                    echo '{label:"'.$mdl['name'].'",idx:'.$mdl['id'].'},';
                                }
                            ?>
                        ];

                        $("#mdl").autocomplete({
                            source: mdlList,
                            select: function(e, i) {
                                <?php foreach ($mdls as $mdl) { ?>
                                if (i.item.idx == <?=$mdl['id']?>) {
                                        // $("ol").append("<li value='<?//=$mdl['id']?>'><?//=$mdl['name']?> : <?//=$mdl['price']?></li>");
                                        // $("ol").append("<input type='hidden' name='mdl[<?//=$mdl['id']?>]' value='<?//=$mdl['id']?>'></input>");
                                        $("#mdl<?=$mdl['name']?>").append(" <div class='tm-h5'><?=$mdl['name']?></div>");
                                        $("#mdl<?=$mdl['price']?>").append(" <div class='tm-h5'><?=$mdl['price']?></div>");
                                        $("#qty<?=$mdl['id']?>").append("<input class='uk-input uk-form-width-small uk-text-center' name='qty' min='1' type='number'></input>");
                                    }
                                <?php } ?>
                            },
                            minLength: 1
                        });
                    });
                </script>
            </div>

            <!-- <ol>
            </ol> -->

            <div>
                <div id="mdl<?=$mdl['id']?>" class="uk-width-large uk-flex-middle uk-grid" uk-grid>
                    <div id="mdl<?=$mdl['name']?>" class="uk-flex-middle uk-width-1-4">
                        <!-- <div class="tm-h5">Kursi</div> -->
                    </div>
                    <div id="mdl<?=$mdl['price']?>"class="uk-flex-middle uk-width-1-4">
                        <!-- <div class="tm-h5">500000</div> -->
                    </div>
                    <div id="qty<?=$mdl['id']?>" class="uk-flex-middle uk-width-1-3">
                        <!-- <input class="uk-input uk-form-width-small uk-text-center" type="number"></input> -->
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" aria-label="Brief"></textarea>
                </div>
            </div>

            <div class="uk-margin">
                <div uk-form-custom="target: > * > span:first-child">
                    <select name="client" aria-label="Custom controls">
                        <option value="">Client</option>
                        <?php foreach ($clients as $client) { ?>
                            <option value="<?=$client->id?>"> <?= $client->name?></option>
                        <?php } ?>
                    </select>
                    <button class="uk-button uk-button-default" type="button" tabindex="-1">
                        <span></span>
                        <span uk-icon="icon: chevron-down"></span>
                    </button>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>
           
        </form>

    </div>
</div>
<!-- end add project modal -->

<div class="uk-child-width-1-1@m" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-card-small uk-card-body">

            <div class="uk-text-center" uk-grid>

                <div class="uk-width-1-2 uk-text-left">
                    <h3 class="uk-card-title">Progress Bar</h3>
                </div>

                <div class="uk-width-1-2 uk-text-right">
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdatepro" uk-icon="pencil" uk-toggle></a>
                </div>
               
            </div>

            <progress id="progress" class="uk-progress" value="<?=$qty?>" max="100"></progress>
        </div>
        <script>
            
            UIkit.util.ready(function () {

                var bar = document.getElementById('js-progressbar');

                var animate = setInterval(function () {

                    bar.value += 10;

                    if (bar.value >= bar.max) {
                        clearInterval(animate);
                    }

                }, 1000);

            });
        </script>
    </div>
</div>

<?php foreach ($projects as $project) { ?>
<div class="uk-child-width-1-1@m uk-grid-match uk-margin" uk-grid>

    <div>
        <div class="uk-card uk-card-default uk-card-hover uk-card-body">

            <div class="uk-text-center" uk-grid>

                <div class="uk-width-1-2 uk-text-left">
                    <h3 class="tm-h1">Project <?=$project['name']?></h3>
                </div>

                <div class="uk-width-1-2 uk-text-right">
                    <a class="uk-icon-button  uk-margin-small-right" href="#updaterab" uk-icon="check" uk-toggle></a>
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdatepro<?=$project['id']?>" uk-icon="pencil" uk-toggle></a>
                </div>
            
            </div>
            <hr>
            <p class="uk-margin-top"><?=$project['brief']?></p>
            <div class="uk-text-center" uk-grid>
                <div class="uk-width-1-1 uk-text-left">
                    <span class="tm-h2">Progress Project</span>
                    <a class="uk-icon-button-default" href="#updaterab" uk-icon="file-edit" uk-toggle></a>
                </div>
            </div>
            <progress id="progress" class="uk-progress" value="<?=$qty?>" max="100"></progress>
        </div>
    </div>

</div>

<!-- update project modal -->
<div id="modalupdatepro<?=$project['id']?>" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Update Project</h2>
        </div>

        <form class="uk-margin-left" action="project/update/<?=$project['id']?>" method="post">
            
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" value="<?=$project['name']?>" placeholder="Name" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="brief" value="<?=$project['brief']?>" aria-label="Brief"></textarea>
                </div>
            </div>

            <h2 class="tm-h4">Update Design</h2>

            <div class="js-upload uk-placeholder uk-form-width-small uk-text-center">
                <span uk-icon="icon: cloud-upload"></span>
                <span class="uk-text-middle">Attach binaries by dropping them here or</span>
                <div uk-form-custom>
                    <input type="file"multiple>
                    <span class="uk-link">selecting one</span>
                </div>
            </div>

            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>

            <script>

                var bar = document.getElementById('js-progressbar');

                UIkit.upload('.js-upload', {

                    url: '',
                    multiple: true,

                    beforeSend: function () {
                        console.log('beforeSend', arguments);
                    },
                    beforeAll: function () {
                        console.log('beforeAll', arguments);
                    },
                    load: function () {
                        console.log('load', arguments);
                    },
                    error: function () {
                        console.log('error', arguments);
                    },
                    complete: function () {
                        console.log('complete', arguments);
                    },

                    loadStart: function (e) {
                        console.log('loadStart', arguments);

                        bar.removeAttribute('hidden');
                        bar.max = e.total;
                        bar.value = e.loaded;
                    },

                    progress: function (e) {
                        console.log('progress', arguments);

                        bar.max = e.total;
                        bar.value = e.loaded;
                    },

                    loadEnd: function (e) {
                        console.log('loadEnd', arguments);

                        bar.max = e.total;
                        bar.value = e.loaded;
                    },

                    completeAll: function () {
                        console.log('completeAll', arguments);

                        setTimeout(function () {
                            bar.setAttribute('hidden', 'hidden');
                        }, 1000);

                        alert('Upload Completed');
                    }

                });

            </script>

            <div class="uk-margin uk-text-left uk-form-width-large">
                <div class="uk-search uk-search-default uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" placeholder="Search MDL ..." id="mdl" name="mdl" aria-label="Not clickable icon" style="border-radius: 5px;">
                </div>
                <script type="text/javascript">
                    $(function() {
                        var mdlList = [
                            {label: 'Show All', idx: '0'},
                            <?php
                                foreach ($mdls as $mdl) {
                                    echo '{label:"'.$mdl['name'].'",idx:'.$mdl['id'].'},';
                                }
                            ?>
                        ];

                        $("#mdl").autocomplete({
                            source: mdlList,
                            select: function(e, i) {
                                <?php foreach ($mdls as $mdl) { ?>
                                if (i.item.idx == <?=$mdl['id']?>) {
                                        $("ol").append("<li value='<?=$mdl['id']?>'><?=$mdl['name']?> : <?=$mdl['price']?></li>");
                                        $("ol").append("<input type='hidden' name='mdl[<?=$mdl['id']?>]' value='<?=$mdl['id']?>'></input>");
                                    }
                                <?php } ?>
                            },
                            minLength: 1
                        });
                    });
                </script>
            </div>

            <ol>
            </ol>

            <div>
                <div class="uk-width-large uk-flex-middle uk-grid" uk-grid>
                    <div class="uk-flex-middle uk-width-1-4">
                        <div class="tm-h5">Kursi</div>
                    </div>
                    <div class="uk-flex-middle uk-width-1-4">
                        <div class="tm-h5">500000</div>
                    </div>
                    <div id="mdlname" class="uk-flex-middle uk-width-1-3">
                        <input class="uk-input uk-form-width-small uk-text-center" type="number"></input>
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <div uk-form-custom="target: > * > span:first-child">
                    <select name="client" aria-label="Custom controls">
                        <?php if($project['clientid'] === $client->id){
                            echo "<option value=".$project['clientid'].">".$clients['name']."</option>";
                        }?>
                        <?php foreach ($clients as $client) { ?>
                            <option value="<?=$client->id?>"> <?= $client->name?></option>
                        <?php } ?>
                    </select>
                    <button class="uk-button uk-button-default" type="button" tabindex="-1">
                        <span></span>
                        <span uk-icon="icon: chevron-down"></span>
                    </button>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-danger" type="button">Delete</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end update project modal -->
<?php } ?>

<!-- update progress modal -->
<div id="updaterab" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title"> Update Progress</h2>
        </div>

        <form class="uk-margin-left" action="bar/update/1" method="post">
            
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: pencil"></span>
                    <input class="uk-input" id="qty" name="qty" placeholder="Quantity" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-danger uk-modal-close" type="button">Delete</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end update progress modal -->

<?= $this->endSection() ?>