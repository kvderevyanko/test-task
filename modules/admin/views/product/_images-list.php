<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $images array */
/* @var $image \app\models\ProductImage */
/* @var $id int */

?>
<div class="row">
<?php foreach ($images as $image): ?>
    <div class="col-sm-3 img-product-prev" data-id="<?=$image->id?>">
        <button type="button" class="close removeImage" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <label>
            <?=Html::radio('default', $image->default, ['class' => 'defaultImage'])?> По умолчанию
        </label>
        <?=Html::img(\app\models\ProductImage::imageThumb($id, $image->file), ['class' => 'img-responsive img-thumbnail'])?>
    </div>
<?php endforeach; ?>
</div>