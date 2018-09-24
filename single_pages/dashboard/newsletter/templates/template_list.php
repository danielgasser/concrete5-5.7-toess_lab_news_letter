<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/templates/template_list.php
 */

?>
<?php      
$session = \Core::make('session');
?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('editor')?>",
        delete_title = '<?php       print t('Warning') ?>',
        delete_yes = '<?php       print t('Delete') ?>',
        delete_no = '<?php       print t('Do not delete') ?>',
        subject ='<?php       print $controller->nl_subject?>',
        get_template = '<?php       print $controller->action('get_newsletter')?>';
</script>
<div id="dialog_delete" style="display: none"><?php       print t('Are you sure you want to permanently remove this Template?')?></div>
<div id="add_new" class="ccm-dashboard-header-buttons">
    <a href="<?php       print URL::to('/dashboard/newsletter/templates/new_template') ?>" class="btn btn-primary"><?php       print t('Add Template') ?></a>

</div>

<div class="clearfix">
    <?php      
    if($session->has('being_sent')) {
        $m = $session->get('being_sent');
        ?>
        <div id="already_sent" class="alert alert-danger">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php      
    }
    $session->remove('being_sent');
    if($session->has('message')) {
        $m = $session->get('message');
        ?>
        <div id="already_sent" class="alert alert-info">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php      
    }
    $session->remove('message');

    if($session->has('error')) {
        $m = $session->get('error');
        ?>
        <div id="already_sent" class="alert alert-danger">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php      
    }
    $session->remove('error');

    ?>
    <?php      
    $form = \Core::make('helper/form');
    $dh = Core::make('helper/date');
    $same = false;
    $print_ul = '';
    $view = View::getInstance();
    ?>

        <?php      
        $i = 0;
        if (!is_null($newsletter_templates)) {
        if(count($newsletter_templates) > 0){
            foreach($newsletter_templates as $v) {

                ?>
                <div class="row">
                    <p>
                        <a id="<?php       print $v['id']?>"></a>
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <?php
                        print '<h3><a class="edit" href="' . $view->url('/dashboard/newsletter/templates/new_template/-/edit', $v['id']) . '">' . $v['handle'] . '</a></h3>';
                        ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="ccm-ui" style="margin-top: 20px; margin-bottom: 10px;">
                            <a href="<?php       print $view->action('duplicate_template', array($v['id'], $v['handle']))?>" style="width: 100%" type="submit" id="duplicate_template" name="duplicate_template" class="btn btn-default"><?php       echo t('Duplicate') ?></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="ccm-ui" style="margin-top: 20px; margin-bottom: 10px;">
                            <a href="<?php       print $view->action('delete_template', array($v['id'], $v['handle']))?>" style="width: 100%" type="submit" id="delete_template" name="delete_template" class="pull-right btn btn-danger"><?php       echo t('Delete') ?></a>
                        </div>
                        <div id="tpl_list_<?php       print $v['id'] ?>"></div>

                    </div>
                    <hr>
                </div>

                <?php
            }
        }
        }?>

</div>
<div id="mail_template_border" class="ccm-ui"></div>
<script>
    var tourist_edit_template_title = '<?php       print t('Edit Template')?>',
        tourist_edit_template_text = '<?php       print t('Edit a Template by clicking this link.')?>',
        tourist_new_template_title = '<?php       print t('Add Template') ?>',
        tourist_new_template_text = '<?php       print t('Add a new Template by clicking here')?>';
</script>