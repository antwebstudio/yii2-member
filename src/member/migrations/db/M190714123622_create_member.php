<?php

namespace ant\member\migrations\db;

use ant\db\Migration;

/**
 * Class M190714123622_create_member
 */
class M190714123622_create_member extends Migration
{
	protected $tableName = '{{%member}}';
	
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'renewed_at' => $this->timestamp()->defaultValue(NULL),
            'expire_at' => $this->timestamp()->defaultValue(NULL),
        ], $this->getTableOptions());

		$this->addForeignKeyTo('{{%user}}', 'user_id', 'cascade', 'cascade');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190714123622_create_member cannot be reverted.\n";

        return false;
    }
    */
}
