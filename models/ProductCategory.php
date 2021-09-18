<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "productCategory".
 *
 * @property int $productId
 * @property int $categoryId
 */
class ProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productCategory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'categoryId'], 'required'],
            [['productId', 'categoryId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'productId' => 'Product ID',
            'categoryId' => 'Category ID',
        ];
    }
}
