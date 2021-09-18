<?php
use yii\helpers\Html;
use yii\data\Pagination;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $products array */
/* @var $product \app\models\Product */

/* @var $pages Pagination */


$this->title = 'Главная';
?>

<?php ActiveForm::begin([
    'method' => 'get',
    'action' => ['index'],
    'id' => 'filterForm'
])?>
<div class="row">
    <div class="col-sm-6">
        <label>Сортировка</label>
        <?=Html::dropDownList('productSort',
            Yii::$app->request->get('productSort'), [
                'sort_price_asc' => 'Сортировать по возрастанию цены',
                'sort_price_desc' => 'Сортировать по  убыванию  цены',
        ], ['class' => 'form-control filter-list', 'prompt' => 'Сортировка по умолчанию'])?>
    </div>
    <div class="col-sm-6">
        <label>Категория</label>
        <?=Html::dropDownList('productCategory',
            Yii::$app->request->get('productCategory'),
            \app\models\Category::categoryList(),
            ['class' => 'form-control filter-list', 'prompt' => 'Все категории'])?>
    </div>
    <hr class="col-sm-12">
</div>
<?php ActiveForm::end()?>

<div class="row"></div>
<?php if (count($products) > 0): ?>
    <div class="row">
        <?php foreach ($products as $product):?>
        <div class="col-sm-4">
            <div class="product-list-item">
                <h6><?=Html::a($product->name, ['view', 'id' => $product->id])?></h6>
                <?=\app\widgets\ProductCategories::widget(['productId' => $product->id])?><br><br>
                <?=Html::a("Стоимость: ".$product->price, ['view', 'id' => $product->id], ['class' => 'btn btn-info'])?>
                <?=Html::a(
                    Html::img($product->thumb(), ['class' => 'img-responsive img-thumbnail']),
                    ['view', 'id' => $product->id],
                    ['class' => 'list-single-item']
                )?>

            </div>

        </div>
        <?php endforeach;?>
    </div>
    <?php
// display pagination
    echo \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>
<?php else: ?>
    <h1>Нет продуктов</h1>
<?php endif; ?>

<?php
$this->registerJs(<<<JS
$(".filter-list").on('change', function(){
    $("#filterForm").submit();
})

JS
);