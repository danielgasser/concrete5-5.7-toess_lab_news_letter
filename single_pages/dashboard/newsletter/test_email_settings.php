<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/test_email_settings.php
 */
$form = \Core::make('helper/form');
$page = \Concrete\Core\Page\Page::getCurrentPage();
$test_email = \Config::get('toess_lab_news_letter.settings.mail_settings.test_email_address');
?>
<div class="clearfix">
    <form id="test_email_form" role="form" method="post" action="<?php       print $controller->action('test_mail')?>" class="form-horizontal">
        <fieldset>
            <?php       print Core::make('token')->output('test_mail_settings')?>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon"><?php       print t('Recipient') ?></span>
                            <?php      
                            print $form->email('test_mail', $test_email);
                            ?>
                            <span class="input-group-addon"><button type="submit" ><i
                                        class="fa fa-envelope-o"></i></button></span>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
