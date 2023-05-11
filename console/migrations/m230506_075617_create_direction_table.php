<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direction}}`.
 */
class m230506_075617_create_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%direction}}', [
            'id' => $this->string()->unique(),
            'full_name' => $this->string()->notNull(),
            'short_name' => $this->string()->notNull(),
            'academic_name' => $this->string()->notNull(),
            'profile' => $this->string()->notNull()

        ]);

        $this->addPrimaryKey('direction-pk', 'direction', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('direction-pk', 'direction');
        $this->dropTable('{{%direction}}');
    }
}
