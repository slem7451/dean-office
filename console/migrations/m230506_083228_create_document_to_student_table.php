<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_to_student}}`.
 */
class m230506_083228_create_document_to_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_to_student}}', [
            'document_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'document_to_student_to_document-fk',
            'document_to_student',
            'document_id',
            'document',
            'id'
        );

        $this->addForeignKey(
            'document_to_student_to_student-fk',
            'document_to_student',
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
        $this->dropForeignKey('document_to_student_to_student-fk', 'document_to_student');
        $this->dropForeignKey('document_to_student_to_document-fk', 'document_to_student');
        $this->dropTable('{{%document_to_student}}');
    }
}
