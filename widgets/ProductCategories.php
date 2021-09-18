<?php

namespace app\widgets;

use app\models\Category;
use app\models\ProductCategory;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Отображение категорий продуктов
 */
class ProductCategories extends Widget
{
    //Id продукта
    public $productId;
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $productId = $this->productId;

        if (!$productId) {
            return '';
        }
        $key = ['ProductCategoriesWidget', $productId];
        $categories = Yii::$app->cache->getOrSet($key, function () use ($productId) {
            $categories = Category::find()
                ->where([ProductCategory::tableName().'.productId' => $productId])
                ->leftJoin(ProductCategory::tableName(), Category::tableName().'.id = '.ProductCategory::tableName().'.categoryId')
                ->all();
            return ArrayHelper::toArray($categories);
        }, 5);

        if (count($categories) < 1) {
            return '';
        }
        return $this->render('product-categories', ['categories' => $categories]);
    }
}
