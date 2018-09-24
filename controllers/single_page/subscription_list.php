<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/settings.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage;

use Concrete\Core\Page\Controller\PageController;

class SubscriptionList extends PageController {



    public function view()
    {
        $this->set('email', \Request::getInstance()->get('email'));
    }

    public function on_start()
    {
        $this->requireAsset('toesslab_subscription');
        parent::on_start();
    }

}
