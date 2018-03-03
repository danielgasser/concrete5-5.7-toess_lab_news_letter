<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/newsletter_sent.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Doctrine\ORM;

class NewsletterSent extends DashboardPageController
{

    public function view ()
    {
        $ns = $this->getSentData();
        if($ns == NULL) {
            $this->set('message', t('No Newsletter yet'));
            return;
        }
        $this->set('ns_sent', $ns);
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        parent::on_start();
    }

    /**
     * Get sent newsletters with addresses
     *
     * @param int $sendID
     * @return array
     */
    public static function getSentData($sendID = 0)
    {
        $entity_manager = \ORM::entityManager();
        if(intval($sendID) > 0){
            $query = $entity_manager->createQueryBuilder()
                ->select('address', 'send')
                ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'address')
                ->leftJoin('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 'send', ORM\Query\Expr\Join::WITH, 'address.sendID = send.sendID')
                ->where('send.sendID = :sendid')
                ->setParameter('sendid', $sendID)
                ->orderBy('send.start_send', 'desc')
                ->addOrderBy('address.send_time', 'asc')
                ->getQuery();
        } else {
            $query = $entity_manager->createQueryBuilder()
                ->select('address')
                ->from('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses', 'address')
                ->leftJoin('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', 'send', ORM\Query\Expr\Join::WITH, 'address.sendID = send.sendID')
                ->addOrderBy('send.start_send', 'desc')
                ->addOrderBy('send.sendID', 'desc')
                ->addOrderBy('address.send_time', 'asc')
                ->getQuery();
        }
        $iterableResult = $query->iterate();
        $i = 0;
        $last_id = null;
        $arr = array();
        foreach($iterableResult as $row){
            $row_o = $row[0];
            $id = $row_o->getSendId();
            if(intval($id) > 0){
                $newsletterSent = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend', $id);
                if(is_object($newsletterSent)) {
                    $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $newsletterSent->getNewsletter());
                    $newsletterTemplate = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $newsletterSent->getTemplateID());
                    if(is_object($newsletter)){
                        $arr[$id]['newsletter_handle'] = $newsletter->getNLHandle();
                        $arr[$id]['newsletter_id'] = $newsletter->getNewsLetterId();
                    } else {
                        $arr[$id]['newsletter_handle'] = t('Newsletter has been deleted');
                        $arr[$id]['newsletter_id'] = NULL;
                    }
                    if (is_object($newsletterTemplate)) {
                        $arr[$id]['template'] = $newsletterTemplate->getMailTemplateHandle();
                        $arr[$id]['template_id'] = $newsletterTemplate->getMailTemplateId();
                    } else {
                        $arr[$id]['template'] = t('Template has been deleted');
                        $arr[$id]['template_id'] = NULL;
                    }
                    $arr[$id]['id'] = $row_o->getSendId();
                    $arr[$id]['ns_handle'] = $newsletterSent->getNSHandle();
                    $arr[$id]['sent_by_job'] = $newsletterSent->getSentByJob();
                    $arr[$id]['start_send'] = $newsletterSent->getStartSend();
                    $arr[$id]['attachments'] = $newsletterSent->getAttachments();
                    if(!isset($arr[$id]['mails'][$i])) {
                        $arr[$id]['mails'][] = array(
                            'id' => $row_o->getSendId(),
                            'uEmail' => $row_o->getUEmail(),
                            'send_time' => $row_o->getSendTime(),
                            'sent' => $row_o->getSent(),
                            'sent_error' => $row_o->getSentError(),
                        );
                        $i++;
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * @param bool $nlid
     * @param bool $nlhandle
     */
    public static function delete_newsletter($nlid = false, $nlhandle = false)
    {
        $nl_id = \Core::make('helper/security')->sanitizeInt($nlid);
        $handle = \Core::make('helper/security')->sanitizeString($nlhandle);
        $session = \Core::make('session');
        $entity_manager = \ORM::entityManager();
        $newsletter =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend')->findOneBy(array('ns_handle' => $handle));
        $addresses =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses')->findBy(array('sendID' => $nl_id));
        try {
            foreach($addresses as $ad) {
                $entity_manager->remove($ad);
            }
            $entity_manager->flush();
            $entity_manager->remove($newsletter);
            $entity_manager->flush();
        }
        catch(Exception $e){
            $session->set('error', $e->getMessage());
        }
        $session->set('history_deleted', t('The Newsletter \'%s\' has been deleted', $handle));
        $response = \Redirect::to('/dashboard/newsletter/newsletter_sent');
        $response->send();
        exit;

    }
}
