<?php
use yii\helpers\Html;
use app\models\ProductImage;

/* @var $this yii\web\View */
/* @var $product \app\models\Product */
/* @var $productImages array */
/* @var $productImage \app\models\ProductImage */

$this->title = $product->name;
?>
<div class="site-error">

    <h5><?= Html::encode($this->title) ?></h5>
    <div class="row">
        <div class="col-sm-4 text-center">
            <?=\app\widgets\ProductCategories::widget(['productId' => $product->id])?>
            <br><br>
            <?=Html::img($product->thumb(), ['class' => 'img-responsive img-thumbnail'])?>
        </div>
        <div class="col-sm-8">
            <?php if(count($productImages) > 0): ?>
            <div class="row productGallery">
                <?php foreach ($productImages as $productImage): ?>
                <?php $imageSize = ProductImage::imageSize($product->id, $productImage->file)?>
                <div class="col-sm-3 prevImage">
                    <?=Html::img(ProductImage::imageThumb($product->id, $productImage->file), [
                        'data-big' => ProductImage::imageThumb($product->id, $productImage->file, false),
                        'class' => 'img-responsive img-thumbnail',
                        'w' => $imageSize['w'],
                        'h' => $imageSize['h'],
                    ])?>
                </div>
                <?php endforeach; ?>
                <hr class="col-sm-12">
            </div>
            <?php endif; ?>
            Стоимость: <?=$product->price?>
            <br><br>
            <?=$product->description?>
        </div>
    </div>



</div>
<?=$this->render('_photoswipe')?>