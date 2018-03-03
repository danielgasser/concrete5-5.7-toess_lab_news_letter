<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Entity/ToessLabNewsLetterSendAddresses.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Entity;

/**
 * @Entity
 * @Table(name="ToessLabNewsLetterSendAddresses")
 */

class ToessLabNewsLetterSendAddresses
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $addID;

    protected $sendNewsletter;

    /**
     * @Column(type="string")
     */
    protected $uEmail;

    /**
     * @Column(type="datetime")
     */
    protected $send_time;

    /**
     * @Column(type="smallint")
     */
    protected $sent;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $sent_error;

    /**
     * @Column(type="integer", options={"unsigned":true})
     */
    protected $sendID;

	/**
	 * @Column(type="string")
	 */
	protected $browserKey;

	public function setSendMailings($newsletter)
    {
        $this->sendNewsletter = $newsletter;
    }

    public function getSendMailings()
    {
        return $this->sendNewsletter;
    }

    public function getAddId()
    {
        return $this->addID;
    }

    public function getSendId()
    {
        return $this->sendID;
    }

    public function setSendId($sendID)
    {
        $this->sendID = $sendID;
    }

    public function getUEmail()
    {
        return $this->uEmail;
    }

    public function setUEmail($uEmail)
    {
        $this->uEmail = $uEmail;
    }

    public function getSendTime()
    {
        return $this->send_time;
    }

    public function setSendTime($send_time)
    {
        $this->send_time = $send_time;
    }

    public function getSent()
    {
        return $this->sent;
    }

    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    public function getSentError()
    {
        return $this->sent_error;
    }

    public function setSentError($sent_error)
    {
        $this->sent_error = $sent_error;
    }

    public function getBrowserKey()
    {
        return $this->browserKey;
    }

    public function setBrowserKey($browserKey)
    {
        $this->browserKey = $browserKey;
    }
}
