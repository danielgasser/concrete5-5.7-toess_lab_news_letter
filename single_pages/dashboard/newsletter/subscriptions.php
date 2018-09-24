<?php
defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/single_pages/dashboard/newsletter/settings.php
 */

$form = \Core::make('helper/form');
?>
<script>
    var url = '<?php echo $controller->action('set_subscription')?>',
        dialog_msg_title_error = '<?php       print t('Warning') ?>',
        dialog_msg_title_success = '<?php       print t('Success') ?>',
        dialog_msg_text_success = '<?php       print t('emails have been') ?>',
        un_sub_text = '<?php       print t('unsubscripted') ?>',
        sub_text = '<?php       print t('subscripted') ?>',
        empty_text = '<?php       print t('Please enter at least one email address.') ?>';

</script>
<div id="dialog_msg" style="display: none"></div>

<div class="clearfix container">
    <form id="subscription_form" role="form" method="post" action="<?php echo $controller->action('set_subscription')?>" class="form-horizontal">
        <fieldset>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="mailFilter"><?php echo t('Enter mail addresses separated by a new line') ?></label>
                        <?php
                        echo $form->textarea('mailFilter', null, array('rows' => '10', 'cols' => '2', 'placeholder' => t('email addresses list')));
                        ?>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <button id="un_sub_members" class="btn btn-danger"><?php echo t('Unsubscribe')?></button>
                    <button id="sub_members" class="btn btn-success"><?php echo t('Subscribe')?></button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <h4><?php echo t('Subscripted') ?>: <span id="sub_fillListLength"><?php echo count($subscriptions['sub']) ?></span></h4>
                    <div>
                        <button id="sub_select_move" disabled="disabled" class="btn btn-danger"><?php echo t('Move selected to \'unsubscripted\'')?>&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                        <h5><?php echo t('selected') ?>: <span id="sub_selected">0</span></h5>
                        <input type="text" class="form-control" id="search_sub" placeholder="<?php echo t('Search Subscripted')?>">
                        <hr>
                        <button id="sub_select_unselect" disabled="disabled" class="btn btn-default"><?php echo t('Unselect selected')?></button>
                        <button id="sub_select_select" class="btn btn-default"><?php echo t('Select all')?></button>
                        <ol id="sub_select" class="connected">
                            <?php
                            if (count($subscriptions['sub']) > 0) {
                                foreach ($subscriptions['sub'] as $s) {
                                    ?>
                                    <li id="<?php echo $s['uID'] ?>"><?php
                                        print $s['uEmail'];
                                        ?>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ol>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <h4><?php echo t('Unsubscripted') ?>: <span id="un_sub_fillListLength"><?php echo sizeof($subscriptions['un_sub']) ?></span></h4>
                    <div>
                        <button id="un_sub_select_move" disabled="disabled" class="btn btn-success"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;<?php echo t('Move selected to \'subscripted\'')?></button>
                        <h5><?php echo t('selected') ?>: <span id="un_sub_selected">0</span></h5>
                        <input type="text" class="form-control" id="search_un_sub" placeholder="<?php echo t('Search Unsubscripted')?>">
                        <hr>
                        <button id="un_sub_select_unselect" disabled="disabled" class="btn btn-default"><?php echo t('Unselect selected')?></button>
                        <ol id="un_sub_select" class="connected">
                        <?php
                        if (sizeof($subscriptions['un_sub']) > 0) {
                            foreach ($subscriptions['un_sub'] as $s) {
                                ?>
                                <li id="<?php echo $s['uID'] ?>"><?php
                                    print $s['uEmail'];
                                    ?>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        </ol>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
