<?php
namespace Concrete\Package\ToessLabNewsLetter\Subscription;
defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Core\User\UserInfo;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSubscription;
use UserAttributeKey;

/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Subscription/Subscription.php
 */

class Subscription
{
    protected $app;

    /**
     * @return bool
     */
    public function setFirstTimeSubscriptions()
    {
        $db = \Core::make('database');
        $arrSel = array();
        $checkCol = $db->getAll('show columns from UserSearchIndexAttributes where field like "ak_toesslab_receive_newsletter%"');
        if (!$checkCol || sizeof($checkCol) == 0) {
            return false;
        }
        $querySel = 'SELECT uID FROM UserSearchIndexAttributes where ' . $checkCol[0]['Field'] . ' like "%Yes%"';
        $paramArray = array();
        $sqlArray = array();
        $resSel = $db->getAll($querySel);
        if (sizeof($resSel) > 0) {
            for ($i = 0; $i < sizeof($resSel); $i++){
                $sqlArray[] = '(' . implode(',', array_fill(0, count($resSel[$i]), '?')) . ')';

                foreach($arrSel[$i] as $element)
                {
                    $paramArray[] = $element;
                }
            }
            $query = "INSERT INTO ToessLabNewsLetterSubscription (newsletterSubscriptionID) VALUES ";
            $query .= implode(',', $sqlArray);
            $stmt = $db->prepare($query);
            return $stmt->execute($paramArray);
        }
        return false;
    }

    /**
     * @param UserInfo $user
     * @param $subscribe
     */
    public function setSubscription(UserInfo $user, $subscribe)
    {
        $entity_manager = \Core::make('Doctrine\ORM\EntityManager');
        $subscriptions = $this->getSubscriptions();
        if (sizeof($subscriptions) == 0 || is_null($subscriptions)) {
            $subscriptions['sub'] = [];
            $subscriptions['un_sub'] = [];
        }
        $uID = $user->getUserID();
        $index = array_search($uID, array_column($subscriptions['sub'], 'uID'));
        $un_index = array_search($uID, array_column($subscriptions['un_sub'], 'uID'));
        if ($subscribe && $index === false) {
            $toessLabNewsLetterSubscription = new ToessLabNewsLetterSubscription();
            $user->setAttribute('toesslab_receive_newsletter_new', true);
            $toessLabNewsLetterSubscription->setNewsletterSubscriptionID($uID);
            $entity_manager->persist($toessLabNewsLetterSubscription);
        }
        if (!$subscribe && $un_index === false) {
            $toessLabNewsLetterSubscription = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSubscription')->findOneBy(array('uID' => $uID));
            if (is_object($toessLabNewsLetterSubscription)) {
                $user->setAttribute('toesslab_receive_newsletter_new', false);
                $entity_manager->remove($toessLabNewsLetterSubscription);
            }
        }
        $entity_manager->flush();
    }

    public function getSubscriptionsOnly()
    {
        return $this->getAllSubscriptions();
    }

    public function getSubscriptions($emailIds = array())
    {
        if (sizeof($emailIds) > 0) {
            $subscribedUIDs = $emailIds;
        } else {
            $subscribedUIDs = $this->getAllSubscriptions();
        }
        $res = $this->substractSubscriptions($subscribedUIDs);
        return $res;
    }

    private function getAllSubscriptions()
    {
        $entity_manager = \ORM::entityManager();
        $toessLabNewsLetterSubscription = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSubscription')->findAll();
        foreach ($toessLabNewsLetterSubscription as $k => $sub) {
            $subscribedUIDs[] = $sub->getNewsletterSubscriptionID();
        }
        return $subscribedUIDs;

    }

    private function substractSubscriptions ($subscribed)
    {
        $db = \Core::make('database');

        if (is_null($subscribed) || sizeof($subscribed) == 0) {
            $res['sub'] = [];
            $sthSub = $db->prepare('select uID, uEmail from Users');
            $sthSub->execute();
            $res['un_sub'] = $sthSub->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        }
        $idPlaceHolders = implode(',', array_fill(0, count($subscribed), '?'));
        $sthUnSub = $db->prepare('select uID, uEmail from Users where uID NOT in (' . $idPlaceHolders . ')');
        $sthUnSub->execute($subscribed);
        $res['un_sub'] = $sthUnSub->fetchAll(\PDO::FETCH_ASSOC);
        $sthSub = $db->prepare('select uID, uEmail from Users where uID in (' . $idPlaceHolders . ')');
        $sthSub->execute($subscribed);
        $res['sub'] = $sthSub->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }
}
