<?php

use CodeIgniter\CodeIgniter;
use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<?php if ($pager->getPageCount() > 1) { ?>
<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="uk-pagination uk-flex-right" uk-margin>
        <?php if ($pager->hasPrevious()) { ?>
            <li><a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" uk-icon="chevron-double-left"></a></li>
            <li><a href="<?= $pager->getPreviousPage() ?>" aria-label="<?= lang('Pager.previous') ?>" uk-icon="chevron-left"></a></li>
            <?php if ($pager->getCurrentPageNumber() >= 4) { ?>
                <li class="uk-disabled"><span>...</span></li>
            <?php } ?>
        <?php } ?>

        <?php
            $i = 1;
            // $uri = current_url(true);
            // $full_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            // $totalpage  = count($pager->links());

            if (isset($_GET['daterange'])) {
                $date = $_GET['daterange'];
            } else {
                $date = "";
            }
            if (isset($_GET['perpagereport'])) {
                $perpage = $_GET['perpagereport'];
            } else {
                $perpage = 10;
            }
            if (isset($_GET['searchreport'])) {
                $report = $_GET['searchreport'];
            } else {
                $report = "";
            }
                // Fallback behaviour 
            // function pagereportfunct($i)
            // {
            //     echo "&pagereport=".$i++;
            // }
        ?>

        <?php foreach ($pager->links() as $link) { ?>
            <li <?= $link['active'] ? 'class="uk-active"' : '' ?>>
                <?php
        
                    // $link['uri'] =  $uri;

                    // $pagereport = [] ;
                    // if (!str_contains($link['uri'], '&pagereport='.$link['title'])) { 
                    //     $pagereport =  '&pagereport='.$link['title'];
                    // }else{
                    //     $pagereport = "";
                    // }
                    

                    // $link['uri'] = $full_url.'&pagereport='.$i++;
                    // if ($link['active'] === true) {
                    //     echo '<span>'.$link['title'].'</span>';
                    // } else {
                    //     echo '<a href="'.$link['uri'].'">'.$link['title'].'</a>';
                    // }

                    $link['uri'] = base_url('?daterange='.$date.'&searchreport='.$report.'&perpagereport='.$perpage.'&pagereport='.$i++);
                    if ($link['active'] === true) {
                        echo '<span>'.$link['title'].'</span>';
                    } else {
                        echo '<a href="'.$link['uri'].'">'.$link['title'].'</a>';
                    }

                ?>
            </li>
        <?php } ?>

        <?php if ($pager->hasNext()) { ?>
            <?php if ($pager->getCurrentPageNumber() <= $pager->getPageCount() - 3) { ?>
                <li class="uk-disabled"><span>...</span></li>
            <?php } ?>
            <li><a href="<?= $pager->getNextPage() ?>" aria-label="<?= lang('Pager.next') ?>" uk-icon="chevron-right"></a></li>
            <li><a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" uk-icon="chevron-double-right"></a></li>
        <?php } ?>
    </ul>
</nav>
<?php } ?>