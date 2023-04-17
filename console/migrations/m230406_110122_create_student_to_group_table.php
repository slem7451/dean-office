<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_to_group}}`.
 */
class m230406_110122_create_student_to_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_to_group}}', [
            'student_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression('NOW()')),
            'closed_at' => $this->timestamp()->defaultValue(new Expression("DATE('3000-01-01 00:00:00')")),
        ]);
        $this->addForeignKey(
            'student_to_group_to_group_fk',
            'student_to_group',
            'group_id',
            'group',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('student_to_group_to_group_fk', 'student_to_group');
        $this->dropTable('{{%student_to_group}}');
    }
}
