<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Help/HelpServiceProvider.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Help;

use Concrete\Core\Foundation\Service\Provider;

class HelpServiceProvider extends Provider {

    public function register()
    {
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter',
            t('Newsletters') . '<ul>' .
                '<li>' . t('Adapt the <a href="%s">Settings</a> to your needs.', \URL::to('/dashboard/newsletter/settings')) . '</li>' .
                '<li>' . t('Design at least one <a href="%s">Template</a> to send your newsletters with.', \URL::to('/dashboard/newsletter/templates/new_template')) . '</li>' .
                '<li>' . t('Create your <a href="%s">Newsletters</a> and link them to your template(s).', \URL::to('/dashboard/newsletter/newsletters/new_newsletter')) . '</li>' .
                '<li>' . t('Test the <a href="%s">Email Settings</a>.', \URL::to('/dashboard/newsletter/test_email_settings')) . '</li>' .
                '<li>' . t('Finally, send your <a href="%s">Newsletter</a> to registered users or any usergroup.', \URL::to('/dashboard/newsletter/send_newsletter')) . '</li>'
                . '</ul>'
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/settings',
            array(t('Newsletters settings:') .
            '<br>' . t('If your provider dictates a limit, please send over "%s".', t('Automated Jobs')) .
            '<br>' . t('The option "%s" only suits for small amount of recipients.',t('Send directly')) .
            '<br>' . t('Ask your provider or webmaster for further information and help.'), 'add-widget')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/send_newsletter',
            array(t('Send Newsletter'), 'add-widget')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/templates/template_list',
            array(t('Edit an existing Template layout or create a new one.'), 'add-widget')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/newsletters/newsletter_list',
            array(t('Edit an existing Newsletter or create a new one.'), 'add-widget')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/templates/new_template',
            t('Design the Template layout.') .
            '<br>' . t('The Template Name or handle is used to identify this Template') .
            '<br>' . t('On the Header Tab you may define a header logo (Optional).<br>Define background color, height, padding and margin of the Header, Body & Footer.<br> Define the font, its size, color, padding & margin.') .
            '<br>' . t('You may use two CSS units (Pixels or percent). See the Styling Help Dialog for more information. If a unit isn\'t recognised, the chosen value won\'t be taken. Default value will be taken instead.')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/newsletters/new_newsletter',
            t('Create your Newsletters here.') .
            '<br>' . t('The Newsletter Name or handle is used to identify this Newsletter') .
            '<br>' . t('Set the Subject of this Newsletter') .
            '<br>' . t('Choose a template to use') .
            '<br>' . t('Write header, body & footer texts.') .
            '<br>' . t('Add files as attachments.')
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/newsletter_sent',
            t('See all Newsletters already saved and sent or not yet sent. (Depending if sending by "%s" or directly)', t('Automated Jobs'))
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/test_email_settings',
            t('Test the global email settings. Click on \'Test Email Settings\'. The settings for sending emails may be found <a href="%s">here</a>.', \URL::to('/dashboard/system/mail/method'))
        );
        $this->app['help/dashboard']->registerMessageString('/dashboard/newsletter/subscriptions',
            t('You may un/subscribe email addresses by entering them either into \'email addresses list\' or selecting them in the un/subscripted list.')
        );
    }
}
