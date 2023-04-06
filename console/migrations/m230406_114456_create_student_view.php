<?php

use yii\db\Migration;

/**
 * Class m230406_114456_create_student_view
 */
class m230406_114456_create_student_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE VIEW student_view AS SELECT * FROM student WHERE closed_at = DATE('3000-01-01')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP VIEW student_view");
    }
}
