<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

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

    /**
     * Возвращает превьюшку для продукта
     * @return mixed
     */
    public function thumb(){
        $productId = $this->id;
        $key = ['ProductThumb', $productId];
        $imageThumb = Yii::$app->cache->getOrSet($key, function () use ($productId) {
            $image = ProductImage::find()->select(['file'])->where(['productId' => $productId, 'default' => true])
                ->one();
            if($image) {
                return ProductImage::imageThumb($productId, $image->file);
            }
            return ProductImage::imageThumb($productId);
        }, 10);
        return $imageThumb;
    }

    public function afterDelete()
    {
        ProductCategory::deleteAll(['productId' => $this->id]);
        FileHelper::removeDirectory(\Yii::getAlias("@webroot")."/uploads/".$this->id."/");
        parent::afterDelete();
    }
}
