<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/templates/new_template.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates;

use Concrete\Core\File\File;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Doctrine\DBAL\DBALException;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Mail;
use OAuth\Common\Exception\Exception;
use FileAttributeKey;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Settings as Settings;

class NewTemplate extends DashboardPageController {


    /**
     * @var string
     */
    public $tl_handle = '';

    /**
     * @var string
     */
    public $nl_subject = '';

    /**
     * @var string
     */
    public $field_name_logo = '';

    /**
     * @var array
     */
    public $units = array();

    /**
     * @var array
     */
    public $border_style = array();

    /**
     * @var array
     */
    public $font_weight = array();

    /**
     * @var array
     */
    public $font_style = array();

    /**
     * @var array
     */
    public $google_fonts = array();

    public function view()
    {
        if(!Settings::checkFirstTimeSettings()) {
            $this->error->add(t('Please adapt the Settings before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        if(!Settings::checkFirstTimeTemplate()) {
            $this->error->add(t('Don\'t forget to design a Template before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        if(!Settings::checkFirstTimeNewsletter()) {
            $this->error->add(t('Don\'t forget to create a Newsletter before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        $this->set('newsletters_d', $this->getNewsletterDropDown());
        $this->set('owner_email', \Config::get('toess_lab_news_letter.settings.owner_email'));
        $this->set('family_font', \Config::get('toess_lab_news_letter.constants.google_fonts'));
    }

    public function on_start()
    {
        $this->requireAsset('css', 'toesslab');
        $this->requireAsset('javascript', 'toesslab');
        $this->requireAsset('css', 'bootstrapswitch');
        $this->requireAsset('javascript', 'bootstrapswitch');
        $this->tl_handle = t('Template Name');
        $this->nl_subject = t('Newsletter subject');
        $this->field_name_logo = t('Header logo');
        $this->units = array(
            'px' => t('Pixels'),
            '%' => t('Percent'),
        );
        $this->font_weight = array(
            '100' => '100',
            '200' => '200',
            '300' => '300',
            '400' => '400',
            '500' => '500',
            '600' => '600',
            '700' => '700',
            '800' => '800',
            '900' => '900',
            'bold' => t('bold'),
            'bolder' => t('bolder'),
            'inherit' => t('inherit'),
            'initial' => t('initial'),
            'lighter' => t('lighter'),
            'normal' => t('normal')

        );
        $this->font_style = array(
            'italic' => t('italic'),
            'normal' => t('normal'),
            'oblique' => t('oblique')

        );
        $this->border_style = array(
            'none' => t('None'),
            'hidden' => t('Hidden'),
            'dotted' => t('Dotted'),
            'dashed' => t('Dashed'),
            'solid' => t('Solid'),
            'double' => t('Double'),
            'groove' => t('Groove'),
            'ridge' => t('Ridge'),
            'inset' => t('Inset'),
            'outset' => t('Outset'),
            'initial' => t('Initial')
        );
        parent::on_start();
    }

    /**
     * Saves the styling dialog settings in config file
     */
    public function save_dialog_config()
    {
        $styling_dialog_show = \Request::getInstance()->get('styling_dialog_show');
        \Config::save('toess_lab_news_letter.dialogs.styling_dialog_show', ($styling_dialog_show == '1') ? false : true);

        echo \Core::make('helper/json')->encode(\Config::get('toess_lab_news_letter.dialogs.styling_dialog_show'));
        exit;
    }

    /**
     * @param bool $tl_id
     * @param bool $handle
     */
    public static function delete_template($tl_id = false, $handle = false)
    {
        self::checkEditable($tl_id);
        $session = \Core::make('session');
        $entity_manager = \ORM::entityManager();
        $template =  $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $tl_id);
        $template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $tl_id));
        $template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $tl_id));
        $template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $tl_id));
        $template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $tl_id));
        $newsletter =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')->findBy(array('mailtemplateID' => $tl_id));
        if(sizeof($newsletter) > 0){
            $session->set('error', t('This Template is attached to the Newsletter \'%s\'. You can\'t delete it.', $newsletter[0]->getNLHandle()));
            $response = \Redirect::to('/dashboard/newsletter/templates/template_list');
            $response->send();
            exit;

        }
        try {
            $entity_manager->remove($template_head);
            $entity_manager->flush();
            $entity_manager->remove($template_body);
            $entity_manager->flush();
            $entity_manager->remove($template_foot);
            $entity_manager->flush();
            $entity_manager->remove($template_title);
            $entity_manager->flush();
            $entity_manager->remove($template);
            $entity_manager->flush();
        }
        catch(Exception $e){
            $session->set('error', $e->getMessage());
        }
        $session->set('message', t('Template \'%s\' has been deleted', $handle));
        $response = \Redirect::to('/dashboard/newsletter/templates/template_list');
        $response->send();
        exit;

    }

    /**
     * @param bool $tl_id
     * @param bool $handle
     */
    public static function duplicate_template($tl_id = false, $handle = false)
    {
        $session = \Core::make('session');
        $handle_like = explode(' (', $handle);
        $entity_manager = \ORM::entityManager();
        $template =  $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $tl_id);
        $template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $tl_id));
        $template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $tl_id));
        $template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $tl_id));
        $template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $tl_id));
        $allTemplates = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate')
            ->createQueryBuilder('t')
            ->select('t.templateHandle')
            ->where('t.templateHandle LIKE :handle')
            ->setParameter('handle', '%' . $handle_like[0] . '%')
            ->getQuery()
            ->getResult();
        $exist_handle_counter = sizeof($allTemplates);
        $template_copy = unserialize(serialize($template));
        $template_copy_head = unserialize(serialize($template_head));
        $template_copy_body = unserialize(serialize($template_body));
        $template_copy_foot = unserialize(serialize($template_foot));
        $template_copy_title = unserialize(serialize($template_title));
        $new_handle_counter = $exist_handle_counter;
        $template_copy->setMailTemplateHandle($handle_like[0] . ' (' . $new_handle_counter . ')');

        $entity_manager->persist($template_copy);
        try {
            $entity_manager->flush();
            $tid = $template_copy->getMailTemplateId();
            $template_copy_head->setTplId($tid);
            $template_copy_body->setTplId($tid);
            $template_copy_foot->setTplId($tid);
            $template_copy_title->setTplId($tid);
            $entity_manager->persist($template_copy_head);
            $entity_manager->persist($template_copy_body);
            $entity_manager->persist($template_copy_foot);
            $entity_manager->persist($template_copy_title);
            $entity_manager->flush();
        }catch(DBALException $e){
            if($e->getPrevious() &&  0 === strpos($e->getPrevious()->getCode(), '23')){
                $session->set('error', t('Template \'%s\' has not been duplicated<br>%s', $handle, $e->getMessage()));
                $response = \Redirect::to('/dashboard/newsletter/templates/template_list');
                $response->send();
                exit;
            }
        }

        $session->set('message', t('Template \'%s\' has been duplicated', $handle));
        $response = \Redirect::to('/dashboard/newsletter/templates/template_list');
        $response->send();
        exit;

    }

    /**
     * Saves the mail-template to DB
     *
     */
    public function save_template()
    {
        $proceededPosts = array();
        $returnArray = array();
        $th = \Core::make('helper/text');
        $session = \Core::make('session');
        $session->set('selectedTabs', explode(',', \Core::make('helper/security')->sanitizeString($this->post('selected_tabs'))));
        $entity_manager = \ORM::entityManager();
        $file_service = \Core::make('helper/file');
        $fh = \Core::make('helper/mime');
        $mail_logo = \Core::make('helper/security')->sanitizeInt(intval($this->post('mail_logo')));
        $im_type_string = array(image_type_to_mime_type(IMAGETYPE_GIF), image_type_to_mime_type(IMAGETYPE_JPEG), image_type_to_mime_type(IMAGETYPE_PNG));

        $newsletter_template_id = \Core::make('helper/security')->sanitizeInt(intval($this->post('newsletter_template_id')));
        $tl_handle = \Core::make('helper/security')->sanitizeString($this->post('tl_handle'));
        if (!$this->token->validate('save_template')) {
             $this->set('error', array($this->token->getErrorMessage()));
            $session->set('selectedTabs', array('general'));
        }
        if (strlen($tl_handle) == 0){
            $this->error->add(t('The field "%s" is required', $this->tl_handle));
            $session->set('selectedTabs', array('general'));
        }
        if(strlen($tl_handle) > 255) {
            $this->error->add(t('The field "%s" is limited to 255 characters', $this->tl_handle));
            $session->set('selectedTabs', array('general'));
        }
        if($this->error->has()) {
            $this->view();
            return;
        }
        if(intval($newsletter_template_id) > 0){
            $mail_template = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $newsletter_template_id);
            $mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $newsletter_template_id));
            $mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $newsletter_template_id));
            $mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $newsletter_template_id));
            $mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $newsletter_template_id));
        } else {
            $mail_template = new ToessLabNewsLetterMailTemplate();
            $mail_template_head = new ToessLabNewsLetterMailTemplateHead();
            $mail_template_body = new ToessLabNewsLetterMailTemplateBody();
            $mail_template_foot = new ToessLabNewsLetterMailTemplateFoot();
            $mail_template_title = new ToessLabNewsLetterMailTemplateTitle();
        }
        if(intval($mail_logo) > 0) {
            $file = File::getRelativePathFromID(intval($mail_logo));
            $extension = $file_service->getExtension($file);
            if(!in_array($fh->mimeFromExtension($extension), $im_type_string)){
                $this->error->add(t('The field "%s" must be an image. (%s)', $this->field_name_logo, implode(', ', $im_type_string)));
            } else {
                $mail_template_head->setMailLogo($mail_logo);
            }
        }else{
            $mail_template_head->setMailLogo(0);
        }
        $this->set('newsletters_d', self::getNewsletterDropDown());
        foreach($this->post() as $key => $post){
            if($key == 'test_mail') continue;
            $proceededPosts[$key] = $th->sanitize($post);
        }
        $session->set('newsletter_id', $proceededPosts['nl_newsletter_preview']);
        $proceededTpl = self::setTemplateData($mail_template, $proceededPosts);
        $entity_manager->persist($proceededTpl);
        try {
            $entity_manager->flush();
        }catch(DBALException $e){
            if($e->getPrevious() &&  0 === strpos($e->getPrevious()->getCode(), '23' && intval($entity_manager->getConnection()->lastInsertId()) > 0)){
                $this->error->add(t('The Template name \'%s\' is already in use. Please choose another one.', $proceededPosts['tl_handle']));
                $session->set('selectedTabs', array('general'));
            }
        }
        if($this->error->has()) {
            $this->view();
            return;
        }
        $proceededTplHead = self::setTemplateDataHead($mail_template_head, $proceededPosts, $mail_template->getMailTemplateId());
        $entity_manager->persist($proceededTplHead);
        $proceededTplBody = self::setTemplateDataBody($mail_template_body, $proceededPosts, $mail_template->getMailTemplateId());
        $entity_manager->persist($proceededTplBody);
        $proceededTplFoot = self::setTemplateDataFoot($mail_template_foot, $proceededPosts, $mail_template->getMailTemplateId());
        $entity_manager->persist($proceededTplFoot);
        $proceededTplTitle = self::setTemplateDataTitle($mail_template_title, $proceededPosts, $mail_template->getMailTemplateId());
        $entity_manager->persist($proceededTplTitle);
        $entity_manager->flush();

        $returnArray['tpl'] = self::getMailTemplate($proceededTpl, $proceededTplHead, $proceededTplBody, $proceededTplFoot, $proceededTplTitle);
        $returnArray['mail_logo'] = $proceededTplHead->getMailLogo();
        $returnArray['newsletter_template_id'] = $entity_manager->getConnection()->lastInsertId();
        $returnArray['success_saved'] = t('The Template \'%s\' has been saved', $proceededTpl->getMailTemplateHandle());
        $returnArray['newsletters_d'] = self::getNewsletterDropDown();
        $this->set('tpl', $returnArray['tpl']);
        $session->set('mail_logo', $returnArray['mail_logo']);
        $session->set('newsletter_template_id', $returnArray['newsletter_template_id']);
        $session->set('success_saved', $returnArray['success_saved']);
        $session->set('newsletters_d', $returnArray['newsletters_d']);
        $response = \Redirect::to('/dashboard/newsletter/templates/new_template/-/edit', $proceededTpl->getMailTemplateId());
        $response->send();
        exit;
    }

    /**
     * Edit existent template
     *
     * @param bool $mailtemplateID (int ID)
     */
    public function edit($mailtemplateID = false)
    {
        $template_id = \Core::make('helper/security')->sanitizeInt($mailtemplateID);
        $entity_manager = \ORM::entityManager();
        self::checkEditable($template_id);
        $mail_template = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $template_id);
        $mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $template_id));
        $mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $template_id));
        $mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $template_id));
        $mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $template_id));
        $tpl = self::getMailTemplate($mail_template, $mail_template_head, $mail_template_body, $mail_template_foot, $mail_template_title);
        $this->set('tpl', $tpl);
        $this->set('mail_template', $mail_template);
        $this->set('mail_template_head', $mail_template_head);
        $this->set('mail_template_body', $mail_template_body);
        $this->set('mail_template_foot', $mail_template_foot);
        $this->set('mail_template_title', $mail_template_title);
        $this->set('newsletter_template_id', $template_id);
        $this->set('newsletters_d', self::getNewsletterDropDown());
        $this->view();
    }

    /**
     * Gets newsletter(n_id) & template(t_id)
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get_newsletter()
    {
        $t_id = \Request::getInstance()->get('t_id');
        $n_id = \Request::getInstance()->get('n_id');
        $entity_manager = \ORM::entityManager();
        $mail_template = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $n_id);
        $mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $n_id));
        $mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $n_id));
        $mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $n_id));
        $mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $n_id));
        $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $t_id);
        $tpl = self::getMailTemplate($mail_template, $mail_template_head, $mail_template_body, $mail_template_foot, $mail_template_title, $newsletter);
        echo \Core::make('helper/json')->encode(array('tpl'=> $tpl, 'subject' => $newsletter->getNLSubject()));
        exit;
    }

    /**
     * Loads the mail template for preview by creating a pseudo Mail/Service
     * and hiding the <html> part
     *
     * @param $tpl
     * @param $tpl_head
     * @param $tpl_body
     * @param $tpl_foot
     * @param $tpl_title
     * @param $ns
     * @return bool|false|array
     */
    public static function getMailTemplate($tpl, $tpl_head, $tpl_body, $tpl_foot, $tpl_title, $ns = false)
    {
        $entity_manager = \ORM::entityManager();
        if (PackageSetup::checkC5Version('8.2')) {
            $m = \Core::make('mail');
        } else {
            $m = new Mail\Service();
        }
        if(!$ns){
            $ns = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', 1);
            if($ns == NULL) {
                return false;
            }
        }
        $m->addParameter('text', stream_get_contents($ns->getMailText()));
        $m->addParameter('footer', stream_get_contents($ns->getMailTextFooter()));
        $m->addParameter('header', stream_get_contents($ns->getHeaderText()));
        $m->addParameter('preview', true);
        $m->addParameter('tpl', $tpl);
        $m->addParameter('tpl_head', $tpl_head);
        $m->addParameter('tpl_body', $tpl_body);
        $m->addParameter('tpl_foot', $tpl_foot);
        $m->addParameter('tpl_title', $tpl_title);
        $m->load('newsletter_template', PackageSetup::$pkgHandle);
        return $m->getBodyHTML();
    }

    /**
     * Gets all templates as a select list with id & handle
     *
     * @return array
     */
    public static function getAllTemplates()
    {
        $mailTemps = array();
        $entity_manager = \ORM::entityManager();
        $mail_templates = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate')->findAll();
        foreach($mail_templates as $mt){
            $mailTemps[$mt->getMailTemplateId()] = $mt->getMailTemplateHandle();
        }
        $choose = array(
            'xxx' => t('Choose a Template')
        );
        return $choose + $mailTemps;
    }

    /**
     * Gets a select list with all newsletters
     *
     * @return array
     */
    public static function getNewsletterDropDown()
    {
        $newsletters = array();
        $entity_manager = \ORM::entityManager();
        $newsletter = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')->findAll();
        foreach($newsletter as $k => $mt){
            $newsletters[$mt->getNewsLetterId()] = $mt->getNLHandle();
        }
        return $newsletters;
    }

    /**
     *
     */
    public function get_image_dimensions()
    {
        $fID = \Request::getInstance()->get('fileID');
        $file = File\File::getByID(intval($fID));
        $imageHelper = \Core::make('helper/image');
        $im = $imageHelper->getThumbnail($file, 9999, 9999);
        echo \Core::make('helper/json')->encode($im);
        exit;

    }

    /**
     *
     */
    public function send_test_mail()
    {
        Settings::send_test_mail();
    }

    /**
     * @param $id
     *
     * Checks if a template is being sent
     */
    private function checkEditable($id)
    {
        $session = \Core::make('session');
        $now = new \DateTime();
        $entity_manager = \ORM::entityManager();
        $query = $entity_manager->createQueryBuilder()
            ->select('address')
            ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'address')
            ->andWhere('address.sent = :sent')
            ->andWhere('address.send_time > :send_time')
            ->setParameter('sent', 0)
            ->setParameter('send_time', $now)
            ->getQuery();
        $iterableResult = $query->iterate();
        foreach($iterableResult as $row){
            $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', $row[0]->getSendId());
            if(intval($id) == $newsletter->getTemplateID()) {
                $session->set('being_sent', t('This Template is being sent at the moment. You can\'t edit or delete it.'));
                $response = \Redirect::to('/dashboard/newsletter/templates/template_list');
                $response->send();
                exit;
            } else {
                unset($newsletter);
            }

        }
    }

    /**
     * @param $mail_template
     * @param $args
     * @return mixed
     */
    private function setTemplateData(&$mail_template, $args)
    {
	    $tlHandle = preg_replace('/[^A-Za-zöäüéàèÜÖËÄẽẼç0-9\-]/', '', $args['tl_handle']);
	    $mail_template->setMailTemplateHandle($tlHandle);
        $mail_template->setTemplateDesigned(1);
        return $mail_template;
    }

    /**
     * @param $mail_template
     * @param $args
     * @param $id
     * @return mixed
     */
    private function setTemplateDataHead(&$mail_template, $args, $id)
    {
        $mail_template->setLogoWidth((strlen($args['logo_width']) == 0 || intval($args['logo_width']) < 0) ? '100' : $args['logo_width']);
        $mail_template->setLogoWidthUnit((strlen($args['logo_width_unit']) == 0) ? 'px' : $args['logo_width_unit']);
        $mail_template->setLogoHeight((strlen($args['logo_height']) == 0 || intval($args['logo_height']) < 0) ? '100' : $args['logo_height']);
        $mail_template->setLogoHeightUnit((strlen($args['logo_height_unit']) == 0) ? 'px' : $args['logo_height_unit']);

        $mail_template->setHeadColor((strlen($args['head_color']) == 0) ? 'rgb(255, 255, 255)' : $args['head_color']);

        $mail_template->setHeadPaddingTop((strlen($args['head_padding_top']) == 0 || intval($args['head_padding_top']) < 0) ? '0' : $args['head_padding_top']);
        $mail_template->setHeadPaddingLeft((strlen($args['head_padding_left']) == 0 || intval($args['head_padding_left']) < 0) ? '0' : $args['head_padding_left']);
        $mail_template->setHeadPaddingRight((strlen($args['head_padding_right']) == 0 || intval($args['head_padding_right']) < 0) ? '0' : $args['head_padding_right']);
        $mail_template->setHeadPaddingBottom((strlen($args['head_padding_bottom']) == 0 || intval($args['head_padding_bottom']) < 0) ? '0' : $args['head_padding_bottom']);

        $mail_template->setHeadPaddingTopUnit((strlen($args['head_padding_top_unit']) == 0) ? 'px' : $args['head_padding_top_unit']);
        $mail_template->setHeadPaddingLeftUnit((strlen($args['head_padding_left_unit']) == 0) ? 'px' : $args['head_padding_left_unit']);
        $mail_template->setHeadPaddingRightUnit((strlen($args['head_padding_right_unit']) == 0) ? 'px' : $args['head_padding_right_unit']);
        $mail_template->setHeadPaddingBottomUnit((strlen($args['head_padding_bottom_unit']) == 0) ? 'px' : $args['head_padding_bottom_unit']);

        $mail_template->setHeadImgMarginTop((strlen($args['logo_img_top']) == 0 || intval($args['logo_img_top']) < 0) ? '0' : $args['logo_img_top']);
        $mail_template->setHeadImgMarginLeft((strlen($args['logo_img_left']) == 0 || intval($args['logo_img_left']) < 0) ? '0' : $args['logo_img_left']);
        $mail_template->setHeadImgMarginRight((strlen($args['logo_img_right']) == 0 || intval($args['logo_img_right']) < 0) ? '0' : $args['logo_img_right']);
        $mail_template->setHeadImgMarginBottom((strlen($args['logo_img_bottom']) == 0 || intval($args['logo_img_bottom']) < 0) ? '0' : $args['logo_img_bottom']);

        $mail_template->setHeadImgMarginTopUnit((strlen($args['logo_img_top_unit']) == 0) ? 'px' : $args['logo_img_top_unit']);
        $mail_template->setHeadImgMarginLeftUnit((strlen($args['logo_img_left_unit']) == 0) ? 'px' : $args['logo_img_left_unit']);
        $mail_template->setHeadImgMarginRightUnit((strlen($args['logo_img_right_unit']) == 0) ? 'px' : $args['logo_img_right_unit']);
        $mail_template->setHeadImgMarginBottomUnit((strlen($args['logo_img_bottom_unit']) == 0) ? 'px' : $args['logo_img_bottom_unit']);

        $mail_template->setHeadMarginTopUnit((strlen($args['head_margin_top_unit']) == 0) ? 'px' : $args['head_margin_top_unit']);
        $mail_template->setHeadMarginLeftUnit((strlen($args['head_margin_left_unit']) == 0) ? 'px' : $args['head_margin_left_unit']);
        $mail_template->setHeadMarginRightUnit((strlen($args['head_margin_right_unit']) == 0) ? 'px' : $args['head_margin_right_unit']);
        $mail_template->setHeadMarginBottomUnit((strlen($args['head_margin_bottom_unit']) == 0) ? 'px' : $args['head_margin_bottom_unit']);

        $mail_template->setHeadMarginTop((strlen($args['head_margin_top']) == 0) ? 'px' : $args['head_margin_top']);
        $mail_template->setHeadMarginLeft((strlen($args['head_margin_left']) == 0) ? 'px' : $args['head_margin_left']);
        $mail_template->setHeadMarginRight((strlen($args['head_margin_right']) == 0) ? 'px' : $args['head_margin_right']);
        $mail_template->setHeadMarginBottom((strlen($args['head_margin_bottom']) == 0) ? 'px' : $args['head_margin_bottom']);

        $mail_template->setHeadHeight((strlen($args['head_size']) == 0 || intval($args['head_size']) < 0) ? '100' : $args['head_size']);
        $mail_template->setHeadHeightUnit((strlen($args['head_size_unit']) == 0) ? 'px' : $args['head_size_unit']);

        $mail_template->setLogoTop((strlen($args['logo_top']) == 0 || intval($args['logo_top']) < 0) ? '0' : $args['logo_top']);
        $mail_template->setLogoLeft((strlen($args['logo_left']) == 0 || intval($args['logo_left']) < 0) ? '0' : $args['logo_left']);
        $mail_template->setLogoRight((strlen($args['logo_right']) == 0 || intval($args['logo_right']) < 0) ? '0' : $args['logo_right']);
        $mail_template->setLogoBottom((strlen($args['logo_bottom']) == 0 || intval($args['logo_bottom']) < 0) ? '0' : $args['logo_bottom']);

        $mail_template->setLogoTopUnit((strlen($args['logo_top_unit']) == 0) ? 'px' : $args['logo_top_unit']);
        $mail_template->setLogoLeftUnit((strlen($args['logo_left_unit']) == 0) ? 'px' : $args['logo_left_unit']);
        $mail_template->setLogoRightUnit((strlen($args['logo_right_unit']) == 0) ? 'px' : $args['logo_right_unit']);
        $mail_template->setLogoBottomUnit((strlen($args['logo_bottom_unit']) == 0) ? 'px' : $args['logo_bottom_unit']);

        $mail_template->setHeadBorderTopWidth((strlen($args['head_border_top_width']) == 0) ? '0' : $args['head_border_top_width']);
        $mail_template->setHeadBorderTopStyle((strlen($args['head_border_top_style']) == 0) ? 'none' : $args['head_border_top_style']);
        $mail_template->setHeadBorderTopColor((strlen($args['head_border_top_color']) == 0) ? 'rgb(255, 255, 255)' : $args['head_border_top_color']);

        $mail_template->setHeadBorderRightWidth((strlen($args['head_border_right_width']) == 0) ? '0' : $args['head_border_right_width']);
        $mail_template->setHeadBorderRightStyle((strlen($args['head_border_right_style']) == 0) ? 'none' : $args['head_border_right_style']);
        $mail_template->setHeadBorderRightColor((strlen($args['head_border_right_color']) == 0) ? 'rgb(255, 255, 255)' : $args['head_border_right_color']);

        $mail_template->setHeadBorderBottomWidth((strlen($args['head_border_bottom_width']) == 0) ? '0' : $args['head_border_bottom_width']);
        $mail_template->setHeadBorderBottomStyle((strlen($args['head_border_bottom_style']) == 0) ? 'none' : $args['head_border_bottom_style']);
        $mail_template->setHeadBorderBottomColor((strlen($args['head_border_bottom_color']) == 0) ? 'rgb(255, 255, 255)' : $args['head_border_bottom_color']);

        $mail_template->setHeadBorderLeftWidth((strlen($args['head_border_left_width']) == 0) ? '0' : $args['head_border_left_width']);
        $mail_template->setHeadBorderLeftStyle((strlen($args['head_border_left_style']) == 0) ? 'none' : $args['head_border_left_style']);
        $mail_template->setHeadBorderLeftColor((strlen($args['head_border_left_color']) == 0) ? 'rgb(255, 255, 255)' : $args['head_border_left_color']);
        $mail_template->setTplId($id);

        return $mail_template;
    }

    /**
     * @param $mail_template
     * @param $args
     * @param $id
     * @return mixed
     */
    private function setTemplateDataBody(&$mail_template, $args, $id)
    {
        $mail_template->setBodyColor((strlen($args['body_color']) == 0) ? 'rgb(255, 255, 255)' : $args['body_color']);
        $mail_template->setBodyMinSize((strlen($args['body_min_size']) == 0 || intval($args['body_min_size']) < 0) ? '200' : $args['body_min_size']);
        $mail_template->setBodyMinSizeUnit((strlen($args['body_min_size_unit']) == 0) ? 'px' : $args['body_min_size_unit']);

        $mail_template->setPaddingTop((strlen($args['padding_top']) == 0 || intval($args['padding_top']) < 0) ? '10' : $args['padding_top']);
        $mail_template->setPaddingLeft((strlen($args['padding_left']) == 0 || intval($args['padding_left']) < 0) ? '10' : $args['padding_left']);
        $mail_template->setPaddingRight((strlen($args['padding_right']) == 0 || intval($args['padding_right']) < 0) ? '10' : $args['padding_right']);
        $mail_template->setPaddingBottom((strlen($args['padding_bottom']) == 0 || intval($args['padding_bottom']) < 0) ? '10' : $args['padding_bottom']);

        $mail_template->setMarginTop((strlen($args['margin_top']) == 0 || intval($args['margin_top']) < 0) ? '0' : $args['margin_top']);
        $mail_template->setMarginLeft((strlen($args['margin_left']) == 0 || intval($args['margin_left']) < 0) ? '0' : $args['margin_left']);
        $mail_template->setMarginRight((strlen($args['margin_right']) == 0 || intval($args['margin_right']) < 0) ? '0' : $args['margin_right']);
        $mail_template->setMarginBottom((strlen($args['margin_bottom']) == 0 || intval($args['margin_bottom']) < 0) ? '0' : $args['margin_bottom']);

        $mail_template->setPaddingTopUnit((strlen($args['padding_top_unit']) == 0) ? 'px' : $args['padding_top_unit']);
        $mail_template->setPaddingLeftUnit((strlen($args['padding_left_unit']) == 0) ? 'px' : $args['padding_left_unit']);
        $mail_template->setPaddingRightUnit((strlen($args['padding_right_unit']) == 0) ? 'px' : $args['padding_right_unit']);
        $mail_template->setPaddingBottomUnit((strlen($args['padding_bottom_unit']) == 0) ? 'px' : $args['padding_bottom_unit']);
        $mail_template->setMarginTopUnit((strlen($args['margin_top_unit']) == 0) ? 'px' : $args['margin_top_unit']);
        $mail_template->setMarginLeftUnit((strlen($args['margin_left_unit']) == 0) ? 'px' : $args['margin_left_unit']);
        $mail_template->setMarginRightUnit((strlen($args['margin_right_unit']) == 0) ? 'px' : $args['margin_right_unit']);
        $mail_template->setMarginBottomUnit((strlen($args['margin_bottom_unit']) == 0) ? 'px' : $args['margin_bottom_unit']);

        $mail_template->setBodyBorderTopWidth((strlen($args['body_border_top_width']) == 0 || intval($args['body_border_top_width']) < 0) ? '0' : $args['body_border_top_width']);
        $mail_template->setBodyBorderTopStyle((strlen($args['body_border_top_style']) == 0) ? 'none' : $args['body_border_top_style']);
        $mail_template->setBodyBorderTopColor((strlen($args['body_border_top_color']) == 0) ? 'rgb(255, 255, 255)' : $args['body_border_top_color']);

        $mail_template->setBodyBorderRightWidth((strlen($args['body_border_right_width']) == 0 || intval($args['body_border_right_width']) < 0) ? '0' : $args['body_border_right_width']);
        $mail_template->setBodyBorderRightStyle((strlen($args['body_border_right_style']) == 0) ? 'none' : $args['body_border_right_style']);
        $mail_template->setBodyBorderRightColor((strlen($args['body_border_right_color']) == 0) ? 'rgb(255, 255, 255)' : $args['body_border_right_color']);

        $mail_template->setBodyBorderBottomWidth((strlen($args['body_border_bottom_width']) == 0 || intval($args['body_border_bottom_width']) < 0) ? '0' : $args['body_border_bottom_width']);
        $mail_template->setBodyBorderBottomStyle((strlen($args['body_border_bottom_style']) == 0) ? 'none' : $args['body_border_bottom_style']);
        $mail_template->setBodyBorderBottomColor((strlen($args['body_border_bottom_color']) == 0) ? 'rgb(255, 255, 255)' : $args['body_border_bottom_color']);

        $mail_template->setBodyBorderLeftWidth((strlen($args['body_border_left_width']) == 0 || intval($args['body_border_left_width']) < 0) ? '0' : $args['body_border_left_width']);
        $mail_template->setBodyBorderLeftStyle((strlen($args['body_border_left_style']) == 0) ? 'none' : $args['body_border_left_style']);
        $mail_template->setBodyBorderLeftColor((strlen($args['body_border_left_color']) == 0) ? 'rgb(255, 255, 255)' : $args['body_border_left_color']);

        $mail_template->setTplId($id);
        return $mail_template;
    }

    /**
     * @param $mail_template
     * @param $args
     * @param $id
     * @return mixed
     */
    private function setTemplateDataFoot(&$mail_template, $args, $id)
    {
        $mail_template->setFootColor((strlen($args['foot_color']) == 0) ? 'rgb(255, 255, 255)' : $args['foot_color']);
        $mail_template->setFootHeight((strlen($args['foot_size']) == 0 || intval($args['foot_size']) < 0) ? '200' : $args['foot_size']);

        $mail_template->setFootPaddingTop((strlen($args['foot_padding_top']) == 0 || intval($args['foot_padding_top']) < 0) ? '0' : $args['foot_padding_top']);
        $mail_template->setFootPaddingLeft((strlen($args['foot_padding_left']) == 0 || intval($args['foot_padding_left']) < 0) ? '0' : $args['foot_padding_left']);
        $mail_template->setFootPaddingRight((strlen($args['foot_padding_right']) == 0 || intval($args['foot_padding_right']) < 0) ? '0' : $args['foot_padding_right']);
        $mail_template->setFootPaddingBottom((strlen($args['foot_padding_bottom']) == 0 || intval($args['foot_padding_bottom']) < 0) ? '0' : $args['foot_padding_bottom']);

        $mail_template->setFootMarginTop((strlen($args['foot_margin_top']) == 0 || intval($args['foot_margin_top']) < 0) ? '0' : $args['foot_margin_top']);
        $mail_template->setFootMarginLeft((strlen($args['foot_margin_left']) == 0 || intval($args['foot_margin_left']) < 0) ? '0' : $args['foot_margin_left']);
        $mail_template->setFootMarginRight((strlen($args['foot_margin_right']) == 0 || intval($args['foot_margin_right']) < 0) ? '0' : $args['foot_margin_right']);
        $mail_template->setFootMarginBottom((strlen($args['foot_margin_bottom']) == 0 || intval($args['foot_margin_bottom']) < 0) ? '0' : $args['foot_margin_bottom']);
        $mail_template->setFootHeightUnit((strlen($args['foot_size_unit']) == 0) ? 'px' : $args['foot_size_unit']);

        $mail_template->setFootPaddingTopUnit((strlen($args['foot_padding_top_unit']) == 0) ? 'px' : $args['foot_padding_top_unit']);
        $mail_template->setFootPaddingLeftUnit((strlen($args['foot_padding_left_unit']) == 0) ? 'px' : $args['foot_padding_left_unit']);
        $mail_template->setFootPaddingRightUnit((strlen($args['foot_padding_right_unit']) == 0) ? 'px' : $args['foot_padding_right_unit']);
        $mail_template->setFootPaddingBottomUnit((strlen($args['foot_padding_bottom_unit']) == 0) ? 'px' : $args['foot_padding_bottom_unit']);

        $mail_template->setFootMarginTopUnit((strlen($args['foot_margin_top_unit']) == 0) ? 'px' : $args['foot_margin_top_unit']);
        $mail_template->setFootMarginLeftUnit((strlen($args['foot_margin_left_unit']) == 0) ? 'px' : $args['foot_margin_left_unit']);
        $mail_template->setFootMarginRightUnit((strlen($args['foot_margin_right_unit']) == 0) ? 'px' : $args['foot_margin_right_unit']);
        $mail_template->setFootMarginBottomUnit((strlen($args['foot_margin_bottom_unit']) == 0) ? 'px' : $args['foot_margin_bottom_unit']);

        $mail_template->setFootBorderTopWidth((strlen($args['foot_border_top_width']) == 0 || intval($args['foot_border_top_width']) < 0) ? '0' : $args['foot_border_top_width']);
        $mail_template->setFootBorderTopStyle((strlen($args['foot_border_top_style']) == 0) ? 'none' : $args['foot_border_top_style']);
        $mail_template->setFootBorderTopColor((strlen($args['foot_border_top_color']) == 0) ? 'rgb(255, 255, 255)' : $args['foot_border_top_color']);

        $mail_template->setFootBorderRightWidth((strlen($args['foot_border_right_width']) == 0 || intval($args['foot_border_right_width']) < 0) ? '0' : $args['foot_border_right_width']);
        $mail_template->setFootBorderRightStyle((strlen($args['foot_border_right_style']) == 0) ? 'none' : $args['foot_border_right_style']);
        $mail_template->setFootBorderRightColor((strlen($args['foot_border_right_color']) == 0) ? 'rgb(255, 255, 255)' : $args['foot_border_right_color']);

        $mail_template->setFootBorderBottomWidth((strlen($args['foot_border_bottom_width']) == 0 || intval($args['foot_border_bottom_width']) < 0) ? '0' : $args['foot_border_bottom_width']);
        $mail_template->setFootBorderBottomStyle((strlen($args['foot_border_bottom_style']) == 0) ? 'none' : $args['foot_border_bottom_style']);
        $mail_template->setFootBorderBottomColor((strlen($args['foot_border_bottom_color']) == 0) ? 'rgb(255, 255, 255)' : $args['foot_border_bottom_color']);

        $mail_template->setFootBorderLeftWidth((strlen($args['foot_border_left_width']) == 0 || intval($args['foot_border_left_width']) < 0) ? '0' : $args['foot_border_left_width']);
        $mail_template->setFootBorderLeftStyle((strlen($args['foot_border_left_style']) == 0) ? 'none' : $args['foot_border_left_style']);
        $mail_template->setFootBorderLeftColor((strlen($args['foot_border_left_color']) == 0) ? 'rgb(255, 255, 255)' : $args['foot_border_left_color']);

        $mail_template->setTplId($id);
        return $mail_template;
    }

    /**
     * @param $mail_template
     * @param $args
     * @param $id
     * @return mixed
     */
    private function setTemplateDataTitle(&$mail_template, $args, $id)
    {
        $mail_template->setH1Color((strlen($args['h1_color']) == 0) ? 'rgb(0, 0, 0)' : $args['h1_color']);
        $mail_template->setH1Weight((strlen($args['h1_weight']) == 0) ? 'normal' : $args['h1_weight']);
        $mail_template->setH1Font((strlen($args['h1_font']) == 0) ? 'Arial' : $args['h1_font']);
        $mail_template->setH1Style((strlen($args['h1_style']) == 0) ? 'normal' : $args['h1_style']);
        $mail_template->setH1Size((strlen($args['h1_size']) == 0 || intval($args['h1_size']) < 0) ? '16' : $args['h1_size']);
        $mail_template->setH1SizeUnit((strlen($args['h1_size_unit']) == 0) ? 'px' : $args['h1_size_unit']);

        $mail_template->setH1PaddingTop((strlen($args['h1_padding_top']) == 0 || intval($args['h1_padding_top']) < 0) ? '0' : $args['h1_padding_top']);
        $mail_template->setH1PaddingLeft((strlen($args['h1_padding_left']) == 0 || intval($args['h1_padding_left']) < 0) ? '0' : $args['h1_padding_left']);
        $mail_template->setH1PaddingRight((strlen($args['h1_padding_right']) == 0 || intval($args['h1_padding_right']) < 0) ? '0' : $args['h1_padding_right']);
        $mail_template->setH1PaddingBottom((strlen($args['h1_padding_bottom']) == 0 || intval($args['h1_padding_bottom']) < 0) ? '0' : $args['h1_padding_bottom']);

        $mail_template->setH1PaddingTopUnit((strlen($args['h1_padding_top_unit']) == 0) ? 'px' : $args['h1_padding_top_unit']);
        $mail_template->setH1PaddingLeftUnit((strlen($args['h1_padding_left_unit']) == 0) ? 'px' : $args['h1_padding_left_unit']);
        $mail_template->setH1PaddingRightUnit((strlen($args['h1_padding_right_unit']) == 0) ? 'px' : $args['h1_padding_right_unit']);
        $mail_template->setH1PaddingBottomUnit((strlen($args['h1_padding_bottom_unit']) == 0) ? 'px' : $args['h1_padding_bottom_unit']);

        $mail_template->setH1MarginTop((strlen($args['h1_margin_top']) == 0 || intval($args['h1_margin_top']) < 0) ? '0' : $args['h1_margin_top']);
        $mail_template->setH1MarginLeft((strlen($args['h1_margin_left']) == 0 || intval($args['h1_margin_left']) < 0) ? '0' : $args['h1_margin_left']);
        $mail_template->setH1MarginRight((strlen($args['h1_margin_right']) == 0 || intval($args['h1_margin_right']) < 0) ? '0' : $args['h1_margin_right']);
        $mail_template->setH1MarginBottom((strlen($args['h1_margin_bottom']) == 0 || intval($args['h1_margin_bottom']) < 0) ? '0' : $args['h1_margin_bottom']);

        $mail_template->setH1MarginTopUnit((strlen($args['h1_margin_top_unit']) == 0) ? 'px' : $args['h1_margin_top_unit']);
        $mail_template->setH1MarginLeftUnit((strlen($args['h1_margin_left_unit']) == 0) ? 'px' : $args['h1_margin_left_unit']);
        $mail_template->setH1MarginRightUnit((strlen($args['h1_margin_right_unit']) == 0) ? 'px' : $args['h1_margin_right_unit']);
        $mail_template->setH1MarginBottomUnit((strlen($args['h1_margin_bottom_unit']) == 0) ? 'px' : $args['h1_margin_bottom_unit']);

        $mail_template->setH2Color((strlen($args['h2_color']) == 0) ? 'rgb(0, 0, 0)' : $args['h2_color']);
        $mail_template->setH2Weight((strlen($args['h2_weight']) == 0) ? 'normal' : $args['h2_weight']);
        $mail_template->setH2Font((strlen($args['h2_font']) == 0) ? 'Arial' : $args['h2_font']);
        $mail_template->setH2Style((strlen($args['h2_style']) == 0) ? 'normal' : $args['h2_style']);
        $mail_template->setH2Size((strlen($args['h2_size']) == 0 || intval($args['h2_size']) < 0) ? '16' : $args['h2_size']);
        $mail_template->setH2SizeUnit((strlen($args['h2_size_unit']) == 0) ? 'px' : $args['h2_size_unit']);

        $mail_template->setH2PaddingTop((strlen($args['h2_padding_top']) == 0 || intval($args['h2_padding_top']) < 0) ? '0' : $args['h2_padding_top']);
        $mail_template->setH2PaddingLeft((strlen($args['h2_padding_left']) == 0 || intval($args['h2_padding_left']) < 0) ? '0' : $args['h2_padding_left']);
        $mail_template->setH2PaddingRight((strlen($args['h2_padding_right']) == 0 || intval($args['h2_padding_right']) < 0) ? '0' : $args['h2_padding_right']);
        $mail_template->setH2PaddingBottom((strlen($args['h2_padding_bottom']) == 0 || intval($args['h2_padding_bottom']) < 0) ? '0' : $args['h2_padding_bottom']);

        $mail_template->setH2PaddingTopUnit((strlen($args['h2_padding_top_unit']) == 0) ? 'px' : $args['h2_padding_top_unit']);
        $mail_template->setH2PaddingLeftUnit((strlen($args['h2_padding_left_unit']) == 0) ? 'px' : $args['h2_padding_left_unit']);
        $mail_template->setH2PaddingRightUnit((strlen($args['h2_padding_right_unit']) == 0) ? 'px' : $args['h2_padding_right_unit']);
        $mail_template->setH2PaddingBottomUnit((strlen($args['h2_padding_bottom_unit']) == 0) ? 'px' : $args['h2_padding_bottom_unit']);

        $mail_template->setH2MarginTop((strlen($args['h2_margin_top']) == 0 || intval($args['h2_margin_top']) < 0) ? '0' : $args['h2_margin_top']);
        $mail_template->setH2MarginLeft((strlen($args['h2_margin_left']) == 0 || intval($args['h2_margin_left']) < 0) ? '0' : $args['h2_margin_left']);
        $mail_template->setH2MarginRight((strlen($args['h2_margin_right']) == 0 || intval($args['h2_margin_right']) < 0) ? '0' : $args['h2_margin_right']);
        $mail_template->setH2MarginBottom((strlen($args['h2_margin_bottom']) == 0 || intval($args['h2_margin_bottom']) < 0) ? '0' : $args['h2_margin_bottom']);

        $mail_template->setH2MarginTopUnit((strlen($args['h2_margin_top_unit']) == 0) ? 'px' : $args['h2_margin_top_unit']);
        $mail_template->setH2MarginLeftUnit((strlen($args['h2_margin_left_unit']) == 0) ? 'px' : $args['h2_margin_left_unit']);
        $mail_template->setH2MarginRightUnit((strlen($args['h2_margin_right_unit']) == 0) ? 'px' : $args['h2_margin_right_unit']);
        $mail_template->setH2MarginBottomUnit((strlen($args['h2_margin_bottom_unit']) == 0) ? 'px' : $args['h2_margin_bottom_unit']);

        $mail_template->setH3Color((strlen($args['h3_color']) == 0) ? 'rgb(0, 0, 0)' : $args['h3_color']);
        $mail_template->setH3Weight((strlen($args['h3_weight']) == 0) ? 'normal' : $args['h3_weight']);
        $mail_template->setH3Font((strlen($args['h3_font']) == 0) ? 'Arial' : $args['h3_font']);
        $mail_template->setH3Style((strlen($args['h3_style']) == 0) ? 'normal' : $args['h3_style']);
        $mail_template->setH3Size((strlen($args['h3_size']) == 0 || intval($args['h3_size']) < 0) ? '16' : $args['h3_size']);
        $mail_template->setH3SizeUnit((strlen($args['h3_size_unit']) == 0) ? 'px' : $args['h3_size_unit']);

        $mail_template->setH3PaddingTop((strlen($args['h3_padding_top']) == 0 || intval($args['h3_padding_top']) < 0) ? '0' : $args['h3_padding_top']);
        $mail_template->setH3PaddingLeft((strlen($args['h3_padding_left']) == 0 || intval($args['h3_padding_left']) < 0) ? '0' : $args['h3_padding_left']);
        $mail_template->setH3PaddingRight((strlen($args['h3_padding_right']) == 0 || intval($args['h3_padding_right']) < 0) ? '0' : $args['h3_padding_right']);
        $mail_template->setH3PaddingBottom((strlen($args['h3_padding_bottom']) == 0 || intval($args['h3_padding_bottom']) < 0) ? '0' : $args['h3_padding_bottom']);

        $mail_template->setH3PaddingTopUnit((strlen($args['h3_padding_top_unit']) == 0) ? 'px' : $args['h3_padding_top_unit']);
        $mail_template->setH3PaddingLeftUnit((strlen($args['h3_padding_left_unit']) == 0) ? 'px' : $args['h3_padding_left_unit']);
        $mail_template->setH3PaddingRightUnit((strlen($args['h3_padding_right_unit']) == 0) ? 'px' : $args['h3_padding_right_unit']);
        $mail_template->setH3PaddingBottomUnit((strlen($args['h3_padding_bottom_unit']) == 0) ? 'px' : $args['h3_padding_bottom_unit']);

        $mail_template->setH3MarginTop((strlen($args['h3_margin_top']) == 0 || intval($args['h3_margin_top']) < 0) ? '0' : $args['h3_margin_top']);
        $mail_template->setH3MarginLeft((strlen($args['h3_margin_left']) == 0 || intval($args['h3_margin_left']) < 0) ? '0' : $args['h3_margin_left']);
        $mail_template->setH3MarginRight((strlen($args['h3_margin_right']) == 0 || intval($args['h3_margin_right']) < 0) ? '0' : $args['h3_margin_right']);
        $mail_template->setH3MarginBottom((strlen($args['h3_margin_bottom']) == 0 || intval($args['h3_margin_bottom']) < 0) ? '0' : $args['h3_margin_bottom']);

        $mail_template->setH3MarginTopUnit((strlen($args['h3_margin_top_unit']) == 0) ? 'px' : $args['h3_margin_top_unit']);
        $mail_template->setH3MarginLeftUnit((strlen($args['h3_margin_left_unit']) == 0) ? 'px' : $args['h3_margin_left_unit']);
        $mail_template->setH3MarginRightUnit((strlen($args['h3_margin_right_unit']) == 0) ? 'px' : $args['h3_margin_right_unit']);
        $mail_template->setH3MarginBottomUnit((strlen($args['h3_margin_bottom_unit']) == 0) ? 'px' : $args['h3_margin_bottom_unit']);

        $mail_template->setH4Color((strlen($args['h4_color']) == 0) ? 'rgb(0, 0, 0)' : $args['h4_color']);
        $mail_template->setH4Weight((strlen($args['h4_weight']) == 0) ? 'normal' : $args['h4_weight']);
        $mail_template->setH4Font((strlen($args['h4_font']) == 0) ? 'Arial' : $args['h4_font']);
        $mail_template->setH4Style((strlen($args['h4_style']) == 0) ? 'normal' : $args['h4_style']);
        $mail_template->setH4Size((strlen($args['h4_size']) == 0 || intval($args['h4_size']) < 0) ? '16' : $args['h4_size']);
        $mail_template->setH4SizeUnit((strlen($args['h4_size_unit']) == 0) ? 'px' : $args['h4_size_unit']);

        $mail_template->setH4PaddingTop((strlen($args['h4_padding_top']) == 0 || intval($args['h4_padding_top']) < 0) ? '0' : $args['h4_padding_top']);
        $mail_template->setH4PaddingLeft((strlen($args['h4_padding_left']) == 0 || intval($args['h4_padding_left']) < 0) ? '0' : $args['h4_padding_left']);
        $mail_template->setH4PaddingRight((strlen($args['h4_padding_right']) == 0 || intval($args['h4_padding_right']) < 0) ? '0' : $args['h4_padding_right']);
        $mail_template->setH4PaddingBottom((strlen($args['h4_padding_bottom']) == 0 || intval($args['h4_padding_bottom']) < 0) ? '0' : $args['h4_padding_bottom']);

        $mail_template->setH4PaddingTopUnit((strlen($args['h4_padding_top_unit']) == 0) ? 'px' : $args['h4_padding_top_unit']);
        $mail_template->setH4PaddingLeftUnit((strlen($args['h4_padding_left_unit']) == 0) ? 'px' : $args['h4_padding_left_unit']);
        $mail_template->setH4PaddingRightUnit((strlen($args['h4_padding_right_unit']) == 0) ? 'px' : $args['h4_padding_right_unit']);
        $mail_template->setH4PaddingBottomUnit((strlen($args['h4_padding_bottom_unit']) == 0) ? 'px' : $args['h4_padding_bottom_unit']);

        $mail_template->setH4MarginTop((strlen($args['h4_margin_top']) == 0 || intval($args['h4_margin_top']) < 0) ? '0' : $args['h4_margin_top']);
        $mail_template->setH4MarginLeft((strlen($args['h4_margin_left']) == 0 || intval($args['h4_margin_left']) < 0) ? '0' : $args['h4_margin_left']);
        $mail_template->setH4MarginRight((strlen($args['h4_margin_right']) == 0 || intval($args['h4_margin_right']) < 0) ? '0' : $args['h4_margin_right']);
        $mail_template->setH4MarginBottom((strlen($args['h4_margin_bottom']) == 0 || intval($args['h4_margin_bottom']) < 0) ? '0' : $args['h4_margin_bottom']);

        $mail_template->setH4MarginTopUnit((strlen($args['h4_margin_top_unit']) == 0) ? 'px' : $args['h4_margin_top_unit']);
        $mail_template->setH4MarginLeftUnit((strlen($args['h4_margin_left_unit']) == 0) ? 'px' : $args['h4_margin_left_unit']);
        $mail_template->setH4MarginRightUnit((strlen($args['h4_margin_right_unit']) == 0) ? 'px' : $args['h4_margin_right_unit']);
        $mail_template->setH4MarginBottomUnit((strlen($args['h4_margin_bottom_unit']) == 0) ? 'px' : $args['h4_margin_bottom_unit']);

        $mail_template->setH5Color((strlen($args['h5_color']) == 0) ? 'rgb(0, 0, 0)' : $args['h5_color']);
        $mail_template->setH5Weight((strlen($args['h5_weight']) == 0) ? 'normal' : $args['h5_weight']);
        $mail_template->setH5Font((strlen($args['h5_font']) == 0) ? 'Arial' : $args['h5_font']);
        $mail_template->setH5Style((strlen($args['h5_style']) == 0) ? 'normal' : $args['h5_style']);
        $mail_template->setH5Size((strlen($args['h5_size']) == 0 || intval($args['h5_size']) < 0) ? '16' : $args['h5_size']);
        $mail_template->setH5SizeUnit((strlen($args['h5_size_unit']) == 0) ? 'px' : $args['h5_size_unit']);

        $mail_template->setH5PaddingTop((strlen($args['h5_padding_top']) == 0 || intval($args['h5_padding_top']) < 0) ? '0' : $args['h5_padding_top']);
        $mail_template->setH5PaddingLeft((strlen($args['h5_padding_left']) == 0 || intval($args['h5_padding_left']) < 0) ? '0' : $args['h5_padding_left']);
        $mail_template->setH5PaddingRight((strlen($args['h5_padding_right']) == 0 || intval($args['h5_padding_right']) < 0) ? '0' : $args['h5_padding_right']);
        $mail_template->setH5PaddingBottom((strlen($args['h5_padding_bottom']) == 0 || intval($args['h5_padding_bottom']) < 0) ? '0' : $args['h5_padding_bottom']);

        $mail_template->setH5PaddingTopUnit((strlen($args['h5_padding_top_unit']) == 0) ? 'px' : $args['h5_padding_top_unit']);
        $mail_template->setH5PaddingLeftUnit((strlen($args['h5_padding_left_unit']) == 0) ? 'px' : $args['h5_padding_left_unit']);
        $mail_template->setH5PaddingRightUnit((strlen($args['h5_padding_right_unit']) == 0) ? 'px' : $args['h5_padding_right_unit']);
        $mail_template->setH5PaddingBottomUnit((strlen($args['h5_padding_bottom_unit']) == 0) ? 'px' : $args['h5_padding_bottom_unit']);

        $mail_template->setH5MarginTop((strlen($args['h5_margin_top']) == 0 || intval($args['h5_margin_top']) < 0) ? '0' : $args['h5_margin_top']);
        $mail_template->setH5MarginLeft((strlen($args['h5_margin_left']) == 0 || intval($args['h5_margin_left']) < 0) ? '0' : $args['h5_margin_left']);
        $mail_template->setH5MarginRight((strlen($args['h5_margin_right']) == 0 || intval($args['h5_margin_right']) < 0) ? '0' : $args['h5_margin_right']);
        $mail_template->setH5MarginBottom((strlen($args['h5_margin_bottom']) == 0 || intval($args['h5_margin_bottom']) < 0) ? '0' : $args['h5_margin_bottom']);

        $mail_template->setH5MarginTopUnit((strlen($args['h5_margin_top_unit']) == 0) ? 'px' : $args['h5_margin_top_unit']);
        $mail_template->setH5MarginLeftUnit((strlen($args['h5_margin_left_unit']) == 0) ? 'px' : $args['h5_margin_left_unit']);
        $mail_template->setH5MarginRightUnit((strlen($args['h5_margin_right_unit']) == 0) ? 'px' : $args['h5_margin_right_unit']);
        $mail_template->setH5MarginBottomUnit((strlen($args['h5_margin_bottom_unit']) == 0) ? 'px' : $args['h5_margin_bottom_unit']);
        $mail_template->setTplId($id);

        return $mail_template;
    }
}
