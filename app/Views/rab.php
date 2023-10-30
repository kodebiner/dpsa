
<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Page Heading -->
<div class="tm-card-header uk-margin-remove-left">
    <div uk-grid class="uk-flex-middle">
        <div class="uk-width-1-2@m">
            <h3 class="tm-h3"><?=lang('Global.rabList')?></h3>
        </div>

        <!-- Button Trigger Modal Add -->
        <div class="uk-width-1-2@m uk-text-right@m">
            <button class="uk-button uk-button-primary uk-border-rounded" href="#modaladd" aria-label="Project" uk-toggle>Add Rab</button>
        </div>
        <!-- End Of Button Trigger Modal Add -->
    </div>
</div>
<!-- End Of Page Heading -->

<!-- Table Of Content -->
<div class="uk-overflow-auto uk-margin">
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>no</th>
                <th>Project</th>
                <th>Quantity</th>
                <th>Qty Delivered</th>
                <th>Qty Complete</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($rabs as $rab) { ?>
                <tr>
                    <td><?= $i++?></td>
                    <td>
                        <?php foreach ($projects as $pro){
                            if($pro['id'] === $rab['projectid']){
                                echo $pro['name'];
                            }
                        }?>
                    </td>
                    <td><?= $rab['qty'] ?></td>
                    <td><?= $rab['qty_deliver'] ?></td>
                    <td><?= $rab['qty_complete'] ?></td>
                    <td><a class="uk-icon-button" href="#modalupdate<?=$rab['id']?>" uk-icon="pencil" uk-toggle></a></td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<!-- End Table Of Content -->

<!-- add rab modal -->
<div id="modaladd" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title">Add Rab</h2>
        </div>

        <form class="uk-margin-left" action="rab/create" method="post">

            <div class="uk-margin uk-text-left uk-form-width-large">
                <div class="uk-search uk-search-default uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" placeholder="Search Projects ..." id="pro" name="pro" aria-label="Not clickable icon" style="border-radius: 5px;">
                </div>
                <script type="text/javascript">
                    $(function() {
                        var proList = [
                            {label: 'Pro List', idx: '0'},
                            <?php
                                foreach ($projects as $pro) {
                                    echo '{label:"'.$pro['name'].'",idx:'.$pro['id'].'},';
                                }
                            ?>
                        ];

                        $("#pro").autocomplete({
                            source: proList,
                            select: function(e, i) {
                                <?php foreach ($projects as $pro) { ?>
                                if (i.item.idx == <?=$pro['id']?>) {
                                    if(!$("#namepro").length){
                                        $("#pro").append("<input type='hidden' name='pro' value='<?=$pro['id']?>'></input>");
                                        $("#proname").append("<div id='namepro' class='tm-h6'><?=$pro['name']?></div>");
                                        $("#prodate").append("<div id='datepro' class='tm-h6'><?=$pro['created_at']?></div>");
                                        $("#prodel").append("<a id='btnremovepro' class='uk-icon-link uk-button-xsmall' uk-icon='close'></a");
                                    }else{
                                        alert("Already Added");
                                    }
                                    $("#btnremovepro").click(function(){
                                        $("#pro").empty();
                                        $("#proname").empty();
                                        $("#prodate").empty();
                                        $("#prodel").empty();
                                    });
                                }
                                <?php } ?>
                            },
                            minLength: 1
                        });
                    });
                </script>
            </div>

            <div>
                <div id="pro" class="uk-width-large uk-flex-middle uk-grid" uk-grid>
                    <div id="proname" class="uk-flex-middle uk-width-1-3">
                        
                    </div>
                    <div id="prodate"class="uk-flex-middle uk-width-1-3">
                       
                    </div>
                    <div id="prodel" class="uk-flex-middle uk-width-1-3">
                       
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="uk-margin uk-text-left uk-form-width-large">
                <div class="uk-search uk-search-default uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" placeholder="Search Projects ..." id="mdl" name="mdl" aria-label="Not clickable icon" style="border-radius: 5px;">
                </div>
                <script type="text/javascript">
                    $(function() {
                        var mdlList = [
                            {label: 'Mdl List', idx: '0'},
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
                                    if(!$("#namemdl").length){
                                        $("#mdl").append("<input type='hidden' name='mdl' value='<?=$mdl['id']?>'></input>");
                                        $("#mdlname").append("<div id='namemdl' class='tm-h6'><?=$mdl['name']?></div>");
                                        $("#mdlprice").append("<div id='datemdl' class='tm-h6'><?=$mdl['price']?></div>");
                                        $("#mdldel").append("<a id='btnremovemdl' class='uk-icon-link uk-button-xsmall' uk-icon='close'></a");
                                    }else{
                                        alert("Already Added");
                                    }
                                    $("#btnremovemdl").click(function(){
                                        $("#mdl").empty();
                                        $("#mdlname").empty();
                                        $("#mdlprice").empty();
                                        $("#mdldel").empty();
                                    });
                                }
                                <?php } ?>
                            },
                            minLength: 1
                        });
                    });
                </script>
            </div>

            <div>
                <div id="mdl" class="uk-width-large uk-flex-middle uk-grid" uk-grid>
                    <div id="mdlname" class="uk-flex-middle uk-width-1-3">
                        
                    </div>
                    <div id="mdlprice"class="uk-flex-middle uk-width-1-3">
                       
                    </div>
                    <div id="mdldel" class="uk-flex-middle uk-width-1-3">
                       
                    </div>
                </div>
            </div>

            <hr>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: database"></span>
                    <input class="uk-input uk-form-width-large" name="qty" placeholder="Quantity" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon:  refresh"></span>
                    <input class="uk-input uk-form-width-large" name="qtydeliv" placeholder="Quantity Delivered" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon:  check"></span>
                    <input class="uk-input uk-form-width-large" name="qtycomp" placeholder="Quantity Completed" type="number" aria-label="Not clickable icon">
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
<!-- end add rab modal -->


<?= $this->endSection() ?>