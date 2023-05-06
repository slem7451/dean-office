<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_to_group}}`.
 */
class m230506_083425_create_student_to_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_to_group}}', [
            'student_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null()
        ]);

        $this->addForeignKey(
            'student_to_group_to_student-fk',
            'student_to_group',
            'student_id',
            'student',
            'id'
        );

        $this->addForeignKey(
            'student_to_group_to_group-fk',
            'student_to_group',
            'group_id',
            'group',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('student_to_group_to_group-fk', 'student_to_group');
        $this->dropForeignKey('student_to_group_to_student-fk', 'student_to_group');
        $this->dropTable('{{%student_to_group}}');
    }
}
