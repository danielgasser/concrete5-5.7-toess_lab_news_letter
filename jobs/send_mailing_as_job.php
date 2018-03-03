<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/jobs/send_newsletter_as_job.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Job;

use Concrete\Core\File\File;
use Concrete\Core\Logging\GroupLogger;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Doctrine\ORM\Query\Expr\Join;
use Log;
use Punic\Exception;
use \ZendQueue\Message as ZendQueueMessage;
use Concrete\Core\Mail;

class SendMailingAsJob extends \Concrete\Core\Job\QueueableJob
{

    public $jSupportsQueue = true;

    public function getJobName()
    {
        return t("Sends Newsletters");
    }

    public function getJobDescription()
    {
        return t("Sends a Newsletter according to the settings defined in Newsletter Single Page");
    }

    public function start(\ZendQueue\Queue $q)
    {
        $now = new \DateTime();
        $next = new \DateTime($now->format('Y-m-d H:i:s'));
        $settings = \Config::get('toess_lab_news_letter.settings');
        $entity_manager = \ORM::entityManager();

	    $args = array();
        $tu = $settings['time_unit'];

        $now->setTime($now->format('G'), $now->format('i'), 0);
        $now->modify('- 2 Hours');
        $next->modify('+ 1 ' . $tu);
	    $next->setTime($next->format('G'), $next->format('i'), 0);
	    $query_address = $entity_manager->createQueryBuilder()
            ->select('address')
            ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'address')
            ->leftJoin('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 'send', Join::WITH, 'address.sendID = send.sendID')
            ->where('address.send_time between :now and :next')
            ->andWhere('address.sent = :sent') //andWhere
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->setParameter('next', $next->format('Y-m-d H:i:s'))
            ->setParameter('sent', '0')
            ->getQuery();
		$iterableResult = $query_address->iterate();
	    $i = 0;
	    while(($row = $iterableResult->next()) !== false){
            $row_o = $row[0];
		    if ($i == 0) {
			    $query_tpl_nl = $entity_manager
				    ->createQueryBuilder()
				    ->select('nls')
				    ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 'nls')
				    ->leftJoin('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', 'nl', Join::WITH, 'nls.newsletter = nl.newsletterID')
				    ->where('nls.sendID = :sendID')
				    ->setParameter('sendID', $row_o->getSendID())
				    ->getQuery()
			        ->getSingleResult();
			    $args['newsletterID'] = $query_tpl_nl->getNewsletter();
			    $args['templateID'] = $query_tpl_nl->getTemplateID();
		    }
		    $args['addID'] = $row_o->getAddId();
		    $q->send(implode('|', $args));
            $i++;
	    }
    }

    public function finish(\ZendQueue\Queue $q)
    {
        return t('Mailing Job done.');
    }

    public function processQueueItem(ZendQueueMessage $msg)
    {
        $args = explode('|', $msg->body);
	    $newsletter_id = $args[0];
	    $template_id = $args[1];
	    $settings = \Config::get('toess_lab_news_letter.settings');
        $entity_manager = \ORM::entityManager();

        $query_address = $entity_manager->createQueryBuilder()
            ->select('address', 'send')
            ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'address')
            ->leftJoin('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 'send', Join::WITH, 'address.sendID = send.sendID')
            ->where('address.addID = :addID')
            ->setParameter('addID', $args[2])
            ->getQuery();
        $iterableResult = $query_address->iterate();
	    while(($row = $iterableResult->next()) !== false) {
            $row_o = $row[0];
            $sendIdF = $row_o->getSendId();
		    $ids[] = $row_o->getAddId();
            $recipient[] = $row_o->getUEmail();
            $browserKey[] = $row_o->getBrowserKey();
            $args[]['send_time'] = $row_o->getSendTime();
            $args[]['sent'] = $row_o->getSent();
        }
        if (sizeof($recipient) == 0) {
            return t('No recipients.');

        }
        $nt = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $template_id);
        $template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $template_id));
        $template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $template_id));
        $template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $template_id));
        $template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $template_id));
        $nl = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $newsletter_id);
        $text = stream_get_contents($nl->getMailText());
	    $text = Newsletter\SendMailing::insertTitleStyles($template_title, $text);
	    $footer = stream_get_contents($nl->getMailTextFooter());
	    $footer = Newsletter\SendMailing::insertTitleStyles($template_title, $footer);
	    $header = stream_get_contents($nl->getHeaderText());
	    $header = Newsletter\SendMailing::insertTitleStyles($template_title, $header);
	    $subject = $nl->getNLSubject();
        $attachments = explode(',', $nl->getAttachments());
        $i = 0;
        foreach($attachments as $a) {
            if(intval($a) > 0){
                $attachFiles[] = File::getByID(intval($a));
            }
            $i++;
        }
        $j = 0;
	    foreach ($recipient as $r) {
		    $href = BASE_URL . '/index.php/newsletter_in_browser?key=' . $browserKey[$j] . '&newsletter=' . $newsletter_id . '&template=' . $template_id . '&uEmail=' . $r;
            if (PackageSetup::checkC5Version('8.2')) {
                $mail_{$j} = \Core::make('mail');
            } else {
                $mail_{$j} = new Mail\Service();
            }
            $mail_{$j}->setTesting(false);
            $mail_{$j}->from($settings['owner_email']);
            $mail_{$j}->to($r);
		    $mail_{$j}->addParameter('inBrowser', false);
		    $mail_{$j}->addParameter('browserKey', $href);
		    $finalBodyText = Newsletter\SendMailing::insertUserAttributes($text, $r);
		    $mail_{$j}->addParameter('text', $finalBodyText);
		    $finalFooterText = Newsletter\SendMailing::insertUserAttributes($footer, $r);
            $mail_{$j}->addParameter('footer', $finalFooterText);
	        $finalHeaderText = Newsletter\SendMailing::insertUserAttributes($header, $r);
	        $mail_{$j}->addParameter('header', $finalHeaderText);
            $mail_{$j}->addParameter('tpl_head', $template_head);
            $mail_{$j}->addParameter('tpl_body', $template_body);
            $mail_{$j}->addParameter('tpl_foot', $template_foot);
            $mail_{$j}->addParameter('tpl_title', $template_title);
            $mail_{$j}->addParameter('tpl', $nt);
            $mail_{$j}->addParameter('url', BASE_URL);
            $mail_{$j}->addParameter('subject', $subject);
            $mail_{$j}->load('newsletter_template', PackageSetup::$pkgHandle);
            if (is_array($attachFiles) && sizeof($attachFiles) > 0)
            {
                foreach ($attachFiles as $k => $f) {
                    ${'f' . $k} = \File::getByID($f);
                    $mail_{$j}->addAttachment(${'f' . $k});
                }
            }
            try {
                $mail_{$j}->sendMail();
            } catch(Exception $e) {
                $l = new GroupLogger(LOG_TYPE_EXCEPTIONS, Logger::CRITICAL);
                $l->write(t('Mail Exception Occurred. Unable to send mail: '). $e->getMessage());
                $args['sent_error'] = t('Mail Exception Occurred. Unable to send mail. See Logs');
            }
            $j++;
        }
        $q = $entity_manager->createQuery('update Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses address set address.sent = 1 where address.addID in (' . implode(',', $ids) . ')');
        $q->execute();
	    $entity_manager->clear();
        Newsletter\SendMailing::send_confirmation_mail($settings, $sendIdF, true);
    }
}
