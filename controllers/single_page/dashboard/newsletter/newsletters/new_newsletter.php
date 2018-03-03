<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/newsletters/new_newsletter.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Newsletters;

use Concrete\Core\Sharing\SocialNetwork\Link;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Doctrine\DBAL\DBALException;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File;
use Punic\Exception;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Settings as Settings;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates\NewTemplate as NewTemplate;

class NewNewsletter extends DashboardPageController
{

    /**
     * @var string
     */
    public $field_name_logo = '';

    /**
     * @var string
     */
    public $nl_handle = '';

    /**
     * @var string
     */
    public $nl_subject = '';

    /**
     * @var string
     */
    public $nl_template = '';


    /**
     * @var string
     */
    public $header_text = '';

    /**
     * @var string
     */
    public $content = '';

    /**
     * @var string
     */
    public $footer = '';

    /**
     * @var string
     */
    public $c5version = '';

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
        $args_settings = \Config::get('toess_lab_news_letter.settings');
        $this->set('owner_email', $args_settings['owner_email']);

        $this->set('family_font', \Config::get('toess_lab_news_letter.constants.google_fonts'));
        $this->set('mail_templates', NewTemplate::getAllTemplates());
        $this->set('files_num', $args_settings['files_num']);
        $a_attributes = Newsletter\SendMailing::getUserAttributeKeys();
        $address_attributes = $this->createUserAttributeKeysArray($a_attributes);
        $this->set('address_attributes', $address_attributes);
        $this->set('social_links', $this->getSocialLinks());
        $this->set('family_font', \Config::get('toess_lab_news_letter.constants.google_fonts'));
    }

    /**
     * @param bool $nl_id Newsletter ID
     * @param bool $handle Newsletter handle
     */
    public static function delete_newsletter($nl_id = false, $handle = false)
    {
        self::checkEditable($nl_id);
        $session = \Core::make('session');
        $entity_manager = \ORM::entityManager();
        $newsletter =  $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $nl_id);
        try {
            $entity_manager->remove($newsletter);
            $entity_manager->flush();
        }
        catch(Exception $e){
            $session->set('error', $e->getMessage());
        }
        $session->set('message', t('Newsletter \'%s\' has been deleted', $handle));
        $response = \Redirect::to('/dashboard/newsletter/newsletters/newsletter_list');
        $response->send();
        exit;

    }

    /**
     * @param bool $nl_id Newsletter ID
     * @param bool $handle Newsletter handle
     */
    public static function duplicate_newsletter($nl_id = false, $handle = false)
    {
        $session = \Core::make('session');
        $handle_like = explode(' (', $handle);
        $entity_manager = \ORM::entityManager();
        $newsletter =  $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $nl_id);
        $allNewsletter = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')
            ->createQueryBuilder('t')
            ->select('t.nl_handle')
            ->where('t.nl_handle LIKE :handle')
            ->setParameter('handle', '%' . $handle_like[0] . '%')
            ->getQuery()
            ->getResult();
        $exist_handle_counter = sizeof($allNewsletter);
        $newsletter_copy = clone $newsletter;
        $new_handle_counter = $exist_handle_counter;
        $newsletter_copy->setNLHandle($handle_like[0] . ' (' . $new_handle_counter . ')');
        $newsletter_copy->setModified(1);
        $entity_manager->persist($newsletter_copy);
        try {
            $entity_manager->flush();
        }catch(DBALException $e){
            if($e->getPrevious() &&  0 === strpos($e->getPrevious()->getCode(), '23')){
                $session->set('error', t('Newsletter \'%s\' has not been duplicated', $handle));
                $response = \Redirect::to('/dashboard/newsletter/newsletters/newsletter_list');
                $response->send();
                exit;
            }
        }

        $session->set('message', t('Newsletter \'%s\' has been duplicated', $handle));
        $response = \Redirect::to('/dashboard/newsletter/newsletters/newsletter_list');
        $response->send();
        exit;

    }

    /**
     * Saves the newsletter
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function save_newsletter()
    {
        $session = \Core::make('session');
        $session->set('selectedTabs', explode(',', \Core::make('helper/security')->sanitizeString($this->post('selected_tabs'))));
        $nl_handle = \Core::make('helper/security')->sanitizeString($this->post('nl_handle'));
        $nl_subject = \Core::make('helper/security')->sanitizeString($this->post('nl_subject'));
        $header_text = $this->post('header_text');
        $content = $this->post('content');
        $footer = $this->post('footer');
        $nl_template = \Core::make('helper/security')->sanitizeString($this->post('nl_template'));
        $newsletter_id = \Core::make('helper/security')->sanitizeInt($this->post('newsletter_id'));
        $this->set('nl_handle', $nl_handle);
        $this->set('header_text', $header_text);
        $this->set('content', $content);
        $this->set('footer', $footer);
        $head_social_links = array();
        if($this->post('head_social_links') != NULL) {
            foreach($this->post('head_social_links') as $hsl) {
                $arr = explode('|', $hsl);
                $head_social_links[] = $arr[1];
            }
        }
        if($this->post('body_social_links') != NULL) {
            foreach($this->post('body_social_links') as $hsl) {
                $arr = explode('|', $hsl);
                $body_social_links[] = $arr[1];
            }
        }
        if($this->post('foot_social_links') != NULL) {
            foreach($this->post('foot_social_links') as $hsl) {
                $arr = explode('|', $hsl);
                $foot_social_links[] = $arr[1];
            }
        }
        $userAttributes = serialize(array(
            'head' => $this->post('head_address_select'),
            'body' => $this->post('content_address_select'),
            'foot' => $this->post('foot_address_select')));
        $socialLinks = serialize(array(
            'head' => $head_social_links,
            'body' => $body_social_links,
            'foot' => $foot_social_links));
        $i = 0;
        $filesize = 0;
        $args = array();
        $attachFiles = array();
        $args_settings = \Config::get('toess_lab_news_letter.settings');
        while($i < intval($args_settings['files_num'])) {
            $attachment = \Core::make('helper/security')->sanitizeInt($this->post('attachment_' . $i));
            if(intval($attachment) > 0){
                $args['saveFiles'][] = $attachment;
                $file = File\File::getByID($attachment);
                $filesize += filesize($_SERVER['DOCUMENT_ROOT'] . File\File::getRelativePathFromID($file->getFileID()));
                $attachFiles[] = $file;
            }
            $i++;
        }
        if(intval($filesize) > \Config::get('toess_lab_news_letter.settings.file_total_size')) {
            $this->error->add(t('The size of attachments is bigger than %s MB', floatval(\Config::get('toess_lab_news_letter.settings.file_total_size') / 1024 / 1024)));
            $session->set('selectedTabs', array('attachments'));
        }
        if (!$this->token->validate('save_newsletter')) {
            $this->set('error', array($this->token->getErrorMessage()));
            $session->set('selectedTabs', array('general'));
        }
        if(strlen($nl_handle) == 0) {
            $this->error->add(t('The field "%s" is required', $this->nl_handle));
            $session->set('selectedTabs', array('general'));
        }
        if(strlen($nl_handle) > 255) {
            $this->error->add(t('The field "%s" is limited to 255 characters', $this->nl_handle));
            $session->set('selectedTabs', array('general'));
        }
        if(strlen($nl_subject) == 0) {
            $this->error->add(t('The field "%s" is required', $this->nl_subject));
            $session->set('selectedTabs', array('general'));
        }
        if(strlen($nl_subject) > 255) {
            $this->error->add(t('The field "%s" is limited to 255 characters', $this->nl_subject));
            $session->set('selectedTabs', array('general'));
        }
        if($nl_template == 'xxx') {
            $this->error->add(t('The field "%s" is required', $this->nl_template));
            $session->set('selectedTabs', array('general'));
        }
        if($this->error->has()) {
            $this->view();
            return;
        }
        $entity_manager = \ORM::entityManager();
        $mt = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $nl_template);
        if(intval($newsletter_id) > 0){
            $ns = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $newsletter_id);
        } else {
            $ns = new ToessLabNewsLetter();
        }
        $ns->setTemplate($mt);
        $ns->setNLHandle($nl_handle);
        $ns->setNLSubject($nl_subject);
        $ns->setMailText($content);
        $ns->setMailTextFooter($footer);
        $ns->setHeaderText($header_text);
        $ns->setUserAttributes($userAttributes);
        $ns->setSocialLinks($socialLinks);
        $ns->setModified(1);
        if (sizeof($attachFiles) > 0) {
            $ns->setAttachments(implode(',', $args['saveFiles']));
        } else {
            $ns->setAttachments('');
        }
        $entity_manager->persist($ns);
        try {
            $entity_manager->flush();
        }catch(DBALException $e){
            if($e->getPrevious() &&  0 === strpos($e->getPrevious()->getCode(), '23' && intval($entity_manager->getConnection()->lastInsertId()) > 0)){
                $this->error->add(t('The Newsletter name \'%s\' is already in use. Please choose another one.', $nl_handle));
                $session->set('selectedTabs', array('general'));
            }
        }
        $session->set('message', t('The Newsletter \'%s\' has been saved', $ns->getNLHandle()));
        $session->set('mail_templates', NewTemplate::getAllTemplates());
        $response = \Redirect::to('/dashboard/newsletter/newsletters/new_newsletter/-/edit', $ns->getNewsLetterId());
        $response->send();
        exit;
    }

    /**
     * Edits an existent newsletter
     *
     * @param bool $newsletterID (int ID)
     */
    public function edit($newsletterID = false)
    {

        $newsletter_id = \Core::make('helper/security')->sanitizeInt($newsletterID);
        $entity_manager = \ORM::entityManager();
        $this->checkEditable($newsletter_id);

        $newsletter = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $newsletter_id);
        $template = $entity_manager->find('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $newsletter->getTemplate());;

        $this->set('newsletter', $newsletter);
        if ($newsletter->getUserAttributes() != null) {
            $userAttributes = unserialize(stream_get_contents($newsletter->getUserAttributes()));
        } else {
            $userAttributes['head'] = array();
            $userAttributes['body'] = array();
            $userAttributes['foot'] = array();
        }
        if ($newsletter->getSocialLinks() != null) {
            $socialLinks = unserialize(stream_get_contents($newsletter->getSocialLinks()));
        } else {
            $socialLinks['head'] = array();
            $socialLinks['body'] = array();
            $socialLinks['foot'] = array();
        }
        $this->set('head_address_select_chosen', $userAttributes['head']);
        $this->set('body_address_select_chosen', $userAttributes['body']);
        $this->set('foot_address_select_chosen', $userAttributes['foot']);
        $this->set('head_social_links_chosen', $socialLinks['head']);
        $this->set('body_social_links_chosen', $socialLinks['body']);
        $this->set('foot_social_links_chosen', $socialLinks['foot']);
        $this->set('newsletter_id', $newsletter_id);
        $this->set('template_id', $template->getMailTemplateId());
        $this->set('family_font', \Config::get('toess_lab_news_letter.constants.google_fonts'));
        $this->view();
    }

    public function on_start()
    {
        $this->requireAsset('bootstrapswitch');
        $this->requireAsset('css', 'toesslab');
        $this->requireAsset('javascript', 'toesslab');
        if (PackageSetup::checkC5Version()) {
            // ToDo ckeditor $this->requireAsset('javascript', 'cke4_googlefontfamily');
        }else {
            $this->requireAsset('javascript', 'googlefontfamily');
        }
        $this->field_name_logo = t('Header logo');
        $this->nl_handle = t('Newsletter name');
        $this->nl_subject = t('Newsletter subject');
        $this->nl_template = t('Template name');
        $this->header_text = t('Head Section Content');
        $this->content = t('Body Section Content');
        $this->footer = t('Footer Section Content');
        parent::on_start();
    }

    /**
     * Gets a template(t_id) & newsletter(n_id)
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get_template()
    {

        $t_id = \Request::getInstance()->get('t_id');
        $n_id = \Request::getInstance()->get('n_id');
        $entity_manager = \ORM::entityManager();
        $mail_template = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $t_id);
        $mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $t_id));
        $mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $t_id));
        $mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $t_id));
        $mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $t_id));
        $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $n_id);
        $tpl = NewTemplate::getMailTemplate($mail_template, $mail_template_head, $mail_template_body, $mail_template_foot, $mail_template_title, $newsletter);
        echo \Core::make('helper/json')->encode(array('tpl'=> $tpl, 'subject' => $newsletter->getNLSubject()));
        exit;
    }

    /**
     * Gets all newsletters as select list id & handle
     *
     * @return array
     */
    public static function getAllNewsletters()
    {
        $newsLetters = array();
        $entity_manager = \ORM::entityManager();
        $newsletters = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')->findAll();
        foreach($newsletters as $mt){
            $newsLetters[$mt->getNewsLetterId()] = $mt->getNLHandle();
        }
        return $newsLetters;
    }

    /**
     * See NewTemplate::send_test_mail()
     */
    public function send_test_mail()
    {
        Settings::send_test_mail();
    }

    /**
     * Checks if a Newsletter is being used in a mailing at the moment
     * @param $id
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
            if(intval($id) == $newsletter->getNewsletter()) {
                $session->set('being_sent', t('This Newsletter is being sent at the moment. You can\'t edit or delete it.'));
                $response = \Redirect::to('/dashboard/newsletter/newsletters/newsletter_list');
                $response->send();
                exit;
            }
        }
    }

    /**
     * @param $args
     * @param bool $email
     * @return array
     */
    private function createUserAttributeKeysArray($args, $email = true)
    {
        $address = array();
        foreach($args as $a) {
            $address[$a['akHandle']] = $a['akName'];
        }
        if($email) {
            $address['uEmail'] = t('Email');
        }
        asort($address);
        return $address;
    }

    /**
     * @return array
     */
    private function getSocialLinks()
    {
        $socialLinks = array();
        $sl= Link::getList();
        $i = 0;
        foreach ($sl as $s) {
            $ji = $s->getServiceObject();
            $socialLinks[$i]['icon'] = $ji->getServiceIconHTML();
            $socialLinks[$i]['name'] = $ji->getDisplayName();
            $socialLinks[$i]['handle'] = $s->getServiceHandle();
            $socialLinks[$i]['link'] = $s->getUrl();
            $i++;
        }
        return $socialLinks;
    }
}
