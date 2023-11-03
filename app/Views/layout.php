<!doctype html>
<html dir="ltr "lang="<?=$lang?>" vocab="http://schema.org/" style="overflow-y: hidden;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="<?=base_url();?>">
        <title><?=$title?></title>
        <meta name="description" content="<?=$description?>">
        <meta name="author" content="PT. Kodebiner Teknologi Indonesia">
        <link rel="icon" href="favicon/favicon.ico">
        <link rel="apple-touch-icon" sizes="16x16" href="favicon/apple-icon-16x16.png">
        <link rel="apple-touch-icon" sizes="32x32" href="favicon/apple-icon-32x32.png">
        <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="96x96" href="favicon/apple-icon-96x96.png">
        <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
        <link rel="apple-touch-icon" sizes="192x192" href="favicon/apple-icon-192x192.png">
        <link rel="manifest" href="favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" href="css/theme.css">
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>

        <!-- Extra Script Section -->
        <?= $this->renderSection('extraScript') ?>
        <!-- Extra Script Section end -->

    </head>
    <body>
        <?php if ($ismobile === true) { ?>
        <!-- Offcanvas Section -->
        <div id="offcanvas" uk-offcanvas="mode: push; overlay: true">
            <div class="uk-offcanvas-bar" role="dialog" aria-modal="true">
                <nav>
                    <ul class="uk-nav uk-nav-default tm-nav" uk-nav>                            
                        <li class="tm-main-navbar <?=($uri->getSegment(1)==='')?'uk-active':''?>">
                            <a class="uk-h4 tm-h4" href="<?= base_url('') ?>">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/dashboard.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center"><?=lang('Global.dashboard');?></div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/client.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Client</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/user.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">User</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === 'access-control')) ? 'uk-active' : '' ?>">
                            <a class="uk-h4 tm-h4" href="users/access-control">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/userrole.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Hak Akses</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/marketing.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Marketing</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/produksi.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Produksi</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/finance.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Finance</div>
                                </div>
                            </a>
                        </li>
                        <li class="tm-main-navbar">
                            <a class="uk-h4 tm-h4" href="">
                                <div class="uk-width-1-1 uk-margin-left">
                                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                                        <img class="uk-width-1-6" src="img/layout/design.svg" uk-svg>
                                    </div>
                                    <div class="tm-navbar-text uk-text-center">Design</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Offcanvas Section end -->
        <?php } ?>

        <!-- Header Section -->
        <header class="uk-margin">
            <nav class="uk-navbar-container uk-navbar-transparent" uk-sticky="media: 960;">
                <div class="uk-container uk-container-expand">
                    <div uk-navbar>
                        <?php if ($ismobile === true) { ?>
                            <div class="uk-navbar-left">
                                <a class="uk-navbar-toggle" href="#offcanvas" uk-navbar-toggle-icon uk-toggle role="button" aria-label="Open menu"></a>
                            </div>
                        <?php } ?>
                        <div class="uk-navbar-center">
                            <a class="uk-navbar-item uk-logo uk-light" href="<?=base_url();?>" aria-label="<?=lang('Global.backHome')?>"><img src="img/logo.png" width="80"></a>
                        </div>
                        <div class="uk-navbar-right">
                            <div class="uk-navbar-item uk-flex uk-flex-middle uk-inline">
                                <a class="uk-link-reset" type="button">
                                    <?php
                                    if (!empty($account->photo)) {
                                        $profile = 'img/profile/'.$account->photo;
                                    } else {
                                        $profile = 'img/layout/user.svg';
                                    }
                                    ?>
                                    <img src="<?= $profile ?>" class="uk-object-cover uk-object-position-top-center uk-border-circle" width="40" height="40" style="aspect-ratio: 1 / 1; border: 2px solid #39f;" alt="<?=$fullname?>" />
                                </a>
                                <div class="uk-width-medium" uk-dropdown="mode: click">
                                    <div class="uk-flex-middle uk-grid-small" uk-grid>
                                        <div class="uk-width-auto">
                                            <img src="<?= $profile ?>" class="uk-object-cover uk-object-position-top-center uk-border-circle" width="40" height="40" style="aspect-ratio: 1 / 1; border: 2px solid #39f;" alt="<?=$fullname?>" /> 
                                        </div>
                                        <div class="uk-width-expand">
                                            <div class="uk-h4 uk-margin-remove" style="color: #000;"><?=$fullname?></div>
                                            <div class="uk-text-meta" style="color: rgba(0, 0, 0, .5);"><?=$role;?></div>
                                        </div>
                                    </div>
                                    <hr style="border-top-color: rgba(0, 0, 0, .5);"/>
                                    <div>
                                        <a class="uk-link-reset uk-h4" href="account"><span uk-icon="user"></span> Kelola Akun</a>
                                    </div>
                                    <hr style="border-top-color: rgba(0, 0, 0, .5);"/>
                                    <a class="uk-button uk-button-danger" href="logout"><?=lang('Global.logout')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <!-- Header Section end -->
        
        <!-- Navbar Section -->
        <?php if ($ismobile === false) { ?>
            <nav class="tm-sidebar-left" style="background-color: #007ec8;">
                <ul class="uk-nav uk-nav-default tm-nav" uk-nav>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('') ?>">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/dashboard.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center"><?=lang('Global.dashboard');?></div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar">
                        <a class="uk-h4 tm-h4" href="">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/client.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Client</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                        <a class="uk-h4 tm-h4" href="users">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/user.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">User</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === 'access-control')) ? 'uk-active' : '' ?>">
                        <a class="uk-h4 tm-h4" href="users/access-control">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/userrole.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Hak Akses</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='rab')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="rab">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/marketing.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Marketing</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?= (($uri->getSegment(1) === 'project')) ? 'uk-active' : '' ?>">
                        <a class="uk-h4 tm-h4" href="project">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/produksi.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Produksi</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar">
                        <a class="uk-h4 tm-h4" href="">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/finance.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Finance</div>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar">
                        <a class="uk-h4 tm-h4" href="">
                            <div class="uk-width-1-1 uk-margin-left">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/design.svg" uk-svg>
                                </div>
                                <div class="tm-navbar-text uk-text-center">Design</div>
                            </div>
                        </a>
                    </li>
                    <!-- <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='project')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('project') ?>">
                            <div class="uk-margin-left">
                                <img src="img/layout/laporan.svg" uk-svg><?=lang('Global.project');?>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='design')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('design') ?>">
                            <div class="uk-margin-left">
                                <img src="img/layout/laporan.svg" uk-svg><?=lang('Global.design');?>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='rab')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('rab') ?>">
                            <div class="uk-margin-left">
                                <img src="img/layout/laporan.svg" uk-svg><?=lang('Global.rab');?>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='mdl')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('mdl') ?>">
                            <div class="uk-margin-left">
                                <img src="img/layout/laporan.svg" uk-svg><?=lang('Global.mdl');?>
                            </div>
                        </a>
                    </li>
                    <li class="uk-margin-left tm-main-navbar <?=($uri->getSegment(1)==='users')?'uk-active':''?>">
                        <a class="uk-h4 tm-h4" href="<?= base_url('users') ?>">
                            <div class="uk-margin-left">
                                <img src="img/layout/pelanggan.svg" uk-svg><?=lang('Global.user');?>
                            </div>
                        </a>
                    </li> -->
                </ul>
            </nav>
        <?php } ?>
        <!-- Navbar Section end -->

        <!-- Main Section -->
        <main role="main">
            <?php
            if ($ismobile === true) {
                $mainPadding = '';
                $mainContainer = 'uk-container';
                $mainCard = '';
            } else {
                $mainPadding = 'uk-padding-xlarge-left';
                $mainContainer = 'uk-container uk-container-expand';
                $mainCard = 'tm-main-card ';
            }
            ?>
            <div class="<?=$mainPadding?>">
                <div class="<?=$mainContainer?>">
                    <div class="<?=$mainCard?>uk-panel uk-panel-scrollable" uk-height-viewport="offset-top: .uk-navbar-container; offset-bottom: .tm-footer;">
                        <?= $this->renderSection('main') ?>
                    </div>
                </div>
            </div>
            
            <!-- Footer Section -->
            <footer class="tm-footer" style="background-color: #007ec8; color: #fff;">
                <?php
                if ($ismobile === true) {
                    $footerPadding = '';
                    $footerContainer = '';
                } else {
                    $footerPadding = 'uk-padding-xlarge-left';
                    $footerContainer = 'uk-container uk-container-expand';
                }
                ?>
                <div class="<?=$footerPadding?>">
                    <div class="<?=$footerContainer?>">
                        <?php
                        function auto_copyright($year = 'auto'){
                            if(intval($year) == 'auto'){ $year = date('Y'); }
                            if(intval($year) == date('Y')){ echo intval($year); }
                            if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); }
                            if(intval($year) > date('Y')){ echo date('Y'); }
                        }
                        ?>
                        <?php if ($ismobile === true) { ?>
                            <div>
                                <div class="uk-margin-small uk-text-center">
                                    Copyright &copy; <?php auto_copyright("2023"); ?>
                                </div>
                                <div class="uk-margin-small uk-text-center uk-link-reset">
                                    Developed by<br/><a href="https://binary111.com" target="_blank">PT. Kodebiner Teknologi Indonesia</a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class=" uk-child-width-auto uk-flex-between uk-flex-middle" uk-grid>
                                <div class="uk-margin-left">                                    
                                    Copyright &copy; <?php auto_copyright("2023"); ?>
                                </div>
                                <div class="uk-text-right uk-link-reset">
                                    Developed by<br/><a href="https://binary111.com" target="_blank">PT. Kodebiner Teknologi Indonesia</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </footer>
            <!-- Footer Section end -->
        </main>
        <!-- Main Section end -->
    </body>
</html>
