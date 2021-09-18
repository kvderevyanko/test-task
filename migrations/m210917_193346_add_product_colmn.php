<?php

use yii\db\Migration;

/**
 * Class m210917_193346_add_product_colmn
 */
class m210917_193346_add_product_colmn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'price', $this->integer()->notNull()->after('description')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'price');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210917_193346_add_product_colmn cannot be reverted.\n";

        return false;
    }
    */
}
