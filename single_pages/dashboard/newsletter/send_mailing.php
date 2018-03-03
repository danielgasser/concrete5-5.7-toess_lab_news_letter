<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/send_newsletter.php
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
$header_tabs = array(
    array('general', t('General'), true),
    array('tpl_nwsl', t('Template/Newsletter')),
    array('groups', t('User groups')),
    array('mail', t('Send Test Message')),
);
$test_email = \Config::get('toess_lab_news_letter.mailing.settings.test_email_address');
?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('send_or_prepare_newsletter')?>",
        get_tpl_for_nl = '<?php       print $this->action('get_template_id_for_newsletter')?>',
        get_newsletter_attachments = '<?php       print $this->action('get_newsletter_attachments') ?>',
        subject = '<?php       print t('Subject') ?>',
        selected_users_in_group = '<?php       print t('Selected users') . ': '; ?>',
        no_users_selected = '<?php       print t('No users selected'); ?>',
        no_users_attribute_newsletter = '<?php       print t('No users have the user attribute <a href="%s">Receive toesslab - Newsletter</a> selected', URL::to('/dashboard/users/attributes')); ?>',
        selected_records = '<?php       print '<span id="selectedRecords">' . $totalRecords . '</span>/' ?>',
        total_records = '<?php       print '<span id="totalRecords">' . $totalRecords . '</span>' ?>',
        chosen_emails = '<?php       print json_encode($chosen_emails) ?>',
        user_group_url = '<?php       print $this->action('change_user_group')?>',
        max_files = '<?php       print $min_max['max_files']; ?>',
        send_test_mail = '<?php       print $this->action('send_test_mail')?>',
        str_attach_more = '<?php       print t('more attachments posssible');  ?>',
        mail_success_title = '<?php       print t('Success') ?>',
        invalid_email = '<?php       print t('Invalid email address') ?>',
        empty_newsletter_title = '<?php       print t('Warning') ?>',
        get_template = '<?php       print $controller->action('get_newsletter')?>';
</script>
<script>
(function ($) {
    "use strict";
    jQuery(document).ready(function () {
        $('input[name^="chooseGroup"]').bootstrapSwitch({
            labelWidth: '200'

        });
        jQuery('li>a[data-tab="mail"]').parent().css('float', 'right');
    });
}(jQuery));

</script>
<div id="dialog_delete" style="display: none"><?php       print t('Are you sure you want to permanently remove this Newsletter?')?></div>
<div id="dialog_save_first" style="display: none"><?php       print t('Please choose a Newsletter to send')?></div>
<div id="dialog_valid_email" style="display: none"><?php       print t('Enter a valid email address first!')?></div>
<div id="dialog_email_success" style="display: none"><div id="dialog_tpl_first_text"></div></div>
<div id="dialog_tpl_first" style="display: none"><div id="dialog_tpl_first_text"></div></div>

<div class="clearfix">
    <?php
    if($session->has('already_sent')) {
        $m = $session->get('already_sent');
        ?>
        <div id="already_sent" class="alert alert-info">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php
    }
    $session->remove('already_sent');
    ?>
    <div id="show_mail_error" class="alert alert-info">
        <a href="#" class="close">&times;</a>
        <div id="show_mail_error_text">
        <?php       print $error_mail ?>
        </div>
    </div>
    <div id="notSent">
        <?php
        print \Core::make('helper/concrete/ui')->tabs($header_tabs);

        ?>
        <form id="newsletter_form" role="form" method="post" action="<?php       print $controller->action('send_or_prepare_newsletter')?>" class="form-horizontal">
            <?php       print $this->controller->token->output('send_or_prepare_newsletter'); ?>
            <div id="ccm-tab-content-general" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span class="input-group-addon"><?php       print $controller->ns_handle ?></span>
                                <?php
                                print $form->text('ns_handle', $ns_handle, array('placeholder' => $controller->ns_handle, 'style' => 'width: 100%'));
                                ?>
                                <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-tpl_nwsl" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group choose_nl_tl">
                                    <span class="input-group-addon"><?php       print t('Choose a Newsletter') ?></span>
                                    <?php
                                    print $form->select('newsletter', array('0' => t('Please choose')) + $newsletters);
                                    ?>
                                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group choose_nl_tl">
                                    <span class="input-group-addon"><?php       print t('Template') ?>:</span>
                                    <?php
                                    print $form->text('template_handle', array('disabled' => 'disabled'));
                                    ?>
                                    <?php
                                    print $form->hidden('template');
                                    ?>
                                    <div id="template_handle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group" id="attachments_for_newsletter">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mail_template_border" class="ccm-ui">
                        <div id="tpl_fixed"></div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-groups" class="ccm-tab-content">
                <fieldset>
                    <legend><?php       print t('Send the Newsletter to the following user group') ?>
                    </legend>
                    <div class="alert alert-info">
                        <div>
                            <?php       print t('Users with the User Attribute <a href="%s">\'Receive toesslab - Newsletter\'</a> set to \'No\' will not appear in this list', URL::to('/dashboard/users/attributes')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text">
                            <label class="control-label" for="chooseGroup" name="chooseGroup"><?php
                                print t('User group');
                                ?>
                            </label>
                            <?php
                            print '<br>';
                            ?>
                            <div class="row">
                                    <?php
                            $i = 0;
                            foreach($possibleGroups as $key => $ps) {
                                if ($i % 4 == 0){
                                    print '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text">';
                                }
                                print $form->checkbox('chooseGroup[]', $key, false, array('data-label-text' => $ps, 'data-size' => 'normal', 'data-first' => ($i == 0)));
                                print '<br>';
                                $i++;
                                if ($i % 4 == 0 && $i > 0){
                                    print '</div>';
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" for="totalText"><?php
                                print t('Selected users');
                                ?>
                            </label>
                            <ul id="userRecords">
                                <li>
                                    <?php
                                    print '<span id="selectedText"></span><span id="selectedRecords"></span>';
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="my-legend"></div>
                        <div class="table-responsive" data-search-element="results">
                            <table id="userRecipientList" class="table ccm-search-results-table" data-search="users">
                                <thead>
                                <tr>
                                    <?php
                                    print $isSortedBy;
                                    ?>
                                    <th class="col-lg-1 col-md-1 col-sm-1 col-xs-12"><?php       print t('send?') ?><input type="checkbox" data-search-checkbox="select-all" class="ccm-flat-checkbox"></th>
                                    <th class="col-lg-1 col-md-1 col-sm-1 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uName"><?php       print t('User name') ?></a></th>
                                    <!--th class="col-lg-1 col-md-1 col-sm-1 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uReceiveNewsletter"><?php       print t('Wants to receive Newsletter') ?></a></th-->
                                    <th class="col-lg-1 col-md-1 col-sm-1 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uAddress"><?php       print t('User address') ?></a></th>
                                    <th class="col-lg-3 col-md-3 col-sm-3 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uFirstName"><?php       print t('User first name') ?></a></th>
                                    <th class="col-lg-3 col-md-3 col-sm-3 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uLastName"><?php       print t('User last name') ?></a></th>
                                    <th class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ccm-results-list-active-sort-asc"><a href="#" data-is-sorted="" data-sort="asc" class="sort_it" data-prop="uEmail"><?php       print t('Email') ?></a></th>
                                </tr>
                                </thead>
                                <tbody id="userList">

                                <?php
                                if (sizeof($totalRecords) > 0){
                                    foreach($users as $u) { ?>
                                    <tr>
                                        <td>
                                            <?php
                                            if(\Core::make('helper/security')->sanitizeEmail($u->uEmail)){
                                                print $form->checkbox('uEmail[]', $u->uEmail, true, array('data-label-text' => $u->uEmail, 'data-size' => 'normal', 'class' => 'ccm-flat-checkbox'));
                                            } else {
                                                print $form->text('error', t('No valid email'), false, array('data-label-text' => $u->uEmail, 'data-size' => 'normal', 'class' => 'ccm-flat-checkbox'));
                                            }
                                            print $form->hidden('uID[]', $u->uID);
                                            print $form->hidden('uAddress[]', $u->uAddress);
                                            print $form->hidden('uFirstName[]', $u->uFirstName);
                                            print $form->hidden('uLastName[]', $u->uLastName);
                                            ?>
                                        </td>
                                        <td>
                                            <?php       print  '<a href="mailto:' . $u->uEmail . '">' . $u->uEmail . '</a>'; ?>
                                        </td>
                                    </tr>
                                        <?php
                                        }
                                }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            print $form->hidden('page', \Concrete\Core\Page\Page::getCurrentPage()->getCollectionPath());
                            print $form->hidden('files_num', $files_num);
                            print $form->hidden('mail_logo', $mail_logo);
                            print $form->hidden('all_Emails');

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ccm-tab-content-mail" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Recipient') ?></span>
                                    <?php
                                    print $form->email('test_mail', $test_email);
                                    ?>
                                    <span class="input-group-addon"><button id="go_test_mail"><i
                                                class="fa fa-envelope-o"></i></button></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <button style="display: none" id="preview_go_fixed" class="pull-left btn btn-default" name="preview_go_fixed" class="btn btn-primary"><?php       echo t('Preview') ?></button>
                    <button id="Send" name="Send" class="pull-right btn btn-primary" type="submit" ><?php       echo t('Send Newsletter')?></button>
                </div>
            </div>
            <?php
            print $form->hidden('isSorted', $isSorted);
            print $form->hidden('isSortedBy', $isSortedBy);
            ?>

        </form>
    </div>
</div>
<script>
    var tourist_newsletter_name_title = '<?php       print $controller->ns_handle?>',
        tourist_newsletter_name_text = '<?php       print t('The name or handle is used to identify this Newsletter')?>',
        tourist_group_title = '<?php       print t('User group')?>',
        tourist_group_text = '<?php       print t('The user group this Newsletter will be sent to')?>',
        tourist_choose_newsletter_title = '<?php       print t('Choose %s', t('Newsletter'))?>',
        tourist_choose_newsletter_text = '<?php       print t('Choose an existing Newsletter or create a new one by clicking Add %s', t('Newsletter'))?>',
        tourist_choose_template_title = '<?php       print t('Choose %s', t('Template'))?>',
        tourist_choose_template_text = '<?php       print t('Choose an existing Template or create a new one by clicking Add %s', t('Template'))?>',
        tourist_message_title = '<?php       print t('Send test message') ?>',
        tourist_message_text = '<?php       print t('Send a test message to the recipient entered here')?>',
        tourist_save_title = '<?php       print t('Send') ?>',
        tourist_save_text = '<?php       print t('Send the %s.', t('Newsletter'))?>';
</script>
