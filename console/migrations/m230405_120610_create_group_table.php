<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m230405_120610_create_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('group');
    }
}
