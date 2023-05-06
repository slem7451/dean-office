<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%academic_degree}}`.
 */
class m230506_075912_create_academic_degree_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%academic_degree}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%academic_degree}}');
    }
}
