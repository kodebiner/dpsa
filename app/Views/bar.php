<?= $this->extend('layout') ?>
<?= $this->section('main') ?>
<!-- add progress modal -->
<div id="modal-sections" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Progress Bar</h2>
        </div>

        <form class="uk-margin-left" action="bar/create" method="post">
            
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: pencil"></span>
                    <input class="uk-input" name="qty" placeholder="Quantity" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<!-- end add progress modal -->

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

<!-- ongoing -->
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
    <!-- ongoing -->
</div>

<?= $this->endSection() ?>