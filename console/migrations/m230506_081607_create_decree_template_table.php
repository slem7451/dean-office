<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decree_template}}`.
 */
class m230506_081607_create_decree_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decree_template}}', [
            'id' => $this->string()->unique(),
            'name' => $this->string()->notNull(),
            'template' => $this->text()->notNull()
        ]);

        $this->addPrimaryKey('decree_template-pk', 'decree_template', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('decree_template-pk', 'decree_template');
        $this->dropTable('{{%decree_template}}');
    }
}
