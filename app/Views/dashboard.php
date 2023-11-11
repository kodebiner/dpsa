<?= $this->extend('layout') ?>
<?= $this->section('main') ?>

<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<?= $this->endSection() ?>


<?php foreach ($projects as $project) { ?>
    <div class="uk-child-width-1-1@m uk-grid-match uk-margin" uk-grid>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body">

                <div class="uk-text-center" uk-grid>
                    <div class="uk-width-1-2 uk-text-left">
                        <h3 class="tm-h1"><?= $project['name'] ?></h3>
                    </div>
                </div>
                <hr>

                <div class="uk-panel">
                    <p class="tm-h3">Brief</p>
                    <p><?= $project['brief'] ?></p>
                    <hr class="uk-divider-icon">
                    <div class="uk-grid-divider uk-child-width-1-3@s" uk-grid>
                        <div>
                            <div class="uk-inline">
                                <h3 class="tm-h4"><span uk-icon="icon: user; ratio: 1"></span> Client</h3>
                                <p>
                                    <?php
                                    $position = array("client pusat", "client cabang");
                                    if ((!in_array($role, $position))) {
                                        foreach ($clients as $user) {
                                            if ($user['id'] == $project['clientid']) {
                                                $clientname = $user['username'];
                                                foreach ($parent as $idParent) {
                                                    if ($user['id'] === $idParent) {
                                                        $name[] = [
                                                            'id'    => $user['id'],
                                                            'name'  => $user['username'],
                                                        ];
                                                    }
                                                }
                                                if ($user['parent'] != "") {
                                                    foreach ($name as $parentname) {
                                                        if ($user['parent'] === $parentname['id']) {
                                                            $client = $clientname . " cabang " . $parentname['name'];
                                                        }
                                                    }
                                                } else {
                                                    $client = $clientname . " pusat ";
                                                }
                                            }
                                        }
                                        if (!empty($client)) {
                                            echo $client;
                                        }
                                    } elseif ($role === "client pusat") {
                                        foreach ($clients as $client) {
                                            $parentname = "";
                                            if ($client['id'] === $project['clientid']) {
                                                $parentid = $client['parent'];
                                                $clientname = $client['username'];
                                                foreach ($clients as $parent) {
                                                    if ($parent['id'] === $parentid) {
                                                        $parentname = $parent['username'];
                                                    }
                                                }
                                                if(empty($parentname)){
                                                    echo $clientname . " pusat " ;
                                                }else{
                                                    echo $clientname . " cabang " . $parentname;
                                                }
                                            }
                                        }
                                    } elseif ($role === "client cabang") {
                                        foreach ($clients as $client) {
                                            if ($client['id'] === $project['clientid']) {
                                                $parentid = $client['parent'];
                                                $clientname = $client['username'];
                                                foreach ($clients as $parent) {
                                                    if ($parent['id'] === $parentid) {
                                                        $parentname = $parent['username'];
                                                    }
                                                }
                                                echo $clientname . " cabang " . $parentname;
                                            }
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="">
                                <h3 class="tm-h4"><span uk-icon="icon: list; ratio: 1"></span> Status</h3>
                                <p>
                                    <?php if ($project['status'] === "1") {
                                        echo "Proses Desain";
                                    } elseif ($project['status'] === "2") {
                                        echo "Menunggu Approval Desain";
                                    } elseif ($project['status'] === "3") {
                                        echo "Pengajuan RAB";
                                    } elseif ($project['status'] === "4") {
                                        echo "Dalam Proses Produksi";
                                    } elseif ($project['status'] === "5") {
                                        echo "Setting";
                                    } ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="">
                                <h3 class="tm-h4"><span uk-icon="icon: future; ratio: 1"></span> Progress Produksi</h3>
                                <p>
                                    <?php
                                    $persentase = "";
                                    if ($project['status'] === "1") {
                                        $persentase = "5";
                                        echo "5 %";
                                    } elseif ($project['status'] === "2") {
                                        $persentase = "10";
                                        echo "10 %";
                                    } elseif ($project['status'] === "3") {
                                        $persentase = "20";
                                        echo "20 %";
                                    } elseif ($project['status'] === "4") {
                                        if ($project['production'] === "0") {
                                            $persentase = "30";
                                        } elseif ($project['production'] != "0") {
                                            $qty = round($project['production'] / 100 * 65, 2);
                                            $persentase = 30 + $qty;
                                        }
                                        echo "$persentase %";
                                    } elseif ($project['status'] === "5") {
                                        echo "95 %";
                                        $persentase = "95";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <progress id="progress" class="uk-progress" value="<?= $persentase ?>" max="100"></progress>
                </div>

            </div>
        </div>

    </div>
<?php } ?>
<!-- bar code -->

<?= $this->endSection() ?>