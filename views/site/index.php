<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $products array */
/* @var $product \app\models\Product */

/* @var $pages Pagination */

use yii\data\Pagination;

$this->title = 'Главная';
?>

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
