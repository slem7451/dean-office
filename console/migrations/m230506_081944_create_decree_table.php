<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decree}}`.
 */
class m230506_081944_create_decree_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decree}}', [
            'id' => $this->primaryKey(),
            'template_id' => $this->string()->notNull(),
            'created_at' => $this->date()->notNull()
        ]);

        $this->addForeignKey(
            'decree_to_decree_template-fk',
            'decree',
            'template_id',
            'decree_template',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('decree_to_decree_template-fk', 'decree');
        $this->dropTable('{{%decree}}');
    }
}
