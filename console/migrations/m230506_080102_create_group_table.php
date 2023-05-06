<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m230506_080102_create_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'direction_id' => $this->string()->notNull(),
            'academic_id' => $this->integer()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null()
        ]);

        $this->addForeignKey(
            'group_to_direction-fk',
            'group',
            'direction_id',
            'direction',
            'id'
        );

        $this->addForeignKey(
            'group_to_academic_degree-fk',
            'group',
            'academic_id',
            'academic_degree',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('group_to_direction-fk', 'group');
        $this->dropForeignKey('group_to_academic_degree-fk', 'group');
        $this->dropTable('{{%group}}');
    }
}
