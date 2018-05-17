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
use czechitas\czechitoviny\records\UserRecord;

/**
 * UserModel Model
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
class UserModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var integer|null
     */
    public $id = null;
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
    public $email = null;
    /**
     * @var string|null
     */
    public $role = null;
    /**
     * @var datetime|null
     */
    public $birth = null;
    /**
     * @var string|null
     */
    public $gender = null;
    /**
     * @var string|null
     */
    public $photoUrl = null;
    /**
     * @var string|null
     */
    public $phone = null;
    /**
     * @var string|null
     */
    public $city = null;
    /**
     * @var boolean
     */
    public $isAccountComplete = false;

    public static function createFromRecord(UserRecord $record)
    {
        $model                    = new self();
        $model->id                = $record->id;
        $model->firstName         = $record->firstName;
        $model->lastName          = $record->lastName;
        $model->email             = $record->email;
        $model->gender            = $record->gender;
        $model->role              = $record->role;
        $model->photoUrl          = $record->photoUrl;
        $model->birth             = $record->birth;
        $model->phone             = $record->phone;
        $model->city              = $record->city;
        $model->isAccountComplete = $record->isAccountComplete;
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
