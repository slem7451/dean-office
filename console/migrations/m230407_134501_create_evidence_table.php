<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%evidence}}`.
 */
class m230407_134501_create_evidence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evidence}}', [
            'evidence_name' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'description' => $this->string()
        ]);
        $this->addPrimaryKey('evidence_pk', 'evidence', ['evidence_name', 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('evidence_pk', 'evidence');
        $this->dropTable('{{%evidence}}');
    }
}
