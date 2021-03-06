<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny;

use czechitas\czechitoviny\services\CzechitovinyService as CzechitovinyServiceService;
use czechitas\czechitoviny\variables\CzechitovinyVariable;
use czechitas\czechitoviny\models\UserModel;
use czechitas\czechitoviny\twigextensions\CzechitovinyTwigExtension;
use czechitas\czechitoviny\fields\CzechitovinyField as CzechitovinyFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\services\Elements;
use craft\services\Fields;
use craft\elements\User;
use craft\web\twig\variables\CraftVariable;
use craft\events\ElementEvent;
use craft\events\RegisterUrlRulesEvent;

use DateTime;
use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 *
 * @property  CzechitovinyServiceService $czechitovinyService
 */
class Czechitoviny extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Czechitoviny::$plugin
     *
     * @var Czechitoviny
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Czechitoviny::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Add in our Twig extensions
        Craft::$app->view->registerTwigExtension(new CzechitovinyTwigExtension());

        // Register our site routes
        //Event::on(
        //    UrlManager::class,
        //    UrlManager::EVENT_REGISTER_SITE_URL_RULES,
        //    function (RegisterUrlRulesEvent $event) {
         //       $event->rules['siteActionTrigger1'] = 'czechitoviny/default';
        //    }
       // );

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['czechitoviny']  = 'czechitoviny/user/index';

            }
        );

        // Catch element being saved in craft administration
        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            function (ElementEvent $event) {
                if ($event->element instanceof User) {
                    $request = Craft::$app->getRequest();
                    if ($request->getBodyParam('action') == 'users/save-user') {
                        $user = new UserModel();
                        $user->firstName = $request->getBodyParam('firstName');
                        $user->lastName = $request->getBodyParam('lastName');
                        $user->email = $request->getBodyParam('email');
                        $user->birth = new DateTime($request->getBodyParam('birth'));
                        $user->role = $request->getBodyParam('role');
                        Czechitoviny::getInstance()->czechitovinyService->saveUser($user);
                    }
                }
            }
        );

        // Register our fields
//        Event::on(
//            Fields::class,
//            Fields::EVENT_REGISTER_FIELD_TYPES,
//            function (RegisterComponentTypesEvent $event) {
//                $event->types[] = CzechitovinyFieldField::class;
//            }
//        );

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('czechitoviny', CzechitovinyVariable::class);
            }
        );

        // Do something after we're installed
//        Event::on(
//            Plugins::class,
//            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
//            function (PluginEvent $event) {
//                if ($event->plugin === $this) {
//                    // We were just installed
//                }
//            }
//        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'czechitoviny',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
