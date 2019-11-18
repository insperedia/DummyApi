<?php

use yii\db\Migration;

/**
 * Class m180409_003520_create_users_table
 * @see https://github.com/yiisoft/yii2-app-advanced/blob/master/console/migrations/m130524_201442_init.php
 */
class m180409_003520_create_users_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $security = Yii::$app->security;
        $now = (new DateTime)->format('Y-m-d h:i:s');

        $this->insert('{{%users}}', [
            'username' => 'admin',
            'auth_key' => $security->generateRandomString(),
            'password_hash' => $security->generatePasswordHash('123456'),
            'email' => 'admin@admin.com.br',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
