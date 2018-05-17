<?php
/**
 * Czechitoviny plugin for Craft CMS 3.x
 *
 * -
 *
 * @link      https://github.com/lucidvoci
 * @copyright Copyright (c) 2018 Lucie Charvat
 */

namespace czechitas\czechitoviny\migrations;

use czechitas\czechitoviny\Czechitoviny;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Czechitoviny Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Lucie Charvat
 * @package   Czechitoviny
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $this->createTable(
            '{{%czechitoviny_user}}',
            [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'firstName' => $this->string(255)->notNull()->defaultValue(''),
                'lastName' => $this->string(255)->notNull()->defaultValue(''),
                'email' => $this->string(255)->notNull()->defaultValue(''),
                'gender' => $this->string(255)->notNull()->defaultValue(''),
                'birth' => $this->dateTime()->notNull(),
                'role' => $this->string(255)->notNull()->defaultValue(''),
                'photoUrl' => $this->string(255)->notNull()->defaultValue(''),
                'phone' => $this->string(255)->notNull()->defaultValue(''),
                'city' => $this->string(255)->notNull()->defaultValue(''),
                'isAccountComplete' => $this->boolean()->notNull()->defaultValue(false),
            ]
        );

        $this->createTable(
            '{{%czechitoviny_course}}',
            [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'calendarEventId' => $this->integer(), // id in the Calendar plugin in Craft
                'name' => $this->string(255)->notNull()->defaultValue(''),
                'date' => $this->dateTime()->notNull(),
                'type' => $this->string(255)->notNull()->defaultValue(''),
                'location' => $this->string(255)->notNull()->defaultValue(''),
            ]
        );

        $this->createTable(
            '{{%czechitoviny_userCourse}}',
            [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'userId' => $this->integer()->notNull(),
                'courseId' => $this->integer()->notNull(),
                'status' => $this->string(255)->notNull()->defaultValue(''),
            ]
        );

        $this->createTable(
            '{{%czechitoviny_userMoreInfo}}',
            [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'userId' => $this->integer()->notNull(),
                'positionStatus' => $this->string(255)->notNull()->defaultValue(''),
                'currentJob' => $this->string(255)->notNull()->defaultValue(''),
                'firstContact' => $this->string(255)->notNull()->defaultValue(''),
                'os' => $this->string(255)->notNull()->defaultValue(''),
                'photoAgree' => $this->boolean()->notNull()->defaultValue(false),
                'wantsWork' => $this->boolean()->notNull()->defaultValue(false),
                'newsletterSignedup' => $this->boolean()->notNull()->defaultValue(false),
            ]
        );

        $this->createTable(
            '{{%czechitoviny_userInvoiceInfo}}',
            [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'userId' => $this->integer()->notNull(),
                'firstName' => $this->string(255)->notNull()->defaultValue(''),
                'lastName' => $this->string(255)->notNull()->defaultValue(''),
                'company' => $this->string(255)->notNull()->defaultValue(''),
                'ic' => $this->integer(),
                'dic' => $this->integer(),
                'dph' => $this->boolean()->notNull()->defaultValue(false),
                'street' => $this->string(255)->notNull()->defaultValue(''),
                'city' => $this->string(255)->notNull()->defaultValue(''),
                'psc' => $this->string(255)->notNull()->defaultValue(''),
            ]
        );

        return true;
    }

    /**
     * Creates the indexes needed for the Records used by the plugin
     *
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                '{{%czechitoviny_userCourse}}',
                'userId',
                false
            ),
            '{{%czechitoviny_userCourse}}',
            'userId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%czechitoviny_userCourse}}',
                'courseId',
                false
            ),
            '{{%czechitoviny_userCourse}}',
            'courseId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%czechitoviny_userMoreInfo}}',
                'userId',
                false
            ),
            '{{%czechitoviny_userMoreInfo}}',
            'userId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%czechitoviny_userInvoiceInfo}}',
                'userId',
                false
            ),
            '{{%czechitoviny_userInvoiceInfo}}',
            'userId',
            false
        );

        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%czechitoviny_userCourse}}', 'userId'),
            '{{%czechitoviny_userCourse}}',
            'userId',
            '{{%czechitoviny_user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%czechitoviny_userCourse}}', 'courseId'),
            '{{%czechitoviny_userCourse}}',
            'courseId',
            '{{%czechitoviny_course}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%czechitoviny_userMoreInfo}}', 'userId'),
            '{{%czechitoviny_userMoreInfo}}',
            'userId',
            '{{%czechitoviny_user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%czechitoviny_userInvoiceInfo}}', 'userId'),
            '{{%czechitoviny_userInvoiceInfo}}',
            'userId',
            '{{%czechitoviny_user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%czechitoviny_userCourse}}');
        $this->dropTableIfExists('{{%czechitoviny_userMoreInfo}}');
        $this->dropTableIfExists('{{%czechitoviny_userInvoiceInfo}}');
        $this->dropTableIfExists('{{%czechitoviny_course}}');
        $this->dropTableIfExists('{{%czechitoviny_user}}');
    }
}
