<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/controller.php
 */

namespace Concrete\Package\ToessLabNewsLetter;
defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Core\Application\Application;
use Concrete\Core\Asset;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Core\Attribute\Set;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Cache\Cache;
use Concrete\Core\Editor\Plugin;
use \Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\File\File;
use Concrete\Core\File\Importer;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Core\Job\Job as Job;
use Concrete\Core\Support\Facade\Events;
use Concrete\Core\User\UserInfo;
use Concrete\Package\ToessLabNewsLetter\Help\HelpServiceProvider;
use Concrete\Package\ToessLabNewsLetter\Entity\ToessLabNewsLetterMailTemplate;
use Concrete\Package\ToessLabNewsLetter\Setup\PackageSetup;
use Concrete\Package\ToessLabNewsLetter\Subscription\Subscription;
use UserAttributeKey;



class Controller extends Package
{

    /**
     * @var string
     */
    protected $pkgHandle = 'toess_lab_news_letter';
    protected $appVersionRequired = '5.7.5.13';
    protected $pkgVersion = '2.2.5';
    protected $pkgAutoloaderMapCoreExtensions = true;

    protected $headImage = 'toesslab_logo_270height.png';

    protected $headImageID;
    protected $subscription;

    protected $templateGeneral = array(
        array(
            'templateHandle' => 'Template Elemental'
        ),
        array(
            'templateHandle' => 'Template toesslab'
        )
    );


    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function getPackageDescription()
    {
        return t('Send Newsletters to registered users or any user group.');
    }

    public function getPackageName()
    {
        return t('toesslab - Newsletter');
    }

    public function getPackageHandle()
    {
        return $this->getPkg();
    }

	public function install()
    {
        $this->subscription = new Subscription();
        $pkg = parent::install();
        $this->installOrUpgrade($pkg);
        $headImg = null;

        $entity_manager = Package::getByHandle($this->getPackageHandle())->getEntityManager();

        $fi = new Importer();
        $fv = $fi->import(DIR_BASE . '/' . DIRNAME_PACKAGES . '/' . $this->getPackageHandle() . '/img/' . $this->headImage, 'Template_toesslab_head_image.png');
        $headImg = $fv->getFileID();

        PackageSetup::setConfig($headImg);
        PackageSetup::setUserAttribute($pkg);
        PackageSetup::getGoogleFonts();
        BlockType::installBlockType('toess_lab_news_letter_un_subscribe_block', $pkg);
        $this->subscription->setFirstTimeSubscriptions();
        $i = 0;
        foreach($this->templateGeneral as $tpl) {
            $mail_template_{$i} = $this->setTemplate($tpl);
            $entity_manager->persist($mail_template_{$i});
            $entity_manager->flush();
            $mail_template_head = PackageSetup::setTemplateHead($mail_template_{$i}->getMailTemplateId(), $i, $headImg);
            $entity_manager->persist($mail_template_head);

            $mail_template_body = PackageSetup::setTemplateBody($mail_template_{$i}->getMailTemplateId(), $i);
            $entity_manager->persist($mail_template_body);

            $mail_template_foot = PackageSetup::setTemplateFoot($mail_template_{$i}->getMailTemplateId(), $i);
            $entity_manager->persist($mail_template_foot);

            $mail_template_title = PackageSetup::setTemplateTitle($mail_template_{$i}->getMailTemplateId(), $i);
            $entity_manager->persist($mail_template_title);
            $newsletter = PackageSetup::setNewsletter($mail_template_{$i}, $i);
            $entity_manager->flush();

            $entity_manager->persist($newsletter);

            $entity_manager->flush();
            $i++;
        }

        if(PackageSetup::checkC5Version('5.7.5.13', '==')){
            $cms = \Core::make('app');
            $cms->clearCaches();
        } else {
            $this->app->clearCaches();
        }
    }

    public function uninstall()
    {
        $drop_query = 'drop table if exists ToessLabNewsLetter,
            ToessLabNewsLetterSend,
            ToessLabNewsLetterSendAddresses,
            ToessLabNewsLetterMailTemplate,
            ToessLabNewsLetterMailTemplateBody,
            ToessLabNewsLetterMailTemplateFoot,
            ToessLabNewsLetterMailTemplateHead,
            ToessLabNewsLetterMailTemplateTitle,
            ToessLabNewsLetterSubscription';
        $stmt = Package::getByHandle($this->getPackageHandle())->getEntityManager()
            ->getConnection();
        $stmt->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        $stmt->executeQuery($drop_query);
        $job = Job::getByHandle('send_mailing_as_job');
        if(is_object($job)){
            $job->uninstall();
        }
        $fID = \Config::get('toess_lab_news_letter.constants.head_image');
        if(intval($fID) > 0) {
            $image = File::getByID($fID);
            if(is_object($image)){
                $image->delete();
            }
        }
        $fs = new \Illuminate\Filesystem\Filesystem();
        $fs->delete(DIR_CONFIG_SITE . '/generated_overrides/toess_lab_news_letter.php');
        $fs->delete(PackageSetup::$subscription_path . PackageSetup::$subscription_file);
        $fs->delete(PackageSetup::$subscription_path . PackageSetup::$un_subscription_file);
        $fs->delete(PackageSetup::$subscription_path);
        $stmt->executeQuery('SET FOREIGN_KEY_CHECKS=1');
	    $p = SinglePage::getByID(\Config::get('toess_lab_news_letter.browser_page'));
	    if (is_object($p)){
	    	$p->delete();
	    }
	    $p = SinglePage::getByID(\Config::get('toess_lab_news_letter.subscription_page'));
	    if (is_object($p)){
	    	$p->delete();
	    }
        parent::uninstall();
    }

    public function upgrade()
    {
        $this->subscription = new Subscription();
        parent::upgrade();
        $bt = BlockType::getByHandle('toess_lab_news_letter_un_subscribe_block');
        if(PackageSetup::checkC5Version('5.7.5.13', '==')) {
            $pkg = $this;
            if (!is_object($bt)) {
                BlockType::installBlockTypeFromPackage('toess_lab_news_letter_un_subscribe_block', $pkg);
            }
        } else {
            $pkg = $this->getPackageEntity();
            if (!is_object($bt)) {
                BlockType::installBlockType('toess_lab_news_letter_un_subscribe_block', $pkg);
            }
        }
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/subscriptions', t('Subscriptions'));
        $args_receive_newsletter = array(
            'akHandle' => 'toesslab_receive_newsletter_new',
            'akName' => t('Receive toesslab - Newsletter'),
            'uakProfileDisplay' => true,
            'uakMemberListDisplay' => true,
            'uakProfileEdit' => true,
            'uakProfileEditRequired' => true,
            'uakRegisterEdit' => true,
            'uakRegisterEditRequired' => true,
            'akSelectOptionDisplayOrder' => 'alpha_asc',
            'akIsSearchableIndexed' => true,
            'akIsSearchable' => true,
            'akCheckedByDefault' => true
        );
        $attrReceiveNewsletterExists = UserAttributeKey::getByHandle('toesslab_receive_newsletter');
        $attrSet = Set::getByHandle('toesslab_attribute_category');
        $entityManager = \Core::make('Doctrine\ORM\EntityManager');
        $this->subscription->setFirstTimeSubscriptions();
        if (is_object($attrReceiveNewsletterExists)) {
            $attr_checkbox = AttributeType::getByHandle('boolean');
            if(PackageSetup::checkC5Version('5.7.5.13', '==')){
                $attrReceiveNewsletterExists->delete();
                UserAttributeKey::add($attr_checkbox, $args_receive_newsletter, $pkg)->setAttributeSet($attrSet);
                $subscriptions = $this->subscription->getSubscriptions();
                if ($subscriptions != null) {
                    foreach ($subscriptions as $subscription){
                        $u = UserInfo::getByID($subscription['uID']);
                        $u->setAttribute('toesslab_receive_newsletter_new', true);
                    }
                }
                $unSubscriptions = $this->subscription->getSubscriptions();
                if ($unSubscriptions != null) {
                    foreach ($unSubscriptions as $unSubscription){
                        $u = UserInfo::getByID($unSubscription['uID']);
                        $u->setAttribute('toesslab_receive_newsletter_new', false);
                    }
                }
            } else {
                $entityManager->remove($attrReceiveNewsletterExists);
                $entityManager->flush();
                $receiveNewsletter = UserKey::add($attr_checkbox, $args_receive_newsletter, $pkg);
                $entityManager->persist($receiveNewsletter);
                $receiveNewsletter->setAttributeSet($attrSet);
                $receiveNewsletter->setAttributeType($attr_checkbox);
                $receiveNewsletter->setAttributeKeyDisplayedOnProfile(true);
                $receiveNewsletter->setAttributeKeyRequiredOnRegister(true);
                $receiveNewsletter->setAttributeKeyEditableOnProfile(true);
                $receiveNewsletter->setAttributeKeyRequiredOnProfile(true);
                $receiveNewsletter->setAttributeKeyEditableOnRegister(true);
                $receiveNewsletter->setAttributeKeyRequiredOnRegister(true);
                $entityManager->persist($receiveNewsletter);
                $entityManager->flush();
                $userInfo = $this->app->make('Concrete\Core\User\UserInfo');
                $subscriptions = $this->subscription->getSubscriptions();

                if ($subscriptions != null) {
                    foreach ($subscriptions as $subscription){
                        $u = $userInfo->getByID($subscription['uID']);
                        $u->setAttribute('toesslab_receive_newsletter_new', true);
                    }

                }
                $unSubscriptions = $this->subscription->getSubscriptions();
                if ($unSubscriptions != null) {
                    foreach ($unSubscriptions as $unSubscription){
                        $u = $userInfo->getByID($unSubscription['uID']);
                        $u->setAttribute('toesslab_receive_newsletter_new', false);
                    }

                }
            }
        }
        if(PackageSetup::checkC5Version('5.7.5.13', '==')){
            $cms = \Core::make('app');
            $cms->clearCaches();
        } else {
            $this->app->clearCaches();
        }

    }

    public function on_start()
    {
        $app = \Core::make('app');
        $this->subscription = new Subscription();
        $provider = new HelpServiceProvider($app);
        $provider->register();

        $pkg = $this;
        $al = Asset\AssetList::getInstance();
        $al->register(
            'css', 'toesslab', 'css/toesslab.css', array('position' => \Asset::ASSET_POSITION_HEADER), $pkg
        );
        $al->register(
            'javascript', 'toesslab', 'js/min/toesslab_global.min.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_subscription', 'js/min/toesslab_subscription.min.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'googlefontfamily', 'js/build/vendor/redactor/googlefontfamily.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
      /*
        $al->register(
            'javascript', 'cke4_googlefontfamily', 'editor/ckeditor/googlefontfamily.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslabevents', 'js/build/vendor/redactor/toesslabevents.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
         */
        $al->register(
            'css', 'bootstrapswitch', 'js/libs/bootstrap_switch/bootstrap-switch.min.css', array('position' => \Asset::ASSET_POSITION_HEADER), $pkg
        );
        $al->register(
            'javascript', 'bootstrapswitch', 'js/libs/bootstrap_switch/bootstrap-switch.min.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_settings', 'js/tourist/toesslab-tourist-settings.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_newsletter_template_list', 'js/tourist/toesslab-tourist-newsletter-template-list.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_newsletter_list', 'js/tourist/toesslab-tourist-newsletter-list.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_new_newsletter', 'js/tourist/toesslab-tourist-new-newsletter.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_new_newsletter_template', 'js/tourist/toesslab-tourist-new-newsletter-template.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'javascript', 'toesslab_tourist_send_newsletter', 'js/tourist/toesslab-tourist-send-newsletter.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        $al->register(
            'css', 'jquery-ui', 'js/libs/jquery-ui/jquery-ui.min.css', array('position' => \Asset::ASSET_POSITION_HEADER), $pkg
        );
        $al->register(
            'css', 'jquery-ui-struct', 'js/libs/jquery-ui/jquery-ui.structure.min.css', array('position' => \Asset::ASSET_POSITION_HEADER), $pkg
        );
        $al->register(
            'css', 'jquery-ui-themes', 'js/libs/jquery-ui/jquery-ui.theme.min.css', array('position' => \Asset::ASSET_POSITION_HEADER), $pkg
        );
        $al->register(
            'javascript', 'jquery-ui', 'js/libs/jquery-ui/jquery-ui.min.js', array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        if (PackageSetup::checkC5Version()) {
            // Todo Google Font family for ckeditor
        }else{
        }
        $gfontFamilyHandle = 'googlefontfamily';
        $s = 'js/build/vendor/redactor/googlefontfamily.js';
        $al->registerGroup('toesslabevents', array(
            array('javascript', 'toesslabevents'),
        ));
        /*
        $plugin_toesslabevents = new Plugin();
        $plugin_toesslabevents->setKey('toesslabevents');
        $plugin_toesslabevents->setName('toesslabevents');
        $plugin_toesslabevents->requireAsset('toesslabevents');
        \Core::make('editor')->getPluginManager()->register($plugin_toesslabevents);

        $al->register(
            'javascript', $gfontFamilyHandle, $s, array('position' => \Asset::ASSET_POSITION_FOOTER), $pkg
        );
        */
        $al->registerGroup('my-jquery-ui', array(
            array('css', 'jquery-ui'),
            array('css', 'jquery-ui-struct'),
            array('css', 'jquery-ui-themes'),
            array('javascript', 'jquery-ui'),
        ));
        /*
        $al->registerGroup($gfontFamilyHandle, array(
            array('javascript', $gfontFamilyHandle)
        ));
        */
        $al->registerGroup('toesslab', array(
            array('css', 'toesslab'),
            array('javascript', 'toesslab'),
        ));
        $al->registerGroup('toesslab_subscription', array(
            array('javascript', 'toesslab_subscription'),
        ));
        $al->registerGroup('bootstrapswitch', array(
            array('css', 'bootstrapswitch'),
            array('javascript', 'bootstrapswitch'),
        ));
        /*
        $plugin_googlefonts = new Plugin();
        $plugin_googlefonts->setKey('googlefontfamily');
        $plugin_googlefonts->setName('googlefontfamily');
        $plugin_googlefonts->requireAsset($gfontFamilyHandle);

        \Core::make('editor')->getPluginManager()->register($plugin_googlefonts);
       */
        $this->setEvent();
    }

    private function setEvent()
    {
        Events::addListener('on_user_attributes_saved', function ($event) {
            $user = $event->getUserInfoObject();
            $sub = $user->getAttribute('toesslab_receive_newsletter_new');
            $this->subscription->setSubscription($user, $sub);
        });
    }

    private function setTemplate($tpl)
    {
        $mail_template = new ToessLabNewsLetterMailTemplate();
        $mail_template->setMailTemplateHandle($tpl['templateHandle']);

        $mail_template->setTemplateDesigned(0);
        return $mail_template;
    }

    private function getPkg ()
    {
        return $this->pkgHandle;
    }

    private function installOrUpgrade($pkg)
    {
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter', t('toesslab - Newsletter'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/settings', t('Newsletter Settings'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/templates/template_list', t('Template List'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/templates/new_template', t('Add Template'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/newsletters/newsletter_list', t('Newsletter List'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/newsletters/new_newsletter', t('Add Newsletter'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/test_email_settings', t('Test Email Settings'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/send_mailing', t('Send Mailing'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/newsletter_sent', t('Mailing History'));
        $this->getOrAddSinglePage($pkg, '/dashboard/newsletter/subscriptions', t('Subscriptions'));
        $sp = $this->getOrAddSinglePage($pkg, '/newsletter_in_browser', t('Show Newsletter in browser'));
	    $this->setAttributeOnSinglePage($sp);
	    \Config::save('toess_lab_news_letter.browser_page', $sp->cID);
        Job::installByPackage('send_mailing_as_job', $pkg);
    }

    private function getOrAddSinglePage($pkg, $cPath, $cName = '', $cDescription = '')
    {
        \Loader::model('single_page');

        $sp = SinglePage::add($cPath, $pkg);

        if (is_null($sp)) {
            $sp = Page::getByPath($cPath);
        } else {
            $data = array();
            if (!empty($cName)) {
                $data['cName'] = $cName;
            }
            if (!empty($cDescription)) {
                $data['cDescription'] = $cDescription;
            }

            if (!empty($data)) {
                $sp->update($data);
            }
        }

        return $sp;
    }

    private function setAttributeOnSinglePage($sp)
    {
	    $sp->setAttribute('exclude_nav', true);
	    $sp->setAttribute('exclude_search_index', true);
	    $sp->setAttribute('exclude_sitemapxml', true);
	    $sp->setAttribute('exclude_page_list', true);
    }
}
