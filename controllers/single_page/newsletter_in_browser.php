<?php
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage;

use Concrete\Core\Mail\Service;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Doctrine\ORM\Query;
use PageController;
use Core;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\SendMailing as SendMailing;

class NewsletterInBrowser extends PageController
{

    public function view()
    {
	    $this->set('test', $this->get());
    }

	public function get_newsletter()
	{
		$t_id = \Request::getInstance()->get('t_id');
		$n_id = \Request::getInstance()->get('n_id');
		$uEmail = \Request::getInstance()->get('uEmail');
		$browserKey = \Request::getInstance()->get('browserKey');

        $entity_manager = \ORM::entityManager();
        $mail_template = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $t_id);
		$mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $t_id));
		$mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $t_id));
		$mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $t_id));
		$mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $t_id));
		$newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $n_id);
        if (PackageSetup::checkC5Version()) {
            $m = \Core::make('mail');
        } else {
            $m = new Service();
        }
		$text = stream_get_contents($newsletter->getMailText());
		$footer = stream_get_contents($newsletter->getMailTextFooter());
		$header = stream_get_contents($newsletter->getHeaderText());
		$text = SendMailing::insertTitleStyles($mail_template_title, $text);
		$footer = SendMailing::insertTitleStyles($mail_template_title, $footer);
		$header = SendMailing::insertTitleStyles($mail_template_title, $header);
		$text = SendMailing::insertUserAttributes($text, $uEmail);
		$footer = SendMailing::insertUserAttributes($footer, $uEmail);
		$header = SendMailing::insertUserAttributes($header, $uEmail);
		$m->addParameter('inBrowser', true);
		$m->addParameter('text', $text);
		$m->addParameter('footer', $footer);
		$m->addParameter('header', $header);
		$m->addParameter('preview', true);
		$m->addParameter('tpl', $mail_template);
		$m->addParameter('tpl_head', $mail_template_head);
		$m->addParameter('tpl_body', $mail_template_body);
		$m->addParameter('tpl_foot', $mail_template_foot);
		$m->addParameter('tpl_title', $mail_template_title);
		$m->load('newsletter_template', PackageSetup::$pkgHandle);
		$tpl = $m->getBodyHTML();
		$entity_manager->clear();
		echo \Core::make('helper/json')->encode(array('tpl'=> $tpl, 'subject' => $newsletter->getNLSubject()));
		exit;
	}

	public function checkKey($uEmail, $t_id, $n_id, $browserKey)
	{
        $entity_manager = \ORM::entityManager();
        $stmt = $entity_manager->getConnection();
        $res = $stmt->executeQuery('SELECT * from ToessLabNewsLetterSendAddresses where browserKey = "' . $browserKey . '";');
/*
        $sendAddress = $entity_manager->createQueryBuilder()
			->select('a')
			->from('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'a')
			->where('a.browserKey = :browserKey')
			->setParameter('browserKey', $browserKey)
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);
        */
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $sendAddress[] = $row;
        }
		$send = $entity_manager->createQueryBuilder()
           ->select('s')
           ->from('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 's')
           ->where('s.sendID = :sendID')
           ->setParameter('sendID', $sendAddress[0]['sendID'])
           ->getQuery()
           ->getResult(Query::HYDRATE_ARRAY);
		if ($sendAddress == null || intval($t_id) != $send[0]['templateID'] || intval($n_id) != $send[0]['newsletter'] || $browserKey != $sendAddress[0]['browserKey'] || $uEmail != $sendAddress[0]['uEmail']) {
			return false;
		}
		return true;
	}
}

