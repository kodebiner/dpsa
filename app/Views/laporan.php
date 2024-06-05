<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<!-- <script src="js/ajax.googleapis.com_ajax_libs_jquery_3.6.4_jquery.min.js"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" /> --->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>

<?= $this->section('main') ?>


<!-- Page Heading -->
<?php if ($authorize->hasPermission('admin.user.read', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>

    <!-- Form Input Mobile View-->
    <?php if ($ismobile === false) { ?>
        <div class="tm-card-header uk-light uk-margin-remove-left">
            <div uk-grid class="uk-flex-middle">
                <div class="uk-width-1-2@m">
                    <h3 class="tm-h3">Laporan</h3>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <h3 class="tm-h3 uk-text-center">Laporan</h3>
        <div class="uk-child-width-auto uk-flex-center" uk-grid>
            <div>
                <button type="button" class="uk-button uk-button-secondary uk-preserve-color" uk-toggle="target: #filter">Filter <span uk-icon="chevron-down"></span></button>
            </div>
        </div>
        <div id="filter" class="uk-margin" hidden>
            <form id="searchform" action="laporan" method="GET">
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <input class="uk-input uk-form-width-small" id="search" name="search" placeholder="Cari" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                </div>
                <div class="uk-margin-small uk-flex uk-flex-center">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                    <input class="uk-input uk-width-medium" type="text" id="daterange" name="daterange" value="<?= date('m/d/Y', $startdate) ?> - <?= date('m/d/Y', $enddate) ?>" />
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
    <?php } ?>
    <!-- End Form Input Mobile View -->

    <?php
    $datachart = [];
    foreach ($projects as $project) {
        $datachart[] = [
            'tanggal'   => $project['created_at'],
            'nilaispk'  => $projectdata[$project['id']]['rabvalueppn'],
            // 'nilaispk'  => $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'],
        ];
    }
    ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([

                ['Tanggal', 'Nilai SPK', ],
                <?php
                if (!empty($datachart)) {
                    foreach ($datachart as $chart) {
                        $date = date_create($chart['tanggal']);
                        $tanggal = date_format($date, 'Y-m-d');
                        $nilaispk = $chart['nilaispk'];
                        echo "['$tanggal', $nilaispk,],";
                    }
                } else {
                    echo "['belum ada proyek', 0,],";
                } ?>
            ]);

            var options = {
                title: 'Laporan SPK',
                hAxis: {
                    title: '',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    minValue: 0
                }
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

    <div id="chart_div" style="width: 100%; height: 500px;"></div>
    <div class="uk-child-width-1-3@s uk-text-left" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Jumlah Proyek : <?= $total ?></div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">
                <?php
                $selesai = 0;
                foreach ($projects as $project) {
                    if ($projectdata[$project['id']]['dateline'] < $projectdata[$project['id']]['now']) {
                        $selesai += 1;
                    }
                }
                ?>
                Total Proyek Selesai : <?= $selesai ?>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Proyek Dalam Proses : <?= $total - $selesai ?></div>
        </div>
    </div>
    <div class="uk-child-width-1-3@s uk-text-left" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total SPK :
                <?php
                $spkvalue = [];
                foreach ($projects as $project) {
                    // $spkvalue[] = $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'];
                    $spkvalue[] = $projectdata[$project['id']]['rabvalueppn'];
                }
                echo "Rp." . number_format(array_sum($spkvalue), 0, ',', '.');
                ?>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Terbayar :
                <?php
                $terbayar = [];
                foreach ($projects as $project) {
                    $terbayar[] = $projectdata[$project['id']]['pembayaran'];
                }
                echo "Rp." . number_format(array_sum($terbayar), 0, ',', '.');
                ?>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">Total Belum Terbayar :
                <?php
                $pembayarankurang = array_sum($terbayar);
                $spkval = array_sum($spkvalue);
                echo "Rp." . number_format($spkval - $pembayarankurang, 0, ',', '.');
                ?>
            </div>
        </div>
    </div>

    <!-- Form Input Desktop View -->
    <?php if ($ismobile === false) { ?>
        <div class="uk-margin-large">
            <form id="searchform" action="laporan" method="get">
                <div class="uk-child-width-1-2@s uk-text-left" uk-grid>
                    <div class="uk-width-1-3">
                        <div class="">
                            <div class="uk-inline">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                <input class="uk-input uk-width-medium" type="text" id="daterange" name="daterange" value="<?= date('m/d/Y', $startdate) ?> - <?= date('m/d/Y', $enddate) ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-auto">
                        <div>
                            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                                <div>
                                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                        <div>Cari:</div>
                                        <div><input class="uk-input uk-form-width-medium" id="search" name="search" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> /></div>
                                    </div>
                                </div>
                                <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                    <div>
                                        <a class="uk-button uk-button-primary uk-button-default uk-width-1-1" href="laporan/excel?daterange=<?=date('Y-m-d', $startdate)?>+-+<?=date('Y-m-d', $enddate)?>" target="_blank"><span uk-icon="download"></span>Laporan</a>
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
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
    <!-- End Form Input Desktop View -->

    <!-- End Of Page Heading -->
    <?= view('Views/Auth/_message_block') ?>

    <script>
        document.getElementById('search').addEventListener("change", submitform);
        document.getElementById('perpage').addEventListener("change", submitform);

        function submitform() {
            const x =  document.getElementById('daterange').value.replace(/[^A-Za-z 0-9~%.:_\\&-]/gi, '-');
            
            // Start date
            let sMonth  = x.slice(0,2);
            let sDate   = x.slice(3,5);
            let sYear   = x.slice(6,10);

            // End date
            let eMonth  = x.slice(13,15);
            let eDate   = x.slice(16,18);
            let eYear   = x.slice(19,23);

            let startdate = sYear + "-" + sMonth + "-" + sDate;
            let enddate   = eYear + "-" + eMonth + "-" + eDate;
            
            let result  = startdate + ' - ' + enddate;
            document.getElementById('daterange').value = result;
            
            // console.log(result);
            // const today = new Date();
            // const yyyy = today.getFullYear();
            // let mm = today.getMonth() + 1; // Months start at 0!
            // let dd = today.getDate();

            // if (dd < 10) dd = '0' + dd;
            // if (mm < 10) mm = '0' + mm;

            // const formattedToday = yyyy + '-' + mm + '-' + dd;
            // console.log(formattedToday);

            console.log(document.getElementById('daterange').value);
            document.getElementById('daterange').value = result;
            document.getElementById('searchform').submit();
        };

        $(document).ready(function() {
            $(function() {
                $('input[name="daterange"]').daterangepicker({
                    maxDate: new Date(),
                    opens: 'right'
                }, function(start, end, label) {
                    document.getElementById('daterange').value = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                    console.log(document.getElementById('daterange').value);
                    // document.getElementById('short').submit();
                    document.getElementById('searchform').submit();
                });
            });
        });
    </script>
    <!-- end script form -->


    <!-- Table Of Content -->
    <div class="uk-overflow-auto uk-margin">
        <table class="uk-table uk-table-justify uk-table-middle uk-table-divider">
            <thead>
                <tr>
                    <th class="uk-width-large">Nama Proyek</th>
                    <th class="uk-width-medium">Klien</th>
                    <th class="uk-width-medium">Marketing</th>
                    <th class="uk-width-medium">Nilai SPK</th>
                    <th class="uk-width-medium">Terbayar</th>
                    <th class="uk-width-medium">Belum Terbayar</th>
                    <th class="uk-text-center uk-width-medium">Status Proyek</th>
                    <th class="uk-text-center uk-width-medium">Tanggal Proyek</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project) { ?>
                    <tr>
                        <td class=""><?= $project['name'] ?></td>
                        <td class=""><?= $projectdata[$project['id']]['klien']['rsname'] ?></td>
                        <td class=""><?= $projectdata[$project['id']]['marketing']->username ?></td>
                        <!-- <td class=""></?= "Rp." . number_format($projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'], 0, ',', '.')  ?></td> -->
                        <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['rabvalueppn'], 0, ',', '.')  ?></td>
                        <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
                        <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['rabvalueppn'] - $projectdata[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
                        <td class="uk-text-center">
                            <?php if ($projectdata[$project['id']]['dateline'] < $projectdata[$project['id']]['now']) {
                                echo 'Selesai';
                            } else {
                                echo 'Dalam Proses';
                            } ?>
                        </td>
                        <td class="uk-text-center"><?= $project['created_at'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?= $pager ?>
    </div>
    <!-- End Of Table Content -->
<?php } ?>


<?= $this->endSection() ?>