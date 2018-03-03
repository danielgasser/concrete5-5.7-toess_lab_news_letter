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
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Concrete\Package\ToessLabNewsLetter\Subscription\Subscription;

class Subscriptions extends DashboardPageController {

    protected $subscription;

    public function __construct(Page $c, Subscription $subscription)
    {
        parent::__construct($c);
        $this->subscription = $subscription;
    }

    public function view()
    {
        $list = $this->subscription->getSubscriptions();
        if ($list == null) {
            $this->error->add(t('No subscriptions'));
        }
        if (!$this->error->has()) {
            $this->set('subscriptions', $list);
        }
    }

    public function on_start()
    {
        $this->requireAsset('css', 'toesslab');
        $this->requireAsset('toesslab_subscription');
        $this->requireAsset('my-jquery-ui');
        parent::on_start();
    }

    public function set_subscription()
    {
        $db = $this->app->make('database');
        $userInfo = $this->app->make('Concrete\Core\User\UserInfo');
        $emailIDs = json_decode(\Core::make('helper/security')->sanitizeString($this->post('emailIDs')));
        $subscribe = json_decode(\Core::make('helper/security')->sanitizeString($this->post('sub')));
        $emailPlaceHolders = implode(',', array_fill(0, count($emailIDs), '?'));
        $sth = $db->prepare("select uID, uEmail from Users where uEmail in ($emailPlaceHolders)");
        $sth->execute($emailIDs);
        $res = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if (sizeof($res) == 0) {
            $li = '<ul>';
            foreach ($emailIDs as $e) {
                $li .= '<li>' . $e . '</li>';
            }
            $li .= '</ul>';
            $msgSingle = t('No member with this email address found. Please register the member first.');
            $msgMulti = t('No members with these email address found. Please register those members first.');
            if (sizeof($emailIDs) > 1) {
                echo json_encode(array('error' => $msgMulti . $li));
                exit;
            }
            echo json_encode(array('error' => $msgSingle . $li));
            exit;
        }
        foreach ($res as $s){
            $u = $userInfo->getByID($s['uID']);
            $this->subscription->setSubscription($u, $subscribe);
        }
        echo json_encode(array('success' => $this->subscription->getSubscriptions()));
        exit;
    }
}
