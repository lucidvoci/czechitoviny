<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny\services;

use czechitas\czechitoviny\Czechitoviny;

use Craft;
use craft\base\Component;
use czechitas\czechitoviny\models\UserInvoiceInfoModel;
use czechitas\czechitoviny\models\UserMoreInfoModel;
use czechitas\czechitoviny\models\UserModel;
use czechitas\czechitoviny\records\UserInvoiceInfoRecord;
use czechitas\czechitoviny\records\UserMoreInfoRecord;
use czechitas\czechitoviny\records\UserRecord;

/**
 * CzechitovinyService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 */
class CzechitovinyService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Czechitoviny::$plugin->czechitovinyService->exampleService()
     *
     * @return mixed
     */

    public function saveUser(UserModel $user) {
        $record = new UserRecord();

        $record->firstName = $user->firstName;
        $record->lastName = $user->lastName;
        $record->birth = $user->birth;
        $record->email = $user->email;
        $record->role = $user->role;

        $record->save(false);
    }

    public function updateUser(UserModel $user) {

        $record = $this->_getUserRecordByEmail($user->email);

        $record->city = $user->city;
        $record->phone = $user->phone;
        $record->gender = $user->gender;
        $record->photoUrl = $user->photoUrl;

        $record->save(false);
    }

    public function saveUserInvoiceInfo(UserInvoiceInfoModel $userInvoiceInfo) {

        $record = UserInvoiceInfoRecord::findOne(['userId' => $userInvoiceInfo->userId]);

        if (!$record) {
            $record = new UserInvoiceInfoRecord();
        }

        if (
            $record->firstName == $userInvoiceInfo->firstName &&
            $record->lastName == $userInvoiceInfo->lastName &&
            $record->company == $userInvoiceInfo->company &&
            $record->ic == $userInvoiceInfo->ic &&
            $record->dic == $userInvoiceInfo->dic &&
            $record->dph == $userInvoiceInfo->dph &&
            $record->street == $userInvoiceInfo->street &&
            $record->city == $userInvoiceInfo->city &&
            $record->psc == $userInvoiceInfo->psc
        ) { return; }

        $record->userId = $userInvoiceInfo->userId;
        $record->firstName = $userInvoiceInfo->firstName;
        $record->lastName = $userInvoiceInfo->lastName;
        $record->company = $userInvoiceInfo->company;
        $record->ic = $userInvoiceInfo->ic;
        $record->dic = $userInvoiceInfo->dic;
        $record->dph = $userInvoiceInfo->dph;
        $record->street = $userInvoiceInfo->street;
        $record->city = $userInvoiceInfo->city;
        $record->psc = $userInvoiceInfo->psc;

        $record->save(false);
    }

    public function saveUserMoreInfo(UserMoreInfoModel $userMoreInfo)
    {

        $record = UserMoreInfoRecord::findOne(['userId' => $userMoreInfo->userId]);

        if (!$record) {
            $record = new UserMoreInfoRecord();
        }

        if (
            $record->userId == $userMoreInfo->userId &&
            $record->positionStatus == $userMoreInfo->positionStatus &&
            $record->currentJob == $userMoreInfo->currentJob &&
            $record->firstContact == $userMoreInfo->firstContact &&
            $record->os == $userMoreInfo->os &&
            $record->photoAgree == $userMoreInfo->photoAgree &&
            $record->wantsWork == $userMoreInfo->wantsWork &&
            $record->newsletterSignedup == $userMoreInfo->newsletterSignedup
        ) { return; }

        $record->userId = $userMoreInfo->userId;
        $record->positionStatus = $userMoreInfo->positionStatus;
        $record->currentJob = $userMoreInfo->currentJob;
        $record->firstContact = $userMoreInfo->firstContact;
        $record->os = $userMoreInfo->os;
        $record->photoAgree = $userMoreInfo->photoAgree;
        $record->wantsWork = $userMoreInfo->wantsWork;
        $record->newsletterSignedup = $userMoreInfo->newsletterSignedup;

        $record->save(false);
    }

    public function getUsers() : array {
        $records = UserRecord::find()->all();
        $models  = [];

        if ($records) {
            foreach ($records as $record) {
                $models[] = UserModel::createFromRecord($record);
            }
        }
        return $models;
    }

    public function getUser(string $email) : UserModel {
        $record = $this->_getUserRecordByEmail($email);
        $model = UserModel::createFromRecord($record);

        return $model;
    }

    public function getUserInvoiceInfo(int $userId) : UserInvoiceInfoModel {
        $record = UserInvoiceInfoRecord::findOne(['userId' => $userId]);

        return $record ? UserInvoiceInfoModel::createFromRecord($record) : new UserInvoiceInfoModel();
    }

    public function getUserMoreInfo(int $userId) : UserMoreInfoModel {
        $record = UserMoreInfoRecord::findOne(['userId' => $userId]);

        return $record ? UserMoreInfoModel::createFromRecord($record) : new UserMoreInfoModel();
    }

    private function _getUserRecordByEmail(string $email): UserRecord
    {
        $record = UserRecord::findOne(['email' => $email]);

        if (!$record) {
            throw new UserNotFoundException("No user exists with the email '{$email}'");
        }

        return $record;
    }

}
