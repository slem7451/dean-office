<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%student}}`.
 */
class m230406_102832_create_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE SEQUENCE student_seq START 1');
        $this->createTable('{{%student}}', [
            'id' => $this->integer()->defaultValue(new Expression("NEXTVAL('student_seq')")),
            'first_name' => $this->string()->notNull(),
            'second_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'sex' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression('NOW()')),
            'closed_at' => $this->timestamp()->defaultValue(new Expression("DATE('3000-01-01 00:00:00')")),
        ]);
        $this->addPrimaryKey('student_pk', 'student', ['id', 'closed_at', 'created_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('student_pk', 'student');
        $this->dropTable('{{%student}}');
        $this->execute('DROP SEQUENCE student_seq');
    }
}
