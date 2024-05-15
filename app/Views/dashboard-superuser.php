<?= $this->extend('layout') ?>

<?= $this->section('extraScript') ?>
<script src="js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="uk-container uk-container-large">
    <?php if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) { ?>

        <!-- Page Heading -->
        <?php if ($ismobile === true) { ?>
            <h1 class="tm-h1 uk-text-center uk-margin-remove">Daftar Proyek</h1>
            <div class="uk-margin uk-text-center">
                <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
            </div>
            <div id="filter" class="uk-margin" hidden>
                <form id="searchform" action="home" method="GET">
                    <div class="uk-margin-small uk-flex uk-flex-center">
                        <input class="uk-input uk-form-width-medium" id="search" name="search" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                    </div>
                    <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
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
            <h3 class="tm-h3 uk-margin-remove">Daftar Proyek</h3>
        <?php } ?>
        <hr class="uk-divider-icon uk-margin-remove-top">
        <!-- end of Page Heading -->

        <!-- Content -->
        <?php if ($ismobile === true) { ?>
            <?php if (!empty($clients)) { ?>
                <?php foreach ($clients as $client) { ?>
                    <a href="dashboard/<?= $client['id'] ?>">
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
                <div class="uk-width-1-1 uk-text-center uk-text-italic"><?= lang('Global.noData') ?></div>
            <?php } ?>
        <?php } else { ?>
            <form class="uk-margin" id="searchform" action="home" method="GET">
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
                            <a href="dashboard/<?= $client['id'] ?>" class="uk-link-reset">
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

        <?= $pager ?>

        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>

        <?php
            $datachart = [];
            foreach ($projects as $project) {
                $datachart[] = [
                    'tanggal'   => $project['created_at'],
                    'nilaispk'  => $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'],
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
                    title: 'Laporan Nilai SPK',
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

        <!-- Section Report  -->
        <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-margin-large-top">
            <!-- Page Heading Report -->
            <?php if ($ismobile === true) { ?>
                <h1 class="tm-h1 uk-text-center uk-margin-remove">Daftar & Laporan Proyek</h1>
                <div class="uk-margin uk-text-center">
                    <button id="filterbutton" class="uk-button uk-button-secondary" uk-toggle="target: #filter">Filter <span id="filteropen" uk-icon="chevron-down"></span><span id="filterclose" uk-icon="chevron-up" hidden></span></button>
                </div>
                <div id="filter" class="uk-margin" hidden>
                    <form id="searchform" action="home" method="GET">
                        <div class="uk-margin-small uk-flex uk-flex-center">
                            <input class="uk-input uk-form-width-medium" id="search" name="searchproyek" placeholder="<?= lang('Global.search') ?>" <?= (isset($input['search']) ? 'value="' . $input['search'] . '"' : '') ?> />
                        </div>
                        <div class="uk-margin uk-child-width-auto uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
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
                <h3 class="tm-h3 uk-margin-remove-bottom">Laporan Proyek Pusat Dan Cabang</h3>
            <?php } ?>
            <!-- end of Page Heading Report -->

            <div id="chart_div" class="uk-margin" style="width: 100%; height: 500px;"></div>

        </div>
        
        <div class="uk-child-width-1-3@s uk-text-left uk-margin" uk-grid>
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
                        $spkvalue[] = $projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'];
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

        <!-- form input -->
        <?php if ($ismobile === false) { ?>
            <div class="uk-child-width-1-2@s uk-text-left" uk-grid>
                <div class="uk-width-1-3">
                    <div class="">
                        <form id="short" action="home" method="get">
                            <div class="uk-inline">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="calendar"></span>
                                <input class="uk-input uk-width-medium" type="text" id="daterange" name="daterange" value="<?= date('m/d/Y', $startdate) ?> - <?= date('m/d/Y', $enddate) ?>" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="uk-width-auto">
                    <div>
                        <form class="uk-margin" id="searchform" action="home" method="GET">
                            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                                <div>
                                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                        <div>Cari:</div>
                                        <div><input class="uk-input uk-form-width-medium" id="search" name="searchproyek" <?= (isset($input['searchproyek']) ? 'value="' . $input['searchproyek'] . '"' : '') ?> /></div>
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
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
        <script>
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };
        </script>

        <script>
            $(document).ready(function() {
                $(function() {
                    $('input[name="daterange"]').daterangepicker({
                        maxDate: new Date(),
                        opens: 'right'
                    }, function(start, end, label) {
                        document.getElementById('daterange').value = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                        console.log(document.getElementById('daterange').value);
                        document.getElementById('short').submit();
                    });
                });
            });
        </script>

        <!-- Table Of Content -->
        <div class="uk-overflow-auto uk-margin-large-top uk-margin-large-bottom">
            <table class="uk-table uk-table-striped">
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
                            <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'], 0, ',', '.')  ?></td>
                            <td class=""><?= "Rp." . number_format($projectdata[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
                            <td class=""><?= "Rp." . number_format(($projectdata[$project['id']]['rabvalue'] + $projectdata[$project['id']]['allcustomrab'])-$projectdata[$project['id']]['pembayaran'], 0, ',', '.')  ?></td>
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
            <?= $pagerpro ?>
        </div>
        <!-- End Of Section Report -->

    <?php } ?>
    <!-- end of Content -->
</div>
<?= $this->endSection() ?>