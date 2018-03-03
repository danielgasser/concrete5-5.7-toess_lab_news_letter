<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter.php
 */

?>
<?php
$session = \Core::make('session');
$folder_names = array(
    t('Templates'),
    t('Newsletters')
);
?>
<div class="clearfix">
    <?php
    if($session->has('success_sent')) {
        $m = $session->get('success_sent');
        ?>
        <div id="success_sent" class="alert alert-info">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php
    }
    $session->remove('success_sent');
    ?>
    <?php
    print '<div class="row">';
    for ($i = 0; $i < count($categories); $i++) {
    $cat = $categories[$i];
        if ($i == 5 && $i > 0){
            print '<div class="row">';
        }
    ?>
    <div class="col-md-4 ccm-dashboard-section-menu">
        <h2><?php       echo t($cat->getCollectionName())?></h2>
        <?php
        $show = array();
        $subcats = $cat->getCollectionChildrenArray(true);
        foreach($subcats as $catID) {
            $subcat = Page::getByID($catID, 'ACTIVE');
            if ($subcat->getAttribute('exclude_nav')) {
                continue;
            }
            $catp = new Permissions($subcat);
            if ($catp->canRead()) {
                $show[] = $subcat;
            }
        }
        ?>

        <ul class="list-unstyled">
            <?php       if (count($show) > 0) { ?>

            <?php       foreach($show as $subcat) { ?>

                    <li><a href="<?php       echo \Core::make('helper/navigation')->getLinkToCollection($subcat, false, true)?>"><i class="<?php       echo $subcat->getAttribute('icon_dashboard')?>"></i> <?php       echo t($subcat->getCollectionName())?></a></li>

                <?php       } ?>

            <?php       } else { ?>
            <li><a href="<?php       echo \Core::make('helper/navigation')->getLinkToCollection($cat, false, true)?>"><i class="<?php       echo $cat->getAttribute('icon_dashboard')?>"></i> <?php       echo t($cat->getCollectionName())?></a></li><?php
                } ?>
        </ul>
    </div>
    <?php
        if ($i == 5 && $i > 0){
            print '</div>';
        }
    }
    ?>
</div>
