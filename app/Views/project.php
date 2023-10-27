
<?= $this->extend('layout') ?>
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

        <form class="uk-margin-left" action="bar/create" method="post">
            
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: file-text"></span>
                    <input class="uk-input uk-form-width-large" name="name" placeholder="Name" type="text" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <textarea class="uk-textarea uk-form-width-large" rows="5" placeholder="Brief" name="Brief" aria-label="Brief"></textarea>
                </div>
            </div>

            <div class="uk-margin">
                <div uk-form-custom="target: > * > span:first-child">
                    <select aria-label="Custom controls">
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

<!-- update progress modal -->
<div id="modalupdate" uk-modal>
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
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end update progress modal -->

<div class="uk-child-width-1-1@m" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-card-small uk-card-body">

            <div class="uk-text-center" uk-grid>

                <div class="uk-width-1-2 uk-text-left">
                    <h3 class="uk-card-title">Progress Bar</h3>
                </div>

                <div class="uk-width-1-2 uk-text-right">
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdate" uk-icon="pencil" uk-toggle></a>
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

<div class="uk-child-width-1-1@m uk-grid-match" uk-grid>

    <div>
        <div class="uk-card uk-card-default uk-card-hover uk-card-body">

            <div class="uk-text-center" uk-grid>

                <div class="uk-width-1-2 uk-text-left">
                    <h3 class="uk-card-title">Project</h3>
                </div>

                <div class="uk-width-1-2 uk-text-right">
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdate" uk-icon="check" uk-toggle></a>
                    <a class="uk-icon-button  uk-margin-small-right" href="#modalupdate" uk-icon="pencil" uk-toggle></a>
                </div>
            
            </div>
            <hr>
            <p class="uk-margin-top">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non accusantium autem laborum totam mollitia! Vitae vero tempore illo iusto voluptas eum repellendus omnis id repudiandae. Tempora aperiam sed odit quos.</p>
            <div class="uk-text-center" uk-grid>
                <div class="uk-width-1-1 uk-text-left">
                    <span class="uk-card-title">Progress Project</span>
                    <a class="uk-icon-button-default" href="#modalupdate" uk-icon="file-edit" uk-toggle></a>
                </div>
            </div>
            <progress id="progress" class="uk-progress" value="<?=$qty?>" max="100"></progress>
        </div>
    </div>

</div>

<?= $this->endSection() ?>