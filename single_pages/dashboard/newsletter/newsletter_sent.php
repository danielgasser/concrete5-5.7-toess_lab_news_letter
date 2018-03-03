<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/newsletter_sent.php
 */

$form = \Core::make('helper/form');
$dh = Core::make('helper/date');
$same = false;
$print_ul = '';
$session = \Core::make('session');
?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('editor')?>",
        delete_title = '<?php       print t('Warning') ?>',
        delete_yes = '<?php       print t('Delete') ?>',
        being_sent_warning = '<?php       print t('WARNING: This mailing has not been sent yet!')?>',
        delete_no = '<?php       print t('Do not delete') ?>';
</script>


<div class="clearfix">
    <?php      
    if($session->has('history_deleted')) {
        $m = $session->get('history_deleted');
        ?>
        <div id="history_deleted" class="alert alert-info">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php      
    }
    $session->remove('history_deleted');
    ?>
    <?php      
        $row_count = 0;
        if(is_array($ns_sent)) {
        foreach($ns_sent as $v) {
            $img = '';
            $now = new DateTime();
            $row = ($row_count % 2 == 0) ? 'even' : 'odd';
            $date = new DateTime($v['start_send']->format('Y-m-d H:i:s'));
            $print_date = $dh->formatDateTime($date->getTimestamp());
            if($v['sent_by_job'] == '1') {
                if ($now->getTimestamp() < $date->getTimestamp()) {
                    $sent_by_job =  t('will be sent as %s', t('Automated job'));
                } else {
                    $sent_by_job = t('has been sent as %s', t('Automated job'));
                    if ($v['mails'][0]['sent'] == '0') {
                        $sent_by_job = t('has not been sent as %s', t('Automated job'));
                    }
                }
            }else{
                $sent_by_job = t('has been sent directly');
                $date =  $v['mails'][0]['send_time'];
                $print_date = $dh->formatDateTime($date->getTimestamp());
            }
            ?>

    <div class="row <?php       print $row ?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="my-legend"><span class="main"><?php       print t('Mailing \'%s\' from the %s %s', $v['ns_handle'], $print_date, $sent_by_job) ?></span></div>
        </div>
    </div>
    <div class="row <?php       print $row ?>">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="my-legend"><?php       print t('Emails sent/not sent') ?></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="my-legend"><?php       print t('Attachments') ?></div>
        </div>
    </div>
    <div class="row <?php       print $row ?>">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 list_uemail_sent">
            <?php      
            if (array_key_exists('mails', $v)) {
                print \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\SendMailing::printSentNewsletters($v['mails']);
            }
            ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 list_files">
            <?php      
            if (strlen($v['attachments']) == 0) {
                print '<h4>' . t('No attachments') . '</h4>';
            } else {
                $attachments = explode(',', $v['attachments']);
                print '<h4>' . sizeof($attachments) . ' ' .t('Attachments') . '</h4>';
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
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="my-legend"><?php       print t('Newsletter used') ?></div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="my-legend"><?php       print t('Template used') ?></div>
                </div>
            </div>
            <div class="row <?php       print $row ?>">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php      
                    if ($v['newsletter_id'] == NULL) {
                        print $v['newsletter_handle'];
                    } else {
                        ?>
                        <a href="<?php       print $view->url('/dashboard/newsletter/newsletters/newsletter_list#' . $v['newsletter_id']) ?>"><?php       print $v['newsletter_handle'] ?></a>
                        <?php      
                    }
                    ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php      
                    if ($v['template_id'] == NULL) {
                        print $v['template'];
                    } else {
                        ?>
                        <a href="<?php       print $view->url('/dashboard/newsletter/templates/template_list#' . $v['template_id'])?>"><?php       print $v['template'] ?></a>
                        <?php      
                    }
                    ?>
                </div>
            </div>
            <div class="row <?php       print $row ?>">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="ccm-ui" style="margin-top: 20px; margin-bottom: 10px;">
                        <a href="<?php       print $view->action('delete_newsletter', array($v['id'], $v['ns_handle']))?>" style="width: 100%" type="submit" id="delete_newsletter_history" name="delete_newsletter" data-id="<?php       print $v['id'] ?>" class="pull-right btn btn-danger"><?php       echo t('Delete') ?></a>
                    </div>

                </div>
            </div><hr>

            <?php      
        $row_count++;
        }
    } ?>
        </div>
    </div>
    <div id="dialog_delete" style="display: none"><div id="is_being_sent_warning"></div> <?php       print t('Are you sure you want to permanently remove this Mailing?')?></div>

</div>