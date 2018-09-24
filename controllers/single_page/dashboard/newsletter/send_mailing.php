<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/send_mailing.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;

use Concrete\Controller\Search\Users;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Newsletters\NewNewsletter;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates\NewTemplate;
use Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter\Templates\TemplateList;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSend;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterSendAddresses;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Concrete\Package\ToessLabNewsLetter\Subscription\Subscription;
use Doctrine\DBAL\DBALException;
use \Concrete\Core\User\Group\Group;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File;
use Concrete\Core\Mail;
use Punic\Exception;

class SendMailing extends DashboardPageController
{
    /**
     * @var Group
     */
    private $userGroup;

    /**
     * @var string
     */
    public $ns_handle = '';

    public function view()
    {

        $session = \Core::make('session');
        if (!Settings::checkUserAmount()) {
            $this->error->add(t('There are no users to send a Newsletter to. Please add some users/groups first.'));
            $this->set('error', $this->error);
        }
        if(!Settings::checkFirstTimeSettings()) {
            $this->error->add(t('Please adapt the Settings before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        if(!Settings::checkFirstTimeTemplate()) {
            $this->error->add(t('Don\'t forget to design a Template before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        if(!Settings::checkFirstTimeNewsletter()) {
            $this->error->add(t('Don\'t forget to create a Newsletter before sending Newsletter.'));
            $this->set('error', $this->error);
        }

        $session->remove('sent');

        $ns = TemplateList::getNewsletterTemplateData();
        $usersInGroup = $this->setUserGroup();
        $this->set('newsletters', NewNewsletter::getAllNewsletters());
        $this->set('templates', NewTemplate::getAllTemplates());
        $this->set('users', $usersInGroup);
        $this->set('possibleGroups', $this->setPossibleUserGroup());

        $row = \Config::get('toess_lab_news_letter.settings');
        $this->set('files_num', $row['files_num']);
        $this->set('owner_email', $row['owner_email']);
        $this->set('totalRecords', '0');
        $this->set('min_max', Settings::getMinMaxValues());
        $this->set('newsletter_templates', $ns);
        $this->set('newsletters_d', NewTemplate::getNewsletterDropDown());
    }

    /**
     * Sends the newsletter directly
     * If the per_job is set to 1
     * else (0) the newsletter will be prepared & saved for a \Job
     */
    public function send_or_prepare_newsletter()
    {
        if (!Settings::checkFirstTimeSettings()) {
            $this->view();
            return;
        }
        if (!Settings::checkFirstTimeTemplate()) {
            $this->view();
            return;
        }
        if (!Settings::checkFirstTimeNewsletter()) {
            $this->view();
            return;
        }
        $isSorted = \Core::make('helper/security')->sanitizeString($this->post('isSorted'));
        $isSortedBy = \Core::make('helper/security')->sanitizeString($this->post('isSortedBy'));
        $ns_handle = \Core::make('helper/security')->sanitizeString($this->post('ns_handle'));
        $newsletter = \Core::make('helper/security')->sanitizeString($this->post('newsletter'));
        $template = \Core::make('helper/security')->sanitizeString($this->post('template'));
	    $uEmails = json_decode($this->post('all_Emails'));
        foreach($uEmails as $key => $email) {
            if(!\Core::make('helper/security')->sanitizeEmail($email)){
                array_splice($uEmails, $key, 1);
            }
        }
        if ($uEmails == null || !is_array($uEmails) || count($uEmails) == 0) {
            $this->error->add(t('No recipients found. Please choose another group'));
        }
        if (!$this->token->validate('send_or_prepare_newsletter')) {
            $this->set('error', array($this->token->getErrorMessage()));
        }
        if (strlen($ns_handle) == 0) {
            $this->error->add(t('The field "%s" is required', $this->ns_handle));
        }
        if (strlen($ns_handle) > 255) {
            $this->error->add(t('The field "%s" is limited to %s characters', $this->ns_handle, 255));
        }
        if ($newsletter == '0') {
            $this->error->add(t('Please choose a Newsletter to send'));
        }
        if ($this->error->has()) {
            $this->view();
            return;
        }
        foreach($uEmails as $email) {
	        $browserKey = $this->generateBrowserKey();
	        $args['browserKey'][] = $browserKey;
	        $args['uEmail'][] = \Core::make('helper/security')->sanitizeEmail($email);
        }

        $this->set('chosen_emails', $args['uEmail']);
        $this->set('isSorted', $isSorted);
        $this->set('isSortedBy', $isSortedBy);
        $session = \Core::make('session');
        if ($session->get('sent')) {
            $session->remove('sent');
            $session->set('already_sent', t('This %s has already been sent or saved as Automated Job', t('Newsletter')));
            $response = \Redirect::to('/dashboard/newsletter/send_mailing/');
            $response->send();
            exit;
        } else {
            $session->remove('already_sent');
        }
        $dh = \Core::make('helper/date');

        $entity_manager = \ORM::entityManager();
        $nt = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $template);
        $template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $template));
        $template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $template));
        $template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $template));
        $template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $template));
        $nl = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $newsletter);
        $text = stream_get_contents($nl->getMailText());
        $text = $this->insertTitleStyles($template_title, $text);
        $footer = stream_get_contents($nl->getMailTextFooter());
        $footer = $this->insertTitleStyles($template_title, $footer);
        $header = stream_get_contents($nl->getHeaderText());
        $header = $this->insertTitleStyles($template_title, $header);
        $subject = $nl->getNLSubject();
        $attachments = explode(',', $nl->getAttachments());
        $session->set('sent', true);
        $args_settings = \Config::get('toess_lab_news_letter.settings');
        $args['sender'] = $args_settings['owner_email'];
        $args['newsletter'] = $nl->getNewsLetterId();
        $args['template'] = $nt->getMailTemplateId();
        $args['filesNum'] = $args_settings['files_num'];
        $args['mails_per'] = $args_settings['mails_per'];
        $args['time_unit'] = $args_settings['time_unit'];
        $args['ns_handle'] = $ns_handle;
        $args['sent_by_job'] = $args_settings['per_job'];
        $today = new \DateTime();
        $today->modify('+ 1 Hours');
        $today->setTime($today->format('H'), 0, 0);

        $args['today'] = $today;
        $start_send = new \DateTime($args['today']->format('Y-m-d H:i:s'));
        $i = 0;
        $fileSize = 0;
        $attachFiles = array();
        foreach ($attachments as $a) {
            if (intval($a) > 0) {
                $args['saveFiles'][] = $a;
                $file = File\File::getByID(intval($a));
                $fileSize += $file->getFileResource()->getSize();
                $attachFiles[] = $file;
            }
            $i++;
        }
        if ($fileSize > \Config::get('toess_lab_news_letter.settings.file_total_size')) {
            $this->error->add(t('The size of attachments is bigger than the allowed maximum of %s Bytes', \Config::get('toess_lab_news_letter.settings.file_total_size')));
        }

        if ($this->error->has()) {
            $this->view();
            return;
        }
        // Send mails directly
        if (!$args['sent_by_job']) {
            $m = 0;
            foreach ($args['uEmail'] as $rec) {
	            $href = BASE_URL . '/index.php/newsletter_in_browser?key=' . $args['browserKey'][$m] . '&newsletter=' . $args['newsletter'] . '&template=' . $args['template'] . '&uEmail=' . $rec;
                if (PackageSetup::checkC5Version('8.2')) {
                    $mail_{$m} = \Core::make('mail');
                } else {
                    $mail_{$m} = new Mail\Service();
                }
                $mail_{$m}->setTesting(false);

                $mail_{$m}->from($args_settings['owner_email']);
                $mail_{$m}->to($rec);
                $finalBodyText = $this->insertUserAttributes($text, $rec);
	            $mail_{$m}->addParameter('inBrowser', false);
	            $mail_{$m}->addParameter('browserKey', $href);
                $mail_{$m}->addParameter('text', $finalBodyText);
                $finalFooterText = $this->insertUserAttributes($footer, $rec);
                $mail_{$m}->addParameter('footer', $finalFooterText);
                $finalHeaderText = $this->insertUserAttributes($header, $rec);
                $mail_{$m}->addParameter('header', $finalHeaderText);
                $mail_{$m}->addParameter('tpl_head', $template_head);
                $mail_{$m}->addParameter('tpl_body', $template_body);
                $mail_{$m}->addParameter('tpl_foot', $template_foot);
                $mail_{$m}->addParameter('tpl_title', $template_title);
                $mail_{$m}->addParameter('subject', $subject);
                $mail_{$m}->addParameter('tpl', $nt);

                $mail_{$m}->addParameter('url', BASE_URL);
                $mail_{$m}->load('newsletter_template', PackageSetup::$pkgHandle);
                if (is_array($attachFiles) && count($attachFiles) > 0) {
                    foreach ($attachFiles as $sf) {
                        $mail_{$m}->addAttachment($sf);
                    }
                }
                try {
                    $mail_{$m}->sendMail();
                    $args['sent'] = '1';
                    $now = new \DateTime();
                    $args['today'] = $now;
                    $args['sent_error'] = 'no error';
                } catch (Exception $e) {
                    $this->error->add($e->getMessage());
                    if ($this->error->has()) {
                        $this->view();
                        return;
                    }
                }
                $m++;
            }
            $c = $this->saveNewsLetter($args);
            if (!$c) {
                $this->error->add(t('The %s name \'%s\' is already in use. Please choose another one.', t('Newsletter'), $args['ns_handle']));
            }
            if ($this->error->has()) {
                $this->view();
                return;
            }
            $message = t('%s emails successfully sent!', $c['count_mails']);
        }

        // Prepare mails for the job
        if ($args['sent_by_job']) {
            $args['sent_error'] = 'no error';
            $args['sent'] = '0';
            $c = $this->saveNewsLetter($args);
            if (!$c) {
                $this->error->add(t('The %s name \'%s\' is already in use. Please choose another one.', t('Mailing'), $args['ns_handle']));
            }
            if ($this->error->has()) {
                $this->view();
                return;
            }
            $message = t('%s emails will be sent as "%s". Dispatching starts at %s', $c['count_mails'], t('Automated Jobs'), $dh->formatDateTime($start_send->getTimestamp()));
        }
        $usersInGroup = $this->setUserGroup();
        $this->set('users', $usersInGroup);
        $this->set('possibleGroups', $this->setPossibleUserGroup());
        $this->set('uEmail', $args['uEmail']);
        $this->set('sender', $args['owner_email']);
        $this->set('files_num', $args['filesNum']);
        $this->send_confirmation_mail($args_settings, $c['last_send']);
        $session->set('success_sent', $message . '<br>' . t('See all Newsletters already saved and/or sent at the <a href="%s">%s</a>.', \URL::to('dashboard/newsletter/newsletter_sent'), t('Mailing') . ' ' . t('History'), t('Automated Jobs')));
        $session->remove('email_settings');
        $response = \Redirect::to('/dashboard/newsletter');
        $response->send();
        exit;
    }

    /**
     *
     */
    public function sort_recipient()
    {
        $cnt = new Users();
        $cnt->search();
        $this->set('sortController', $cnt);
        $result = $cnt->getSearchResultObject();
        if (is_object($result)) {
            $object = $result->getJSONObject();
            echo \Core::make('helper/json')->encode($object);
            exit;
        }
        echo \Core::make('helper/json')->encode(t('No results'));
        exit;

    }

    /**
     *
     * Changes the group by AJAX
     * and echoes it as Json if $json is true
     *
     * @param bool $json
     * @return array
     *
     */
    public function change_user_group($json = true)
    {
        $db = \Core::make('database');
        $superuser = array();
        $allUsers = array();
        $subscriptions = new Subscription();
        $subscribedUIDs = $subscriptions->getSubscriptionsOnly();
        if (!is_null($subscribedUIDs)) {
            $placeHolders = implode(',', array_fill(0, count($subscribedUIDs), '?'));
        }
        $group_id = \Request::getInstance()->get('group_id');
        $was_checked = (\Request::getInstance()->get('was_checked') == NULL) ? array() : \Request::getInstance()->get('was_checked');
        if (count($subscribedUIDs) == 0) {
            echo \Core::make('helper/json')->encode(array('toesslab_receive_newsletter' => false));
            exit;
        }
        if (!is_null($group_id)) {
            if (count($group_id) == 0) {
                if ($json) {
                    echo \Core::make('helper/json')->encode(NULL);
                    exit;
                }
                return NULL;
            }
            foreach($group_id as $gi) {
                if ($gi == '2') {
                    $sth = $db->prepare("select uID, uName, uEmail from Users where uID in ($placeHolders)");
                    $sth->execute($subscribedUIDs);
                    while ($row = $sth->fetch(\PDO::FETCH_OBJ)) {
                        $row->isChecked = true;
                        $users[] = $row;
                    }
                } else {
                    $userGroup = Group::getByID($gi);
                    $users = $userGroup->getGroupMembers();
                    if ($gi == '3') {
                        $res = $db->execute("select uID, uName, uEmail from Users where uID = 1");
                        while ($row = $res->fetch(\PDO::FETCH_OBJ)) {
                            $row->isChecked = in_array($row->uEmail, $was_checked);
                            $superuser = $row;
                        }
                        $users[] = $superuser;
                    }
                }
                foreach($users as $u) {
                    $userInfo = $this->app->make('Concrete\Core\User\UserInfo');
                    if ($u instanceof \stdClass) {
                        $ui = $userInfo->getByID($u->uID);
                    } else {
                        $ui = $userInfo->getByID($u->getUserID());
                    }
                    if (!in_array($ui->getUserID(), $subscribedUIDs)) {
                        continue;
                    }
                    $userList = new \StdClass();
                    $userList->isChecked = in_array($ui->getUserEmail(), $was_checked);
                    $userList->uID = $ui->getUserID();
                    $userList->uEmail = $ui->getUserEmail();
                    $userList->uName = $ui->getUserName();
                    $userList->uAddress = ($ui->getAttribute('toesslab_address') === false || $ui->getAttribute('toesslab_address') === null) ? t('not defined') : $ui->getAttribute('toesslab_address')->__toString();
                    $userList->uFirstName = ($ui->getAttribute('toesslab_first_name') === false || $ui->getAttribute('toesslab_first_name') === null) ? t('not defined') : $ui->getAttribute('toesslab_first_name');
                    $userList->uLastName = ($ui->getAttribute('toesslab_last_name') === false || $ui->getAttribute('toesslab_last_name') === null) ? t('not defined') : $ui->getAttribute('toesslab_last_name');
                    $userList->uName = $ui->getUserName();
                    $allUsers[] = $userList;
                }
            }
        }
        if ($json) {
            if (count($allUsers) == 0) {
                echo \Core::make('helper/json')->encode(array('toesslab_receive_newsletter' => false));
                exit;
            }
            echo \Core::make('helper/json')->encode($allUsers);
            exit;
        }
        return $users;
    }

    /**
     *
     */
    public function sort_user_group()
    {
        $this->getUserAttributeKeys();
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        $this->requireAsset('bootstrapswitch');
        $this->requireAsset('javascript', 'toesslab_tourist_send_newsletter');
        $this->ns_handle = t('Mailing name');
        parent::on_start();
    }

    /**
     *
     * Sends a confirmation mail to the admin user
     *
     * @param array $args
     * @param int $sendID
     * @param bool $from_job
     * @throws Exception
     * @throws \Exception
     */
    public static function send_confirmation_mail($args, $sendID, $from_job = false)
    {
        if (!$args['report_newsletter']) return;
        $dh = \Core::make('helper/date');
        $records = '';
        $recs = '';
        $data = NewsletterSent::getSentData($sendID);
        foreach ($data as $v) {
            $now = new \DateTime();
            $date = new \DateTime($v['start_send']->format('Y-m-d H:i:s'));
            $print_date = $dh->formatDateTime($date->getTimestamp());
            $handle = $v['ns_handle'];
            if ($v['sent_by_job'] == '1') {
                if ($now->getTimestamp() < $date->getTimestamp()) {
                    $sent_by_job = t('will be sent as %s', t('Automated job'));
                } else {
                    $sent_by_job = t('has been sent as %s', t('Automated job'));
                    if ($v['mails'][0]['sent'] == '0') {
                        $sent_by_job = t('has not been sent as %s', t('Automated job'));
                    }
                }
                $sent_until = true;
            } else {
                $sent_by_job = t('has been sent directly');
                $date = $v['mails'][0]['send_time'];
                $print_date = $dh->formatDateTime($date->getTimestamp());
                $sent_until = false;
            }
            if($from_job) $sent_until = false;
            $records .= self::printSentNewsletters($v['mails'], $sent_until);

        }
        $recs .= t('Newsletter \'%s\' from the %s %s', $handle, $print_date, $sent_by_job);
        $recs .= '<br>';
        $recs .= $records;
        if (PackageSetup::checkC5Version('8.2')) {
            $confirmMail = \Core::make('mail');
        } else {
            $confirmMail = new Mail\Service();
        }
        $confirmMail->setTesting(false);
        $confirmMail->from($args['owner_email']);
        $confirmMail->to($args['owner_email']);
        $confirmMail->addParameter('recs', $recs);
        $confirmMail->load('confirmation_template', PackageSetup::$pkgHandle);
        $confirmMail->sendMail();
    }

    /**
     *
     * Sets a list with recipients to whom a newsletter has been sent
     * For mail confirmation and newsletter_sent view
     *
     * @param array $recipients
     * @param bool $sent_until
     * @return string|void
     *
     */
    public static function printSentNewsletters($recipients, $sent_until = false)
    {
        $dh = \Core::make('helper/date');
        if ($recipients == NULL) return null;
        $i = 0;
        $str = '';
        $now = new \DateTime();
        $next = new \DateTime();
        foreach ($recipients as $v) {
            $sent_until_text = (!$sent_until) ? t('at') : t('until');
            $sent = ($v['sent'] == 0) ? t('Not sent yet. Will be sent') : t('Sent');
            $glyph = ($v['sent'] == 0) ? '' : '<i class="fa fa-check alert-success mailing"></i>';
            if ($now->getTimestamp() > $v['send_time']->getTimeStamp() && $v['sent'] == 0) {
                $sent = t('Has not been sent');
                $glyph = '<i class="fa fa-warning alert-danger mailing"></i>';
            }
            if ($next != $v['send_time']) {
                $str .= '<div style="margin-bottom: 10px"><h5 id="sent_not_sent_' . $v['id'] . '">' . $glyph . $sent . ' ' . $sent_until_text . ' ' . $dh->formatDateTime($v['send_time']->getTimestamp()) . '</h5></div>';
            }
            $str .= '<div style="padding-left: 35px">' . ($i + 1) . ': ' . $v['uEmail'] . '</div>';
            $next = $v['send_time'];
            $i++;
        }
        return $str;
    }

    /**
     *
     * Sets the used user-group
     *
     * @param string $key
     * @return mixed
     *
     */
    private function setUserGroup($key = 'Administrators')
    {
        $this->userGroup = Group::getByName($key);
        return $this->userGroup->getGroupMembers();
    }

	public static function generateBrowserKey($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/**
	 *
	 * Sets user groups allowed to use
	 *
	 * @param array $key Not like $key
	 * @return array
	 *
	 */

	private function setPossibleUserGroup($key = array('Guest'))
    {
        $db = \Core::make('database');
        $arr = array();
        $key_str = implode('" and gName not like "', $key);
        $query = 'select * from  Groups where gName not like "' . $key_str . '"';
        $res = $db->execute($query);
        while ($row = $res->fetchRow()) {
            $arr[$row['gID']] = $row['gName'];
        }
        return $arr;
    }

    /**
     * Saves a newsletter to DB DanielGasserComNewsLetterSend
     * @param array $args
     * @return array
     */
    private function saveNewsLetter(array $args)
    {
        $em = \ORM::entityManager();
        $ns = new ToessLabNewsLetterSend();
        $nsHandle = preg_replace('/[^A-Za-zöäüéàèÜÖËÄẽẼç0-9\-]/', '', $args['ns_handle']);
	    $ns->setNSHandle($nsHandle);
        $ns->setSentByJob($args['sent_by_job']);
        $ns->setStartSend($args['today']);
        if (is_array($args['saveFiles'])) {
            $ns->setAttachments(implode(',', $args['saveFiles']));
        } else {
            $ns->setAttachments('');
        }
        $ns->setNewsletter($args['newsletter']);
        $ns->setTemplateID($args['template']);
        $em->persist($ns);
        try {
            $em->flush();
        } catch (DBALException $e) {
            if ($e->getPrevious() && 0 === strpos($e->getPrevious()->getCode(), '23')) {
                return false;
            }
        }
        $i = 0;
        $last_id = $ns->getSendId();
	    $m = 0;
        foreach ($args['uEmail'] as $e) {
            $i++;
            if ($args['mails_per'] != Null) {
                if ($i % intval($args['mails_per']) == 0) {
                    $xdate = $args['today'];
                    $xdate->modify('+ 1 ' . $args['time_unit']);
                }
            }
            $this->saveNewsletterAdresses($last_id, $args, $m, $e, $ns);
            $m++;
        }
        return array('count_mails' => $i, 'last_send' => $last_id);
    }

    private function saveNewsletterAdresses($lastID, $args, $counter, $email, $ns)
    {
        $em = \ORM::entityManager();
        $ns_a = new ToessLabNewsLetterSendAddresses();
        $ns_a->setSendId($lastID);
        $ns_a->setSentError($args['sent_error']);
        $ns_a->setSent($args['sent']);
        $ns_a->setBrowserKey($args['browserKey'][$counter]);
        $ns_a->setUEmail($email);
        $ns_a->setSendMailings($ns);
        $ns_a->setSendTime($args['today']);
        $em->persist($ns_a);
        $em->flush();
        $em->clear();
    }
    /**
     * @param $content
     * @param $userEmail
     * @return mixed
     */
    public static function insertUserAttributes($content, $userEmail)
    {
        $akHandles = self::getUserAttributeKeys();
        $app = \Core::make('app');
	    $userInfo = $app->make('Concrete\Core\User\UserInfo')->getByEmail($userEmail);
	    $attributes = array();
	    if ($userInfo != null && is_object($userInfo)) {
            foreach($akHandles as $ah) {
                $attributes[$ah['akHandle']] = $userInfo->getAttribute($ah['akHandle']);
            }
        }
	    foreach ($attributes as $key => $ua) {
            if (is_array($ua)) {
                $ua_as_string = implode('<br>', $ua);
            }
            else if (is_object($ua)) {
                $ua_as_array = json_decode(json_encode($ua), true);
                $ua_as_string = implode('<br>', $ua_as_array);
            }else {
	            $ua_as_string = $ua;
            }
            $content = str_replace('{{' . $key . '}}', $ua_as_string, $content);
        }
        $content = str_replace('{{uEmail}}', $userEmail, $content);

        return $content;
    }

    /**
     * @param $tpl_title
     * @param $content
     * @return mixed
     */
    public static function insertTitleStyles($tpl_title, $content)
    {
        $h1 = '<h1 style="';
        $h1 .= 'color: ' . $tpl_title->getH1Color() . ';';
        $h1 .= 'font-weight: ' . $tpl_title->getH1Weight() . ';';
        $h1 .= 'font-family: "' . $tpl_title->getH1Font() . '";';
        $h1 .= 'font-size: ' . $tpl_title->getH1Size() . $tpl_title->getH1SizeUnit() . ';';
        $h1 .= 'padding: ' . $tpl_title->getH1PaddingTop() . $tpl_title->getH1PaddingTopUnit() . ' ';
        $h1 .= $tpl_title->getH1PaddingRight() . $tpl_title->getH1PaddingRightUnit() . ' ';
        $h1 .= $tpl_title->getH1PaddingBottom() . $tpl_title->getH1PaddingBottomUnit() . ' ';
        $h1 .= $tpl_title->getH1PaddingLeft() . $tpl_title->getH1PaddingLeftUnit() . '; ';
        $h1 .= 'margin: ' . $tpl_title->getH1MarginTop() . $tpl_title->getH1MarginTopUnit() . ' ';
        $h1 .= $tpl_title->getH1MarginRight() . $tpl_title->getH1MarginRightUnit() . ' ';
        $h1 .= $tpl_title->getH1MarginBottom() . $tpl_title->getH1MarginBottomUnit() . ' ';
        $h1 .= $tpl_title->getH1MarginLeft() . $tpl_title->getH1MarginLeftUnit() . ';';
        $h1 .= '">';

        $h2 = '<h2 style="';
        $h2 .= 'color: ' . $tpl_title->getH2Color() . ';';
        $h2 .= 'font-weight: ' . $tpl_title->getH2Weight() . ';';
        $h2 .= 'font-family: "' . $tpl_title->getH2Font() . '";';
        $h2 .= 'font-size: ' . $tpl_title->getH2Size() . $tpl_title->getH2SizeUnit() . ';';
        $h2 .= 'padding: ' . $tpl_title->getH2PaddingTop() . $tpl_title->getH2PaddingTopUnit() . ' ';
        $h2 .= $tpl_title->getH2PaddingRight() . $tpl_title->getH2PaddingRightUnit() . ' ';
        $h2 .= $tpl_title->getH2PaddingBottom() . $tpl_title->getH2PaddingBottomUnit() . ' ';
        $h2 .= $tpl_title->getH2PaddingLeft() . $tpl_title->getH2PaddingLeftUnit() . '; ';
        $h2 .= 'margin: ' . $tpl_title->getH2MarginTop() . $tpl_title->getH2MarginTopUnit() . ' ';
        $h2 .= $tpl_title->getH2MarginRight() . $tpl_title->getH2MarginRightUnit() . ' ';
        $h2 .= $tpl_title->getH2MarginBottom() . $tpl_title->getH2MarginBottomUnit() . ' ';
        $h2 .= $tpl_title->getH2MarginLeft() . $tpl_title->getH2MarginLeftUnit() . ';';
        $h2 .= '">';

        $h3 = '<h3 style="';
        $h3 .= 'color: ' . $tpl_title->getH3Color() . ';';
        $h3 .= 'font-weight: ' . $tpl_title->getH3Weight() . ';';
        $h3 .= 'font-family: "' . $tpl_title->getH3Font() . '";';
        $h3 .= 'font-size: ' . $tpl_title->getH3Size() . $tpl_title->getH3SizeUnit() . ';';
        $h3 .= 'padding: ' . $tpl_title->getH3PaddingTop() . $tpl_title->getH3PaddingTopUnit() . ' ';
        $h3 .= $tpl_title->getH3PaddingRight() . $tpl_title->getH3PaddingRightUnit() . ' ';
        $h3 .= $tpl_title->getH3PaddingBottom() . $tpl_title->getH3PaddingBottomUnit() . ' ';
        $h3 .= $tpl_title->getH3PaddingLeft() . $tpl_title->getH3PaddingLeftUnit() . '; ';
        $h3 .= 'margin: ' . $tpl_title->getH3MarginTop() . $tpl_title->getH3MarginTopUnit() . ' ';
        $h3 .= $tpl_title->getH3MarginRight() . $tpl_title->getH3MarginRightUnit() . ' ';
        $h3 .= $tpl_title->getH3MarginBottom() . $tpl_title->getH3MarginBottomUnit() . ' ';
        $h3 .= $tpl_title->getH3MarginLeft() . $tpl_title->getH3MarginLeftUnit() . ';';
        $h3 .= '">';

        $h4 = '<h4 style="';
        $h4 .= 'color: ' . $tpl_title->getH4Color() . ';';
        $h4 .= 'font-weight: ' . $tpl_title->getH4Weight() . ';';
        $h4 .= 'font-family: "' . $tpl_title->getH4Font() . '";';
        $h4 .= 'font-size: ' . $tpl_title->getH4Size() . $tpl_title->getH4SizeUnit() . ';';
        $h4 .= 'padding: ' . $tpl_title->getH4PaddingTop() . $tpl_title->getH4PaddingTopUnit() . ' ';
        $h4 .= $tpl_title->getH4PaddingRight() . $tpl_title->getH4PaddingRightUnit() . ' ';
        $h4 .= $tpl_title->getH4PaddingBottom() . $tpl_title->getH4PaddingBottomUnit() . ' ';
        $h4 .= $tpl_title->getH4PaddingLeft() . $tpl_title->getH4PaddingLeftUnit() . '; ';
        $h4 .= 'margin: ' . $tpl_title->getH4MarginTop() . $tpl_title->getH4MarginTopUnit() . ' ';
        $h4 .= $tpl_title->getH4MarginRight() . $tpl_title->getH4MarginRightUnit() . ' ';
        $h4 .= $tpl_title->getH4MarginBottom() . $tpl_title->getH4MarginBottomUnit() . ' ';
        $h4 .= $tpl_title->getH4MarginLeft() . $tpl_title->getH4MarginLeftUnit() . ';';
        $h4 .= '">';

        $h5 = '<h5 style="';
        $h5 .= 'color: ' . $tpl_title->getH5Color() . ';';
        $h5 .= 'font-weight: ' . $tpl_title->getH5Weight() . ';';
        $h5 .= 'font-family: "' . $tpl_title->getH5Font() . '";';
        $h5 .= 'font-size: ' . $tpl_title->getH5Size() . $tpl_title->getH5SizeUnit() . ';';
        $h5 .= 'padding: ' . $tpl_title->getH5PaddingTop() . $tpl_title->getH5PaddingTopUnit() . ' ';
        $h5 .= $tpl_title->getH5PaddingRight() . $tpl_title->getH5PaddingRightUnit() . ' ';
        $h5 .= $tpl_title->getH5PaddingBottom() . $tpl_title->getH5PaddingBottomUnit() . ' ';
        $h5 .= $tpl_title->getH5PaddingLeft() . $tpl_title->getH5PaddingLeftUnit() . '; ';
        $h5 .= 'margin: ' . $tpl_title->getH5MarginTop() . $tpl_title->getH5MarginTopUnit() . ' ';
        $h5 .= $tpl_title->getH5MarginRight() . $tpl_title->getH5MarginRightUnit() . ' ';
        $h5 .= $tpl_title->getH5MarginBottom() . $tpl_title->getH5MarginBottomUnit() . ' ';
        $h5 .= $tpl_title->getH5MarginLeft() . $tpl_title->getH5MarginLeftUnit() . ';';
        $h5 .= '">';

        $content = str_replace('<h1>', $h1, $content);
        $content = str_replace('<h2>', $h2, $content);
        $content = str_replace('<h3>', $h3, $content);
        $content = str_replace('<h4>', $h4, $content);
        $content = str_replace('<h5>', $h5, $content);
        return $content;
    }

    /**
     * See NewNewsletter::get_template()
     */
    public function get_newsletter()
    {
        return NewNewsletter::get_template();
    }

    /**
     * Gets the mailtemplateID for the newsletter (nl_id)
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get_template_id_for_newsletter()
    {
        $nl_id = \Request::getInstance()->get('nl_id');

        $entity_manager = \ORM::entityManager();
        $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $nl_id);

        echo \Core::make('helper/json')->encode(array('id' => $newsletter->getTemplate()->getMailTemplateID(), 'handle' => $newsletter->getTemplate()->getMailTemplateHandle()));
        exit;

    }

    /**
     * Gets attachments for the newsletter (nl_id)
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get_newsletter_attachments()
    {
        $nl_id = \Request::getInstance()->get('nl_id');

        $entity_manager = \ORM::entityManager();
        $newsletter = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $nl_id);

        $attachment_string = $newsletter->getAttachments();
        $str = '';
        if (strlen($attachment_string) == 0) {
            $str .= '<h4>' . t('No attachments') . '</h4>';
        } else {
            $attachments = explode(',', $attachment_string);
            $str .= '<h4>' . count($attachments) . ' ' .t('Attachments') . '</h4>';
            $str .= '<ul>';
            foreach($attachments as $f) {
                if (intval($f) > 0) {
                    $file = \File::getByID($f);
                    $version = $file->getRecentVersion();
                    $str .= '<li><a target="_blank" href="' . File\File::getRelativePathFromID($file->getFileID()) . '">' . $version->getFileName() . '</a></li>';
                }
            }
            $str .= '</ul>';
        }

        echo \Core::make('helper/json')->encode($str);
        exit;

    }

    /**
     * See NewTemplate::send_test_mail()
     */
    public function send_test_mail()
    {
        Settings::send_test_mail();
    }

    /**
     * Gets user attribute keys by handle & type (atID)
     *
     * @param string $cHandle
     * @param array $attrType
     * @param boolean $toesslab_only
     * @return array
     */
    public static function getUserAttributeKeys($cHandle = 'user', $attrType = array(1, 2, 4, 6, 9), $toesslab_only = false)
    {
        $db = \Core::make('database');
        $toesslab_attr = ($toesslab_only) ? 'and akHandle like "toesslab_%" ' : '';
        $query = 'select akHandle, akName
          from AttributeKeys
          join AttributeKeyCategories
          on AttributeKeys.akCategoryID = AttributeKeyCategories.akCategoryID
          where AttributeKeyCategories.akCategoryHandle = ? ' . $toesslab_attr . ' 
           and AttributeKeys.atID in (' . implode(',', $attrType) . ')';
        $res = $db->execute($query, array($cHandle));
        $i = 0;
        while ($row = $res->fetchRow()) {
            $arr[$i]['akHandle'] = $row['akHandle'];
            $arr[$i]['akName'] = $row['akName'];
            $i++;
        }
        return $arr;
    }
}
