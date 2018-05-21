<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny\controllers;

use czechitas\czechitoviny\Czechitoviny;

use Craft;
use craft\web\Controller;

use czechitas\czechitoviny\models\UserModel;
use czechitas\czechitoviny\models\UserInvoiceInfoModel;
use czechitas\czechitoviny\models\UserMoreInfoModel;
use czechitas\czechitoviny\records\UserRecord;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 */
class UserController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/czechitoviny/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $records = UserRecord::find()->all();

        $models  = [];

        if ($records) {
            foreach ($records as $record) {
                $models[] = UserModel::createFromRecord($record);
            }
        }
        return $this->renderTemplate('czechitoviny/index',['users' => $models]);
    }

    public function actionSaveUserFront()
    {
        $this->requirePostRequest();
        $request = \Craft::$app->request;

        $user = new UserModel();
        $user->email  = $request->post('email');
        $user->gender = $request->post('gender');
        $user->photoUrl = $request->post('photoUrl');
        $user->phone  = $request->post('phone');
        $user->city   = $request->post('city');

        Czechitoviny::getInstance()->czechitovinyService->updateUser($user);

        $userInvoiceInfo = new UserInvoiceInfoModel();
        $userInvoiceInfo->userId  = $request->post('userId');
        $userInvoiceInfo->firstName  = $request->post('firstName');
        $userInvoiceInfo->lastName = $request->post('lastName');
        $userInvoiceInfo->company  = $request->post('company');
        $userInvoiceInfo->ic = $request->post('ic');
        $userInvoiceInfo->dic = $request->post('dic');
        $userInvoiceInfo->dph = $request->post('dph') == null ? 0 : $request->post('dph');
        $userInvoiceInfo->street = $request->post('street');
        $userInvoiceInfo->city = $request->post('cityInvoice');
        $userInvoiceInfo->psc = $request->post('psc');

        Czechitoviny::getInstance()->czechitovinyService->saveUserInvoiceInfo($userInvoiceInfo);

        $userMoreInfo = new UserMoreInfoModel();
        $userMoreInfo->userId = $request->post('userId');
        $userMoreInfo->positionStatus = $request->post('positionStatus');
        $userMoreInfo->currentJob = $request->post('currentJob');
        $userMoreInfo->firstContact = $request->post('firstContact');
        $userMoreInfo->os = $request->post('os');
        $userMoreInfo->photoAgree = $request->post('photoAgree') == null ? 0 : $request->post('photoAgree');
        $userMoreInfo->wantsWork = $request->post('wantsWork') == null ? 0 : $request->post('wantsWork');
        $userMoreInfo->newsletterSignedup = $request->post('newsletterSignedup') == null ? 0 : $request->post('newsletterSignedup');

        Czechitoviny::getInstance()->czechitovinyService->saveUserMoreInfo($userMoreInfo);
    }
}
