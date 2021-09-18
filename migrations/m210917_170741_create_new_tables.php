<?php

use yii\db\Migration;

/**
 * Class m210917_170741_create_new_tables
 */
class m210917_170741_create_new_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'active' => $this->boolean(),
        ], $tableOptions);


        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'color' => $this->string(),
        ], $tableOptions);


        $this->createTable('{{%productCategory}}', [
            'productId' => $this->integer()->notNull(),
            'categoryId' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->createTable('{{%productImage}}', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'default' => $this->boolean(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%productCategory}}');
        $this->dropTable('{{%productImage}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210917_170741_create_new_tables cannot be reverted.\n";

        return false;
    }
    */
}
