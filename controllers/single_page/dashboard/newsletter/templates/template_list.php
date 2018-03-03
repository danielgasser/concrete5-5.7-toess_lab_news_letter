<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/templates/template_list.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates;

use Concrete\Core\Page;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Settings as Settings;

class TemplateList extends Page\Controller\DashboardPageController {


    /**
     * @var string
     */
    public $nl_subject = '';

    function __construct(\Concrete\Core\Page\Page $c)
    {
        parent::__construct($c);
    }

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
        $ns = $this::getNewsletterTemplateData();
        if($ns == NULL) {
            $this->set('message', t('No Templates yet. Create one by clicking "Add Template"'));
            return;
        }
        $this->set('newsletter_templates', $ns);
        $this->set('newsletters_d', NewTemplate::getNewsletterDropDown());
    }

    /**
     * Gets templates with content
     *
     * @return array
     */
    public static function getNewsletterTemplateData()
    {
        $tpl = array();
        $app = \Core::make('app');
        $entity_manager = \ORM::entityManager();
        $nt = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate')->findAll();
        $i = 0;
        foreach($nt as $n){
            $tpl[$i]['id'] = $n->getMailTemplateId();
            $tpl[$i]['handle'] = $n->getMailTemplateHandle();
            $i++;
        }
        return $tpl;
    }

    public function on_start()
    {
        $this->requireAsset('css', 'toesslab');
        $this->requireAsset('javascript', 'toesslab');
        $this->requireAsset('css', 'bootstrapswitch');
        $this->requireAsset('javascript', 'bootstrapswitch');
        $this->requireAsset('javascript', 'toesslab_tourist_newsletter_template_list');
        $this->nl_subject = t('Subject');
        parent::on_start();
    }

    /**
     * See NewNewsletter::get_template()
     */
    public function get_newsletter()
    {
        return Controller\SinglePage\Dashboard\Newsletter\Newsletters\NewNewsletter::get_template();
    }

    /**
     * @param bool $tl_id
     * @param bool $handle
     */
    public function delete_template($tl_id = false, $handle = false)
    {
        NewTemplate::delete_template($tl_id, $handle);
    }

    /**
     * @param bool $tl_id
     * @param bool $handle
     */
    public function duplicate_template($tl_id = false, $handle = false)
    {
        NewTemplate::duplicate_template($tl_id, $handle);
    }
}
