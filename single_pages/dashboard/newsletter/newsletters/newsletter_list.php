<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/newsletters/newsletter_list.php
 */

?>
<?php      
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$cdh  = \Core::make('helper/concrete/ui');
$form = \Core::make('helper/form');
$v = \Core::make('helper/file');
$al = \AssetList::getInstance();
$this->requireAsset('redactor');
$this->requireAsset('core/file-manager');
$path = $c->getCollectionPath();
$str_attach_max = t('You may attach up to %s files', $files_num);
$editor = Core::make('editor');
$editor->getPluginManager()->select('googlefontfamily');
$session = \Core::make('session');
?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('editor')?>",
        delete_title = '<?php       print t('Warning') ?>',
        delete_yes = '<?php       print t('Delete') ?>',
        delete_no = '<?php       print t('Do not delete') ?>',
        subject ='<?php       print $controller->nl_subject?>';
</script>
<div id="dialog_delete" style="display: none"><?php       print t('Are you sure you want to permanently remove this Newsletter?')?></div>

<div id="add_new" class="ccm-dashboard-header-buttons">
    <a href="<?php       print URL::to('/dashboard/newsletter/newsletters/new_newsletter') ?>" class="btn btn-primary"><?php       print t('Add Newsletter') ?></a>
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
        $i = 0;
    if(sizeof($newsletter) > 0) {
        foreach($newsletter as $v) {

        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p style="margin-top: 44px;">
                    <a name="<?php       print $v->getNewsLetterId() ?>"></a>
                </p>
            </div>
        </div>
        <div class="row <?php       print $row ?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php      
                print '<h3><a class="edit" href="' . $view->url('/dashboard/newsletter/newsletters/new_newsletter/-/edit', $v->getNewsLetterId()) . '">' . $v->getNLHandle() . '</a></h3>';
                ?>
            </div>
        </div>
        <div class="row <?php       print $row ?>">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter-label"><?php       print t('Head Section Text') ?></div>
                <div class="mail_texts toesslab_newsletter_template">
                    <?php       print stream_get_contents($v->getHeaderText()) ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter-label"><?php       print t('Body Section Text') ?></div>
                <div class="mail_texts toesslab_newsletter_template">
                    <?php       print stream_get_contents($v->getMailText()) ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter-label"><?php       print t('Footer Section Text') ?></div>
                <div class="mail_texts toesslab_newsletter_template">
                    <?php       print stream_get_contents($v->getMailTextFooter()) ?>
                </div>
            </div>
        </div>
        <div class="row <?php       print $row ?>">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter-label"><?php       print t('Template') ?></div>
                <div class="mail_texts toesslab_newsletter_template">
                    <a class="edit_t" href="<?php       print $view->url('/dashboard/newsletter/templates/new_template/-/edit/', $v->getTemplate()->getMailTemplateID()) ?>"><?php       print $v->getTemplate()->getMailTemplateHandle() ?></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="newsletter-label"><?php       print t('Subject') ?></div>
                <div class="mail_texts toesslab_newsletter_template">
                    <?php       print $v->getNLSubject() ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 list_files">
                <?php      
                if (strlen($v->getAttachments()) == 0) {
                    print '<div class="newsletter-label">' . t('No attachments') . '</div>';
                } else {
                    $attachments = explode(',', $v->getAttachments());
                    print '<div class="newsletter-label">' . sizeof($attachments) . ' ' .t('Attachments') . '</div>';
                    print '<ul>';
                    foreach($attachments as $f) {
                        if (intval($f) > 0) {
                            $file = \File::getByID($f);
                            $version = $file->getRecentVersion();
                            print '<li><a target="_blank" href="' . \File::getRelativePathFromID($file->getFileID()) . '">' . $version->getFileName() . '</a></li>';
                        }
                    }
                }
                print '</ul></div>';
                print '</div>';
                ?>
                <div class="row <?php       print $row ?>">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="ccm-ui" style="margin-top: 20px; margin-bottom: 10px;">
                            <a href="<?php       print $view->action('duplicate_newsletter', array($v->getNewsletterID(), $v->getNLHandle()))?>" style="width: 100%" type="submit" id="duplicate_newsletter" name="duplicate_newsletter" class="btn btn-default"><?php       echo t('Duplicate') ?></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="ccm-ui" style="margin-top: 20px; margin-bottom: 10px;">
                            <a href="<?php       print $view->action('delete_newsletter', array($v->getNewsletterID(), $v->getNLHandle()))?>" style="width: 100%" type="submit" id="delete_newsletter" name="delete_newsletter" class="pull-right btn btn-danger"><?php       echo t('Delete') ?></a>
                        </div>

                    </div>
                </div><hr>

                <?php      
                $i++;
                }
            } ?>
            </div>
    </div>
<script>
    var tourist_edit_newsletter_title = '<?php       print t('Edit Newsletter')?>',
        tourist_edit_template_title = '<?php       print t('Edit Template')?>',
        tourist_edit_template_text = '<?php       print t('Edit a Template by clicking this link.')?>',
        tourist_edit_newsletter_text = '<?php       print t('Edit a Newsletter by clicking this link.')?>',
        tourist_new_newsletter_title = '<?php       print t('Add Newsletter') ?>',
        tourist_new_newsletter_text = '<?php       print t('Add a new Newsletter by clicking here.')?>';
</script>