<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group_to_flow}}`.
 */
class m230506_083759_create_group_to_flow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group_to_flow}}', [
            'group_id' => $this->integer()->notNull(),
            'flow_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'group_to_flow_to_group-fk',
            'group_to_flow',
            'group_id',
            'group',
            'id'
        );

        $this->addForeignKey(
            'group_to_flow_to_flow-fk',
            'group_to_flow',
            'flow_id',
            'flow',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('group_to_flow_to_flow-fk', 'group_to_flow');
        $this->dropForeignKey('group_to_flow_to_group-fk', 'group_to_flow');
        $this->dropTable('{{%group_to_flow}}');
    }
}
