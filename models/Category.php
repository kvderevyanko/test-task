<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'color'], 'string', 'max' => 255],
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
            'color' => 'Цвет',
        ];
    }

    /**
     * Возвращаем список категорий для выпадающего списка
     * @return array
     */
    public static function categoryList(){
        return ArrayHelper::map(self::allCategories(), 'id', 'name');
    }

    /**
     * Возвращает полный список всех категорий
     * @return array
     */
    public static function allCategories(){
        $categories = self::find()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::index($categories, 'id');
    }

    public function afterDelete()
    {
        ProductCategory::deleteAll(['categoryId' => $this->id]);
        parent::afterDelete();
    }
}
