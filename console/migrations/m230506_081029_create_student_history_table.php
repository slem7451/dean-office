<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_history}}`.
 */
class m230506_081029_create_student_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_history}}', [
            'id' => $this->integer()->notNull(),
            'first_name' => $this->string()->notNull(),
            'second_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'sex' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'payment' => $this->tinyInteger()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null(),
            'updated_at' => $this->date()->defaultValue(new Expression('NOW()')),
            'operation' => $this->tinyInteger()->notNull()
        ]);

        $this->addForeignKey(
            'student_history_to_student-fk',
            'student_history',
            'id',
            'student',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('student_history_to_student-fk', 'student_history');
        $this->dropTable('{{%student_history}}');
    }
}
