<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny\models;

use czechitas\czechitoviny\Czechitoviny;

use Craft;
use craft\base\Model;
use czechitas\czechitoviny\records\UserInvoiceInfoRecord;

/**
 * UserInvoiceInfoModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 */
class UserInvoiceInfoModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var integer|null
     */
    public $id = null;
    /**
     * @var integer|null
     */
    public $userId = null;
    /**
     * @var string|null
     */
    public $firstName = null;
    /**
     * @var string|null
     */
    public $lastName = null;
    /**
     * @var string|null
     */
    public $company = null;
    /**
     * @var integer|null
     */
    public $ic = null;
    /**
     * @var integer|null
     */
    public $dic = null;
    /**
     * @var boolean|null
     */
    public $dph = false;
    /**
     * @var string|null
     */
    public $street = null;
    /**
     * @var string|null
     */
    public $city = null;
    /**
     * @var string|null
     */
    public $psc = null;

    public static function createFromRecord(UserInvoiceInfoRecord $record)
    {
        $model                    = new self();
        $model->id                = $record->id;
        $model->userId            = $record->userId;
        $model->firstName         = $record->firstName;
        $model->lastName          = $record->lastName;
        $model->company           = $record->company;
        $model->ic                = $record->ic;
        $model->dic               = $record->dic;
        $model->dph               = $record->dph;
        $model->street            = $record->street;
        $model->city              = $record->city;
        $model->psc               = $record->psc;
        return $model;
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
