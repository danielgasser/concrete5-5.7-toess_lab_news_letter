<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/newsletters/new_newsletter.php
 */

?>
<?php
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$cdh  = \Core::make('helper/concrete/ui');
$form = \Core::make('helper/form');
$v = \Core::make('helper/file');
$page = \Core::make('helper/form/page_selector');
$al = \AssetList::getInstance();
$c5Version = intval(\Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup::checkC5Version());
if (!$c5Version) {
    $this->requireAsset('redactor');
    $this->requireAsset('core/file-manager');
}
$path = $c->getCollectionPath();
$str_attach_max = t('You may attach up to %s files', $files_num);
$editor = Core::make('editor');
if (!$c5Version) {
    $editor->setAllowFileManager(false);
    $editor->getPluginManager()->select('fontsize');
    $editor->getPluginManager()->select('fontcolor');
    //$editor->getPluginManager()->select('googlefontfamily');
    //$editor->getPluginManager()->select('toesslabevents');
}
$session = \Core::make('session');
$test_email = \Config::get('toess_lab_news_letter.settings.newsletters.test_email_address');
if (strpos($_SERVER['REQUEST_URI'], 'edit') === false) {
    $header_tabs = array(
        array('general', t('General'), true),
        array('header', t('Head Section Content')),
        array('body', t('Body Section Content')),
        array('footer', t('Footer Section Content')),
        array('attachments', t('Attachments')),
        array('mail', t('Send Test Message')),
    );
} else {
    $header_tabs = array(
        array('general', t('General')),
        array('header', t('Head Section Content')),
        array('body', t('Body Section Content')),
        array('footer', t('Footer Section Content')),
        array('attachments', t('Attachments')),
        array('mail', t('Send Test Message')),
    );
}
if(strpos($_SERVER['REQUEST_URI'], 'edit') !== false) {
    $header_tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($header_tabs);
}

?>
<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php       print \Core::make('helper/validation/token')->generate('save_newsletter')?>",
        subject ='<?php       print $controller->nl_subject?>',
        edit_page_title = '<?php       print t('Edit Newsletter')?>',
        edit_true = '<?php       (strpos($_SERVER['REQUEST_URI'], 'edit') !== false) ? print 1 : print 0 ?>',
        send_test_mail = '<?php       print $this->action('send_test_mail')?>',
        font_family = <?php       print json_encode($family_font) ?>,
        no_preview = '<?php       print t('Please save Newsletter to preview.')?>',
        get_template = '<?php       print $controller->action('get_template')?>',
        input_contents = {},
        redactorInstances = [],
        empty_newsletter_title = '<?php       print t('Warning') ?>',
        mail_success_title = '<?php       print t('Success') ?>',
        empty_newsletter_yes = '<?php       print t('Save Newsletter anyway') ?>',
        delete_title = '<?php       print t('Warning') ?>',
        delete_yes = '<?php       print t('Delete') ?>',
        delete_no = '<?php       print t('Do not delete') ?>',
        C5_VERSION = parseInt('<?php echo $c5Version ?>', 10),
        empty_newsletter_no = '<?php       print t('Continue editing') ?>',
    insert_Link = '<?php echo t('Insert') ?>',
        setUnSubLinksLegacy = function (s, r, val) {
            var content = r.code.get(),
                new_content = '',
                value = val,
                tags = [];
            if (s) {
                $('[name="header_text"]').focus();
                r.insert.html(value, false);
            } else {
                tags = $(content).find('[class="' + value[1] + '"]');
                $.each(tags, function (i, n) {
                    new_content = content.replace(n.outerHTML, '');
                })
                r.code.set(new_content);
            }
        },
        setUnSubLinks = function (s, r, val) {
            if (C5_VERSION === 0) {
                setUnSubLinksLegacy(e, s, r, c, val);
                return false;
            }
            var content = r.getData(),
                new_content = '',
                value = val,
                tags = [];
            if (s) {
                $('[name="header_text"]').focus();
                r.insertHtml(value, false);
            } else {
                tags = $(content).find('[class="' + value[1] + '"]');
                $.each(tags, function (i, n) {
                    new_content = content.replace(n.outerHTML, '');
                });
                r.setData(new_content);
            }
        };
</script>
<script>
(function ($) {
    "use strict";
    jQuery(document).ready(function () {
        $('input[name^="content_address_select"]').bootstrapSwitch({
            labelWidth: '200'
        });
        $('input[name^="head_address_select"]').bootstrapSwitch({
            labelWidth: '200'
        });
        $('input[name^="foot_address_select"]').bootstrapSwitch({
            labelWidth: '200'
        });
        $('input[name^="head_social_links"]').bootstrapSwitch({
            labelWidth: '200'
        });
        $('input[name^="body_social_links"]').bootstrapSwitch({
            labelWidth: '200'
        });
        $('input[name^="foot_social_links"]').bootstrapSwitch({
            labelWidth: '200'
        });
        if ($('#nl_handle').val().length === 0) {
            $('#mail_template_border').html(window.no_preview);
        }
        $('#nl_template_preview').val($('#nl_template').val());
        $('#nl_template_preview').trigger('change');
        jQuery('li>a[data-tab="mail"]').parent().css('float', 'right');
        jQuery('input, textarea').each(function (i, n) {
            window.input_contents[n.name] = n.value;
        });
        if (window.edit_true === '1') {
            jQuery('.ccm-dashboard-page-header>h1').text(window.edit_page_title);
        }
        $(document).on('submit', 'form', function (e) {
            var arr = [];
            e.preventDefault();
            $('[data-tab]').parents('li').each(function (i, n) {
                if ($(n).hasClass('active')) {
                    arr.push($(n).children('a').attr('data-tab'));
                }
            });
            $('#selected_tabs').val(arr.join(','));
            $.each(arr, function (i, n) {

            });
            this.submit();
        });
        window.ConcreteEvent.bind('PageSelectorClose', function(e) {
            e.preventDefault();
            var cID,
            cName;
            if(window.C5_VERSION > 0 && location.href.indexOf('new_newsletter') > -1) {
                var redactor = window.CKEDITOR.instances;
                for(var i in redactor) {
                    // ToDo redactor[i].config.extraPlugins = 'ck4_googlefontfamily';
                    redactorInstances.push(redactor[i]);
                }
            } else {
                redactorInstances = window.redactorInstances;
            }
            window.setTimeout(function () {
                var tab = $('li.active>a').attr('data-tab');
                cID = $('input[name="FootLinkToUnsubScribePage"]').val();
                cName = $('.ccm-item-selector-item-selected-title').text();
                $('#un_sub_link').attr('data-linktab', tab).append('<div id="un_sub_link_container" style="margin-top: 1em"><button class="btn btn-default" id="InsertUnSubLink">' + insert_Link + ': <span id="LinkToInsert"><a href="/index.php?cID=' + cID + '&email={{uEmail}}">' + cName + '</a></span></button></div>');
            }, 300);
        });
        $(document).on('click', '#un_sub_link', function (e) {
            e.preventDefault();
            var r;
            if($('#un_sub_link').attr('data-linktab') === 'header') {
                r = redactorInstances[0];
            }
            if($('#un_sub_link').attr('data-linktab') === 'body') {
                r = redactorInstances[1];
            }
            if($('#un_sub_link').attr('data-linktab') === 'footer') {
                r = redactorInstances[2];
            }
            setUnSubLinks(true, r, $('#LinkToInsert').html());
        });
        $(document).on('click', 'a[data-page-selector-action=clear]', function (e) {
            e.preventDefault();
            $('#un_sub_link_container').remove();
        });
    });
}(jQuery));

</script>
<div id="add_new" class="ccm-dashboard-header-buttons btn-group">
    <?php
    if($newsletter != NULL){
        ?>
        <a href="<?php       print $view->action('duplicate_newsletter', array($newsletter->getNewsLetterId(), $newsletter->getNLHandle()))?>" type="submit" id="duplicate_newsletter" name="duplicate_newsletter" class="btn btn-default"><?php       echo t('Duplicate') ?></a>
        <a href="<?php       print $view->action('delete_newsletter', array($newsletter->getNewsLetterId(), $newsletter->getNLHandle()))?>" type="submit" id="delete_newsletter" name="delete_newsletter" class="btn btn-danger"><?php       echo t('Delete') ?></a>
    <?php       }
    ?>
</div>

<div class="ccm-ui save_tpl_msg_div" id="ccm-dashboard-result-message" style="display: none">
    <div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button><span id="save_tpl_msg"></span></div>
</div>
<div id="dialog_valid_email" style="display: none"><?php       print t('Enter a valid email address first!')?></div>
<div id="dialog_save_first" style="display: none"><?php       print t('Please save the Newsletter first!')?></div>
<div id="dialog_email_success" style="display: none"><div id="dialog_tpl_first_text"></div></div>
<div id="dialog_delete" style="display: none"><?php       print t('Are you sure you want to permanently remove this Newsletter?')?></div>

<div class="clearfix">
    <?php
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
    ?>
    <?php
    if($session->has('google_font_error')) {
        $m = $session->get('google_font_error');
        ?>
        <div id="already_sent" class="alert alert-info">
            <a href="#" class="close">&times;</a>

            <div id="show_mail_error_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php
    }
    $session->remove('google_font_error');
    ?>
    <?php
    print \Core::make('helper/concrete/ui')->tabs($header_tabs);

    ?>
        <form id="new_newsletter" role="form" method="post" action="<?php       print $controller->action('save_newsletter')?>" class="form-horizontal" novalidate>
            <?php       print $this->controller->token->output('save_newsletter');
            print $form->hidden('selected_tabs', '');
            ?>
            <div id="ccm-tab-content-general" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print $controller->nl_handle ?></span>
                                    <?php
                                    print $form->text('nl_handle', ($newsletter != NULL) ? \Core::make('helper/text')->entities($newsletter->getNLHandle()) : '', array('placeholder' => $controller->nl_handle, 'style' => 'width: 100%'));
                                    ?>
                                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Newsletter subject') ?></span>
                                    <?php
                                    print $form->text('nl_subject', ($newsletter != NULL) ? \Core::make('helper/text')->entities($newsletter->getNLSubject()) : '', array('placeholder' => $controller->nl_subject, 'style' => 'width: 100%'));
                                    ?>
                                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print $controller->nl_template ?></span>
                                    <?php
                                    print $form->select('nl_template', $mail_templates, ($newsletter != NULL) ? $template_id : 'xxx', array('placeholder' => $controller->nl_template));
                                    ?>
                                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                                </div>
                            </div>
                        </div>

                        <?php
                        print $form->hidden('newsletter_id', $newsletter_id);
                        ?>
                        <?php
                        print $form->hidden('newsletter_template_id', $template_id);
                        ?>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-header" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Head Section Text') ?>
                    </legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <div>
                                    <?php
                                    print $editor->outputStandardEditor('header_text', ($newsletter != NULL) ? stream_get_contents($newsletter->getHeaderText()) : $content);
                                    ?>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text">
                                <label class="control-label" for="head_address_select"><?php
                                    print t('Choose User Attributes to insert');
                                    ?>
                                    <i id="info_user_attributes_insert" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_user_attributes_insert_text" class="alert alert-info"><span><?php       print t('Click desired attribute to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $head_chosen = ($head_address_select_chosen == null) ? array() : $head_address_select_chosen;
                                    foreach($address_attributes as $key => $ps) {
                                        print $form->checkbox('head_address_select[]', $key, in_array($key, $head_chosen), array('data-label-text' => $ps, 'data-size' => 'normal'));
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="head_address_select"><?php
                                    print t('Choose Social Links to insert');
                                    ?>
                                    <i id="info_social_links_insert" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_social_links_insert_text" class="alert alert-info"><span><?php       print t('Click desired attribute to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $head_social_chosen = ($head_social_links_chosen == null) ? array() : $head_social_links_chosen;
                                    foreach($social_links as $key => $sl) {
                                        ?>

                                        <input type="checkbox" id="head_social_links_<?php       print $sl['handle'] ?>" name="head_social_links[]" data-label-text="<?php       print str_replace('"', '\'', $sl['icon']) . ' ' . $sl['name']?>" data-size="normal" value="<?php       print str_replace('"', '\'', $sl['icon']) . '|' . $sl['handle'] . '|' . $sl['name'] . '|' . $sl['link']?>" <?php       (in_array($sl['handle'], $head_social_chosen)) ? print 'checked="checked"' : ''?>>
                                        <?php
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="FootLinkToUnsubScribePage"><?php
                                    print t('Unsubscribe-Link');
                                    ?>
                                    <i id="info_unsubscribe_link_insert" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_unsubscribe_link_insert_text" class="alert alert-info"><span id="save_tpl_msg"><ol><li><?php print t('Choose the Page where the \'Un/Subscribe from toesslab - Newsletter\' Block is placed.') ?></li><li><?php print t('Place the cursor at the desired position in the Text Section below.') ?></li><li><?php print t('Then click \'Insert\' to place the link at the cursors position.') ?></li><li><?php print t('Important! Do NOT change the URL of the link. Otherwise the Un/subscribe-Link won\'t work.') ?></li></ol></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="row">
                                    <?php
                                    echo $page->selectPage('FootLinkToUnsubScribePage', null, array('id' => 'FootLinkToUnsubScribePage'));
                                    ?>
                                </div>
                                <div class="row" id="un_sub_link">

                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-body" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Body Section Text') ?>
                    </legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <div>
                                    <?php
                                    print $editor->outputStandardEditor('content', ($newsletter != NULL) ? stream_get_contents($newsletter->getMailText()) : $content);
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text">
                                <label class="control-label" for="content_address_select"><?php
                                    print t('Choose User Attributes to insert');
                                    ?>
                                    <i id="info_user_attributes_insert_body" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_user_attributes_insert_body_text" class="alert alert-info"><span><?php       print t('Click desired attribute to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $body_chosen = ($body_address_select_chosen == null) ? array() : $body_address_select_chosen;
                                    foreach($address_attributes as $key => $ps) {
                                        print $form->checkbox('content_address_select[]', $key, in_array($key, $body_chosen), array('data-label-text' => $ps, 'data-size' => 'normal'));
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="content_address_select"><?php
                                    print t('Choose Social Links to insert');
                                    ?>
                                    <i id="info_social_links_insert_body" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_social_links_insert_body_text" class="alert alert-info"><span><?php       print t('Click desired Social Link to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $body_social_chosen = ($body_social_links_chosen == null) ? array() : $body_social_links_chosen;
                                    foreach($social_links as $key => $sl) {
                                        ?>
                                        <input type="checkbox" id="body_social_links_<?php       print $sl['handle'] ?>" name="body_social_links[]" data-label-text="<?php       print str_replace('"', '\'', $sl['icon']) . ' ' . $sl['name']?>" data-size="normal" value="<?php       print str_replace('"', '\'', $sl['icon']) . '|' . $sl['handle'] . '|' . $sl['name'] . '|' . $sl['link']?>" <?php       (in_array($sl['handle'], $body_social_chosen)) ? print 'checked="checked"' : ''?>>
                                        <?php
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="BodyLinkToUnsubScribePage"><?php
                                    print t('Unsubscribe-Link');
                                    ?>
                                    <i id="info_unsubscribe_link_insert_body" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_unsubscribe_link_insert_body_text" class="alert alert-info"><span id="save_tpl_msg"><ol><li><?php print t('Choose the Page where the \'Un/Subscribe from toesslab - Newsletter\' Block is placed.') ?></li><li><?php print t('Place the cursor at the desired position in the Text Section below.') ?></li><li><?php print t('Then click \'Insert\' to place the link at the cursors position.') ?></li><li><?php print t('Important! Do NOT change the URL of the link. Otherwise the Un/subscribe-Link won\'t work.') ?></li></ol></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="row">
                                    <?php
                                    echo $page->selectPage('BodyLinkToUnsubScribePage', null, array('id' => 'BodyLinkToUnsubScribePage'));
                                    ?>
                                </div>
                                <div class="row" id="un_sub_link">

                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-footer" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Footer Section Text') ?>
                    </legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <div>
                                    <?php
                                    print $editor->outputStandardEditor('footer', ($newsletter != NULL) ? stream_get_contents($newsletter->getMailTextFooter()) : $footer);
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text">
                                <label class="control-label" for="foot_address_select"><?php
                                    print t('Choose User Attributes to insert');
                                    ?>
                                    <i id="info_user_attributes_insert_foot" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_user_attributes_insert_foot_text" class="alert alert-info"><span><?php       print t('Click desired attribute to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $foot_chosen = ($foot_address_select_chosen == null) ? array() : $foot_address_select_chosen;
                                    foreach($address_attributes as $key => $ps) {
                                        print $form->checkbox('foot_address_select[]', $key, in_array($key, $foot_chosen), array('data-label-text' => $ps, 'data-size' => 'normal'));
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="foot_address_select"><?php
                                    print t('Choose Social Links to insert');
                                    ?>
                                    <i id="info_social_links_insert_foot" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_social_links_insert_foot_text" class="alert alert-info"><span><?php       print t('Click desired Social Link to be inserted at the cursors position.') ?></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="flow-it">
                                    <?php
                                    $foot_social_chosen = ($foot_social_links_chosen == null) ? array() : $foot_social_links_chosen;
                                    foreach($social_links as $key => $sl) {
                                        ?>
                                        <input type="checkbox" id="foot_social_links_<?php       print $sl['handle'] ?>" name="foot_social_links[]" data-label-text="<?php       print str_replace('"', '\'', $sl['icon']) . ' ' . $sl['name']?>" data-size="normal" value="<?php       print str_replace('"', '\'', $sl['icon']) . '|' . $sl['handle'] . '|' . $sl['name'] . '|' . $sl['link']?>" <?php       (in_array($sl['handle'], $foot_social_chosen)) ? print 'checked="checked"' : ''?>>
                                        <?php
                                        print '<br>';
                                    }
                                    ?>
                                </div>
                                <label class="control-label" for="FootLinkToUnsubScribePage"><?php
                                    print t('Unsubscribe-Link');
                                    ?>
                                    <i id="info_unsubscribe_link_insert_foot" class="fa fa-question-circle" aria-hidden="true"></i>
                                </label>
                                <div id="info_unsubscribe_link_insert_foot_text" class="alert alert-info"><span id="save_tpl_msg"><ol><li><?php print t('Choose the Page where the \'Un/Subscribe from toesslab - Newsletter\' Block is placed.') ?></li><li><?php print t('Place the cursor at the desired position in the Text Section below.') ?></li><li><?php print t('Then click \'Insert\' to place the link at the cursors position.') ?></li><li><?php print t('Important! Do NOT change the URL of the link. Otherwise the Un/subscribe-Link won\'t work.') ?></li></ol></span></div>
                                <?php
                                print '<br>';
                                ?>
                                <div class="row">
                                    <?php
                                    echo $page->selectPage('FootLinkToUnsubScribePage', null, array('id' => 'FootLinkToUnsubScribePage'));
                                    ?>
                                </div>
                                <div class="row" id="un_sub_link">

                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-attachments" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Attachments') ?>
                    </legend>
                    <span style=""><?php       print $str_attach_max; ?></span>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <?php
                                    $fl = ($newsletter != NULL) ? \Core::make('helper/text')->entities($newsletter->getAttachments()) : 0;
                                    $files = explode(',', $fl);
                                    $i = 0;
                                    while($i < $files_num) {
                                        ?>
                                        <div id="chooseFile_<?php       print $i ?>" class="file-col">
                                            <?php
                                            $html = Core::make('helper/concrete/file_manager');
                                            $file = (isset($files[$i])) ? \Concrete\Core\File\File::getByID($files[$i]) : '';
                                            print $html->file('attachment_' . $i, 'attachment_' . $i, '', $file);
                                            ?>
                                        </div>
                                        <?php
                                        $html = '';
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
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
                            <div class="col-sm-6">
                                <div id="mail_sent_alert" class="alert alert-info" style="display: none"><button type="button" class="close" data-dismiss="alert">×</button><span id="mail_sent_msg"></span></div>
                                <div id="mail_not_sent_alert" class="alert alert-danger" style="display: none"><button type="button" class="close" data-dismiss="alert">×</button><span id="mail_not_sent_msg"></span></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <button id="Send_new_newsletter" name="Send_new_newsletter" class="pull-right btn btn-primary" type="submit" ><?php       echo t('Save Newsletter')?></button>
                </div>
            </div>


        </form>
    <div id="empty_newsletter" style="display: none">
        <div>
                <?php       print t('You are about to save an empty Newsletter. Are you sure you want to save it?') ?>
        </div>
    </div>
    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ccm-ui legend">
                <legend><?php       print t('Preview') ?></legend>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div>
                    <div id="mail_template_border" class="ccm-ui">
                        <form role="form" class="form-inline" method="get">
                        <?php
                        print $form->select('nl_template_preview', $mail_templates, '0');
                        ?>
                        </form>
                        <div id="tpl"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
