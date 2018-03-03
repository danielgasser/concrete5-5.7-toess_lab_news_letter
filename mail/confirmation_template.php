<?php      defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/mail/confirmation_template.php
 */

$u = new \Concrete\Core\User\User();
$subject = t('Newsletter sent or saved');
$bodyHTML .= '<!DOCTYPE html>';
$bodyHTML .= '<html lang="de">';
$bodyHTML .= '	<head>';
$bodyHTML .= '		<meta charset="utf-8">';
$bodyHTML .= '	</head>';
$bodyHTML .= '	<body style="margin: 0; padding: 0">';
$bodyHTML .= '        <div style="width: 100%; height: 100px; margin: 0">';
$bodyHTML .= '    </div>';
$bodyHTML .= '		<div style="margin: 16px; padding-top: 1px; min-height: 300px">';
$bodyHTML .= $recs;
$bodyHTML .= '		</div>';
$bodyHTML .= '	</body>';
$bodyHTML .= '</html>';
