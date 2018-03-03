<?php
namespace Concrete\Package\ToessLabNewsLetter\Block\ToessLabNewsLetterUnSubscribeBlock;

defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Core\Block\BlockController;
use Concrete\Package\ToessLabNewsLetter\Subscription\Subscription;
use Loader;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 450;
    protected $btTable = 'btToessLabNewsLetterUnSubscribeBlock';
    protected $subscription;

    public function getBlockTypeDescription()
    {
        return t("Displays the Un/Subscribe form for the toesslab - Newsletter Add-On.");
    }

    public function getBlockTypeName()
    {
        return t("Un/Subscribe from toesslab - Newsletter");
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
    }

    public function view()
    {
    }

    public function save($data)
    {
        parent::save($data);
    }

    public function validate($args)
    {
        $e = \Core::make('helper/validation/error');
        if (!$args['title']) {
            $e->add(t('You must specify a title.'));
        }
        if (!$args['button_unsub']) {
            $e->add(t('You must specify an unsubscribe text.'));
        }
        if (!$args['button_sub']) {
            $e->add(t('You must specify an subscribe text.'));
        }
        if (!$args['email_label']) {
            $e->add(t('You must specify an email label.'));
        }
        if (!$args['text_unsub']) {
            $e->add(t('You must specify an Unsubscription Text.'));
        }
        if (!$args['text_sub']) {
            $e->add(t('You must specify a Subscription Text.'));
        }
        if (!$args['text_no_member']) {
            $e->add(t('You must specify an Email Not Found Text.'));
        }

        return $e;
    }

    public function action_unsub($bID = false)
    {
        if ($this->bID != $bID) {
            return false;
        }
        $success = '';
        $token = \Core::make('token');
        if (!$token->validate('unsub')) {
            throw new Exception(t('Invalid Request'));
        }
        $app = \Core::make('app');
        $email = \Request::getInstance()->get('email');
        $sub = (\Request::getInstance()->get('subscribe') == null);
        $userInfo = $app->make('Concrete\Core\User\UserInfo');
        $user = $userInfo->getByEmail($email);
        if (is_object($user)) {
            $this->subscription->setSubscription($user, $sub);
            $success = ($sub) ? $this->text_sub : $this->text_unsub;
        } else {
            $error = $this->text_no_member;
        }

        $this->set('success', $success);
        $this->set('error', $error);
    }

    public function on_start()
    {
        parent::on_start();
        $this->subscription = new Subscription();
    }
}
