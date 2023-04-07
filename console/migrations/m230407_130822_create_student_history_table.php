<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_history}}`.
 */
class m230407_130822_create_student_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_history}}', [
            'student_id' => $this->integer()->notNull(),
            'first_name' => $this->string()->notNull(),
            'second_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression('NOW()')),
            'closed_at' => $this->timestamp()->defaultValue(new Expression("DATE('3000-01-01 00:00:00')")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression('NOW()')),
            'operation' => $this->tinyInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_history}}');
    }
}
