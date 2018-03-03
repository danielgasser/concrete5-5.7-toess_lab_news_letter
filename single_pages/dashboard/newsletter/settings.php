<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/settings.php
 */

$form = \Core::make('helper/form');
$path = $c->getCollectionPath();
$u = new \Concrete\Core\Application\Service\FileManager();
$editor = Core::make('editor');
$editor->getPluginManager()->select('googlefontfamily');
$session = \Core::make('session');
?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('save_settings')?>",
        user_group_url = '<?php       print $this->action('change_user_group')?>',
        change_time_unit = '<?php       print $this->action('change_cron_job_command')?>',
        is_per_job = $('#per_job').val();
</script>
<script>
(function ($) {
        "use strict";
    jQuery(document).ready(function () {
        $('input:checkbox').bootstrapSwitch({
            labelWidth: '180'
        });

    });
}(jQuery));

</script>
<div class="clearfix">
    <?php
    if ($session->has('dont_forget_job')){
        $m = $session->get('dont_forget_job')?>
        <div id="alert_install_job" class="alert alert-info">
                        <i class="fa fa-exclamation-circle"></i>
                        <a href="#" class="close" id="ok">×</a>
                        <?php       print $m ?>
        </div>
<?php
}
    $session->remove('dont_forget_job');
    ?>
    <form id="settings_form" role="form" method="post" action="<?php       print $controller->action('save_settings')?>" class="form-inline ccm-search-fields">
        <?php       print $this->controller->token->output('save_settings'); ?>
        <fieldset>
            <legend>* <?php       print t('Required fields') ?></legend>
        </fieldset>
        <fieldset>
            <legend><?php           print $controller->field_browser_link_text ?> *</legend>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    print $form->text('browser_link_text', $browser_link_text, array('style' => 'width: 100%')); ?>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php       print $controller->field_name_attachment ?> *</legend>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="control-label" for="files_num"><?php
                        print t('Attachments');
                        ?>
                    </label><br>
                    <div class="input-group">
                    <?php       print $form->number('files_num', $files_num, array('min' => \Config::get('toess_lab_news_letter.constants.min_files'), 'max' => \Config::get('toess_lab_news_letter.constants.max_files'))); ?>
                        <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="control-label" for="files_num"><?php
                        print t('Size of <i>one</i> Attachment');
                        ?>
                    </label><br>
                    <div class="input-group">
                    <?php       print $form->number('file_size', $file_size, array('step' => '0.01', 'min' => '0.01')); ?>
                    <span class="input-group-addon"><?php       print  t('MB') ?></span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="control-label" for="files_num"><?php
                        print t('Total attachments size');
                        ?>
                    </label><br>
                    <div class="input-group">
                        <span class="input-group-addon"><?php       print t('Size of <i id="all_attachment">%s</i> Attachments: ', $files_num); print '<i id="all_attachment_size">' . $file_total_size . '</i> ' . t('MB') ?></span>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>
                <?php       print t('Send by "<a href="%s">%s</a>" or directly', URL::to('/dashboard/system/optimization/jobs'), t('Automated Jobs')) ?> *
            </legend>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="control-label" for="per_job" name="per_job"><?php
                        print t('Send by');
                        ?>
                    </label><br>
                    <?php
                    print $form->select('per_job', array('1' => t('Automated Jobs'), '0' => t('Send directly')), ($per_job) ? 1 : 0); ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-warning alert alert-warning">
                    <label class="control-label" for="per_job_warning"><?php
                        print t('Warning!');
                        ?>
                    </label><br>
                    <ul id="per_job_warning">
                        <li><?php       print t('If your provider dictates a limit, please send over "%s"', t('Automated Jobs')) ?>!</li>
                        <li><?php       print t('The option "%s" only suits for small amount of recipients.',t('Send directly')) ?></li>
                        <li><?php       print t('Ask your provider or webmaster for further information and help.') ?></li>
                    </ul>
                </div>
            </div>
        </fieldset>
        <fieldset class="only_per_job">
            <legend><?php       print $controller->field_name_mails_per_unit ?> *</legend>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text">
                    <div class="form-group">
                        <label class="control-label" for="time_unit_warning" name="time_unit_warning"><?php
                            print t('Number per time unit');
                            ?>
                        </label><br>
                        <div class="input-group">
                            <?php
                            print $form->number('mails_per', (intval($mails_per > 0)) ? $mails_per : 1, array('min' => 1, 'max' => $max_number, 'style' => 'width: 100% !important'));
                            ?>
                            <?php
                            print '<div class="input-group-addon">' . t('per') . '</div>';
                            ?>
                            <?php       print $form->select('time_unit', $time_units, (strlen($time_unit) == 0) ? 'Minute' : $time_unit); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-warning alert alert-warning">
                    <label class="control-label" for="time_unit_warning" name="time_unit_warning"><?php
                        print t('Warning!');
                        ?>
                    </label>
                    <ul id="time_unit_warning">
                        <li>
                    <?php       print t('Some providers limits the number of emails being sent in a certain time interval (Eg. 100 emails per hour).');
                    ?></li>
                        <li><?php       print t('Ask your provider or webmaster for further information and help.') ?></li>
                    </ul>
                </div>
            </div>
        </fieldset>
        <fieldset class="only_per_job">
            <legend>
                <?php
                    print t('Commands for crontab') . ':';
                ?>
            </legend>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label" for="time_unit_warning" name="time_unit_warning"><?php
                        print t('Crontabs command');
                        ?>
                    </label><br>
                    <pre style="white-space: normal;" id="cron_job_path"><?php       print $job_path ?></pre>
                    <div id="alert_cron" style="display: none;" class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i>
                        <a href="#" class="close" id="ok">×</a>
                        <strong><?php       print t('Don\'t forget to adapt your cron job!') ?></strong>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="only_per_job">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info">
                    <label class="control-label" for="cron_tab_warning" name="cron_tab_warning"><?php
                        print t('Info');
                        ?>
                    </label><br>
                    <ul id="cron_tab_warning">
                        <li><?php       print t('This command is for crontab only.') ?></li>
                        <li><?php       print t('The path to the \'<i>wget</i>\' or \'<i>get</i>\' program may be different.') ?></li>
                        <li><?php       print t('The first URL starts the process - the second ensures that it completes in batches.') ?></li>
                    </ul>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php
                    print t('Newsletter-report per email') ?> *</legend>
            <div class="text">
                <label class="control-label" for="report_newsletter" name="report_newsletter"><?php
                    print t('Report');
                    ?>

                </label><br>
                <?php       print $form->checkbox('report_newsletter', 1, ($report_newsletter == '1') ? true : false, array('data-label-text' => t('Report'), 'data-size' => 'normal')); ?>
            </div>
        </fieldset>
        <fieldset id="owner_email_show" style="display: <?php       ($report_newsletter == '1' || !isset($report_newsletter)) ? print 'block' : print 'none' ?>">
            <legend><?php
                    print t('Sender email address') ?> *</legend>
            <div class="text">
                <label class="control-label" for="owner_email" name="owner_email"><?php
                    print t('Email address');
                    ?>

                </label><br>
                <?php       print $form->email('owner_email', $owner_email, array('style' => 'width: 50%;')); ?>
            </div>
        </fieldset>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button id="Send" name="Send" class="pull-right btn btn-primary" type="submit" ><?php       echo t('Save settings')?></button>
            </div>
        </div>

    </form>
</div>
<script>
    var tourist_attachments_title = '<?php       print t('Attachments')?>',
        tourist_attachments_text = '<?php       print t('Set the maximum number of attachments.')?>',
        tourist_perjob_title = '<?php       print t('Send by "<a href="/dashboard/system/optimization/jobs">%s</a>" or directly', t('Automated Jobs')) ?>',
        tourist_perjob_text = '<?php       print t('Define if a Newsletter should be sent by "%s" or directly.', t('Automated Jobs'))?>',
        tourist_is_perjob_title = "<?php       print t("Don't forget to install your cron job!") ?>",
        tourist_mailsper_title = '<?php       print t('Number per time unit') ?>',
        tourist_mailsper_text = '<?php       print t('Set the number of mails being sent per time unit') ?>',
        tourist_cronjob_title = '<?php       print t('Cronjob') ?>',
        tourist_cronjob_text = '<?php       print t('You may hand this command to your provider or webmaster.') ?>',
        tourist_time_unit_title = '<?php       print t('Time unit') ?>',
        tourist_time_unit_text = '<?php       print t('Set the time unit for number of mails set in step 3') ?>',
        tourist_report_title = '<?php       print t('Newsletter-report per email') ?>',
        tourist_report_text = '<?php       print t('Would you like to receive a report about this Newsletter?') ?>',
        tourist_mailowner_title = '<?php       print t('Email address') ?>',
        tourist_mailowner_text = '<?php       print t('The senders email address. "From" and "Reply to"') ?>',
        steps_job = [
            {
            content: '<p><span class="h5">' + window.tourist_attachments_title + '</span>:<br/>' + window.tourist_attachments_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('#files_num'),
            my: 'top center',
            at: 'bottom center'
        },
            {
                content: '<p><span class="h5">' + window.tourist_perjob_title + '</span>:<br/>' + window.tourist_perjob_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#per_job'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_mailsper_title + '</span>:<br/>' + window.tourist_mailsper_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#mails_per'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_time_unit_title + '</span>:<br/>' + window.tourist_time_unit_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#time_unit'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_cronjob_title + '</span>:<br/>' + window.tourist_cronjob_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#cron_job_path'),
                my: 'bottom right',
                at: 'bottom right'
            },
            {
                content: '<p><span class="h5">' + window.tourist_report_title + '</span>:<br/>' + window.tourist_report_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[name="report_newsletter"]'),
                my: 'left bottom',
                at: 'left bottom'
            },
            {
                content: '<p><span class="h5">' + window.tourist_mailowner_title + '</span>:<br/>' + window.tourist_mailowner_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#owner_email'),
                my: 'bottom center',
                at: 'top center'
            }],
        steps_direct = [            {
            content: '<p><span class="h5">' + window.tourist_attachments_title + '</span>:<br/>' + window.tourist_attachments_text + '</p>',
            highlightTarget: true,
            nextButton: true,
            target: $('#files_num'),
            my: 'top center',
            at: 'bottom center'
        },
            {
                content: '<p><span class="h5">' + window.tourist_perjob_title + '</span>:<br/>' + window.tourist_perjob_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#per_job'),
                my: 'top center',
                at: 'bottom center'
            },
            {
                content: '<p><span class="h5">' + window.tourist_report_title + '</span>:<br/>' + window.tourist_report_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('[name="report_newsletter"]'),
                my: 'left bottom',
                at: 'left bottom'
            },
            {
                content: '<p><span class="h5">' + window.tourist_mailowner_title + '</span>:<br/>' + window.tourist_mailowner_text + '</p>',
                highlightTarget: true,
                nextButton: true,
                target: $('#owner_email'),
                my: 'bottom center',
                at: 'top center'
            }];
</script>
