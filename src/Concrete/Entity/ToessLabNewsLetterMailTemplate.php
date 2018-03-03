<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Entity/ToessLabNewsLetterMailTemplate.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Entity;

/**
 * @Entity
 * @Table(name="ToessLabNewsLetterMailTemplate")
 */

class ToessLabNewsLetterMailTemplate
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $mailtemplateID;

    /**
     * @OneToMany(targetEntity="ToessLabNewsLetter", mappedBy="ToessLabNewsLetterMailTemplate", cascade={"persist"})
     * @JoinColumn(name="newsletterID", referencedColumnName="newsletterID", onDelete="cascade")
     **/
    protected $newsletters;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $newslettersSend = null;

    /**
     * @Column(type="string", unique=true)
     */
    protected $templateHandle = 'handle';

    /**
     * @Column(type="integer")
     */
    protected $tpl_designed = '0';

    public function getNewsletters()
    {
        return $this->newsletters;
    }

    public function setNewsletters($newsletter)
    {
        $this->newsletters = $newsletter;
    }

    public function getMailTemplateId()
    {
        return $this->mailtemplateID;
    }

    public function setMailTemplate($mail_template_id)
    {
        $this->mailtemplateID = $mail_template_id;
    }

    public function getMailTemplateHandle()
    {
        return $this->templateHandle;
    }

    public function setMailTemplateHandle($mail_template_handle)
    {
        $this->templateHandle = $mail_template_handle;
    }

    public function getTemplateDesigned()
    {
        return $this->tpl_designed;
    }

    public function setTemplateDesigned($tpl_designed)
    {
        $this->tpl_designed = $tpl_designed;
    }

}
