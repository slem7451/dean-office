<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certificate_to_student}}`.
 */
class m230506_082608_create_certificate_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificate_to_student}}', [
            'certificate_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null()
        ]);

        $this->addForeignKey(
            'certificate_to_student_to_certificate-fk',
            'certificate_to_student',
            'certificate_id',
            'certificate',
            'id'
        );

        $this->addForeignKey(
            'certificate_to_student_to_student-fk',
            'certificate_to_student',
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
        $this->dropForeignKey('certificate_to_student_to_student-fk', 'certificate_to_student');
        $this->dropForeignKey('certificate_to_student_to_certificate-fk', 'certificate_to_student');
        $this->dropTable('{{%certificate_to_student}}');
    }
}
