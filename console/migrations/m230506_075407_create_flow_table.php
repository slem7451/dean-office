<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flow}}`.
 */
class m230506_075407_create_flow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flow}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%flow}}');
    }
}
