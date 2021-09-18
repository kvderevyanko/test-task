<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $price
 * @property bool|null $active
 */
class Product extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['description'], 'string'],
            [['active'], 'boolean'],
            [['price'], 'integer', 'max' => 2147483647, 'min' => -2147483648],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'description' => 'Описание',
            'price' => 'Цена',
            'active' => 'Активно',
        ];
    }

    //Возвращает превьюшку для продукта
    public function thumb(){
        $image = ProductImage::find()->select(['file'])->where(['productId' => $this->id, 'default' => true])
            ->one();
        if($image) {
            return ProductImage::imageThumb($this->id, $image->file);
        }

        return ProductImage::imageThumb($this->id);
    }
}
