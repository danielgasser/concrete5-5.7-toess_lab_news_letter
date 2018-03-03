<?php       defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/templates/view.php
 */

$response = \Concrete\Core\Routing\Redirect::to('/dashboard/newsletter/templates/template_list');
$response->send();
exit;


