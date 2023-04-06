<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%group_history}}`.
 */
class m230406_115234_create_group_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group_history}}', [
            'group_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(new Expression('NOW()')),
            'operation' => $this->tinyInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%group_history}}');
    }
}
