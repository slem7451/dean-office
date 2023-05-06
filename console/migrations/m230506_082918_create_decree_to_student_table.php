<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decree_to_student}}`.
 */
class m230506_082918_create_decree_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decree_to_student}}', [
            'decree_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'decree_to_student_to_decree-fk',
            'decree_to_student',
            'decree_id',
            'decree',
            'id'
        );

        $this->addForeignKey(
            'decree_to_student_to_student-fk',
            'decree_to_student',
            'student_id',
            'student',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('decree_to_student_to_student-fk', 'decree_to_student');
        $this->dropForeignKey('decree_to_student_to_decree-fk', 'decree_to_student');
        $this->dropTable('{{%decree_to_student}}');
    }
}
