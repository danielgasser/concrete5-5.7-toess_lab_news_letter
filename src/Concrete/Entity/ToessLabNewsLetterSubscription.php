<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Entity/ToessLabNewsLetter.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Entity;

/**
 * @Entity
 * @Table(name="ToessLabNewsLetterSubscription")
 */

class ToessLabNewsLetterSubscription
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $newsletterSubscriptionID;

    /**
     * @Column(type="integer", options={"unsigned":true})
     */
    protected $uID;

    public function setNewsletterSubscriptionID($uID)
    {
        $this->uID = $uID;
    }

    public function getNewsletterSubscriptionID()
    {
        return $this->uID;
    }
}
