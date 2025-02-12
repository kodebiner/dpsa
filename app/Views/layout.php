<!doctype html>
<html dir="ltr " lang="<?= $lang ?>" vocab="http://schema.org/" style="overflow-y: hidden;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= base_url(); ?>">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
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
                        <?php if ($authorize->hasPermission('client.read', $uid) && !$authorize->inGroup('finance',$uid)) { ?>
                            <li class="tm-main-navbar <?= ($uri->getSegment(1) === '') ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="<?= base_url('') ?>">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/dashboard.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Dashboard</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'client')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="client">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/client.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Client</div>
                                    </div>
                                </a>
                            </li>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="users">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/user.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">User</div>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === 'access-control')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="users/access-control">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/userrole.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Hak Akses</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'project') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="project">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/project.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Pengelolaan Proyek</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'mdl') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="mdl">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/produksi.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">MDL</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('client.read', $uid) && !$authorize->inGroup('finance',$uid) ) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'pesanan') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="pesanan">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/cart.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Pesan Item</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'pesanmasuk') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="pesanmasuk">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/listorder.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Pesanan Diterima</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('admin.user.read', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'mdl') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="laporan">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/report.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Laporan</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'setting') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="setting">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/settings.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Pengaturan Umum</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_groups('superuser', $uid)) { ?>
                            <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users/log') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                                <a class="tm-h4" href="users/log">
                                    <div class="uk-width-1-1 uk-margin-left">
                                        <div class="uk-width-1-1 uk-flex uk-flex-center">
                                            <img class="uk-width-1-6" src="img/layout/marketing.svg" uk-svg>
                                        </div>
                                        <div class="uk-text-center">Log Aktivitas</div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Offcanvas Section end -->
    <?php } ?>

    <!-- Navbar Section -->
    <?php if ($ismobile === false) { ?>
        <nav class="tm-sidebar-left" style="background-color: #007ec8;">
            <ul class="uk-nav uk-nav-default tm-nav" uk-nav>
                <?php if ($authorize->hasPermission('client.read', $uid) && !$authorize->inGroup('finance',$uid)) { ?>
                    <li class="tm-main-navbar <?= ($uri->getSegment(1) === '') ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="<?= base_url('') ?>">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/dashboard.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Dashboard</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
                    <li class="tm-main-navbar <?= ($uri->getSegment(1) === 'client') ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="client">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/client.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Client</div>
                            </div>
                        </a>
                    </li>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === '')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="users">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/user.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">User</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users') && ($uri->getSegment(2) === 'access-control')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="users/access-control">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/userrole.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Hak Akses</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('admin.project.read', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'project')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="project">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/project.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Pengelolaan Proyek</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('admin.mdl.read', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'mdl')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="mdl">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/produksi.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">MDL</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('client.read', $uid) && !$authorize->inGroup('finance',$uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'pesanan')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="pesanan">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/cart.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Pesan Item</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('marketing.project.edit', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'pesanmasuk')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="pesanmasuk">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/listorder.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Pesanan Diterima</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($authorize->hasPermission('admin.user.read', $uid) || $authorize->hasPermission('finance.project.edit', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'laporan')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="laporan">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/report.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Laporan</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($authorize->hasPermission('admin.user.read', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'setting')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="setting">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/settings.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Pengaturan Umum</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>

                <?php if (in_groups('superuser', $uid)) { ?>
                    <li class="tm-main-navbar <?= (($uri->getSegment(1) === 'users/log')) ? 'uk-active' : '' ?>">
                        <a class="tm-h4" href="users/log">
                            <div class="uk-width-1-1 uk-margin-right">
                                <div class="uk-width-1-1 uk-flex uk-flex-center">
                                    <img class="uk-width-1-2" src="img/layout/marketing.svg" uk-svg>
                                </div>
                                <div class="uk-text-center">Log Aktivitas</div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
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
        <div class="<?= $mainPadding ?>">

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
                                <a class="uk-navbar-item uk-logo uk-light" href="<?= base_url(); ?>" aria-label="<?= lang('Global.backHome') ?>"><img src="img/logo.png" width="80"></a>
                            </div>
                            <div class="uk-navbar-right">
                                <div class="uk-navbar-item uk-flex uk-flex-middle">
                                    <div class="uk-margin-small-right">
                                        <?php if(!empty($notifications)) { ?>
                                            <a href="" class="uk-icon-button uk-inline">
                                                <span class="uk-position-center" uk-icon="bell"></span>
                                                <span class="uk-badge uk-position-top-left" style="background: red;"><?= $countnotif ?></span>
                                            </a>
                                        <?php } else { ?>
                                            <a href="" class="uk-icon-button" uk-icon="bell"></a>
                                        <?php } ?>
                                        <div class="uk-width-large" uk-dropdown="mode: click">
                                            <div class="uk-h4" style="color: #000;">Notifikasi</div>
                                            <hr>
                                            <div class="uk-grid-small uk-panel uk-panel-scrollable uk-grid-divider" style="height: 300px !important;" uk-grid>
                                                <?php if(!empty($notifications)) {
                                                    foreach ($notifications as $notif) {
                                                        if ($notif['status'] === "0") { ?>
                                                            <div>
                                                                <a id="notif<?= $notif['id'] ?>" value="1" href="<?= $notif['url'] ?>"><?= $notif['keterangan'] ?></a>
                                                            </div>
                                                        <?php } ?>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $("#notif<?= $notif['id'] ?>").click(function() {
                                                                    $.ajax({
                                                                        url: "home/notif/<?= $notif['id'] ?>",
                                                                        method: "POST",
                                                                        data: {
                                                                            status: $('#notif<?= $notif['id'] ?>').val(),
                                                                        },
                                                                        dataType: "json",
                                                                        error: function() {
                                                                            console.log('error', arguments);
                                                                        },
                                                                        success: function() {
                                                                            console.log('success', arguments);
                                                                        },
                                                                    })
                                                                });
                                                            });
                                                        </script>
                                                    <?php }
                                                } else { ?>
                                                    <div class="uk-text-meta" style="color: rgba(0, 0, 0, .5);">
                                                        <div>Belum ada notifikasi</div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <a class="uk-link-reset" type="button">
                                        <?php
                                        if (!empty($account->photo)) {
                                            $profile = 'img/profile/' . $account->photo;
                                        } else {
                                            $profile = 'img/layout/user.svg';
                                        }
                                        ?>
                                        <img src="<?= $profile ?>" class="uk-object-cover uk-object-position-top-center uk-border-circle" width="40" height="40" style="aspect-ratio: 1 / 1; border: 2px solid #39f;" alt="<?= $fullname ?>" />
                                    </a>
                                    <div class="uk-width-medium" uk-dropdown="mode: click">
                                        <div class="uk-flex-middle uk-grid-small" uk-grid>
                                            <div class="uk-width-auto">
                                                <img src="<?= $profile ?>" class="uk-object-cover uk-object-position-top-center uk-border-circle" width="40" height="40" style="aspect-ratio: 1 / 1; border: 2px solid #39f;" alt="<?= $fullname ?>" />
                                            </div>
                                            <div class="uk-width-expand">
                                                <div class="uk-h4 uk-margin-remove" style="color: #000;"><?= $fullname ?></div>
                                                <div class="uk-text-meta" style="color: rgba(0, 0, 0, .5);"><?= $role; ?></div>
                                            </div>
                                        </div>
                                        <hr style="border-top-color: rgba(0, 0, 0, .5);" />
                                        <div>
                                            <a class="uk-link-reset uk-h4" href="account"><span uk-icon="user"></span> Kelola Akun</a>
                                        </div>
                                        <hr style="border-top-color: rgba(0, 0, 0, .5);" />
                                        <a class="uk-button uk-button-danger" href="logout"><?= lang('Global.logout') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <!-- Header Section end -->

            <div class="<?= $mainContainer ?>">
                <div id="main" class="<?= $mainCard ?>uk-panel uk-panel-scrollable" uk-height-viewport="offset-top: .uk-navbar-container; offset-bottom: .tm-footer;">
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
                $footerPadding = 'uk-padding-xlarge-left-footer';
                $footerContainer = 'uk-container uk-container-expand';
            }
            ?>
            <div class="<?= $footerPadding ?>">
                <div class="<?= $footerContainer ?>" style="padding-left: 0px !important;">
                    <?php
                    function auto_copyright($year = 'auto')
                    {
                        if (intval($year) == 'auto') {
                            $year = date('Y');
                        }
                        if (intval($year) == date('Y')) {
                            echo intval($year);
                        }
                        if (intval($year) < date('Y')) {
                            echo intval($year) . ' - ' . date('Y');
                        }
                        if (intval($year) > date('Y')) {
                            echo date('Y');
                        }
                    }
                    ?>
                    <?php if ($ismobile === true) { ?>
                        <div>
                            <div class="uk-margin-small uk-text-center">
                                Copyright &copy; <?php auto_copyright("2023"); ?><br />PT Dharma Putra Sejahtera Abadi
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="uk-margin-left">
                            Copyright &copy; <?php auto_copyright("2023"); ?><br />PT Dharma Putra Sejahtera Abadi
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