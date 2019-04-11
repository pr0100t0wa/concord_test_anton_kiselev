<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(32)->notNull()->unique(),
            'password' => $this->string(32)->notNull(),
            'email' => $this->string(64)->notNull()->unique(),
            'group_id' => $this->integer()->notNull(),
            'photo' => $this->string(256)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ], $tableOptions);

        $this->alterColumn('{{%users}}', 'id', $this->integer(11).' NOT NULL AUTO_INCREMENT');

        $this->createTable('{{%groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->alterColumn('{{%groups}}', 'id', $this->integer(11).' NOT NULL AUTO_INCREMENT');

        $this->createIndex(
            'idx-users-group_id',
            'users',
            'group_id'
        );

        $this->addForeignKey(
            'fk-user-group_id',
            'users',
            'group_id',
            'groups',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%users}}');
        $this->dropTable('{{%groups}}');
    }
}
