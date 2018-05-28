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
use czechitas\czechitoviny\records\UserMoreInfoRecord;

/**
 * UserMoreInfoModel Model
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
class UserMoreInfoModel extends Model
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
    public $positionStatus = null;
    /**
     * @var string|null
     */
    public $currentJob = null;
    /**
     * @var string|null
     */
    public $firstContact = null;
    /**
     * @var string|null
     */
    public $os = null;
    /**
     * @var boolean|null
     */
    public $photoAgree = null;
    /**
     * @var boolean|null
     */
    public $wantsWork = null;
    /**
     * @var boolean|null
     */
    public $newsletterSignedup = null;


    public static function createFromRecord(UserMoreInfoRecord $record)
    {
        $model                     = new self();
        $model->id                 = $record->id;
        $model->userId             = $record->userId;
        $model->positionStatus     = $record->positionStatus;
        $model->currentJob         = $record->currentJob;
        $model->firstContact       = $record->firstContact;
        $model->os                 = $record->os;
        $model->photoAgree         = $record->photoAgree;
        $model->wantsWork          = $record->wantsWork;
        $model->newsletterSignedup = $record->newsletterSignedup;
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
