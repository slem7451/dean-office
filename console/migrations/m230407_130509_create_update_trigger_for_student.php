<?php

use yii\db\Migration;

/**
 * Class m230407_130509_create_update_trigger_for_student
 */
class m230407_130509_create_update_trigger_for_student extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION student_after_update_insert()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO student VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.birthdate, old.created_at, NOW());
                RETURN NULL;
            END
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER student_after_update_insert
                AFTER UPDATE
                ON "student"
                FOR EACH ROW
            EXECUTE PROCEDURE student_after_update_insert();
DB
);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute(<<<DB
            DROP TRIGGER student_after_update_insert on "student"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION student_after_update_insert();
DB
);
    }
}
