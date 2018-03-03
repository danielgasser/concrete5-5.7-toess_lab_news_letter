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
 * @Table(name="ToessLabNewsLetter")
 */

class ToessLabNewsLetter
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $newsletterID;

    /**
     * @Column(type="string", unique=true)
     */
    protected $nl_handle;

    /**
     * @Column(type="string")
     */
    protected $nl_subject;

    /**
     * @Column(type="blob")
     */
    protected $mail_text;

    /**
     * @Column(type="blob")
     */
    protected $mail_text_footer;

    /**
     * @Column(type="blob")
     */
    protected $header_text;

    /**
     * @Column(type="string")
     */
    protected $attachments;

    /**
     * @ManyToOne(targetEntity="ToessLabNewsLetterMailTemplate", inversedBy="ToessLabNewsLetter", cascade={"detach"})
     * @JoinColumn(name="mailtemplateID", referencedColumnName="mailtemplateID")
     **/
    protected $emailTemplates;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $newslettersSend = null;

    /**
     * @Column(type="integer", options={"unsigned":true})
     */
    protected $mailtemplateID;

    /**
     * @Column(type="integer", options={"unsigned":true})
     */
    protected $modified;

    /**
     * @Column(type="blob", nullable=true)
     */
    protected $userAttributes;

    /**
     * @Column(type="blob", nullable=true)
     */
    protected $socialLinks;

    public function __clone()
    {
        if($this->newsletterID){
            $this->newsletterID = null;
        }
    }

    public function setSendMailings($newsletter)
    {
        $this->newslettersSend = $newsletter;
    }

    public function getSendMailings()
    {
        return $this->newslettersSend;
    }

    public function setTemplate($template)
    {
        $this->emailTemplates = $template;
    }

    public function getTemplate()
    {
        return $this->emailTemplates;
    }

    public function getNewsLetterId()
    {
        return $this->newsletterID;
    }

    public function getNLHandle()
    {
        return $this->nl_handle;
    }

    public function setNLHandle($nl_handle)
    {
        $this->nl_handle = $nl_handle;
    }

    public function setNLSubject($nl_subject)
    {
        $this->nl_subject = $nl_subject;
    }

    public function getNLSubject()
    {
        return $this->nl_subject;
    }

    public function getMailText()
    {
        return $this->mail_text;
    }

    public function setMailText($mail_text)
    {
        $this->mail_text = $mail_text;
    }

    public function getMailTextFooter()
    {
        return $this->mail_text_footer;
    }

    public function setMailTextFooter($mail_text_footer)
    {
        $this->mail_text_footer = $mail_text_footer;
    }

    public function getHeaderText()
    {
        return $this->header_text;
    }

    public function setHeaderText($header_text)
    {
        $this->header_text = $header_text;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getUserAttributes()
    {
        return $this->userAttributes;
    }

    public function setUserAttributes($userAttributes)
    {
        $this->userAttributes = $userAttributes;
    }

    public function getSocialLinks()
    {
        return $this->socialLinks;
    }

    public function setSocialLinks($socialLinks)
    {
        $this->socialLinks = $socialLinks;
    }
}
