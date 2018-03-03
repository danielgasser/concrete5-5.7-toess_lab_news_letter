<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controllers/single_page/dashboard/newsletter/test_email_settings.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Controller\SinglePage\Dashboard\Newsletter;

use \Concrete\Core\Page\Controller\DashboardPageController;

class TestEmailSettings extends DashboardPageController {


    public function view()
    {
        $this->set('owner_email', \Config::get('toess_lab_news_letter.settings.owner_email'));
    }

    /**
     * test email settings
     */
    public function test_mail()
    {
        $session = \Core::make('session');
        if (!Settings::checkEmailSettings()) {
            $this->error->add(t('An error was found while sending a test email. Please adapt the global email settings at <a href="%s">%s</a> before sending newsletters.', \URL::to('/dashboard/system/mail/method'), t('Mail settings')));
        }
        if (!$this->token->validate('test_mail_settings')) {
            $this->error->add($this->token->getErrorMessage());
        }
        if(strlen($session->get('email_settings')) != NULL) {
            $this->error->add($session->get('email_settings'));
        }
        if($this->error->has()) return;
        $this->set('message', t('The email settings are fine.'));
    }

    public function on_start()
    {
        $this->requireAsset('toesslab');
        parent::on_start();
    }
}
