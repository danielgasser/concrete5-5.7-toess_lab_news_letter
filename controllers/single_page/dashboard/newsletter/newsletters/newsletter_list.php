<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/newsletters/newsletter_list.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Newsletters;

use Concrete\Core\Page;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Settings as Settings;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates\NewTemplate;

class NewsletterList extends Page\Controller\DashboardPageController
{

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
        $ns = $this->getNewsletterData();
        if($ns == NULL) {
            $this->set('message', t('No Newsletter yet. Create one by clicking "Add Newsletter"'));
            return;
        }
        $this->set('newsletter', $ns);
        $this->set('mail_templates', NewTemplate::getAllTemplates());
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        $this->requireAsset('javascript', 'toesslab_tourist_newsletter_list');
        parent::on_start();
    }

    /**
     * Gets all Newsletters
     *
     * @return mixed
     */
    private function getNewsletterData()
    {
        $entity_manager = \ORM::entityManager();
        return $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')->findAll();
    }

    /**
     * @param bool $nl_id
     * @param bool $handle
     */
    public function delete_newsletter($nl_id = false, $handle = false)
    {
        NewNewsletter::delete_newsletter($nl_id, $handle);
    }

    /**
     * @param bool $nl_id
     * @param bool $handle
     */
    public function duplicate_newsletter($nl_id = false, $handle = false)
    {
        NewNewsletter::duplicate_newsletter($nl_id, $handle);
    }
}
