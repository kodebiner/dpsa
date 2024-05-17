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
                <div class="uk-width-1-1 uk-text-center uk-text-italic">Belum Ada Proyek</div>
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

        <?= $pagerpro ?>

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
                                <select class="uk-select uk-form-width-xsmall" id="perpagereport" name="perpagereport">
                                    <option value="10" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '10') ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '25') ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '50') ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '100') ? 'selected' : '') ?>>100</option>
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
                        <form class="uk-margin" id="searchreport" action="home" method="GET">
                            <div class="uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                                <div>
                                    <div class="uk-child-width-auto uk-grid-small uk-flex-middle" uk-grid>
                                        <div>Cari:</div>
                                        <div><input class="uk-input uk-form-width-medium" id="searchreport" name="searchreport" <?= (isset($input['searchreport']) ? 'value="' . $input['searchreport'] . '"' : '') ?> /></div>
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
                                            <select class="uk-select uk-form-width-xsmall" id="perpagereport" name="perpagereport">
                                                <option value="10" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '10') ? 'selected' : '') ?>>10</option>
                                                <option value="25" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '25') ? 'selected' : '') ?>>25</option>
                                                <option value="50" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '50') ? 'selected' : '') ?>>50</option>
                                                <option value="100" <?= (isset($input['perpagereport']) && ($input['perpagereport'] === '100') ? 'selected' : '') ?>>100</option>
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
            // Searching Proyek
            document.getElementById('search').addEventListener("change", submitform);
            document.getElementById('perpage').addEventListener("change", submitform);

            function submitform() {
                document.getElementById('searchform').submit();
            };

            // Searching Report Proyek
            document.getElementById('searchreport').addEventListener("change", submitreport);
            document.getElementById('perpagereport').addEventListener("change", submitreport);

            function submitreport() {
                document.getElementById('searchreport').submit();
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
            <?= $pagerreport ?>
        </div>
        <!-- End Of Section Report -->

        <!-- Testing New Concept Pagination -->
        <div class="container">
            <div class="header_wrap">
                <div class="num_rows">
                    <div class="form-group"> 	
                        <!--Show Numbers Of Rows -->
                        <select class  ="form-control" name="state" id="maxRows">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="70">70</option>
                            <option value="100">100</option>
                            <option value="5000">Show ALL Rows</option>
                        </select>
                    </div>
                </div>
                <div class="tb_search">
                    <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                </div>
            </div>
            <table class="table table-striped table-class" id= "table-id">
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
            <!--Start Pagination -->
                <div class='pagination-container'>
                    <nav>
                        <ul class="pagination">
                        <!--	Here the JS Function Will Add the Rows -->
                        </ul>
                    </nav>
                </div>
            <div class="rows_count">Showing 11 to 20 of 91 entries</div>
        </div> 
        <!--End of Container -->
        <script>
            getPagination('#table-id');
	        $('#maxRows').trigger('change');
                function getPagination (table){
                        $('#maxRows').on('change',function(){
                            $('.pagination').html('');						// reset pagination div
                            var trnum = 0 ;									// reset tr counter 
                            var maxRows = parseInt($(this).val());			// get Max Rows from select option
                
                            alRows = $(table+' tbody tr').length;		// numbers of rows 
                            $(table+' tr:gt(0)').each(function(){			// each TR in  table and not the header
                                trnum++;									// Start Counter 
                            if (trnum > maxRows ){						// if tr number gt maxRows
                                $(this).hide();							// fade it out 
                            }if (trnum <= maxRows ){$(this).show();}// else fade in Important in case if it ..
                        });

                        //  was fade out to fade it in 
                        if (totalRows > maxRows){						// if tr total rows gt max rows option
                            var pagenum = Math.ceil(totalRows/maxRows);	// ceil total(rows/maxrows) to get ..   //	numbers of pages 
                            for (var i = 1; i <= pagenum ;){			// for each page append pagination li 
                                $('.pagination').append('<li data-page="'+i+'">\<span>'+ i++ +'<span class="sr-only">(current)</span></span>\</li>').show();
                            }											// end for i 
                        } 												// end if row count > max rows
                        $('.pagination li:first-child').addClass('active'); // add active class to the first li 
                
                
                        //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT
                        showig_rows_count(maxRows, 1, totalRows);
                        //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT

                        $('.pagination li').on('click',function(e){		// on click each page
                            e.preventDefault();
                            var pageNum = $(this).attr('data-page');	// get it's number
                            var trIndex = 0 ;							// reset tr counter
                            $('.pagination li').removeClass('active');	// remove active class from all li 
                            $(this).addClass('active');					// add active class to the clicked 

                            //SHOWING ROWS NUMBER OUT OF TOTAL
                            showig_rows_count(maxRows, pageNum, totalRows);
                            //SHOWING ROWS NUMBER OUT OF TOTAL
                
                            $(table+' tr:gt(0)').each(function(){		// each tr in table not the header
                                trIndex++;								// tr index counter 
                                // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
                                if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
                                    $(this).hide();		
                                }else {$(this).show();} 				//else fade in 
                            }); 										// end of for each tr in table
                        });										// end of on click pagination list
                    });

                    // end of on select change 
                    // END OF PAGINATION 
                }	

                // SI SETTING
                $(function(){
                    // Just to append id number for each row  
                default_index();            
                });

                //ROWS SHOWING FUNCTION
                function showig_rows_count(maxRows, pageNum, totalRows) {
                //Default rows showing
                        var end_index = maxRows*pageNum;
                        var start_index = ((maxRows*pageNum)- maxRows) + parseFloat(1);
                        var string = 'Showing '+ start_index + ' to ' + end_index +' of ' + totalRows + ' entries';               
                        $('.rows_count').html(string);
                }

                // CREATING INDEX
                function default_index() {
                $('table tr:eq(0)').prepend('<th> ID </th>')
                    var id = 0;

                    $('table tr:gt(0)').each(function(){	
                        id++
                        $(this).prepend('<td>'+id+'</td>');
                    });
                }

                // All Table search script
                function FilterkeyWord_all_table() {
  
                    // Count td if you want to search on all table instead of specific column
                    var count = $('.table').children('tbody').children('tr:first-child').children('td').length; 

                    // Declare variables
                    var input, filter, table, tr, td, i;
                    input = document.getElementById("search_input_all");
                    var input_value =     document.getElementById("search_input_all").value;
                    filter = input.value.toLowerCase();
                    if(input_value !=''){
                        table = document.getElementById("table-id");
                        tr = table.getElementsByTagName("tr");

                        // Loop through all table rows, and hide those who don't match the search query
                        for (i = 1; i < tr.length; i++) {
                            var flag = 0;
                            
                            for(j = 0; j < count; j++){
                                td = tr[i].getElementsByTagName("td")[j];
                                if (td) {
                                    var td_text = td.innerHTML;  
                                    if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                                    //var td_text = td.innerHTML;  
                                    //td.innerHTML = 'shaban';
                                    flag = 1;
                                    } else {
                                    //DO NOTHING
                                    }
                                }
                                }
                            if(flag==1){
                                        tr[i].style.display = "";
                            }else {
                                tr[i].style.display = "none";
                            }
                        }
                    }else {
                        //RESET TABLE
                        $('#maxRows').trigger('change');
                    }
                }
        </script>
        <!-- EndTesting New Concept Pagination -->
    <?php } ?>
    <!-- end of Content -->
</div>
<?= $this->endSection() ?>