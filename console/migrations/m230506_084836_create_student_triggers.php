<?php

use yii\db\Migration;

/**
 * Class m230506_084836_create_student_triggers
 */
class m230506_084836_create_student_triggers extends Migration
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
                INSERT INTO student_history VALUES (new.id, new.first_name, new.second_name, new.patronymic, new.sex, new.phone, new.payment, new.birthdate, new.created_at, new.closed_at, NOW(), 1);
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
                IF (new.closed_at IS NULL) THEN
                    IF (old.closed_at IS NULL) THEN
                        INSERT INTO student_history VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.payment, old.birthdate, old.created_at, old.closed_at, NOW(), 2);
                    ELSE
                        INSERT INTO student_history VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.payment, old.birthdate, old.created_at, new.closed_at, NOW(), 1);
                    END IF;
                ELSE 
                    INSERT INTO student_history VALUES (old.id, old.first_name, old.second_name, old.patronymic, old.sex, old.phone, old.payment, old.birthdate, old.created_at, new.closed_at, NOW(), 3);
                END IF;
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
    }
}
