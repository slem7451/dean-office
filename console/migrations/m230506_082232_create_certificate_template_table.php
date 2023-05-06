<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certificate_template}}`.
 */
class m230506_082232_create_certificate_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificate_template}}', [
            'id' => $this->string()->unique(),
            'name' => $this->string()->notNull(),
            'template' => $this->text()->notNull()
        ]);

        $this->addPrimaryKey('certificate_template-pk', 'certificate_template', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('certificate_template-pk', 'certificate_template');
        $this->dropTable('{{%certificate_template}}');
    }
}
