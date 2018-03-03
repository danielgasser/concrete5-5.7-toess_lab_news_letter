<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/settings.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;

use Concrete\Core\Mail\Service;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Job\Job;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup as PackageSetup;
use Punic\Exception;
use Concrete\Core\File;

class Settings extends DashboardPageController {


    /**
     * @var array time units defined in on_start method
     */
    public $time_units = array();

    /**
     * @var string attachment field name defined in on_start method
     */
    public $field_name_attachment = '';

    /**
     * @var string mails per unit field defined in on_start method
     */
    public $field_name_mails_per_unit = '';

    /**
     * @var string owner email field defined in on_start method
     */
    public $field_name_owner_email = '';

    /**
     * @var string header logo field defined in on_start method
     */
    public $field_name_logo = '';

    /**
     * @var int
     */
    static $min_files = 0;

    /**
     * @var int
     */
    static $max_files = 10;

    /**
     * @var int
     */
    static $min_mails_per_unit = 1;

    public function view()
    {
        if (!Settings::checkEmailSettings()) {
            $this->error->add(t('Please adapt the Settings before sending Newsletter.'));
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
        $row = \Config::get('toess_lab_news_letter.settings');
        $this->set('files_num', $row['files_num']);
        $this->set('file_size', floatval($row['file_size']) / 1024 / 1024);
        $this->set('file_total_size', floatval($row['file_total_size']) / 1024 / 1024);
        $this->set('per_job', $row['per_job']);
        $this->set('mails_per', $row['mails_per']);
        $this->set('time_unit', $row['time_unit']);
        $this->set('max_number', $this->getMaxPerUnit($row['time_unit']));
        $this->set('owner_email', $row['owner_email']);
	    $this->set('browser_link_text', $row['browser_link_text']);
	    $this->set('report_newsletter', $row['report_newsletter']);
        $this->set('job_path', $this->setCronjobPath($row['time_unit']));
        $this->set('time_units', $this->time_units);
    }

    /**
     * Saves to DB table btDanielGasserComNewsLetterSettings
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function save_settings()
    {
        $session = \Core::make('session');

        $files_num = \Core::make('helper/security')->sanitizeInt($this->post('files_num'));
        // There is no sanitizeFloat in /concrete/src/Validation/SanitizeService.php
        $file_size = filter_var($this->post('file_size'), FILTER_VALIDATE_FLOAT);
        $per_job = (\Core::make('helper/security')->sanitizeInt($this->post('per_job')) == 1) ? true : false;
        $mails_per = \Core::make('helper/security')->sanitizeInt($this->post('mails_per'));
        $time_unit = \Core::make('helper/security')->sanitizeString($this->post('time_unit'));
        $owner_email = \Core::make('helper/security')->sanitizeEmail($this->post('owner_email'));
        $report_newsletter = (\Core::make('helper/security')->sanitizeInt($this->post('report_newsletter')) == NULL) ? false : true;
	    $browser_link_text = \Core::make('helper/security')->sanitizeString($this->post('browser_link_text'));
	    if (!$this->token->validate('save_settings')) {
            $this->set('error', array($this->token->getErrorMessage()));
        }
        if ($owner_email == NULL && $this->post('report_newsletter') == '1' || !filter_var($owner_email, FILTER_VALIDATE_EMAIL)) {
            $this->error->add(t('The field "%s" is required and must be a valid email address.', $this->field_name_owner_email));
        }
        if (strlen($browser_link_text) <= 3) {
            $this->error->add(t('The field "%s" is required and must be greater or equal to %s characters length.', $this->field_browser_link_text, 3));
        }
        if (intval($files_num) < self::$min_files) {
            $this->error->add(t('The field "%s" must be greater or equal to %s', $this->field_name_attachment, $this->min_files));
        }
        if (intval($files_num) > self::$max_files) {
            $this->error->add(t('The field "%s" must be smaller or equal to %s', $this->field_name_attachment, $this->max_files));
        }
        if (intval($file_size) < 0) {
            $this->error->add(t('The field "%s" must be greater than 0', $this->field_name_attachment));
        }
        if (intval($mails_per) < self::$min_mails_per_unit && $per_job) {
            $this->error->add(t('The field "%s" must be greater than %s', $this->field_name_mails_per_unit, '0'));
        }
        $this->set('time_units', $this->time_units);
        $this->set('files_num', $files_num);
        $this->set('file_total_size', \Config::get('toess_lab_news_letter.settings.file_total_size') / 1024 / 1024);
        $this->set('file_size', $file_size);
        $this->set('per_job', $per_job);
        $this->set('mails_per', $mails_per);
        $this->set('time_unit', $time_unit);
        $this->set('max_number', $this->getMaxPerUnit($time_unit));
        $this->set('owner_email', $owner_email);
        $this->set('browser_link_text', $browser_link_text);
        $this->set('report_newsletter', $report_newsletter);
        if($this->error->has()) return;

        \Config::save('toess_lab_news_letter.settings.files_num', intval($files_num));
        \Config::save('toess_lab_news_letter.settings.file_size', floatval($file_size) * 1024 * 1024);
        \Config::save('toess_lab_news_letter.settings.file_total_size', floatval($file_size * 1024 * 1024) * $files_num);
        \Config::save('toess_lab_news_letter.settings.per_job', $per_job);
        \Config::save('toess_lab_news_letter.settings.mails_per', $mails_per);
        \Config::save('toess_lab_news_letter.settings.time_unit', $time_unit);
        \Config::save('toess_lab_news_letter.settings.owner_email', $owner_email);
        \Config::save('toess_lab_news_letter.settings.browser_link_text', $browser_link_text);
        \Config::save('toess_lab_news_letter.settings.report_newsletter', $report_newsletter);
        \Config::save('toess_lab_news_letter.settings.settings_made', true);


        $this->set('job_path', $this->setCronjobPath($time_unit));
        if (\Config::get('toess_lab_news_letter.settings.per_job')) {
            $session->set('dont_forget_job', t('Don\'t forget to install your cron job!') . '<br>' . t('Ask your provider or webmaster for further information and help. You may hand the below command to your provider or webmaster:<br><pre>%s</pre>', $this->setCronjobPath($time_unit)));
        }
        if(!Settings::checkFirstTimeTemplate()) {
            $this->error->add(t('Don\'t forget to design a Template before sending Newsletter.'));
        }
        if(!Settings::checkFirstTimeNewsletter()) {
            $this->error->add(t('Don\'t forget to create a Newsletter before sending Newsletter.'));
            $this->set('error', $this->error);
        }
        $this->set('message', t('Settings have been saved'));
    }

    /**
     * Changes the time settings of the cron job command string
     */
    public function change_cron_job_command()
    {
        echo \Core::make('helper/json')->encode(array('path' => $this->setCronjobPath($this->get('unit')), 'max_number' => $this->getMaxPerUnit($this->get('unit'))));
        exit;
    }

    /**
     * Gets the min/max values from btDanielGasserComNewsLetterConstants
     *
     * @return array
     */
    public static function getMinMaxValues()
    {
        return array(
            'min_files' => \Config::get('toess_lab_news_letter.constants.min_files'),
            'max_files' => \Config::get('toess_lab_news_letter.constants.max_files'),
            'min_mails_per_unit' => \Config::get('toess_lab_news_letter.constants.mails_per')
        );
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        $this->requireAsset('bootstrapswitch');
        $this->requireAsset('javascript', 'toesslab_tourist_settings');
        $this->time_units = array(
            'Minute' => t('Minute'),
            'Hour' => t('Hour'),
            'Day' => t('Day'),
            'Month' => t('Month'),
        );
        $this->field_name_attachment = t('Maximum number of attachments to be sent with the Newsletter');
        $this->field_name_mails_per_unit = t('Number of emails per %s', implode('/', $this->time_units));
        $this->field_name_owner_email = t('Owner email');
        $this->field_browser_link_text = t('See Newsletter in Browser text');
        parent::on_start();
    }

    /**
     * Checks if site wide mail settings have been set correctly
     *
     * @return mixed
     */
    public static function checkEmailSettings ()
    {
        if(\Config::get('toess_lab_news_letter.settings.email_settings_made')) {
            return true;
        }
        $session = \Core::make('session');
        $db = \Core::make('database');
        $s = new \DateTime();

        $start = $s->getTimestamp();
        $e = new \DateTime();
        $e->sub(new \DateInterval('PT10S'));
        $s->sub(new \DateInterval('PT2S'));
        $end = $e->getTimestamp();
        $mail = \Core::make('helper/mail');
        $mail->setTesting(false);
        $mail->setSubject(t('toesslab-Newsletter: Test message from %s', \Config::get('concrete.site')));
        $mail->to(\Config::get('toess_lab_news_letter.settings.owner_email'));
        $body = t('This is a test message from the toesslab-Newsletter addon from the site: %s', \Config::get('concrete.site'));
        $body .= "\n\n" . t('Configuration:');
        $body .= "\n- " . t('Send mail method: %s', \Config::get('concrete.mail.method'));
        switch(\Config::get('concrete.mail.method')) {
            case 'smtp':
                $body .= "\n- " . t('SMTP Server: %s', \Config::get('concrete.mail.methods.smtp.server'));
                $body .= "\n- " . t('SMTP Port: %s', \Config::get('concrete.mail.methods.smtp.port', tc('SMTP Port', 'default')));
                $body .= "\n- " . t('SMTP Encryption: %s', \Config::get('concrete.mail.methods.smtp.encryption', tc('SMTP Encryption', 'none')));
                if(!\Config::get('concrete.mail.methods.smtp.username')) {
                    $body .= "\n- " . t('SMTP Authentication: none');
                }
                else {
                    $body .= "\n- " . t('SMTP Username: %s', \Config::get('concrete.mail.methods.smtp.username'));
                    $body .= "\n- " . t('SMTP Password: %s', tc('Password', '<hidden>'));
                }
                break;
        }
        $mail->setBody($body);
        $mail->sendMail();
        $query = $db->execute('SELECT time, message FROM Logs where message like "%ToessLabNewsLetter%" and message like "%zend-mail%" and time between ? and ?  order by time desc;', array($end, $start));
        $row = $query->fetchRow();
        if (strlen($row['message']) > 0) {
            $session->set('email_settings', $row['message']);
            \Config::save('toess_lab_news_letter.settings.email_settings_made', false);
            return false;
        }
        $session->remove('email_settings', NULL);
        \Config::save('toess_lab_news_letter.settings.email_settings_made', true);
        return true;
    }

    /**
     * Checks if settings have been made
     *
     * @return bool
     */
    public static function checkFirstTimeSettings()
    {
        if(\Config::get('toess_lab_news_letter.settings.settings_made')) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a template has been designed/edited
     *
     * @return bool
     */
    public static function checkFirstTimeTemplate()
    {
        $entity_manager = \ORM::entityManager();
        $mail_template = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate')->findAll();
        foreach($mail_template as $mt){
            if($mt->getTemplateDesigned() == '1') {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if a newsletter has been created/edited
     *
     * @return bool
     */
    public static function checkFirstTimeNewsletter()
    {
        $entity_manager = \ORM::entityManager();

        $mail_template = $entity_manager->getRepository('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter')->findAll();
        foreach($mail_template as $mt){
            if($mt->getModified() == '1') {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function checkUserAmount()
    {
        $db = \Core::make('database');
        $users = $db->getOne('select count(*) as user_count from Users');
        return (intval($users) > 1);
    }

    /**
     * Changes the time settings for the cron command display
     *
     * @param string $unit
     * @return string
     *
     */
    private function setCrontabParams ($unit = 'Minute')
    {
        switch($unit) {
            case 'Minute':
                return '* * * * *';
            case 'Hour':
                return '0 * * * *';
            case 'Day':
                return '0 0 * * *';
            case 'Month':
                return '0 0 1 * *';
        }
        return '* * * * *';
    }

    /**
     * @param $unit
     * @return bool
     */
    private function getMaxPerUnit($unit)
    {
        switch($unit) {
            case 'Minute':
                return $max = \Config::get('toess_lab_news_letter.constants.max_number_per_minute');
            case 'Hour':
                return $max = \Config::get('toess_lab_news_letter.constants.max_number_per_hour');
            case 'Day':
                return $max = \Config::get('toess_lab_news_letter.constants.max_number_per_day');
            case 'Month':
                return $max = \Config::get('toess_lab_news_letter.constants.max_number_per_month');

        }
        return false;
    }

    /**
     * Sets the crontab command line
     *
     * @param string $time_unit
     * @return string
     *
     */
    private function setCronjobPath ($time_unit = 'Minute')
    {
        $j = Job::getByHandle('send_mailing_as_job');
        $auth = Job::generateAuth();
        $jobString = t('The single Job:<br>');
        $jobString .= $this->setCrontabParams($time_unit) .' /usr/bin/wget '  .' \'' . \URL::to('/ccm/system/jobs/run_single?auth=' . $auth . '&jID=' . $j->jID) . '\'';
        $jobString .= t('<hr>The Queueable Job running every five minutes:<br>');
        $jobString .= '*/5 * * * *' .' /usr/bin/wget '  .' \'' . \URL::to('/ccm/system/jobs/check_queue?auth=' . $auth) . '\'';
        return $jobString;
    }

    /**
     * Sends a test mail with selected template (tpl)/newsletter (nl)
     *
     * @throws \Exception
     */
    public static function send_test_mail ()
    {
        $tpl_id = self::post('tpl');
        $nl_id = self::post('nl');
        $email = self::post('email_address');
        $path = self::post('path');
        \Config::save('toess_lab_news_letter.settings.' . $path . '.test_email_address', $email);
        if($tpl_id == NULL || $tpl_id == '' || $nl_id == NULL){
            echo \Core::make('helper/json')->encode(array('failed' => t('Please choose a Template/Newsletter first.', $email)));
            exit;
        }
        if(!isset($tpl_id)){
            $tpl_id = 1;
        }
        if(!isset($nl_id)){
            $nl_id = 1;
        }
        $entity_manager = \ORM::entityManager();
        $nl = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetter', $nl_id);
        $nt = $entity_manager->find('\Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate', $tpl_id);
        $mail_template_head =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateHead')->findOneBy(array('tplID' => $tpl_id));
        $mail_template_body =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateBody')->findOneBy(array('tplID' => $tpl_id));
        $mail_template_foot =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateFoot')->findOneBy(array('tplID' => $tpl_id));
        $mail_template_title =  $entity_manager->getRepository('Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplateTitle')->findOneBy(array('tplID' => $tpl_id));
        $text = stream_get_contents($nl->getMailText());
        $footer = stream_get_contents($nl->getMailTextFooter());
        $header = stream_get_contents($nl->getHeaderText());
        $subject = $nl->getNLSubject();
        $browserKey = SendMailing::generateBrowserKey();
        $href = BASE_URL . '/index.php/newsletter_in_browser?key=' . $browserKey . '&newsletter=' . $nl_id . '&template=' . $tpl_id . '&uEmail=' . $email . '&preview=1';

        if (PackageSetup::checkC5Version()) {
            $mail = \Core::make('mail');
        } else {
            $mail = new Service();
        }
        $mail->addParameter('inBrowser', false);
        $mail->addParameter('browserKey', $href);
        $mail->addParameter('text', $text);
        $mail->addParameter('footer', $footer);
        $mail->addParameter('header', $header);
        $mail->addParameter('subject', $subject);
        $mail->addParameter('tpl', $nt);
        $mail->addParameter('tpl_head', $mail_template_head);
        $mail->addParameter('tpl_body', $mail_template_body);
        $mail->addParameter('tpl_foot', $mail_template_foot);
        $mail->addParameter('tpl_title', $mail_template_title);
        $mail->load('newsletter_template', PackageSetup::$pkgHandle);
        $attachments = explode(',', $nl->getAttachments());
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
            echo \Core::make('helper/json')->encode(array('failed' => t('The size of attachments is bigger than the allowed maximum of %s Bytes', \Config::get('toess_lab_news_letter.settings.file_total_size'))));
            exit;
        }
        if (is_array($attachFiles) && sizeof($attachFiles) > 0) {
            foreach ($attachFiles as $sf) {
                $mail->addAttachment($sf);
            }
        }
        $mail->to($email);
        try {
            $mail->sendMail();
        } catch (Exception $e) {
            echo \Core::make('helper/json')->encode(array('failed' => $e->getMessage()));
            exit;
        }
        echo \Core::make('helper/json')->encode(array('success' => t('The test email has been sent to %s', $email)));
        exit;
    }
}
