<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/templates/new_template.php
 */

$form = \Core::make('helper/form');
$session = \Core::make('session');
$color = Core::make('helper/form/color');
$test_email = \Config::get('toess_lab_news_letter.settings.templates.test_email_address');
if (strpos($_SERVER['REQUEST_URI'], 'edit') === false) {
    $tabs = array(
        array('general', t('General'), true),
        array('header', t('Head Section')),
        array('body', t('Body Section')),
        array('footer', t('Footer Section')),
        array('titles', t('Headers')),
        array('mail', t('Send Test Message')),
    );
} else {
    $tabs = array(
        array('general', t('General')),
        array('header', t('Head Section')),
        array('body', t('Body Section')),
        array('footer', t('Footer Section')),
        array('titles', t('Headers')),
        array('mail', t('Send Test Message')),
    );

}
$header_tabs = array(
    array('header-image',t('Image')),
    array('header-background', t('Background')),
    array('header-padding', t('Padding')),
    array('header-margin', t('Margin')),
    array('header-border', t('Borders'))
);
$body_tabs = array(
    array('body-background', t('Background')),
    array('body-padding', t('Padding')),
    array('body-margin', t('Margin')),
    array('body-border', t('Borders'))
);
$footer_tabs = array(
    array('footer-background', t('Background')),
    array('footer-padding', t('Padding')),
    array('footer-margin', t('Margin')),
    array('footer-border', t('Borders'))
);
$title_tabs = array(
    array('title-one', t('Header %s', t('1'))),
    array('title-two', t('Header %s', t('2'))),
    array('title-three', t('Header %s', t('3'))),
    array('title-four', t('Header %s', t('4'))),
    array('title-five', t('Header %s', t('5')))
);
if(strpos($_SERVER['REQUEST_URI'], 'edit') !== false) {
    $tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($tabs);
    $header_tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($header_tabs);
    $body_tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($body_tabs);
    $footer_tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($footer_tabs);
    $title_tabs = \Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter::setSelectedTabs($title_tabs);
}
$u = new \Concrete\Core\Application\Service\FileManager();
?>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    var CCM_EDITOR_SECURITY_TOKEN = '<?php       print \Core::make('helper/validation/token')->generate('save_template')?>',
        change_template = '<?php       print $this->action('save_template')?>',
        get_image_dimensions = '<?php       print $this->action('get_image_dimensions')?>',
        concrete_config = '<?php       print $this->action('save_dialog_config')?>',
        edit_page_title = '<?php       print t('Edit Template')?>',
        edit_true = '<?php       (strpos($_SERVER['REQUEST_URI'], 'edit') !== false) ? print 1 : print 0 ?>',
        preview_check = "<?php       print t("Don't show this Help anymore") ?>",
        title = '<?php       print t('Styling Help') ?>',
        mail_success_title = '<?php       print t('Success') ?>',
        send_test_mail = '<?php       print $this->action('send_test_mail')?>',
        get_template = '<?php       print $controller->action('get_newsletter')?>',
        show_help = '<?php       (\Config::get('toess_lab_news_letter.dialogs.styling_dialog_show')) ? print 1 : print 0?>',
        showHelpDialog = function () {
            jQuery('#help_dialog').dialog({
                modal: true,
                title: window.title,
                width: 800,
                height: 550,
                open: function () {
                    $(this).scrollTop(0);
                    if (jQuery('#dialog_show_c').length === 0) {
                        jQuery('.ui-dialog-buttonpane').append('<div id="dialog_show_c"  class="checkbox"><label><input id="styling_dialog_show" type="checkbox" value="1"/>' + window.preview_check + '</label></div>');
                    }

                },
                close: function () {
                    jQuery(this).find('div').eq(0).html('');
                    jQuery(this).find('select').eq(0).val('xxx');
                },
                closeOnEscape: true,
                buttons: {
                    'Ok': function () {
                        $(this).dialog("close");
                        jQuery.ajax({
                            url: window.concrete_config,
                            data: {
                                styling_dialog_show: jQuery('#styling_dialog_show:checked').val()
                            },
                            success: function (data) {
                                jQuery('#add_new').show();
                            }
                        })

                    }
                }
            });
        },
        input_contents = {},
        getFileDimensions = function (fid) {
            $.ajax({
                url: get_image_dimensions,
                method: 'GET',
                data: {
                    fileID: fid
                },
                success: function (file) {
                    var dim = $.parseJSON(file);
                    $('#logo_width').val(dim.width);
                    $('#logo_height').val(dim.height);
                    $('#logo_width_odd').val(dim.width);
                    $('#logo_height_odd').val(dim.height);
                }
            })

        },
        resizeImage = function (w) {
            var im_width = parseInt($('#logo_width_odd').val(), 10),
                im_height = parseInt($('#logo_height_odd').val(), 10),
                factor = (im_width > im_height) ? im_width / im_height : im_height / im_width,
                new_width = parseInt($('#logo_width').val(), 10),
                new_height = parseInt($('#logo_height').val(), 10);
            if (w) {
                new_height = new_width / factor;
                $('#logo_height').val(parseInt(new_height, 10));
            } else {
                new_width = new_height * factor;
                $('#logo_width').val(parseInt(new_width, 10));
            }
        };
(function ($) {
    "use strict";
    $(document).ready(function () {
        $.each($('[name*="_border_"][name$="_style"]'), function (i, n) {
            $(n).trigger('change');
        });
        if (window.show_help === '1') {
            jQuery('#add_new').hide();
            showHelpDialog();
        } else {
            jQuery('#add_new').show();
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
            this.submit();
        });
        $(document).on('change blur', '#logo_width, #logo_height', function () {
            var id = $(this).attr('id');
            resizeImage((id === 'logo_width'));
        });
        $('li>a[data-tab="mail"]').parent().css('float', 'right');
        $('input, select').each(function (i, n) {
            input_contents[n.name] = n.value;
        });
        if (window.edit_true === '1') {
            $('.ccm-dashboard-page-header>h1').text(window.edit_page_title);
        }
        $('input, select:not(#nl_newsletter_preview):not(#nl_template_preview)').on('keyup blur change', function () {
            if (this.value !== input_contents[this.name]) {
                $('#preview_go').hide();
            } else {
                $('#preview_go').show();
            }
        });
        if ($('[name="mail_logo"]').val() === '0' || $('[name="mail_logo"]').val() === undefined) {
            $('.show_hide_file_settings').hide();
        }
        window.ConcreteEvent.bind('FileManagerBeforeSelectFile', function (e, fdata) {
            if (fdata.hasOwnProperty('fID')) {
                $('.show_hide_file_settings').show();
                $('[name="mail_logo"]').val(fdata.fID);
                getFileDimensions(fdata.fID);
            } else {
                $('.show_hide_file_settings').hide();
                $('[name="mail_logo"]').val('');
            }
        });

        $(document).on('focusout', 'a[data-file-manager-action]', function (e, fdata) {
            if (fdata === undefined) {
                $('.show_hide_file_settings').hide();
                $('[name="mail_logo"]').val('');

            }
        });
        $(document).on('click', '#show_help_dialog', function (e) {
            e.preventDefault();
            showHelpDialog();
        });
    });
}(jQuery));
</script>
<div id="dialog_delete" style="display: none"><?php       print t('Are you sure you want to permanently remove this Template?')?></div>

<div id="add_new" style="display:none" class="ccm-dashboard-header-buttons btn-group">
    <button id="show_help_dialog" name="show_help_dialog"
            class="btn btn-default"><?php       echo t('Show Styling Help Dialog') ?></button>
    <?php      
    if ($mail_template != NULL) {
        ?>
        <a href="<?php       print $view->action('duplicate_template', array($mail_template->getMailTemplateId(), $mail_template->getMailTemplateHandle())) ?>"
           type="submit" id="duplicate_template" name="duplicate_template"
           class="btn btn-default"><?php       echo t('Duplicate') ?></a>
        <a href="<?php       print $view->action('delete_template', array($mail_template->getMailTemplateId(), $mail_template->getMailTemplateHandle())) ?>"
           type="submit" id="delete_template" name="delete_template"
           class="btn btn-danger"><?php       echo t('Delete') ?></a>
    <?php       }
    ?>
</div>

<div class="ccm-ui save_tpl_msg_div" id="ccm-dashboard-result-message" style="display: none">
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <span id="save_tpl_msg"></span>
    </div>
</div>
<div id="dialog_valid_email" style="display: none"><?php       print t('Enter a valid email address first!')?></div>
<div id="dialog_save_first" style="display: none"><?php       print t('Please save the Template first!')?></div>
<div id="dialog_email_success" style="display: none"><div id="dialog_tpl_first_text"></div></div>

<div class="clearfix">
    <?php      

        $m = $session->get('success_saved');
        ?>
        <div id="success_saved" class="alert alert-info" style="display: none">
            <a href="#" class="close">&times;</a>

            <div id="success_saved_text">
                <?php       print $m ?>
            </div>
        </div>
    <?php      
    $session->remove('success_saved');

    print \Core::make('helper/concrete/ui')->tabs($tabs);
    ?>
    <div id="help_dialog" style="display: none; max-height: 450px !important; overflow: auto">
        <div class="text row explanations">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Padding') ?></div>
                <div class="newsletter-padding" style="width: 95%; margin-left: 20px; margin-right: 10px">
                    <span>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Explanation') ?></div>
                    <span style="background-color: #428bca; color: white; padding: 1px">
                        <?php      
                        print t('Padding');
                        ?>
                    </span>
                <?php      
                print t('is the distance between the container and the inner content.');
                ?>
                <br>
                <?php      
                print t('In this example the padding is as follows:');
                ?>
                <ul>
                    <li><?php       print t('Padding') . ' ' . t('Top') ?>: 5 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Padding') . ' ' . t('Right') ?>: 10 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Padding') . ' ' . t('Bottom') ?>: 15 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Padding') . ' ' . t('Left') ?>: 20 <?php       print t('Pixels') ?></li>
                </ul>
            </div>
        </div>
        <div class="text row explanations">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Margin') ?></div>
                <div class="newsletter-margin">
                    <div class="newsletter-padding">
                        <span>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Explanation') ?></div>
                    <span style="background-color: #000; color: white; padding: 1px"><?php      
                        print t('Margin');
                        ?></span>
                <?php      
                print t('is the distance between the containers and/or its parent container, in this case this would be the email container.');
                ?><br>
                <?php      
                print t('In this example the margin is as follows:');
                ?>
                <ul>
                    <li><?php       print t('Margin') . ' ' . t('Top') ?>: 5 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Margin') . ' ' . t('Right') ?>: 10 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Margin') . ' ' . t('Bottom') ?>: 15 <?php       print t('Pixels') ?></li>
                    <li><?php       print t('Margin') . ' ' . t('Left') ?>: 20 <?php       print t('Pixels') ?></li>
                </ul>
            </div>
        </div>
        <div class="text row explanations">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Minimum height') ?></div>
                <div class="newsletter-margin">
                    <div class="newsletter-padding" style="min-height: 75px">
                        <span>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span>
                        <br>
                    </div>
                </div>
                <br>

                <div class="newsletter-margin">
                    <div class="newsletter-padding" style="min-height: 75px">
                        <span>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Explanation') ?></div>
                <?php      
                print t('The minimum height defines the height a container <em>must</em> have. If the content is bigger than this height, the height will be adapted automatically.');
                ?><br>
                <?php      
                print t('In this example the minimum height is as follows:');
                ?>
                <ul>
                    <li><?php       print t('Minimum height') ?>: 75 <?php       print t('Pixels') ?></li>
                </ul>
            </div>
        </div>
        <div class="text row explanations">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="newsletter-label"><?php       print t('Units') ?></div>
                <?php      
                print t('Following CSS units are allowed:') . '<br><strong>' . t('Hint:') . ' ' . t('For this purpose (sending emails) you should use pixels or percents.') . '</strong>';
                ?>
                <ul>
                    <li><b><?php       print t('Pixels') ?></b>: <em>
                            <?php      
                            print t('In digital imaging, a pixel, pel, or picture element is a physical point in a raster image, or the smallest addressable element in an all points addressable display device.') . '... <a href="' . t('https://en.wikipedia.org/wiki/Pixel') . '">' . t('Read more') . '</a>';
                            ?></em><sup>[1]</sup></li>
                    <li><b><?php       print t('Percent') ?></b>: <em>
                            <?php      
                            print t('The percentage CSS data types represent a percentage value. Many CSS properties can take percentage values, often to define sizes in terms of parent objects.') . '... <a href="' . t('https://developer.mozilla.org/en/docs/Web/CSS/percentage') . '">' . t('Read more') . '</a>';
                            ?></em><sup>[2]</sup></li>
                </ul>
                <span><em>[1] <?php       print t('Reference') ?>: <a
                            href="<?php       print t('https://en.wikipedia.org/wiki/Pixel') ?>">Wikipedia</a></em></span><br>
                <span><em>[2] <?php       print t('Reference') ?>: <a
                            href="<?php       print t('https://developer.mozilla.org/en/docs/Web/CSS/percentage') ?>">Mozilla
                            Developer Network</a></em></span>
            </div>
        </div>
    </div>
    <form id="template_form" role="form" method="post" action="<?php       print $controller->action('save_template') ?>"
          class="form-horizontal" novalidate>
        <?php       print $this->controller->token->output('save_template');
        print $form->hidden('selected_tabs', '');
        ?>
        <div id="ccm-tab-content-general" class="ccm-tab-content">
            <fieldset>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-addon"><?php       print t('Template Name') ?></span>
                                <?php      
                                print $form->text('tl_handle', ($mail_template != NULL) ? \Core::make('helper/text')->entities($mail_template->getMailTemplateHandle()) : '');
                                ?>
                                <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                            </div>
                        </div>
                    </div>
                    <?php      
                    print $form->hidden('newsletter_template_id', $newsletter_template_id);
                    ?>
                </div>
            </fieldset>

        </div>
        <div id="ccm-tab-content-header" class="ccm-tab-content">
            <fieldset>
                <?php      
                print \Core::make('helper/concrete/ui')->tabs($header_tabs);

                ?></fieldset>
            <div id="ccm-tab-content-header-image" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <?php      
                                $mail_logo = ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getMailLogo()) : '';
                                $ml = (isset($mail_logo)) ? \Concrete\Core\File\File::getByID($mail_logo) : '';
                                $mp = (isset($mail_logo)) ? \Concrete\Core\File\File::getRelativePathFromID($mail_logo) : '';
                                ?>
                                <label><?php       print t('Header image') ?></label>

                                        <?php      
                                        print $u->image('mail_logo', 'mail_logo', '', $ml);
                                        print $form->hidden('mail_path', $mp)
                                        ?>
                            </div>
                        </div>
                        <div class="form-group show_hide_file_settings">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('logo_width', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoWidth()) : '', array('min' => '0'));
                                    print $form->hidden('logo_width_odd', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoWidth()) : '');
                                    ?>

                                    <?php      
                                    print $form->select('logo_width_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoWidthUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Height') ?></span>
                                    <?php      
                                    print $form->number('logo_height', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoHeight()) : '', array('min' => '0'));
                                    print $form->hidden('logo_height_odd', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoHeight()) : '');
                                    ?>

                                    <?php      
                                    print $form->select('logo_height_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoHeightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="show_hide_file_settings">
                    <legend>
                        <?php      
                        print t('Header image & text position');
                        ?>
                    </legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('logo_top', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoTop()) : '', array('min' => '0'));
                                    print $form->select('logo_top_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('logo_right', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoRight()) : '', array('min' => '0'));
                                    print $form->select('logo_right_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('logo_bottom', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoBottom()) : '', array('min' => '0'));
                                    print $form->select('logo_bottom_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('logo_left', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoLeft()) : '', array('min' => '0'));
                                    print $form->select('logo_left_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getLogoLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="show_hide_file_settings">
                    <legend>
                        <?php      
                        print t('Header image margin');
                        ?>
                    </legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('logo_img_top', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginTop()) : '', array('min' => '0'));
                                    print $form->select('logo_img_top_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('logo_img_right', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginRight()) : '', array('min' => '0'));
                                    print $form->select('logo_img_right_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('logo_img_bottom', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginBottom()) : '', array('min' => '0'));
                                    print $form->select('logo_img_bottom_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('logo_img_left', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginLeft()) : '', array('min' => '0'));
                                    print $form->select('logo_img_left_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadImgMarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-header-background" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Background color') ?></span>
                                    <?php      
                                    $color->output('head_color', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadColor()) : '')
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Minimum height') ?></span>
                                    <?php      

                                    print $form->number('head_size', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadHeight()) : '', array('min' => '0'));
                                    print $form->select('head_size_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadHeightUnit()) : '', array('class' => 'input-group-addon'));

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-header-padding" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('head_padding_top', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingTop()) : '', array('min' => '0'));
                                    print $form->select('head_padding_top_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('head_padding_right', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingRight()) : '', array('min' => '0'));
                                    print $form->select('head_padding_right_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('head_padding_bottom', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingBottom()) : '', array('min' => '0'));
                                    print $form->select('head_padding_bottom_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('head_padding_left', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingLeft()) : '', array('min' => '0'));
                                    print $form->select('head_padding_left_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadPaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-header-margin" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('head_margin_top', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginTop()) : '', array('min' => '0'));
                                    print $form->select('head_margin_top_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('head_margin_right', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginRight()) : '', array('min' => '0'));
                                    print $form->select('head_margin_right_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('head_margin_bottom', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginBottom()) : '', array('min' => '0'));
                                    print $form->select('head_margin_bottom_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('head_margin_left', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginLeft()) : '', array('min' => '0'));
                                    print $form->select('head_margin_left_unit', $controller->units, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadMarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-header-border" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Top') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('head_border_top_style', $controller->border_style, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderTopStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('head_border_top_width', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderTopWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('head_border_top_color', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderTopColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Right') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('head_border_right_style', $controller->border_style, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderRightStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('head_border_right_width', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderRightWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('head_border_right_color', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderRightColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Bottom') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('head_border_bottom_style', $controller->border_style, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderBottomStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('head_border_bottom_width', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderBottomWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('head_border_bottom_color', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderBottomColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Left') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('head_border_left_style', $controller->border_style, ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderLeftStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('head_border_left_width', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderLeftWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('head_border_left_color', ($mail_template_head != NULL) ? \Core::make('helper/text')->entities($mail_template_head->getHeadBorderLeftColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div id="ccm-tab-content-body" class="ccm-tab-content">
            <fieldset>
                <?php      
                print \Core::make('helper/concrete/ui')->tabs($body_tabs);

                ?>
            </fieldset>
            <div id="ccm-tab-content-body-background" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Background color') ?></span>
                                    <?php      
                                    $color->output('body_color', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyColor()) : '')
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Minimum height') ?></span>
                                    <?php      
                                    print $form->number('body_min_size', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyMinSize()) : '', array('min' => '0'));
                                    print $form->select('body_min_size_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyMinSizeUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-body-padding" class="ccm-tab-content">
                <fieldset>
                    <div class="text row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('padding_top', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingTop()) : '', array('min' => '0'));
                                    print $form->select('padding_top_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('padding_right', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingRight()) : '', array('min' => '0'));
                                    print $form->select('padding_right_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('padding_bottom', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingBottom()) : '', array('min' => '0'));
                                    print $form->select('padding_bottom_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('padding_left', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingLeft()) : '', array('min' => '0'));
                                    print $form->select('padding_left_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getPaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-body-margin" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('margin_top', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginTop()) : '', array('min' => '0'));
                                    print $form->select('margin_top_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('margin_right', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginRight()) : '', array('min' => '0'));
                                    print $form->select('margin_right_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('margin_bottom', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginBottom()) : '', array('min' => '0'));
                                    print $form->select('margin_bottom_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('margin_left', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginLeft()) : '', array('min' => '0'));
                                    print $form->select('margin_left_unit', $controller->units, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getMarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-body-border" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Top') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('body_border_top_style', $controller->border_style, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderTopStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('body_border_top_width', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderTopWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('body_border_top_color', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderTopColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Right') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('body_border_right_style', $controller->border_style, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderRightStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('body_border_right_width', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderRightWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('body_border_right_color', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderRightColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Bottom') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('body_border_bottom_style', $controller->border_style, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderBottomStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('body_border_bottom_width', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderBottomWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('body_border_bottom_color', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderBottomColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Left') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('body_border_left_style', $controller->border_style, ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderLeftStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('body_border_left_width', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderLeftWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('body_border_left_color', ($mail_template_body != NULL) ? \Core::make('helper/text')->entities($mail_template_body->getBodyBorderLeftColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

        </div>
        <div id="ccm-tab-content-footer" class="ccm-tab-content">
            <fieldset>
                <?php      
                print \Core::make('helper/concrete/ui')->tabs($footer_tabs);

                ?>
            </fieldset>
            <div id="ccm-tab-content-footer-background" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Background color') ?></span>
                                    <?php      
                                    $color->output('foot_color', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootColor()) : '')
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Minimum height') ?></span>
                                    <?php      
                                    print $form->number('foot_size', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootHeight()) : '', array('min' => '0'));
                                    print $form->select('foot_size_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootHeightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-footer-padding" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('foot_padding_top', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingTop()) : '', array('min' => '0'));
                                    print $form->select('foot_padding_top_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('foot_padding_right', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingRight()) : '', array('min' => '0'));
                                    print $form->select('foot_padding_right_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('foot_padding_bottom', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingBottom()) : '', array('min' => '0'));
                                    print $form->select('foot_padding_bottom_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('foot_padding_left', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingLeft()) : '', array('min' => '0'));
                                    print $form->select('foot_padding_left_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootPaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-footer-margin" class="ccm-tab-content">
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Top') ?></span>
                                    <?php      
                                    print $form->number('foot_margin_top', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginTop()) : '', array('min' => '0'));
                                    print $form->select('foot_margin_top_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Right') ?></span>
                                    <?php      
                                    print $form->number('foot_margin_right', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginRight()) : '', array('min' => '0'));
                                    print $form->select('foot_margin_right_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                    <?php      
                                    print $form->number('foot_margin_bottom', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginBottom()) : '', array('min' => '0'));
                                    print $form->select('foot_margin_bottom_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Left') ?></span>
                                    <?php      
                                    print $form->number('foot_margin_left', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginLeft()) : '', array('min' => '0'));
                                    print $form->select('foot_margin_left_unit', $controller->units, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootMarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="ccm-tab-content-footer-border" class="ccm-tab-content">
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Top') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('foot_border_top_style', $controller->border_style, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderTopStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('foot_border_top_width', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderTopWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('foot_border_top_color', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderTopColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Right') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('foot_border_right_style', $controller->border_style, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderRightStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('foot_border_right_width', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderRightWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('foot_border_right_color', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderRightColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Bottom') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('foot_border_bottom_style', $controller->border_style, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderBottomStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('foot_border_bottom_width', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderBottomWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('foot_border_bottom_color', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderBottomColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        <?php       print t('Border') . ' ' . t('Left') ?>
                    </legend>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Style') ?></span>
                                    <?php      
                                    print $form->select('foot_border_left_style', $controller->border_style, ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderLeftStyle()) : '');
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Width') ?></span>
                                    <?php      
                                    print $form->number('foot_border_left_width', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderLeftWidth()) : '', array('min' => '0'));
                                    ?>
                                    <span class="input-group-addon"><?php       print t('Pixels') ?></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php       print t('Color') ?></span>
                                    <?php      
                                    $color->output('foot_border_left_color', ($mail_template_foot != NULL) ? \Core::make('helper/text')->entities($mail_template_foot->getFootBorderLeftColor()) : '');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

        </div>
        <div id="ccm-tab-content-titles" class="ccm-tab-content">
            <fieldset>
                <?php      
                print \Core::make('helper/concrete/ui')->tabs($title_tabs);
                    ?>
                <div class="ccm-tab-content" id="ccm-tab-content-title-one">
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Font');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font family', t('1')) ?></span>
                                        <?php      
                                        print $form->select('h1_font', $family_font, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1Font()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Font size', t('1')) ?></span>
                                        <?php      
                                        print $form->number('h1_size', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1Size()) : '', array('min' => '0'));
                                        print $form->select('h1_size_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1SizeUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font style', t('1')) ?></span>
                                        <?php      
                                        print $form->select('h1_style', $controller->font_style, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1Style()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font weight', t('1')) ?></span>
                                        <?php      
                                        print $form->select('h1_weight', $controller->font_weight, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1Weight()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Color', t('1')) ?></span>
                                        <?php      
                                        $color->output('h1_color', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1Color()) : '')
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Padding');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h1_padding_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingTop()) : '', array('min' => '0'));
                                        print $form->select('h1_padding_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h1_padding_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingRight()) : '', array('min' => '0'));
                                        print $form->select('h1_padding_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h1_padding_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingBottom()) : '', array('min' => '0'));
                                        print $form->select('h1_padding_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h1_padding_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingLeft()) : '', array('min' => '0'));
                                        print $form->select('h1_padding_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1PaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Margin');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h1_margin_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginTop()) : '', array('min' => '0'));
                                        print $form->select('h1_margin_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h1_margin_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginRight()) : '', array('min' => '0'));
                                        print $form->select('h1_margin_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h1_margin_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginBottom()) : '', array('min' => '0'));
                                        print $form->select('h1_margin_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h1_margin_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginLeft()) : '', array('min' => '0'));
                                        print $form->select('h1_margin_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH1MarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="ccm-tab-content" id="ccm-tab-content-title-two">
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Font');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font family', t('2')) ?></span>
                                        <?php      
                                        print $form->select('h2_font', $family_font, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2Font()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Font size', t('2')) ?></span>
                                        <?php      
                                        print $form->number('h2_size', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2Size()) : '', array('min' => '0'));
                                        print $form->select('h2_size_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2SizeUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font style', t('2')) ?></span>
                                        <?php      
                                        print $form->select('h2_style', $controller->font_style, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2Style()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font weight', t('2')) ?></span>
                                        <?php      
                                        print $form->select('h2_weight', $controller->font_weight, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2Weight()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Color', t('2')) ?></span>
                                        <?php      
                                        $color->output('h2_color', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2Color()) : '')
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Padding');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h2_padding_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingTop()) : '', array('min' => '0'));
                                        print $form->select('h2_padding_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h2_padding_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingRight()) : '', array('min' => '0'));
                                        print $form->select('h2_padding_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h2_padding_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingBottom()) : '', array('min' => '0'));
                                        print $form->select('h2_padding_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h2_padding_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingLeft()) : '', array('min' => '0'));
                                        print $form->select('h2_padding_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2PaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Margin');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h2_margin_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginTop()) : '', array('min' => '0'));
                                        print $form->select('h2_margin_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h2_margin_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginRight()) : '', array('min' => '0'));
                                        print $form->select('h2_margin_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h2_margin_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginBottom()) : '', array('min' => '0'));
                                        print $form->select('h2_margin_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h2_margin_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginLeft()) : '', array('min' => '0'));
                                        print $form->select('h2_margin_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH2MarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="ccm-tab-content" id="ccm-tab-content-title-three">
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Font');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font family', t('3')) ?></span>
                                        <?php      
                                        print $form->select('h3_font', $family_font, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3Font()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Font size', t('3')) ?></span>
                                        <?php      
                                        print $form->number('h3_size', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3Size()) : '', array('min' => '0'));
                                        print $form->select('h3_size_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3SizeUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font style', t('3')) ?></span>
                                        <?php      
                                        print $form->select('h3_style', $controller->font_style, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3Style()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font weight', t('3')) ?></span>
                                        <?php      
                                        print $form->select('h3_weight', $controller->font_weight, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3Weight()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Color', t('3')) ?></span>
                                        <?php      
                                        $color->output('h3_color', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3Color()) : '')
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Padding');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h3_padding_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingTop()) : '', array('min' => '0'));
                                        print $form->select('h3_padding_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h3_padding_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingRight()) : '', array('min' => '0'));
                                        print $form->select('h3_padding_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h3_padding_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingBottom()) : '', array('min' => '0'));
                                        print $form->select('h3_padding_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h3_padding_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingLeft()) : '', array('min' => '0'));
                                        print $form->select('h3_padding_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3PaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Margin');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h3_margin_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginTop()) : '', array('min' => '0'));
                                        print $form->select('h3_margin_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h3_margin_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginRight()) : '', array('min' => '0'));
                                        print $form->select('h3_margin_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h3_margin_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginBottom()) : '', array('min' => '0'));
                                        print $form->select('h3_margin_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h3_margin_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginLeft()) : '', array('min' => '0'));
                                        print $form->select('h3_margin_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH3MarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="ccm-tab-content" id="ccm-tab-content-title-four">
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Font');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font family', t('4')) ?></span>
                                        <?php      
                                        print $form->select('h4_font', $family_font, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4Font()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Font size', t('4')) ?></span>
                                        <?php      
                                        print $form->number('h4_size', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4Size()) : '', array('min' => '0'));
                                        print $form->select('h4_size_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4SizeUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font style', t('4')) ?></span>
                                        <?php      
                                        print $form->select('h4_style', $controller->font_style, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4Style()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font weight', t('4')) ?></span>
                                        <?php      
                                        print $form->select('h4_weight', $controller->font_weight, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4Weight()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Color', t('4')) ?></span>
                                        <?php      
                                        $color->output('h4_color', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4Color()) : '')
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Padding');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h4_padding_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingTop()) : '', array('min' => '0'));
                                        print $form->select('h4_padding_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h4_padding_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingRight()) : '', array('min' => '0'));
                                        print $form->select('h4_padding_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h4_padding_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingBottom()) : '', array('min' => '0'));
                                        print $form->select('h4_padding_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h4_padding_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingLeft()) : '', array('min' => '0'));
                                        print $form->select('h4_padding_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4PaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Margin');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h4_margin_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginTop()) : '', array('min' => '0'));
                                        print $form->select('h4_margin_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h4_margin_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginRight()) : '', array('min' => '0'));
                                        print $form->select('h4_margin_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h4_margin_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginBottom()) : '', array('min' => '0'));
                                        print $form->select('h4_margin_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h4_margin_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginLeft()) : '', array('min' => '0'));
                                        print $form->select('h4_margin_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH4MarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="ccm-tab-content" id="ccm-tab-content-title-five">
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Font');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font family', t('5')) ?></span>
                                        <?php      
                                        print $form->select('h5_font', $family_font, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5Font()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Font size', t('5')) ?></span>
                                        <?php      
                                        print $form->number('h5_size', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5Size()) : '', array('min' => '0'));
                                        print $form->select('h5_size_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5SizeUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font style', t('5')) ?></span>
                                        <?php      
                                        print $form->select('h5_style', $controller->font_style, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5Style()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                            <span
                                                class="input-group-addon"><?php       print t('Font weight', t('5')) ?></span>
                                        <?php      
                                        print $form->select('h5_weight', $controller->font_weight, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5Weight()) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Color', t('5')) ?></span>
                                        <?php      
                                        $color->output('h5_color', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5Color()) : '')
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Padding');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h5_padding_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingTop()) : '', array('min' => '0'));
                                        print $form->select('h5_padding_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h5_padding_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingRight()) : '', array('min' => '0'));
                                        print $form->select('h5_padding_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h5_padding_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingBottom()) : '', array('min' => '0'));
                                        print $form->select('h5_padding_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h5_padding_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingLeft()) : '', array('min' => '0'));
                                        print $form->select('h5_padding_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5PaddingLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <?php      
                            print t('Margin');
                            ?>
                        </legend>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Top') ?></span>
                                        <?php      
                                        print $form->number('h5_margin_top', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginTop()) : '', array('min' => '0'));
                                        print $form->select('h5_margin_top_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginTopUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Right') ?></span>
                                        <?php      
                                        print $form->number('h5_margin_right', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginRight()) : '', array('min' => '0'));
                                        print $form->select('h5_margin_right_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginRightUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Bottom') ?></span>
                                        <?php      
                                        print $form->number('h5_margin_bottom', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginBottom()) : '', array('min' => '0'));
                                        print $form->select('h5_margin_bottom_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginBottomUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php       print t('Left') ?></span>
                                        <?php      
                                        print $form->number('h5_margin_left', ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginLeft()) : '', array('min' => '0'));
                                        print $form->select('h5_margin_left_unit', $controller->units, ($mail_template_title != NULL) ? \Core::make('helper/text')->entities($mail_template_title->getH5MarginLeftUnit()) : '', array('class' => 'input-group-addon'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </fieldset>
        </div>
        <div id="ccm-tab-content-mail" class="ccm-tab-content">
            <fieldset>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-7">
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
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div id="mail_sent_alert" class="alert alert-info" style="display: none">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <span id="mail_sent_msg"></span></div>
                        <div id="mail_not_sent_alert" class="alert alert-danger" style="display: none">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <span id="mail_not_sent_msg"></span></div>
                    </div>
                </div>
            </fieldset>
        </div>
            <fieldset>
                <legend><?php       print t('Preview') ?></legend>
                <?php       if(strpos($_SERVER['REQUEST_URI'], 'edit') !== false) { ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div>
                            <div id="mail_template_border" class="ccm-ui">
                                <?php      
                                print $form->select('nl_newsletter_preview', array('xxx' => t('Choose a Newsletter for preview')) + $newsletters_d, $session->get('newsletter_id')); ?>
                                <div id="tpl"><?php       print $tpl ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php      
                } else {?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-info">
                        <?php       print t('Please save Template before preview'); ?>
                        </div>
                    </div>
                </div>
                    <?php      
                }
                ?>
            </fieldset>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button id="Send" name="Send" class="pull-right btn btn-primary" type="submit"><?php       echo t('Save Template') ?></button>
            </div>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(function () {
        setTimeout(function () {
            if (jQuery('.ccm-file-selector-file-selected-thumbnail').length > 0) {
                jQuery('.show_hide_file_settings').show();
            }
        }, 100);
    });

</script>