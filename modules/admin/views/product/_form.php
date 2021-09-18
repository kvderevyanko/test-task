<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $category \app\models\Category */
/* @var $productCategories array */

?>
<div class="product-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-9"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'price')->input('integer') ?></div>
        <div class="col-sm-12"><?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'editorOptions' => [
                    'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                    'inline' => false, //по умолчанию false
                    'height' => 300
                ],
            ]) ?></div>
        <hr class="col-sm-12"/>
        <div class="col-sm-12">
            <h4>Категории:</h4>
            <div class="row">
                <?php foreach (\app\models\Category::allCategories() as $category): ?>
                    <div class="col-sm-6">
                        <label>
                            <?= Html::checkbox('Categories[' . $category->id . ']', array_key_exists($category->id, $productCategories)) ?>
                            <?= $category->name ?>
                            <span class="badge"
                                  style="background-color: <?= $category->color ?>"> &nbsp;  &nbsp; </span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <hr class="col-sm-12"/>
        <div class="col-sm-12"><?= $form->field($model, 'active')->checkbox() ?></div>
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
