<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certificate}}`.
 */
class m230506_082421_create_certificate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificate}}', [
            'id' => $this->primaryKey(),
            'template_id' => $this->string()->notNull(),
            'created_at' => $this->date()->notNull(),
            'closed_at' => $this->date()->null()
        ]);

        $this->addForeignKey(
            'certificate_to_certificate_template-fk',
            'certificate',
            'template_id',
            'certificate_template',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('certificate_to_certificate_template-fk', 'certificate');
        $this->dropTable('{{%certificate}}');
    }
}
