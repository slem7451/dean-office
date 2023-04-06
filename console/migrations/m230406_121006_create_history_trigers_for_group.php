<?php

use yii\db\Migration;

/**
 * Class m230406_121006_create_history_trigers_for_group
 */
class m230406_121006_create_history_trigers_for_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION after_group_insert()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO group_history VALUES (new.id, new.name, new.created_at, new.closed_at, NOW(), 1);
                RETURN NULL;
            END;
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER group_after_insert
                AFTER INSERT
                ON "group"
                FOR EACH ROW
            EXECUTE PROCEDURE after_group_insert();
DB
);

        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION after_group_update()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO group_history VALUES (old.id, old.name, old.created_at, old.closed_at, NOW(), 2);
                RETURN NULL;
            END;
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER group_after_update
                AFTER UPDATE
                ON "group"
                FOR EACH ROW
            EXECUTE PROCEDURE after_group_update();
DB
);

        $this->execute(<<<DB
            CREATE OR REPLACE FUNCTION after_group_delete()
                RETURNS trigger AS $$
            BEGIN
                INSERT INTO group_history VALUES (old.id, old.name, old.created_at, old.closed_at, NOW(), 3);
                RETURN NULL;
            END;
            $$
            LANGUAGE 'plpgsql';
DB
);
        $this->execute(<<<DB
            CREATE TRIGGER group_after_delete
                AFTER DELETE
                ON "group"
                FOR EACH ROW
            EXECUTE PROCEDURE after_group_delete();
DB
);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute(<<<DB
            DROP TRIGGER group_after_insert on "group"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION after_group_insert();
DB
);

        $this->execute(<<<DB
            DROP TRIGGER group_after_update on "group"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION after_group_update();
DB
);

        $this->execute(<<<DB
            DROP TRIGGER group_after_delete on "group"
DB
);
        $this->execute(<<<DB
            DROP FUNCTION after_group_delete();
DB
);
    }
}
