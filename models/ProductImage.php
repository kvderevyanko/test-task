<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "productImage".
 *
 * @property int $id
 * @property int $productId
 * @property string $file
 * @property bool|null $default
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productImage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'file'], 'required'],
            [['productId'], 'integer'],
            [['default'], 'boolean'],
            [['file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Product ID',
            'file' => 'File',
            'default' => 'Default',
        ];
    }


    /**
     * @param $productId
     * @param $fileName
     * @return array
     */
    public static function imageSize($productId, $fileName)
    {
        $size = [
            'w' => 0,
            'h' => 0,
        ];
        $file = \Yii::getAlias("@webroot") . "/uploads/" . $productId . "/" . $fileName;
        if (file_exists($file)) {
            try {
                $fileSize = getimagesize($file);
                if ($fileSize [0] > 0) {
                    $size['w'] = $fileSize [0];
                }

                if ($fileSize [1] > 0) {
                    $size['h'] = $fileSize [1];
                }
            } catch (\Exception $e) {
            }
        }
        return $size;
    }

    /**
     * Возвращает урл на превьюшку для основной картинки, или на саму картинку
     * @param $productId
     * @param string $fileName
     * @param bool $thumb
     * @return string
     */
    public static function imageThumb($productId, $fileName = "", $thumb = true)
    {
        if($fileName && $thumb) {
            return \Yii::getAlias("@web") . "/uploads/" . $productId . "/thumb_" . $fileName;
        }

        if($fileName) {
            return \Yii::getAlias("@web") . "/uploads/" . $productId . "/" . $fileName;
        }
        return \Yii::getAlias("@web") . "/images/default.jpg";
    }

    public function beforeSave($insert)
    {
        //Проверяем, есть ли значение по умолчанию, и если нет - то назначаем его
        if (!self::find()->where(['productId' => $this->productId, 'default' => true])->count()) {
            $this->default = true;
        }
        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        //При удалении изображения удаляем сам файл с превьюшкой и если оно было по умолчанию, то назначаем другое
        $directory = \Yii::getAlias("@webroot") . "/uploads/" . $this->productId . "/";
        @unlink($directory . "thumb_" . $this->file);
        @unlink($directory . $this->file);

        if ($this->default) {
            $image = self::findOne(['productId' => $this->productId]);
            if ($image) {
                $image->default = true;
                $image->save();
            }
        }
    }
}
