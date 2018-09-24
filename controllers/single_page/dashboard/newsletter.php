<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter.php
 */

namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard;

use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Settings;
use \Concrete\Core\Page\Controller\DashboardPageController;

class Newsletter extends DashboardPageController
{
    public function view()
    {
        if (!Settings::checkEmailSettings()) {
            $this->error->add(t('Please adapt the Settings before sending Newsletter.'));
            $this->set('error', $this->error);
        }

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
        $this->enableNativeMobile();
        $categories = array();
        $c = \Page::getCurrentPage();
        $children = $c->getCollectionChildrenArray(true);
        foreach($children as $cID) {
            $nc = \Page::getByID($cID, 'ACTIVE');
            $ncp = new \Permissions($nc);
            if ($ncp->canRead()) {
                $categories[] = $nc;
            }
        }

        $this->set('categories', $categories);
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        parent::on_start();
    }


    /**
     * @param array $tabs
     * @return array
     */
    public static function setSelectedTabs (array $tabs)
    {
        $session = \Core::make('session');
        $selectedTabs = array();
        if ($session->has('selectedTabs') && count($session->get('selectedTabs')) > 0) {
            $selectedTabs = $session->get('selectedTabs');
        }
        if(count($selectedTabs) == 0) {
            $tabs[0][2] = true;
            return $tabs;
        }
        $i = 0;
        foreach($tabs as $st){
            if(in_array($st[0], $selectedTabs)){
                $tabs[$i][2] = true;
            }else {
                $tabs[$i][2] = false;
            }
            $i++;
        }

        return $tabs;
    }
}
