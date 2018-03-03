<?php
namespace Concrete\Package\ToessLabNewsLetter\Setup;
defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Illuminate\Filesystem\Filesystem;
use UserAttributeKey;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Attribute\Select\Option;
use Concrete\Core\Logging\GroupLogger;
use Concrete\Core\Logging\Logger;

/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Setup/PackageSetup.php
 */

class PackageSetup
{
    public static $subscription_path = 'application/files/toess_lab_news_letter_subscriptions/';//SUBSCRIPTION_FILE_PATH = DIRNAME_APPLICATION . '/files/toess_lab_news_letter_subscriptions/';
    public static $subscription_file = 'toess_lab_news_letter_subscriptions.json'; // SUBSCRIPTION_FILE = 'toess_lab_news_letter_subscriptions.json';
    public static $un_subscription_file = 'toess_lab_news_letter_un_subscriptions.json';// UN_SUBSCRIPTION_FILE

    public static $pkgHandle = 'toess_lab_news_letter';
    public static $templateHead = array(
        array(
            'LogoWidth' => '100',
            'LogoHeight' => '100',
            'LogoWidthUnit' => 'px',
            'LogoHeightUnit' => 'px',
            'HeadColor' => 'rgb(247,247,247)',
            'HeadPaddingTop' => '10',
            'HeadPaddingLeft' => '10',
            'HeadPaddingRight' => '10',
            'HeadPaddingBottom' => '10',
            'HeadPaddingTopUnit' => 'px',
            'HeadPaddingLeftUnit' => 'px',
            'HeadPaddingRightUnit' => 'px',
            'HeadPaddingBottomUnit' => 'px',
            'HeadMarginTop' => '0',
            'HeadMarginLeft' => '0',
            'HeadMarginRight' => '0',
            'HeadMarginBottom' => '0',
            'HeadMarginTopUnit' => 'px',
            'HeadMarginLeftUnit' => 'px',
            'HeadMarginRightUnit' => 'px',
            'HeadMarginBottomUnit' => 'px',
            'LogoTop' => '0',
            'LogoLeft' => '0',
            'LogoRight' => '0',
            'LogoBottom' => '0',
            'LogoTopUnit' => 'px',
            'LogoLeftUnit' => 'px',
            'LogoRightUnit' => 'px',
            'LogoBottomUnit' => 'px',
            'HeadBorderTopStyle' => 'none',
            'HeadBorderTopColor' => '',
            'HeadBorderTopWidth' => '',
            'HeadBorderRightStyle' => 'none',
            'HeadBorderRightColor' => '',
            'HeadBorderRightWidth' => '',
            'HeadBorderBottomStyle' => 'none',
            'HeadBorderBottomColor' => '',
            'HeadBorderBottomWidth' => '',
            'HeadBorderLeftStyle' => 'none',
            'HeadBorderLeftColor' => '',
            'HeadBorderLeftWidth' => '',
        ),
        array(
            'LogoWidth' => '600',
            'LogoHeight' => '128',
            'LogoWidthUnit' => 'px',
            'LogoHeightUnit' => 'px',
            'HeadColor' => 'rgb(255, 255, 255)',
            'HeadPaddingTop' => '10',
            'HeadPaddingLeft' => '10',
            'HeadPaddingRight' => '10',
            'HeadPaddingBottom' => '10',
            'HeadPaddingTopUnit' => 'px',
            'HeadPaddingLeftUnit' => 'px',
            'HeadPaddingRightUnit' => 'px',
            'HeadPaddingBottomUnit' => 'px',
            'HeadMarginTop' => '10',
            'HeadMarginLeft' => '10',
            'HeadMarginRight' => '10',
            'HeadMarginBottom' => '0',
            'HeadMarginTopUnit' => '%',
            'HeadMarginLeftUnit' => '%',
            'HeadMarginRightUnit' => '%',
            'HeadMarginBottomUnit' => '%',
            'LogoTop' => '10',
            'LogoLeft' => '0',
            'LogoRight' => '0',
            'LogoBottom' => '0',
            'LogoTopUnit' => 'px',
            'LogoLeftUnit' => 'px',
            'LogoRightUnit' => 'px',
            'LogoBottomUnit' => 'px',
            'HeadBorderTopStyle' => 'solid',
            'HeadBorderTopColor' => 'rgb(183, 40, 46)',
            'HeadBorderTopWidth' => '1',
            'HeadBorderRightStyle' => 'solid',
            'HeadBorderRightColor' => 'rgb(183, 40, 46)',
            'HeadBorderRightWidth' => '1',
            'HeadBorderBottomStyle' => 'none',
            'HeadBorderBottomColor' => '',
            'HeadBorderBottomWidth' => '',
            'HeadBorderLeftStyle' => 'solid',
            'HeadBorderLeftColor' => 'rgb(183, 40, 46)',
            'HeadBorderLeftWidth' => '1',
        )
    );
    public static $templateBody = array(
        array(
            'PaddingTop' => '10',
            'PaddingLeft' => '10',
            'PaddingRight' => '10',
            'PaddingBottom' => '10',
            'PaddingTopUnit' => 'px',
            'PaddingLeftUnit' => 'px',
            'PaddingRightUnit' => 'px',
            'PaddingBottomUnit' => 'px',
            'BodyColor' => 'rgb(247,247,247)',
            'BodyMinSize' => '200',
            'MarginTop' => '0',
            'MarginLeft' => '0',
            'MarginRight' => '0',
            'MarginBottom' => '0',
            'MarginTopUnit' => 'px',
            'MarginLeftUnit' => 'px',
            'MarginRightUnit' => 'px',
            'MarginBottomUnit' => 'px',
            'BodyBorderTopStyle' => 'none',
            'BodyBorderTopColor' => '',
            'BodyBorderTopWidth' => '',
            'BodyBorderRightStyle' => 'none',
            'BodyBorderRightColor' => '',
            'BodyBorderRightWidth' => '',
            'BodyBorderBottomStyle' => 'none',
            'BodyBorderBottomColor' => '',
            'BodyBorderBottomWidth' => '',
            'BodyBorderLeftStyle' => 'none',
            'BodyBorderLeftColor' => '',
            'BodyBorderLeftWidth' => '',
        ),
        array(
            'PaddingTop' => '10',
            'PaddingLeft' => '4',
            'PaddingRight' => '4',
            'PaddingBottom' => '4',
            'PaddingTopUnit' => 'px',
            'PaddingLeftUnit' => '%',
            'PaddingRightUnit' => '%',
            'PaddingBottomUnit' => 'px',
            'BodyColor' => 'rgb(255, 255, 255)',
            'BodyMinSize' => '400',
            'MarginTop' => '0',
            'MarginLeft' => '10',
            'MarginRight' => '10',
            'MarginBottom' => '0',
            'MarginTopUnit' => 'px',
            'MarginLeftUnit' => '%',
            'MarginRightUnit' => '%',
            'MarginBottomUnit' => 'px',
            'BodyBorderTopStyle' => 'none',
            'BodyBorderTopColor' => '',
            'BodyBorderTopWidth' => '',
            'BodyBorderRightStyle' => 'solid',
            'BodyBorderRightColor' => 'rgb(183, 40, 46)',
            'BodyBorderRightWidth' => '1',
            'BodyBorderBottomStyle' => 'solid',
            'BodyBorderBottomColor' => 'rgb(183, 40, 46)',
            'BodyBorderBottomWidth' => '1',
            'BodyBorderLeftStyle' => 'solid',
            'BodyBorderLeftColor' => 'rgb(183, 40, 46)',
            'BodyBorderLeftWidth' => '1',
        )
    );
    public static $templateFoot = array(
        array(
            'FootColor' => 'rgb(247,247,247)',
            'FootHeight' => '200',
            'FootHeightUnit' => 'px',
            'FootPaddingTop' => '10',
            'FootPaddingLeft' => '10',
            'FootPaddingRight' => '10',
            'FootPaddingBottom' => '10',
            'FootMarginTop' => '0',
            'FootMarginLeft' => '0',
            'FootMarginRight' => '0',
            'FootMarginBottom' => '0',
            'FootPaddingTopUnit' => 'px',
            'FootPaddingLeftUnit' => 'px',
            'FootPaddingRightUnit' => 'px',
            'FootPaddingBottomUnit' => 'px',
            'FootMarginTopUnit' => 'px',
            'FootMarginLeftUnit' => 'px',
            'FootMarginRightUnit' => 'px',
            'FootMarginBottomUnit' => 'px',
            'FootBorderTopStyle' => 'none',
            'FootBorderTopColor' => '',
            'FootBorderTopWidth' => '',
            'FootBorderRightStyle' => 'none',
            'FootBorderRightColor' => '',
            'FootBorderRightWidth' => '',
            'FootBorderBottomStyle' => 'none',
            'FootBorderBottomColor' => '',
            'FootBorderBottomWidth' => '',
            'FootBorderLeftStyle' => 'none',
            'FootBorderLeftColor' => '',
            'FootBorderLeftWidth' => '',
        ),
        array(
            'FootColor' => 'rgb(255, 255, 255)',
            'FootHeight' => '150',
            'FootHeightUnit' => 'px',
            'FootPaddingTop' => '10',
            'FootPaddingLeft' => '4',
            'FootPaddingRight' => '4',
            'FootPaddingBottom' => '10',
            'FootMarginTop' => '0',
            'FootMarginLeft' => '10',
            'FootMarginRight' => '10',
            'FootMarginBottom' => '10',
            'FootPaddingTopUnit' => 'px',
            'FootPaddingLeftUnit' => '%',
            'FootPaddingRightUnit' => '%',
            'FootPaddingBottomUnit' => 'px',
            'FootMarginTopUnit' => 'px',
            'FootMarginLeftUnit' => '%',
            'FootMarginRightUnit' => '%',
            'FootMarginBottomUnit' => '%',
            'FootBorderTopStyle' => 'none',
            'FootBorderTopColor' => '',
            'FootBorderTopWidth' => '',
            'FootBorderRightStyle' => 'solid',
            'FootBorderRightColor' => 'rgb(183, 40, 46)',
            'FootBorderRightWidth' => '1',
            'FootBorderBottomStyle' => 'solid',
            'FootBorderBottomColor' => 'rgb(183, 40, 46)',
            'FootBorderBottomWidth' => '1',
            'FootBorderLeftStyle' => 'solid',
            'FootBorderLeftColor' => 'rgb(183, 40, 46)',
            'FootBorderLeftWidth' => '1',
        )
    );
    public static $templateTitles = array(
        array(
            'H1Color' => 'rgb(117, 202, 42)',
            'H1Weight' => '700',
            'H1Font' => '\'Helvetica Neue\', Helvetica, Arial, sans-serif',
            'H1Size' => '21',
            'H1SizeUnit' => 'px',
            'H1MarginTop' => '20',
            'H1MarginTopUnit' => 'px',
            'H1MarginBottom' => '10',
            'H1MarginBottomUnit' => 'px',
            'H1MarginRight' => '20',
            'H1MarginRightUnit' => 'px',
            'H1MarginLeft' => '20',
            'H1MarginLeftUnit' => 'px',
            'H1PaddingTop' => '20',
            'H1PaddingTopUnit' => 'px',
            'H1PaddingBottom' => '10',
            'H1PaddingBottomUnit' => 'px',
            'H1PaddingRight' => '20',
            'H1PaddingRightUnit' => 'px',
            'H1PaddingLeft' => '20',
            'H1PaddingLeftUnit' => 'px',
            'H2Color' => 'rgb(117, 202, 42)',
            'H2Weight' => '200',
            'H2Font' => '\'Titillium Web\'',
            'H2Size' => '32',
            'H2SizeUnit' => 'px',
            'H2MarginTop' => '20',
            'H2MarginTopUnit' => 'px',
            'H2MarginBottom' => '10',
            'H2MarginBottomUnit' => 'px',
            'H2MarginRight' => '20',
            'H2MarginRightUnit' => 'px',
            'H2MarginLeft' => '20',
            'H2MarginLeftUnit' => 'px',
            'H2PaddingTop' => '20',
            'H2PaddingTopUnit' => 'px',
            'H2PaddingBottom' => '10',
            'H2PaddingBottomUnit' => 'px',
            'H2PaddingRight' => '20',
            'H2PaddingRightUnit' => 'px',
            'H2PaddingLeft' => '20',
            'H2PaddingLeftUnit' => 'px',
            'H3Color' => 'rgb(117, 202, 42)',
            'H3Weight' => '200',
            'H3Font' => '\'Titillium Web\'',
            'H3Size' => '22',
            'H3SizeUnit' => 'px',
            'H3MarginTop' => '20',
            'H3MarginTopUnit' => 'px',
            'H3MarginBottom' => '10',
            'H3MarginBottomUnit' => 'px',
            'H3MarginRight' => '20',
            'H3MarginRightUnit' => 'px',
            'H3MarginLeft' => '20',
            'H3MarginLeftUnit' => 'px',
            'H3PaddingTop' => '20',
            'H3PaddingTopUnit' => 'px',
            'H3PaddingBottom' => '10',
            'H3PaddingBottomUnit' => 'px',
            'H3PaddingRight' => '20',
            'H3PaddingRightUnit' => 'px',
            'H3PaddingLeft' => '20',
            'H3PaddingLeftUnit' => 'px',
            'H4Color' => 'rgb(117, 202, 42)',
            'H4Weight' => '200',
            'H4Font' => '\'Titillium Web\'',
            'H4Size' => '17',
            'H4SizeUnit' => 'px',
            'H4MarginTop' => '20',
            'H4MarginTopUnit' => 'px',
            'H4MarginBottom' => '10',
            'H4MarginBottomUnit' => 'px',
            'H4MarginRight' => '20',
            'H4MarginRightUnit' => 'px',
            'H4MarginLeft' => '20',
            'H4MarginLeftUnit' => 'px',
            'H4PaddingTop' => '20',
            'H4PaddingTopUnit' => 'px',
            'H4PaddingBottom' => '10',
            'H4PaddingBottomUnit' => 'px',
            'H4PaddingRight' => '20',
            'H4PaddingRightUnit' => 'px',
            'H4PaddingLeft' => '20',
            'H4PaddingLeftUnit' => 'px',
            'H5Color' => 'rgb(117, 202, 42)',
            'H5Weight' => '200',
            'H5Font' => '\'Titillium Web\'',
            'H5Size' => '16',
            'H5SizeUnit' => 'px',
            'H5MarginTop' => '20',
            'H5MarginTopUnit' => 'px',
            'H5MarginBottom' => '10',
            'H5MarginBottomUnit' => 'px',
            'H5MarginRight' => '20',
            'H5MarginRightUnit' => 'px',
            'H5MarginLeft' => '20',
            'H5MarginLeftUnit' => 'px',
            'H5PaddingTop' => '20',
            'H5PaddingTopUnit' => 'px',
            'H5PaddingBottom' => '10',
            'H5PaddingBottomUnit' => 'px',
            'H5PaddingRight' => '20',
            'H5PaddingRightUnit' => 'px',
            'H5PaddingLeft' => '20',
            'H5PaddingLeftUnit' => 'px',
        ),
        array(
            'H1Color' => 'rgb(183, 40, 46)',
            'H1Weight' => '900',
            'H1Font' => 'Ubuntu',
            'H1Size' => '21',
            'H1SizeUnit' => 'px',
            'H1MarginTop' => '20',
            'H1MarginTopUnit' => 'px',
            'H1MarginBottom' => '10',
            'H1MarginBottomUnit' => 'px',
            'H1MarginRight' => '20',
            'H1MarginRightUnit' => 'px',
            'H1MarginLeft' => '20',
            'H1MarginLeftUnit' => 'px',
            'H1PaddingTop' => '5',
            'H1PaddingTopUnit' => 'px',
            'H1PaddingBottom' => '5',
            'H1PaddingBottomUnit' => 'px',
            'H1PaddingRight' => '20',
            'H1PaddingRightUnit' => 'px',
            'H1PaddingLeft' => '20',
            'H1PaddingLeftUnit' => 'px',
            'H2Color' => 'rgb(183, 40, 46)',
            'H2Weight' => '600',
            'H2Font' => 'Ubuntu',
            'H2Size' => '19',
            'H2SizeUnit' => 'px',
            'H2MarginTop' => '20',
            'H2MarginTopUnit' => 'px',
            'H2MarginBottom' => '20',
            'H2MarginBottomUnit' => 'px',
            'H2MarginRight' => '20',
            'H2MarginRightUnit' => 'px',
            'H2MarginLeft' => '20',
            'H2MarginLeftUnit' => 'px',
            'H2PaddingTop' => '20',
            'H2PaddingTopUnit' => 'px',
            'H2PaddingBottom' => '10',
            'H2PaddingBottomUnit' => 'px',
            'H2PaddingRight' => '20',
            'H2PaddingRightUnit' => 'px',
            'H2PaddingLeft' => '20',
            'H2PaddingLeftUnit' => 'px',
            'H3Color' => 'rgb(0, 0, 0)',
            'H3Weight' => '400',
            'H3Font' => 'Ubuntu',
            'H3Size' => '17',
            'H3SizeUnit' => 'px',
            'H3MarginTop' => '0',
            'H3MarginTopUnit' => 'px',
            'H3MarginBottom' => '0',
            'H3MarginBottomUnit' => 'px',
            'H3MarginRight' => '0',
            'H3MarginRightUnit' => 'px',
            'H3MarginLeft' => '0',
            'H3MarginLeftUnit' => 'px',
            'H3PaddingTop' => '0',
            'H3PaddingTopUnit' => 'px',
            'H3PaddingBottom' => '0',
            'H3PaddingBottomUnit' => 'px',
            'H3PaddingRight' => '0',
            'H3PaddingRightUnit' => 'px',
            'H3PaddingLeft' => '0',
            'H3PaddingLeftUnit' => 'px',
            'H4Color' => 'rgb(183, 40, 46)',
            'H4Weight' => 'bold',
            'H4Font' => 'Ubuntu',
            'H4Size' => '17',
            'H4SizeUnit' => 'px',
            'H4MarginTop' => '0',
            'H4MarginTopUnit' => 'px',
            'H4MarginBottom' => '0',
            'H4MarginBottomUnit' => 'px',
            'H4MarginRight' => '0',
            'H4MarginRightUnit' => 'px',
            'H4MarginLeft' => '0',
            'H4MarginLeftUnit' => 'px',
            'H4PaddingTop' => '0',
            'H4PaddingTopUnit' => 'px',
            'H4PaddingBottom' => '0',
            'H4PaddingBottomUnit' => 'px',
            'H4PaddingRight' => '0',
            'H4PaddingRightUnit' => 'px',
            'H4PaddingLeft' => '0',
            'H4PaddingLeftUnit' => 'px',
            'H5Color' => 'rgb(183, 40, 46)',
            'H5Weight' => '200',
            'H5Font' => 'Ubuntu',
            'H5Size' => '16',
            'H5SizeUnit' => 'px',
            'H5MarginTop' => '0',
            'H5MarginTopUnit' => 'px',
            'H5MarginBottom' => '0',
            'H5MarginBottomUnit' => 'px',
            'H5MarginRight' => '0',
            'H5MarginRightUnit' => 'px',
            'H5MarginLeft' => '0',
            'H5MarginLeftUnit' => 'px',
            'H5PaddingTop' => '0',
            'H5PaddingTopUnit' => 'px',
            'H5PaddingBottom' => '0',
            'H5PaddingBottomUnit' => 'px',
            'H5PaddingRight' => '0',
            'H5PaddingRightUnit' => 'px',
            'H5PaddingLeft' => '0',
            'H5PaddingLeftUnit' => 'px',
        )
    );

    public static function setConfig($headImageID)
    {
        $db = \Core::make('database');
        $p = $db->getRow('select uEmail from Users where uID = 1');
        \Config::save('toess_lab_news_letter.settings.files_num', 5);
        \Config::save('toess_lab_news_letter.settings.per_job', true);
        \Config::save('toess_lab_news_letter.settings.mails_per', 2);
        \Config::save('toess_lab_news_letter.settings.time_unit', 'Minute');
        \Config::save('toess_lab_news_letter.settings.owner_email', $p['uEmail']);
        \Config::save('toess_lab_news_letter.settings.mailing.test_email_address', $p['uEmail']);
        \Config::save('toess_lab_news_letter.settings.mail_settings.test_email_address', $p['uEmail']);
        \Config::save('toess_lab_news_letter.settings.templates.test_email_address', $p['uEmail']);
        \Config::save('toess_lab_news_letter.settings.newsletters.test_email_address', $p['uEmail']);
        \Config::save('toess_lab_news_letter.settings.report_newsletter', true);
        \Config::save('toess_lab_news_letter.settings.settings_made', false);
        \Config::save('toess_lab_news_letter.settings.browser_link_text', 'See this E-Mail in your Browser');
        \Config::save('toess_lab_news_letter.settings.file_size', 1048576);
        \Config::save('toess_lab_news_letter.settings.file_total_size', 5242880);

        \Config::save('toess_lab_news_letter.constants.min_files', 0);
        \Config::save('toess_lab_news_letter.constants.max_files', 10);
        \Config::save('toess_lab_news_letter.constants.mails_per', 1);

        \Config::save('toess_lab_news_letter.constants.max_number_per_minute', 60);
        \Config::save('toess_lab_news_letter.constants.max_number_per_hour', 60);
        \Config::save('toess_lab_news_letter.constants.max_number_per_day', 24);
        \Config::save('toess_lab_news_letter.constants.max_number_per_month', 31);
        \Config::save('toess_lab_news_letter.constants.head_image', $headImageID);
    }

    public static function setNewsletter($mail_template, $i)
    {
        $newsletter = new ToessLabNewsLetter();
        $newsletter->setTemplate($mail_template);

        $count = ($i == 0) ? 'One' : 'Two';
        $newsletter->setNLHandle('Newsletter ' . $count);
        $newsletter->setNLSubject('Subject ' . $count);
        $newsletter->setMailText('<h1>This is a default body title</h1><h2>Second title</h2><h3>Third title</h3><h4>Fourth title</h4><h5>Fifth title</h5><p>This is your Newsletter content</p>');
        $newsletter->setMailTextFooter('<h2>You may want to put your social links in here.</h2>');
        $newsletter->setHeaderText('<h2>This is the header.</h2><p>You may want to put your slogan or address in here</p>');
        $newsletter->setAttachments('');
        $newsletter->setModified(0);
        return $newsletter;
    }

    public static function setTemplateHead($tpl_id, $i, $headImageID)
    {
        extract(self::$templateHead[$i]);
        $mail_template = new ToessLabNewsLetterMailTemplateHead();
        $mail_template->setTplId($tpl_id);

        $mail_template->setMailLogo($headImageID);
        $mail_template->setLogoWidth($LogoWidth);
        $mail_template->setLogoHeight($LogoHeight);

        $mail_template->setLogoWidthUnit($LogoWidthUnit);
        $mail_template->setLogoHeightUnit($LogoHeightUnit);

        $mail_template->setHeadColor($HeadColor);

        $mail_template->setHeadPaddingTop($HeadPaddingTop);
        $mail_template->setHeadPaddingLeft($HeadPaddingLeft);
        $mail_template->setHeadPaddingRight($HeadPaddingRight);
        $mail_template->setHeadPaddingBottom($HeadPaddingBottom);

        $mail_template->setHeadPaddingTopUnit($HeadPaddingTopUnit);
        $mail_template->setHeadPaddingLeftUnit($HeadPaddingLeftUnit);
        $mail_template->setHeadPaddingRightUnit($HeadPaddingRightUnit);
        $mail_template->setHeadPaddingBottomUnit($HeadPaddingBottomUnit);

        $mail_template->setHeadMarginTop($HeadMarginTop);
        $mail_template->setHeadMarginLeft($HeadMarginLeft);
        $mail_template->setHeadMarginRight($HeadMarginRight);
        $mail_template->setHeadMarginBottom($HeadMarginBottom);

        $mail_template->setHeadMarginTopUnit($HeadMarginTopUnit);
        $mail_template->setHeadMarginLeftUnit($HeadMarginLeftUnit);
        $mail_template->setHeadMarginRightUnit($HeadMarginRightUnit);
        $mail_template->setHeadMarginBottomUnit($HeadMarginBottomUnit);

        $mail_template->setLogoTop($LogoTop);
        $mail_template->setLogoLeft($LogoLeft);
        $mail_template->setLogoRight($LogoRight);
        $mail_template->setLogoBottom($LogoBottom);

        $mail_template->setLogoTopUnit($LogoTopUnit);
        $mail_template->setLogoLeftUnit($LogoLeftUnit);
        $mail_template->setLogoRightUnit($LogoRightUnit);
        $mail_template->setLogoBottomUnit($LogoBottomUnit);

        $mail_template->setHeadBorderTopStyle($HeadBorderTopStyle);
        $mail_template->setHeadBorderTopColor($HeadBorderTopColor);
        $mail_template->setHeadBorderTopWidth($HeadBorderTopWidth);

        $mail_template->setHeadBorderRightStyle($HeadBorderRightStyle);
        $mail_template->setHeadBorderRightColor($HeadBorderRightColor);
        $mail_template->setHeadBorderRightWidth($HeadBorderRightWidth);

        $mail_template->setHeadBorderBottomStyle($HeadBorderBottomStyle);
        $mail_template->setHeadBorderBottomColor($HeadBorderBottomColor);
        $mail_template->setHeadBorderBottomWidth($HeadBorderBottomWidth);

        $mail_template->setHeadBorderLeftStyle($HeadBorderLeftStyle);
        $mail_template->setHeadBorderLeftColor($HeadBorderLeftColor);
        $mail_template->setHeadBorderLeftWidth($HeadBorderLeftWidth);

        return $mail_template;
    }

    public static function setTemplateBody($tpl_id, $i)
    {
        extract(self::$templateBody[$i]);
        $mail_template = new ToessLabNewsLetterMailTemplateBody();
        $mail_template->setTplId($tpl_id);

        $mail_template->setPaddingTop($PaddingTop);
        $mail_template->setPaddingLeft($PaddingLeft);
        $mail_template->setPaddingRight($PaddingRight);
        $mail_template->setPaddingBottom($PaddingBottom);

        $mail_template->setPaddingTopUnit($PaddingTopUnit);
        $mail_template->setPaddingLeftUnit($PaddingLeftUnit);
        $mail_template->setPaddingRightUnit($PaddingRightUnit);
        $mail_template->setPaddingBottomUnit($PaddingBottomUnit);
        $mail_template->setBodyColor($BodyColor);

        $mail_template->setBodyMinSize($BodyMinSize);

        $mail_template->setMarginTop($MarginTop);
        $mail_template->setMarginLeft($MarginLeft);
        $mail_template->setMarginRight($MarginRight);
        $mail_template->setMarginBottom($MarginBottom);

        $mail_template->setMarginTopUnit($MarginTopUnit);
        $mail_template->setMarginLeftUnit($MarginLeftUnit);
        $mail_template->setMarginRightUnit($MarginRightUnit);
        $mail_template->setMarginBottomUnit($MarginBottomUnit);

        $mail_template->setBodyBorderTopStyle($BodyBorderTopStyle);
        $mail_template->setBodyBorderTopColor($BodyBorderTopColor);
        $mail_template->setBodyBorderTopWidth($BodyBorderTopWidth);

        $mail_template->setBodyBorderRightStyle($BodyBorderRightStyle);
        $mail_template->setBodyBorderRightColor($BodyBorderRightColor);
        $mail_template->setBodyBorderRightWidth($BodyBorderRightWidth);

        $mail_template->setBodyBorderBottomStyle($BodyBorderBottomStyle);
        $mail_template->setBodyBorderBottomColor($BodyBorderBottomColor);
        $mail_template->setBodyBorderBottomWidth($BodyBorderBottomWidth);

        $mail_template->setBodyBorderLeftStyle($BodyBorderLeftStyle);
        $mail_template->setBodyBorderLeftColor($BodyBorderLeftColor);
        $mail_template->setBodyBorderLeftWidth($BodyBorderLeftWidth);

        return $mail_template;
    }

    public static function setTemplateFoot($tpl_id, $i)
    {
        extract(self::$templateFoot[$i]);
        $mail_template = new ToessLabNewsLetterMailTemplateFoot();
        $mail_template->setTplId($tpl_id);

        $mail_template->setFootColor($FootColor);
        $mail_template->setFootHeight($FootHeight);
        $mail_template->setFootHeightUnit($FootHeightUnit);

        $mail_template->setFootPaddingTop($FootPaddingTop);
        $mail_template->setFootPaddingLeft($FootPaddingLeft);
        $mail_template->setFootPaddingRight($FootPaddingRight);
        $mail_template->setFootPaddingBottom($FootPaddingBottom);

        $mail_template->setFootMarginTop($FootMarginTop);
        $mail_template->setFootMarginLeft($FootMarginLeft);
        $mail_template->setFootMarginRight($FootMarginRight);
        $mail_template->setFootMarginBottom($FootMarginBottom);

        $mail_template->setFootPaddingTopUnit($FootPaddingTopUnit);
        $mail_template->setFootPaddingLeftUnit($FootPaddingLeftUnit);
        $mail_template->setFootPaddingRightUnit($FootPaddingRightUnit);
        $mail_template->setFootPaddingBottomUnit($FootPaddingBottomUnit);

        $mail_template->setFootMarginTopUnit($FootMarginTopUnit);
        $mail_template->setFootMarginLeftUnit($FootMarginLeftUnit);
        $mail_template->setFootMarginRightUnit($FootMarginRightUnit);
        $mail_template->setFootMarginBottomUnit($FootMarginBottomUnit);

        $mail_template->setFootBorderTopStyle($FootBorderTopStyle);
        $mail_template->setFootBorderTopColor($FootBorderTopColor);
        $mail_template->setFootBorderTopWidth($FootBorderTopWidth);

        $mail_template->setFootBorderRightStyle($FootBorderRightStyle);
        $mail_template->setFootBorderRightColor($FootBorderRightColor);
        $mail_template->setFootBorderRightWidth($FootBorderRightWidth);

        $mail_template->setFootBorderBottomStyle($FootBorderBottomStyle);
        $mail_template->setFootBorderBottomColor($FootBorderBottomColor);
        $mail_template->setFootBorderBottomWidth($FootBorderBottomWidth);

        $mail_template->setFootBorderLeftStyle($FootBorderLeftStyle);
        $mail_template->setFootBorderLeftColor($FootBorderLeftColor);
        $mail_template->setFootBorderLeftWidth($FootBorderLeftWidth);

        return $mail_template;
    }

    public static function setTemplateTitle($tpl_id, $i)
    {
        extract(self::$templateTitles[$i]);
        $mail_template = new ToessLabNewsLetterMailTemplateTitle();
        $mail_template->setTplId($tpl_id);

        $mail_template->setH1Color($H1Color);
        $mail_template->setH1Weight($H1Weight);
        $mail_template->setH1Font($H1Font);
        $mail_template->setH1Size($H1Size);
        $mail_template->setH1SizeUnit($H1SizeUnit);

        $mail_template->setH1MarginTop($H1MarginTop);
        $mail_template->setH1MarginTopUnit($H1MarginTopUnit);

        $mail_template->setH1MarginBottom($H1MarginBottom);
        $mail_template->setH1MarginBottomUnit($H1MarginBottomUnit);

        $mail_template->setH1MarginRight($H1MarginRight);
        $mail_template->setH1MarginRightUnit($H1MarginRightUnit);

        $mail_template->setH1MarginLeft($H1MarginLeft);
        $mail_template->setH1MarginLeftUnit($H1MarginLeftUnit);

        $mail_template->setH1PaddingTop($H1PaddingTop);
        $mail_template->setH1PaddingTopUnit($H1PaddingTopUnit);

        $mail_template->setH1PaddingBottom($H1PaddingBottom);
        $mail_template->setH1PaddingBottomUnit($H1PaddingBottomUnit);

        $mail_template->setH1PaddingRight($H1PaddingRight);
        $mail_template->setH1PaddingRightUnit($H1PaddingRightUnit);

        $mail_template->setH1PaddingLeft($H1PaddingLeft);
        $mail_template->setH1PaddingLeftUnit($H1PaddingLeftUnit);

        $mail_template->setH2Color($H2Color);
        $mail_template->setH2Weight($H2Weight);
        $mail_template->setH2Font($H2Font);
        $mail_template->setH2Size($H2Size);
        $mail_template->setH2SizeUnit($H2SizeUnit);

        $mail_template->setH2MarginTop($H2MarginTop);
        $mail_template->setH2MarginTopUnit($H2MarginTopUnit);

        $mail_template->setH2MarginBottom($H2MarginBottom);
        $mail_template->setH2MarginBottomUnit($H2MarginBottomUnit);

        $mail_template->setH2MarginRight($H2MarginRight);
        $mail_template->setH2MarginRightUnit($H2MarginRightUnit);

        $mail_template->setH2MarginLeft($H2MarginLeft);
        $mail_template->setH2MarginLeftUnit($H2MarginLeftUnit);

        $mail_template->setH2PaddingTop($H2PaddingTop);
        $mail_template->setH2PaddingTopUnit($H2PaddingTopUnit);

        $mail_template->setH2PaddingBottom($H2PaddingBottom);
        $mail_template->setH2PaddingBottomUnit($H2PaddingBottomUnit);

        $mail_template->setH2PaddingRight($H2PaddingRight);
        $mail_template->setH2PaddingRightUnit($H2PaddingRightUnit);

        $mail_template->setH2PaddingLeft($H2PaddingLeft);
        $mail_template->setH2PaddingLeftUnit($H2PaddingLeftUnit);

        $mail_template->setH3Color($H3Color);
        $mail_template->setH3Weight($H3Weight);
        $mail_template->setH3Font($H3Font);
        $mail_template->setH3Size($H3Size);
        $mail_template->setH3SizeUnit($H3SizeUnit);

        $mail_template->setH3MarginTop($H3MarginTop);
        $mail_template->setH3MarginTopUnit($H3MarginTopUnit);

        $mail_template->setH3MarginBottom($H3MarginBottom);
        $mail_template->setH3MarginBottomUnit($H3MarginBottomUnit);

        $mail_template->setH3MarginRight($H3MarginRight);
        $mail_template->setH3MarginRightUnit($H3MarginRightUnit);

        $mail_template->setH3MarginLeft($H3MarginLeft);
        $mail_template->setH3MarginLeftUnit($H3MarginLeftUnit);

        $mail_template->setH3PaddingTop($H3PaddingTop);
        $mail_template->setH3PaddingTopUnit($H3PaddingTopUnit);

        $mail_template->setH3PaddingBottom($H3PaddingBottom);
        $mail_template->setH3PaddingBottomUnit($H3PaddingBottomUnit);

        $mail_template->setH3PaddingRight($H3PaddingRight);
        $mail_template->setH3PaddingRightUnit($H3PaddingRightUnit);

        $mail_template->setH3PaddingLeft($H3PaddingLeft);
        $mail_template->setH3PaddingLeftUnit($H3PaddingLeftUnit);

        $mail_template->setH4Color($H4Color);
        $mail_template->setH4Weight($H4Weight);
        $mail_template->setH4Font($H4Font);
        $mail_template->setH4Size($H4Size);
        $mail_template->setH4SizeUnit($H4SizeUnit);

        $mail_template->setH4MarginTop($H4MarginTop);
        $mail_template->setH4MarginTopUnit($H4MarginTopUnit);

        $mail_template->setH4MarginBottom($H4MarginBottom);
        $mail_template->setH4MarginBottomUnit($H4MarginBottomUnit);

        $mail_template->setH4MarginRight($H4MarginRight);
        $mail_template->setH4MarginRightUnit($H4MarginRightUnit);

        $mail_template->setH4MarginLeft($H4MarginLeft);
        $mail_template->setH4MarginLeftUnit($H4MarginLeftUnit);

        $mail_template->setH4PaddingTop($H4PaddingTop);
        $mail_template->setH4PaddingTopUnit($H4PaddingTopUnit);

        $mail_template->setH4PaddingBottom($H4PaddingBottom);
        $mail_template->setH4PaddingBottomUnit($H4PaddingBottomUnit);

        $mail_template->setH4PaddingRight($H4PaddingRight);
        $mail_template->setH4PaddingRightUnit($H4PaddingRightUnit);

        $mail_template->setH4PaddingLeft($H4PaddingLeft);
        $mail_template->setH4PaddingLeftUnit($H4PaddingLeftUnit);

        $mail_template->setH5Color($H5Color);
        $mail_template->setH5Weight($H5Weight);
        $mail_template->setH5Font($H5Font);
        $mail_template->setH5Size($H5Size);
        $mail_template->setH5SizeUnit($H5SizeUnit);

        $mail_template->setH5MarginTop($H5MarginTop);
        $mail_template->setH5MarginTopUnit($H5MarginTopUnit);

        $mail_template->setH5MarginBottom($H5MarginBottom);
        $mail_template->setH5MarginBottomUnit($H5MarginBottomUnit);

        $mail_template->setH5MarginRight($H5MarginRight);
        $mail_template->setH5MarginRightUnit($H5MarginRightUnit);

        $mail_template->setH5MarginLeft($H5MarginLeft);
        $mail_template->setH5MarginLeftUnit($H5MarginLeftUnit);

        $mail_template->setH5PaddingTop($H5PaddingTop);
        $mail_template->setH5PaddingTopUnit($H5PaddingTopUnit);

        $mail_template->setH5PaddingBottom($H5PaddingBottom);
        $mail_template->setH5PaddingBottomUnit($H5PaddingBottomUnit);

        $mail_template->setH5PaddingRight($H5PaddingRight);
        $mail_template->setH5PaddingRightUnit($H5PaddingRightUnit);

        $mail_template->setH5PaddingLeft($H5PaddingLeft);
        $mail_template->setH5PaddingLeftUnit($H5PaddingLeftUnit);

        return $mail_template;
    }

    public static function setUserAttribute($pkg)
    {
        $args_receive_newsletter = array(
            'akHandle' => 'toesslab_receive_newsletter_new',
            'akName' => t('Receive toesslab - Newsletter'),
            'uakProfileDisplay' => true,
            'uakMemberListDisplay' => true,
            'uakProfileEdit' => true,
            'uakProfileEditRequired' => false,
            'uakRegisterEdit' => true,
            'uakRegisterEditRequired' => true,
            'akSelectOptionDisplayOrder' => 'alpha_asc',
            'akIsSearchableIndexed' => true,
            'akIsSearchable' => true,
            'akCheckedByDefault' => true
        );
        $args_first_name = array(
            'akHandle' => 'toesslab_first_name',
            'akName' => t('toesslab - First Name'),
            'uakProfileDisplay' => true,
            'uakMemberListDisplay' => true,
            'uakProfileEdit' => true,
            'uakProfileEditRequired' => true,
            'uakRegisterEdit' => true,
            'uakRegisterEditRequired' => true,
            'akIsSearchableIndexed' => true,
            'akIsSearchable' => true,
            'akCheckedByDefault' => true
        );
        $args_last_name = array(
            'akHandle' => 'toesslab_last_name',
            'akName' => t('toesslab - Last Name'),
            'uakProfileDisplay' => true,
            'uakMemberListDisplay' => true,
            'uakProfileEdit' => true,
            'uakProfileEditRequired' => true,
            'uakRegisterEdit' => true,
            'uakRegisterEditRequired' => true,
            'akIsSearchableIndexed' => true,
            'akIsSearchable' => true,
            'akCheckedByDefault' => true
        );
        $args_address = array(
            'akHandle' => 'toesslab_address',
            'akName' => t('toesslab - Address'),
            'uakProfileDisplay' => true,
            'uakMemberListDisplay' => true,
            'uakProfileEdit' => true,
            'uakProfileEditRequired' => true,
            'uakRegisterEdit' => true,
            'uakRegisterEditRequired' => true,
            'akIsSearchableIndexed' => true,
            'akSelectAllowOtherValues' => false,
            'akSelectOptionDisplayOrder' => 'alpha_asc',
            'akIsSearchable' => true,
            'akCheckedByDefault' => true
        );
        $attr_text = AttributeType::getByHandle('text');
        $attr_select = AttributeType::getByHandle('select');
        $attr_checkbox = AttributeType::getByHandle('boolean');
        $attrCategory = AttributeKeyCategory::getByHandle('user');
        $attrCategory->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_MULTIPLE);
        $attrSet = $attrCategory->addSet('toesslab_attribute_category', t('toesslab - Newsletter'), $pkg);
        $attrReceiveNewsletterExists = UserAttributeKey::getByHandle($args_receive_newsletter['akHandle']);
        $attrFirstNameExists = UserAttributeKey::getByHandle($args_first_name['akHandle']);
        $attrLastNameExists = UserAttributeKey::getByHandle($args_last_name['akHandle']);
        $attrAddressExists = UserAttributeKey::getByHandle($args_address['akHandle']);
        if(!$attrReceiveNewsletterExists) {
            if (self::checkC5Version('8.2')){
                $select_newsletter = UserAttributeKey::add($attr_checkbox, $args_receive_newsletter, $pkg);
                $select_newsletter->setAttributeSet($attrSet);
            } else {
                UserAttributeKey::add($attr_checkbox, $args_receive_newsletter, $pkg)->setAttributeSet($attrSet);
                $select_newsletter = UserKey::getByHandle($args_receive_newsletter['akHandle']);
            }

            $select_newsletter->setAttributeKeyDisplayedOnProfile(true);
            $select_newsletter->setAttributeKeyRequiredOnRegister(true);
            $select_newsletter->setAttributeKeyEditableOnProfile(true);
            $select_newsletter->setAttributeKeyRequiredOnProfile(true);
            $select_newsletter->setAttributeKeyEditableOnRegister(true);
            $select_newsletter->setAttributeKeyRequiredOnRegister(true);
        }
        if(!$attrFirstNameExists) {
            UserAttributeKey::add($attr_text, $args_first_name, $pkg)->setAttributeSet($attrSet);
        }
        if(!$attrLastNameExists) {
            UserAttributeKey::add($attr_text, $args_last_name, $pkg)->setAttributeSet($attrSet);
        }
        if(!$attrAddressExists) {
            if (self::checkC5Version('8.2')){
                $select_address = UserAttributeKey::add($attr_select, $args_address, $pkg);
                $select_address->setAttributeSet($attrSet);
            } else {
                UserAttributeKey::add($attr_select, $args_address, $pkg)->setAttributeSet($attrSet);
                $select_address = UserKey::getByHandle($args_receive_newsletter['akHandle']);
            }
            Option::add($select_address, t('Madam'));
            Option::add($select_address, t('Sir'));
            Option::add($select_address, t('Dear Madam'));
            Option::add($select_address, t('Dear Sir'));
            Option::add($select_address, t('Dear'));
            Option::add($select_address, t('Mister'));
            Option::add($select_address, t('Misses'));
        }
    }

    /**
     * Gets Google fonts list from a DB table of daniel-gasser.com
     *
     * @return array
     */
    public static function getGoogleFonts()
    {
        $session = \Core::make('session');
        $url = 'http://daniel-gasser.com/googlefonts/';
        $res = array();
        $fs = new Filesystem();
        $nf = array(
            'Arial' => 'Arial',
            'Helvetica' => 'Helvetica',
            'Georgia' => 'Georgia',
            'Times New Roman' => 'Times New Roman',
            'Monospace' => 'Monospace',
        );
        try {
            $ch = $fs->get($url);
            foreach(json_decode($ch) as $value) {
                foreach((array)$value as $k => $v) {
                    if($k == 'family') {
                        $res[$v] = $v;
                    }
                }
            }
        } catch (\Exception $e) {
            $l = new GroupLogger(LOG_TYPE_EXCEPTIONS, Logger::WARNING);
            $l->write(t('Exception Occurred. Unable to load Google Fonts from %s.', $url));
            $l->write(t('Error message: %s', $e->getMessage()));
            $l->write(t('Please contact support at <a href="%s">support@toesslab.ch</a>', 'mailto:support@toesslab.ch'));
            $l->close();
            $session->set('google_font_error', t('Unable to load Google Fonts. See the <a href="%s">Logs</a> for more information.', \URL::to('/dashboard/reports/logs')));
            \Config::save('toess_lab_news_letter.constants.google_fonts', $nf);
        }
        $session->remove('google_font_error');
        \Config::save('toess_lab_news_letter.constants.google_fonts', $nf + $res);
    }

    public static function checkC5Version($v = '8.0', $o = '>=')
    {
        return (version_compare(\Config::get('concrete.version'), $v, $o)) ? true : false;
    }
}
