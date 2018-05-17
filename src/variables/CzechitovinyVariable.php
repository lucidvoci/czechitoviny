<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny\variables;

use czechitas\czechitoviny\Czechitoviny;

use Craft;
use czechitas\czechitoviny\models\UserInvoiceInfoModel;
use czechitas\czechitoviny\models\UserMoreInfoModel;
use czechitas\czechitoviny\models\UserModel;

/**
 * Czechitoviny Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.czechitoviny }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 */
class CzechitovinyVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.czechitoviny.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.czechitoviny.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }

    public function indexLoad(
        $firstName = "",
        $lastName = ""
    ) {
        return Czechitoviny::$plugin->czechitovinyService->outputIndex(
            $firstName,
            $lastName
        );
    }

    public function users(): array
    {
        return Czechitoviny::getInstance()->czechitovinyService->getUsers();
    }

    public function user($email): UserModel
    {
        return Czechitoviny::getInstance()->czechitovinyService->getUser($email);
    }

    public function userInvoiceInfo($userId): UserInvoiceInfoModel
    {
        $userInvoiceInfo = Czechitoviny::getInstance()->czechitovinyService->getUserInvoiceInfo($userId);
        return $userInvoiceInfo ? $userInvoiceInfo : new UserInvoiceInfoModel();
    }

    public function userMoreInfo($userId): UserMoreInfoModel
    {
        $userMoreInfo = Czechitoviny::getInstance()->czechitovinyService->getUserMoreInfo($userId);
        return $userMoreInfo ? $userMoreInfo : new UserMoreInfoModel();
    }
}
