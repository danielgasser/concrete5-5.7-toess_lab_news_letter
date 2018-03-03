<?php defined('C5_EXECUTE') or die("Access Denied.");

$title_text = ($title) ? $title : t('Unsubscribe here');
$email_label_text = ($email_label) ? $email_label : t('email');
$unsubscribe_text = ($unsubscribe) ? $unsubscribe : t('Unsubscribe');
$subscribe_text = ($subscribe) ? $subscribe : t('Subscribe');
$unsubscription_text = ($unsubscription) ? $unsubscription : t('You have been successfully unsubscripted');
$subscription_text = ($subscription) ? $subscription : t('You have been successfully subscripted');
$no_member_text = ($text_no_member) ? $text_no_member : t('No member with this email address found');

?>

<fieldset>
    <legend><?php echo t('Title & Labels')?></legend>
    <div class="form-group">
        <label class="control-label" for="title"><?php echo t('Title')?></label>
        <input class="form-control" name="title" id="title" value="<?php echo $title_text?>" />
    </div>
    <div class="form-group">
        <label class="control-label" for="email_label"><?php echo t('Email field label')?></label>
        <input class="form-control" name="email_label" id="email_label" value="<?php echo $email_label_text?>" />
    </div>
</fieldset>
<fieldset>
    <legend><?php echo t('Button Texts')?></legend>
    <div class="form-group">
        <label class="control-label" for="button_unsub"><?php echo t('Unsubscribe')?></label>
        <input class="form-control" name="button_unsub" id="button_unsub" value="<?php echo $unsubscribe_text?>" />
    </div>
    <div class="form-group">
        <label class="control-label" for="button_sub"><?php echo t('Subscribe')?></label>
        <input class="form-control" name="button_sub" id="button_sub" value="<?php echo $subscribe_text?>" />
    </div>
</fieldset>
<fieldset>
    <legend><?php echo t('Success Texts')?></legend>
    <div class="form-group">
        <label class="control-label" for="text_unsub"><?php echo t('Unsubscription Text')?></label>
        <input class="form-control" name="text_unsub" id="text_unsub" value="<?php echo $unsubscription_text?>" />
    </div>
    <div class="form-group">
        <label class="control-label" for="text_sub"><?php echo t('Subscription Text')?></label>
        <input class="form-control" name="text_sub" id="text_sub" value="<?php echo $subscription_text?>" />
    </div>
    <div class="form-group">
        <label class="control-label" for="text_no_member"><?php echo t('Email Not Found Text')?></label>
        <input class="form-control" name="text_no_member" id="text_no_member" value="<?php echo $no_member_text?>" />
    </div>
</fieldset>
