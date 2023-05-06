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
            'name' => $this->string()->notNull()
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
