<?php

use yii\db\Migration;

/**
 * Class m230407_131121_create_history_triggers_for_student
 */
class m230407_131121_create_history_triggers_for_student extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION student_after_insert()
                RETURNS trigger AS $$
            BEGIN
                IF (SELECT count(*) AS qty FROM student WHERE id = new.id) = 1 THEN
                    INSERT INTO student_history VALUES (new.id, new.first_name, new.second_name, new.patronymic, new.sex, new.phone, new.birthdate, new.created_at, new.closed_at, NOW(), 1);
                END IF;
                RETURN NULL;
            END
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER student_after_insert
                AFTER INSERT
                ON "student"
                FOR EACH ROW
            EXECUTE PROCEDURE student_after_insert();
DB
);

        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION student_after_update()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO student_history VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.birthdate, old.created_at, old.closed_at, NOW(), 2);
                RETURN NULL;
            END
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER student_after_update
                AFTER UPDATE
                ON "student"
                FOR EACH ROW
            EXECUTE PROCEDURE student_after_update();
DB
);

        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION student_after_delete()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO student_history VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.birthdate, old.created_at, old.closed_at, NOW(), 3);
                RETURN NULL;
            END
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER student_after_delete
                AFTER DELETE
                ON "student"
                FOR EACH ROW
            EXECUTE PROCEDURE student_after_delete();
DB
);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute(<<<DB
            DROP TRIGGER student_after_insert on "student"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION student_after_insert();
DB
);

        $this->execute(<<<DB
            DROP TRIGGER student_after_update on "student"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION student_after_update();
DB
);

        $this->execute(<<<DB
            DROP TRIGGER student_after_delete on "student"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION student_after_delete();
DB
);
    }
}
