<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/mail/newsletter_template.php
 */
$u = new \Concrete\Core\User\User();
if(is_object($tpl)){
    if($tpl_head->getMailLogo() != '0' && $tpl_head->getMailLogo() != NULL){
        $ml = $tpl_head->getMailLogo();
        $img = \Concrete\Core\File\File::getRelativePathFromID($ml);
    } else {
        $img = '';
    }
    $subject = $subject;
    if(!$preview){
        $bodyHTML .= '<!DOCTYPE html>' . "\n";
        $bodyHTML .= '<html>' . "\n";
        $bodyHTML .= '	<head>' . "\n";
        $bodyHTML .= '		<meta charset="utf-8">' . "\n";
        $bodyHTML .= '<link href="https://fonts.googleapis.com/css?family=' . $tpl_title->getH1Font() . '" rel="stylesheet" type="text/css">' . "\n";
        $bodyHTML .= '<link href="https://fonts.googleapis.com/css?family=' . $tpl_title->getH2Font() . '" rel="stylesheet" type="text/css">' . "\n";
        $bodyHTML .= '<link href="https://fonts.googleapis.com/css?family=' . $tpl_title->getH3Font() . '" rel="stylesheet" type="text/css">' . "\n";
        $bodyHTML .= '<link href="https://fonts.googleapis.com/css?family=' . $tpl_title->getH4Font() . '" rel="stylesheet" type="text/css">' . "\n";
        $bodyHTML .= '<link href="https://fonts.googleapis.com/css?family=' . $tpl_title->getH5Font() . '" rel="stylesheet" type="text/css">' . "\n";
        $bodyHTML .= '<link rel="stylesheet" href="' . BASE_URL . 'concrete/css/font-awesome.css">' . "\n";
    }
    if($preview){
        $bodyHTML .= '<style>' . "\n";
        $bodyHTML .= '.toesslab_newsletter_template>h1{' . "\n";
        $bodyHTML .= 'color: ' . $tpl_title->getH1Color() . ';' . "\n";
        $bodyHTML .= 'font-weight: ' . $tpl_title->getH1Weight() . ';' . "\n";
        $bodyHTML .= 'font-family: "' . $tpl_title->getH1Font() . '";' . "\n";
        $bodyHTML .= 'font-size: ' . $tpl_title->getH1Size() . $tpl_title->getH1SizeUnit() . ';' . "\n";
        $bodyHTML .= 'padding: ' . $tpl_title->getH1PaddingTop() . $tpl_title->getH1PaddingTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1PaddingRight() . $tpl_title->getH1PaddingRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1PaddingBottom() . $tpl_title->getH1PaddingBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1PaddingLeft() . $tpl_title->getH1PaddingLeftUnit() . '; ' . "\n";
        $bodyHTML .= 'margin: ' . $tpl_title->getH1MarginTop() . $tpl_title->getH1MarginTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1MarginRight() . $tpl_title->getH1MarginRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1MarginBottom() . $tpl_title->getH1MarginBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH1MarginLeft() . $tpl_title->getH1MarginLeftUnit() . '; ' . "\n";
        $bodyHTML .= '}' . "\n";
        $bodyHTML .= '.toesslab_newsletter_template>h2{' . "\n";
        $bodyHTML .= 'color: ' . $tpl_title->getH2Color() . ';' . "\n";
        $bodyHTML .= 'font-weight: ' . $tpl_title->getH2Weight() . ';' . "\n";
        $bodyHTML .= 'font-family: "' . $tpl_title->getH2Font() . '";' . "\n";
        $bodyHTML .= 'font-size: ' . $tpl_title->getH2Size() . $tpl_title->getH2SizeUnit() . ';' . "\n";
        $bodyHTML .= 'padding: ' . $tpl_title->getH2PaddingTop() . $tpl_title->getH2PaddingTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2PaddingRight() . $tpl_title->getH2PaddingRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2PaddingBottom() . $tpl_title->getH2PaddingBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2PaddingLeft() . $tpl_title->getH2PaddingLeftUnit() . ';' . "\n";
        $bodyHTML .= 'margin: ' . $tpl_title->getH2MarginTop() . $tpl_title->getH2MarginTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2MarginRight() . $tpl_title->getH2MarginRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2MarginBottom() . $tpl_title->getH2MarginBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH2MarginLeft() . $tpl_title->getH2MarginLeftUnit() . ';' . "\n";
        $bodyHTML .= '}' . "\n";
        $bodyHTML .= '.toesslab_newsletter_template>h3{' . "\n";
        $bodyHTML .= 'color: ' . $tpl_title->getH3Color() . ';' . "\n";
        $bodyHTML .= 'font-weight: ' . $tpl_title->getH3Weight() . ';' . "\n";
        $bodyHTML .= 'font-family: "' . $tpl_title->getH3Font() . '";' . "\n";
        $bodyHTML .= 'font-size: ' . $tpl_title->getH3Size() . $tpl_title->getH3SizeUnit() . ';' . "\n";
        $bodyHTML .= 'padding: ' . $tpl_title->getH3PaddingTop() . $tpl_title->getH3PaddingTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3PaddingRight() . $tpl_title->getH3PaddingRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3PaddingBottom() . $tpl_title->getH3PaddingBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3PaddingLeft() . $tpl_title->getH3PaddingLeftUnit() . ';' . "\n";
        $bodyHTML .= 'margin: ' . $tpl_title->getH3MarginTop() . $tpl_title->getH3MarginTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3MarginRight() . $tpl_title->getH3MarginRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3MarginBottom() . $tpl_title->getH3MarginBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH3MarginLeft() . $tpl_title->getH3MarginLeftUnit() . ';' . "\n";
        $bodyHTML .= '}' . "\n";
        $bodyHTML .= '.toesslab_newsletter_template>h4{' . "\n";
        $bodyHTML .= 'color: ' . $tpl_title->getH4Color() . ';' . "\n";
        $bodyHTML .= 'font-weight: ' . $tpl_title->getH4Weight() . ';' . "\n";
        $bodyHTML .= 'font-family: "' . $tpl_title->getH4Font() . '";' . "\n";
        $bodyHTML .= 'font-size: ' . $tpl_title->getH4Size() . $tpl_title->getH4SizeUnit() . ';' . "\n";
        $bodyHTML .= 'padding: ' . $tpl_title->getH4PaddingTop() . $tpl_title->getH4PaddingTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4PaddingRight() . $tpl_title->getH4PaddingRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4PaddingBottom() . $tpl_title->getH4PaddingBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4PaddingLeft() . $tpl_title->getH4PaddingLeftUnit() . ';' . "\n";
        $bodyHTML .= 'margin: ' . $tpl_title->getH4MarginTop() . $tpl_title->getH4MarginTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4MarginRight() . $tpl_title->getH4MarginRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4MarginBottom() . $tpl_title->getH4MarginBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH4MarginLeft() . $tpl_title->getH4MarginLeftUnit() . ';' . "\n";
        $bodyHTML .= '}' . "\n";
        $bodyHTML .= '.toesslab_newsletter_template>h5{' . "\n";
        $bodyHTML .= 'color: ' . $tpl_title->getH5Color() . ';' . "\n";
        $bodyHTML .= 'font-weight: ' . $tpl_title->getH5Weight() . ';' . "\n";
        $bodyHTML .= 'font-family: "' . $tpl_title->getH5Font() . '";' . "\n";
        $bodyHTML .= 'font-size: ' . $tpl_title->getH5Size() . $tpl_title->getH5SizeUnit() . ';' . "\n";
        $bodyHTML .= 'padding: ' . $tpl_title->getH5PaddingTop() . $tpl_title->getH5PaddingTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5PaddingRight() . $tpl_title->getH5PaddingRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5PaddingBottom() . $tpl_title->getH5PaddingBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5PaddingLeft() . $tpl_title->getH5PaddingLeftUnit() . ';' . "\n";
        $bodyHTML .= 'margin: ' . $tpl_title->getH5MarginTop() . $tpl_title->getH5MarginTopUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5MarginRight() . $tpl_title->getH5MarginRightUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5MarginBottom() . $tpl_title->getH5MarginBottomUnit() . ' ';
        $bodyHTML .= $tpl_title->getH5MarginLeft() . $tpl_title->getH5MarginLeftUnit() . ';' . "\n";
        $bodyHTML .= '}' . "\n";
        $bodyHTML .= '</style>' . "\n";
    }
    if(!$preview) {
        $bodyHTML .= '	</head>' . "\n";
        $bodyHTML .= '	<body style="margin: 0; padding: 0; position: relative">' . "\n";
    }
    if(!$inBrowser) {
	    $bodyHTML .= '<div style="text-align: center"><a href="' . $browserKey . '">' . \Config::get('toess_lab_news_letter.settings.browser_link_text') . '</a></div>';
    }
    $bodyHTML .= '        <div id="toesslab_newsletter_template_header" class="toesslab_newsletter_template" style="padding: ';
    $bodyHTML .= $tpl_head->getHeadPaddingTop() . $tpl_head->getHeadPaddingTopUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadPaddingRight() . $tpl_head->getHeadPaddingRightUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadPaddingBottom() . $tpl_head->getHeadPaddingBottomUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadPaddingLeft() . $tpl_head->getHeadPaddingLeftUnit() . '; ';
    $bodyHTML .= 'margin: ' . $tpl_head->getHeadMarginTop() . $tpl_head->getHeadMarginTopUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadMarginRight() . $tpl_head->getHeadMarginRightUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadMarginBottom() . $tpl_head->getHeadMarginBottomUnit() . ' ';
    $bodyHTML .= $tpl_head->getHeadMarginLeft() . $tpl_head->getHeadMarginLeftUnit() . '; ';
    $bodyHTML .= 'min-height:  ' . $tpl_head->getHeadHeight() . $tpl_head->getHeadHeightUnit() . '; ';
    $bodyHTML .= 'background-color: ' . $tpl_head->getHeadColor() . '; ';
    $bodyHTML .= 'border-top: ' . $tpl_head->getHeadBorderTopWidth() . 'px ' . $tpl_head->getHeadBorderTopStyle() . ' ' . $tpl_head->getHeadBorderTopColor() . '; ';
    $bodyHTML .= 'border-right: ' . $tpl_head->getHeadBorderRightWidth() . 'px ' . $tpl_head->getHeadBorderRightStyle() . ' ' . $tpl_head->getHeadBorderRightColor() . '; ';
    $bodyHTML .= 'border-bottom: ' . $tpl_head->getHeadBorderBottomWidth() . 'px ' . $tpl_head->getHeadBorderBottomStyle() . ' ' . $tpl_head->getHeadBorderBottomColor() . '; ';
    $bodyHTML .= 'border-left: ' . $tpl_head->getHeadBorderLeftWidth() . 'px ' . $tpl_head->getHeadBorderLeftStyle() . ' ' . $tpl_head->getHeadBorderLeftColor() . '; ';
    $bodyHTML .= '">' . "\n";
    if(strlen($img) > 0) {
        $bodyHTML .= '<div id="toesslab_newsletter_template_header_image_container" class="toesslab_newsletter_template" style="';
        $bodyHTML .= ' ;display: block; position: relative; ';
        $bodyHTML .= 'top: ' . $tpl_head->getLogoTop() . $tpl_head->getLogoTopUnit() . '; ';
        $bodyHTML .= 'left: ' . $tpl_head->getLogoLeft() . $tpl_head->getLogoLeftUnit() . '; ';
        $bodyHTML .= 'right: ' . $tpl_head->getLogoRight() . $tpl_head->getLogoRightUnit() . '; ';
        $bodyHTML .= 'bottom: ' . $tpl_head->getLogoBottom() . $tpl_head->getLogoBottomUnit() . '; ';
        $bodyHTML .= 'margin-top: ' . $tpl_head->getHeadImgMarginTop() . $tpl_head->getHeadImgMarginTopUnit() . '; ';
        $bodyHTML .= 'margin-left: ' . $tpl_head->getHeadImgMarginLeft() . $tpl_head->getHeadImgMarginLeftUnit() . '; ';
        $bodyHTML .= 'margin-right: ' . $tpl_head->getHeadImgMarginRight() . $tpl_head->getHeadImgMarginRightUnit() . '; ';
        $bodyHTML .= 'margin-bottom: ' . $tpl_head->getHeadImgMarginBottom() . $tpl_head->getHeadImgMarginBottomUnit() . '; ';
        $bodyHTML .= '">';
        $bodyHTML .= '<a width="' . $tpl_head->getLogoWidth() . $tpl_head->getLogoWidthUnit() . '" height="' . $tpl_head->getLogoHeight() . $tpl_head->getLogoHeightUnit() . '" href="' . BASE_URL . '"><img id="toesslab_newsletter_template_header_image" style="';
        $bodyHTML .= 'width: ' . $tpl_head->getLogoWidth() . $tpl_head->getLogoWidthUnit() . '; ';
        $bodyHTML .= 'height: ' . $tpl_head->getLogoHeight() . $tpl_head->getLogoHeightUnit() . '; display: block" ';

        $bodyHTML .= '" title="' . \Config::get('concrete.site') . '" src="' . BASE_URL . $img . '" alt="' . \Config::get('concrete.site') . '"></a></div>' . "\n";
    }
    $bodyHTML .= '<div class="toesslab_newsletter_template" style="clear: both;';
    $bodyHTML .= ' ;display: block; position: relative; ';
    $bodyHTML .= 'top: ' . $tpl_head->getLogoTop() . $tpl_head->getLogoTopUnit() . '; ';
    $bodyHTML .= 'left: ' . $tpl_head->getLogoLeft() . $tpl_head->getLogoLeftUnit() . '; ';
    $bodyHTML .= 'right: ' . $tpl_head->getLogoRight() . $tpl_head->getLogoRightUnit() . '; ';
    $bodyHTML .= 'bottom: ' . $tpl_head->getLogoBottom() . $tpl_head->getLogoBottomUnit() . ';">';
    $bodyHTML .= $header . '</div>' . "\n";
    $bodyHTML .= '    </div>' . "\n";
    $bodyHTML .= '		<div id="toesslab_newsletter_template_body" class="toesslab_newsletter_template" style="min-height: ' . $tpl_body->getBodyMinSize() . $tpl_body->getBodyMinSizeUnit() . '; ';
    $bodyHTML .= 'padding: ' . $tpl_body->getPaddingTop() . $tpl_body->getPaddingTopUnit() . ' ';
    $bodyHTML .=  $tpl_body->getPaddingRight() . $tpl_body->getPaddingRightUnit() . ' ';
    $bodyHTML .=  $tpl_body->getPaddingBottom() . $tpl_body->getPaddingBottomUnit() . ' ';
    $bodyHTML .=  $tpl_body->getPaddingLeft() . $tpl_body->getPaddingLeftUnit() . '; ';
    $bodyHTML .= 'margin: ' . $tpl_body->getMarginTop() . $tpl_body->getMarginTopUnit() . ' ';
    $bodyHTML .=  $tpl_body->getMarginRight() . $tpl_body->getMarginRightUnit() . ' ';
    $bodyHTML .=  $tpl_body->getMarginBottom() . $tpl_body->getMarginBottomUnit() . ' ';
    $bodyHTML .=  $tpl_body->getMarginLeft() . $tpl_body->getMarginLeftUnit() . '; ';
    $bodyHTML .= 'background-color: ' . $tpl_body->getBodyColor() . '; ';
    $bodyHTML .= 'border-top: ' . $tpl_body->getBodyBorderTopWidth() . 'px ' . $tpl_body->getBodyBorderTopStyle() . ' ' . $tpl_body->getBodyBorderTopColor() . '; ';
    $bodyHTML .= 'border-right: ' . $tpl_body->getBodyBorderRightWidth() . 'px ' . $tpl_body->getBodyBorderRightStyle() . ' ' . $tpl_body->getBodyBorderRightColor() . '; ';
    $bodyHTML .= 'border-bottom: ' . $tpl_body->getBodyBorderBottomWidth() . 'px ' . $tpl_body->getBodyBorderBottomStyle() . ' ' . $tpl_body->getBodyBorderBottomColor() . '; ';
    $bodyHTML .= 'border-left: ' . $tpl_body->getBodyBorderLeftWidth() . 'px ' . $tpl_body->getBodyBorderLeftStyle() . ' ' . $tpl_body->getBodyBorderLeftColor() . '; ';
    $bodyHTML .= '">' . "\n";
    $bodyHTML .= '<p>' . $address . '</p>' . "\n";
    $bodyHTML .= $text . "\n";
    $bodyHTML .= '		</div>' . "\n";
    $bodyHTML .= '		<div id="toesslab_newsletter_template_footer" class="toesslab_newsletter_template" style="min-height:  ' . $tpl_foot->getFootHeight() . $tpl_foot->getFootHeightUnit() . '; ';
    $bodyHTML .= 'padding: ' . $tpl_foot->getFootPaddingTop() . $tpl_foot->getFootPaddingTopUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootPaddingRight() . $tpl_foot->getFootPaddingRightUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootPaddingBottom() . $tpl_foot->getFootPaddingBottomUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootPaddingLeft() . $tpl_foot->getFootPaddingLeftUnit() . '; ';
    $bodyHTML .=  'margin: ' . $tpl_foot->getFootMarginTop() . $tpl_foot->getFootMarginTopUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootMarginRight() . $tpl_foot->getFootMarginRightUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootMarginBottom() . $tpl_foot->getFootMarginBottomUnit() . ' ';
    $bodyHTML .=  $tpl_foot->getFootMarginLeft() . $tpl_foot->getFootMarginLeftUnit() . '; ';
    $bodyHTML .= 'background-color: ' . $tpl_foot->getFootColor() . '; ';
    $bodyHTML .= 'border-top: ' . $tpl_foot->getFootBorderTopWidth() . 'px ' . $tpl_foot->getFootBorderTopStyle() . ' ' . $tpl_foot->getFootBorderTopColor() . '; ';
    $bodyHTML .= 'border-right: ' . $tpl_foot->getFootBorderRightWidth() . 'px ' . $tpl_foot->getFootBorderRightStyle() . ' ' . $tpl_foot->getFootBorderRightColor() . '; ';
    $bodyHTML .= 'border-bottom: ' . $tpl_foot->getFootBorderBottomWidth() . 'px ' . $tpl_foot->getFootBorderBottomStyle() . ' ' . $tpl_foot->getFootBorderBottomColor() . '; ';
    $bodyHTML .= 'border-left: ' . $tpl_foot->getFootBorderLeftWidth() . 'px ' . $tpl_foot->getFootBorderLeftStyle() . ' ' . $tpl_foot->getFootBorderLeftColor() . '; ';
    $bodyHTML .= '">' . "\n";
    $bodyHTML .= $footer . "\n";
    $bodyHTML .= '		</div>' . "\n";
    if(!$preview) {
        $bodyHTML .= '	</body>' . "\n";
        $bodyHTML .= '</html>';
    }
}
