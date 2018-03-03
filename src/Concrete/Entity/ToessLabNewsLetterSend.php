<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Entity/ToessLabNewsLetterSend.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Entity;

/**
 * @Entity
 * @Table(name="ToessLabNewsLetterSend")
 */

class ToessLabNewsLetterSend
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $sendID;

    /**
     * @Column(type="string", unique=true)
     */
    protected $ns_handle;

    /**
     * @Column(type="integer")
     */
    protected $sent_by_job;

    /**
     * @Column(type="datetime")
     */
    protected $start_send;

    /**
     * @Column(type="string")
     */
    protected $attachments;

    /**
     * @Column(type="integer")
     */
    protected $newsletter;

    /**
     * @Column(type="integer")
     */
    protected $templateID;

	public function setTemplateID($template)
    {
        $this->templateID = $template;
    }

    public function getTemplateID()
    {
        return $this->templateID;
    }

    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function getNewsletter()
    {
        return $this->newsletter;
    }

    public function getSendId()
    {
        return $this->sendID;
    }

    public function getNSHandle()
    {
        return $this->ns_handle;
    }

    public function setNSHandle($ns_handle)
    {
        $this->ns_handle = $ns_handle;
    }

    public function getSentByJob()
    {
        return $this->sent_by_job;
    }

    public function setSentByJob($sent_by_job)
    {
        $this->sent_by_job = $sent_by_job;
    }

    public function getStartSend()
    {
        return $this->start_send;
    }

    public function setStartSend($start_send)
    {
        $this->start_send = $start_send;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

}
