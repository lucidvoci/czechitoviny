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
use czechitas\czechitoviny\records\CourseRecord;

/**
 * CourseModel Model
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
class CourseModel extends Model
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
    public $calendarEventId = null;
    /**
     * @var string|null
     */
    public $name = null;
    /**
     * @var string|null
     */
    public $type = null;
    /**
     * @var string|null
     */
    public $location = null;
    /**
     * @var datetime|null
     */
    public $date = null;


    public static function createFromRecord(CourseRecord $record)
    {
        $model                    = new self();
        $model->id                = $record->id;
        $model->calendarEventId   = $record->calendarEventId;
        $model->name              = $record->name;
        $model->type              = $record->type;
        $model->location          = $record->location;
        $model->date              = $record->date;
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
