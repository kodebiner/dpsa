
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
                    <input class="uk-input" type="text" placeholder="Search Projects ..." id="prosearch" name="pro" aria-label="Not clickable icon" style="border-radius: 20px;">
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

                        $("#prosearch").autocomplete({
                            source: proList,
                            select: function(e, i) {
                                <?php foreach ($projects as $pro) { ?>
                                if (i.item.idx == <?=$pro['id']?>) {
                                    if(!$("#namepro").length){
                                        $("#pro").append("<input type='hidden' name='pro' value='<?=$pro['id']?>'></input>");
                                        $("#proname").append("<div id='namepro' style='color:white' class='tm-h6'><?=$pro['name']?></div>");
                                        $("#prodate").append("<div id='datepro' style='color:white' class='tm-h6'><?=$pro['created_at']?></div>");
                                        $("#prodel").append("<a id='btnremovepro' style='color:white' class='uk-icon-link uk-button-xsmall uk-text-left uk-margin-remove-left' uk-icon='close'></a");
                                        $("#pro").attr('style', 'border:1px #39f; border-style: solid; background: #39f; padding: 2px; border-radius: 50px 20px;');
                                    }else{
                                        alert("Already Added");
                                    }
                                    $("#btnremovepro").click(function(){
                                        $("#pro").empty();
                                        $("#pro").removeAttr('style');
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

                <div id="pro" class="uk-text-center uk-margin-left uk-ligth uk-width-large" uk-grid>
                    <div id="proname" class="uk-width-1-3">
                        
                    </div>
                    <div id="prodate"class="uk-width-expand">
                       
                    </div>
                    <div id="prodel" class="uk-width-1-4">
                       
                    </div>
                </div>
            
            <hr class="uk-width-large">

            <div class="uk-margin uk-text-left uk-form-width-large">
                <div class="uk-search uk-search-default uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" placeholder="Search MDL ..." id="mdl" name="mdl" aria-label="Not clickable icon" style="border-radius: 20px;">
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
                                    if(!$("#namemdl<?=$mdl['id']?>").length){
                                        $("#mdlcontain<?=$mdl['id']?>").append("<div id='containermdl<?=$mdl['id']?>' style='border:1px #39f; border-style: solid; background: #39f; padding: 2px; border-radius: 50px 20px;' class='uk-margin uk-width-large'></div>");
                                        $("#mdlcontain<?=$mdl['id']?>").append("<div class='uk-flex-middle uk-margin uk-width-large uk-grid' id='qtycontainer<?=$mdl['id']?>' uk-grid></div>");
                                        $("#containermdl<?=$mdl['id']?>").append("<div id='mdl<?=$mdl['id']?>' class='uk-flex-middle uk-width-large uk-light uk-grid' uk-grid></div>");
                                        $("#mdl<?=$mdl['id']?>").append("<input type='hidden' name='mdl[<?=$mdl['id']?>]' value='<?=$mdl['id']?>'></input>");
                                        $("#mdl<?=$mdl['id']?>").append("<div id='mdlname<?=$mdl['id']?>' class='uk-flex-middle uk-width-1-3'></div>");
                                        $("#mdl<?=$mdl['id']?>").append("<div id='mdlprice<?=$mdl['id']?>'class='uk-flex-middle uk-text-center uk-width-1-3'></div>");
                                        $("#mdl<?=$mdl['id']?>").append("<div id='mdldel<?=$mdl['id']?>' class='uk-flex-middle uk-text-right uk-width-1-3'></div>");
                                        $("#mdlname<?=$mdl['id']?>").append("<div id='namemdl<?=$mdl['id']?>' class='tm-h6 uk-text-center'><?=$mdl['name']?></div>");
                                        $("#mdlprice<?=$mdl['id']?>").append("<div id='datemdl' class='tm-h6'><?=$mdl['price']?></div>");
                                        $("#mdldel<?=$mdl['id']?>").append("<a id='btnremovemdl<?=$mdl['id']?>' class='uk-icon-link uk-button-xsmall' style='color:white' uk-icon='close'></a");
                                        $("#qtycontainer<?=$mdl['id']?>").append("<div id='qtywidth<?=$mdl['id']?>' class='uk-width-1-3 uk-text-xsmall'></div>");
                                        $("#qtycontainer<?=$mdl['id']?>").append("<div id='delivwidth<?=$mdl['id']?>' class='uk-width-1-3 uk-text-xsmall'></div>");
                                        $("#qtycontainer<?=$mdl['id']?>").append("<div id='completedwidth<?=$mdl['id']?>' class='uk-width-1-3 uk-text-xsmall'></div>");
                                        $("#qtywidth<?=$mdl['id']?>").append("<div class='uk-inline' id='qty<?=$mdl['id']?>'></div>");
                                        $("#qty<?=$mdl['id']?>").append("<span class='uk-form-icon' uk-icon='icon: database; ratio:0.5'></span>");
                                        $("#qty<?=$mdl['id']?>").append("<input class='uk-input uk-form-width-large' name='qty[<?=$mdl['id']?>]' placeholder='Quantity' type='number' aria-label='Not clickable icon'>");
                                        $("#delivwidth<?=$mdl['id']?>").append("<div id='deliv<?=$mdl['id']?>' class='uk-inline'></div>");
                                        $("#deliv<?=$mdl['id']?>").append("<span class='uk-form-icon' uk-icon='icon: refresh; ratio:0.5'></span>");
                                        $("#deliv<?=$mdl['id']?>").append("<input class='uk-input uk-form-width-large' name='qtydeliv[<?=$mdl['id']?>]' placeholder='Delivered' type='number' aria-label='Not clickable icon'>");
                                        $("#completedwidth<?=$mdl['id']?>").append("<div class='uk-inline' id='completed<?=$mdl['id']?>'></div>")
                                        $("#completed<?=$mdl['id']?>").append("<span class='uk-form-icon' uk-icon='icon: check; ratio:0.5'></span>");
                                        $("#completed<?=$mdl['id']?>").append("<input class='uk-input uk-form-width-large' name='completed[<?=$mdl['id']?>]' placeholder='Completed' type='number' aria-label='Not clickable icon'>");
                                    }else{
                                        alert("Already Added");
                                    }
                                    $("#btnremovemdl<?=$mdl['id']?>").click(function(){
                                        $("#mdlcontain<?=$mdl['id']?>").empty();
                                        $("#mdl<?=$mdl['id']?>").empty();
                                        $("#mdlname<?=$mdl['id']?>").empty();
                                        $("#mdlprice<?=$mdl['id']?>").empty();
                                        $("#mdldel<?=$mdl['id']?>").empty();
                                        $("#qty<?=$mdl['id']?>").empty();
                                        $("#deliv<?=$mdl['id']?>").empty();
                                        $("#completed<?=$mdl['id']?>").empty();
                                    });
                                }
                                <?php } ?>
                            },
                            minLength: 1
                        });
                    });
                </script>
            </div>
            
            <?php foreach ($mdls as $mdl){?>
            <div id="mdlcontain<?=$mdl['id']?>">
            </div>
            <?php } ?>
             

            <hr class="uk-width-large">

            <!-- <div class="uk-margin"> -->
                <!-- <div class="uk-inline">
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
            </div> -->

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>
           
        </form>

    </div>
</div>
<!-- end add rab modal -->

<?php foreach ($rabs as $rab){?>
<!-- update progress modal -->
<div id="modalupdate<?=$rab['id']?>" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-margin">
            <h2 class="uk-modal-title"> Update Progress</h2>
        </div>

        <form class="uk-margin-left" action="bar/update/1" method="post">
            
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: pencil"></span>
                    <input class="uk-input" id="rabqty" value="<?=$rab['qty']?>" name="qty" placeholder="<?=$rab['qty']?>" type="number" aria-label="Not clickable icon">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <a class="uk-button uk-button-danger" href="rab/delete/<?=$rab['id']?>" onclick="return confirm('<?=lang('Global.deleteConfirm')?>')" method="post">Delete</a>
                <button class="uk-button uk-button-primary" type="submit">Save</button>
            </div>

        </form>

    </div>
</div>
<?php } ?>
<!-- end update progress modal -->


<?= $this->endSection() ?>