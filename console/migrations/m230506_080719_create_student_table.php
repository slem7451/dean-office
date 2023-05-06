<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%student}}`.
 */
class m230506_080719_create_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'second_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'sex' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'payment' => $this->tinyInteger()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student}}');
    }
}
