<?php  defined('C5_EXECUTE') or die("Access Denied.");
$form = \Core::make('helper/form');
$email_text = (isset($_GET['email'])) ? $_GET['email'] : $email;
?>

<div id="container">
    <?php  if ($success): ?>

        <div class="alert alert-success">
            <?php  echo $success; ?>
        </div>

    <?php  endif; ?>
    <?php  if ($error): ?>

        <div class="alert alert-danger">
            <?php  echo $error; ?>
        </div>

    <?php  endif; ?>
    <h3><?php echo $title ?></h3>
    <form id="unsubForm" method="post" action="<?php       print $view->action('unsub')?>" class="form-horizontal">
        <?php echo Core::make('token')->output('unsub');?>
        <fieldset>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">

                        <?php
                        print $form->label('email', $email_label);
                        print $form->text('email', $email_text, array('placeholder' => $email_label, 'style' => 'width: 100%'));
                        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">

                        <?php
                        print $form->submit('subscribe' , $button_unsub);
                        ?>
                        <?php
                        print $form->submit('unsubscribe' , $button_sub);
                        ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>

