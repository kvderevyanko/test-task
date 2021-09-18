<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот продукт?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <hr>
    <?=\app\widgets\ProductCategories::widget(['productId' => $model->id])?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:html',
            'price',
            'active:boolean',
        ],
    ]) ?>
<hr>
    <h4>Загрузка изображения</h4>
    <div class="row">
        <div class="col-sm-4">
            <div id="imageUploadBlock">
                <button type="button" class="close" aria-label="Close" id="cancelUploadImage"><span aria-hidden="true">&times;</span></button>
                <div class="progress image-upload-progress">
                    <div class="progress-bar progress-bar-striped bg-info" id="progressImageUpload" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
            <label class="btn btn-info" id="imageUploadBtn">
                Выбрать изображение (max 1Mb) <input type="file"  accept="image/jpeg" id="selectImageBtn" hidden >
            </label>
        </div>
        <div class="col-sm-8" id="imagesListBlock"></div>
    </div>
</div>
<script>
    let urlImageUpload = "<?=Url::to(['image-upload', 'id' => $model->id])?>";
    let urlImageDefault = "<?=Url::to(['image-default', 'id' => $model->id])?>";
    let urlImageRemove = "<?=Url::to(['image-remove'])?>";
    let urlImagesList = "<?=Url::to(['images-list', 'id' => $model->id])?>";
</script>