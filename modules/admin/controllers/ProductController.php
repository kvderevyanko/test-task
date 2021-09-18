<?php

namespace app\modules\admin\controllers;

use app\models\Product;
use app\models\ProductCategory;
use app\models\ProductImage;
use app\modules\admin\models\ProductSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'image-remove' => ['POST'],
                        'image-default' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $this->saveCategories($model->id, $this->request->post('Categories'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $this->saveCategories($id, $this->request->post('Categories'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $productCategories = ProductCategory::findAll(['productId' => $id]);
        $productCategories = ArrayHelper::map($productCategories, 'categoryId', 'productId');
        return $this->render('update', [
            'model' => $model,
            'productCategories' => $productCategories
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Удаление изображений
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionImageRemove(){
        $id = \Yii::$app->request->post('id');
        $image = ProductImage::findOne($id);
        if($image && $image->delete()) {
            return 1;
        }

        return 0;
    }

    public function actionImageDefault ($id){
        $product = $this->findModel($id);
        $imageId = \Yii::$app->request->post('imageId');
        if($imageId) {
            ProductImage::updateAll(['default' => false], ['productId' => $id]);
            ProductImage::updateAll(['default' => true], ['id' => $imageId]);
        }
    }

    /**
     * Список изображений
     * @param $id
     * @return string
     */
    public function actionImagesList($id){
        $images = ProductImage::findAll(['productId' => $id]);
        return $this->renderPartial('_images-list', ['id' => $id, 'images' => $images]);
    }

    /**
     * Загрузка изображения для продукта
     * @param $id
     * @return int
     * @throws \yii\base\Exception
     */
    public function actionImageUpload($id){
        $product = $this->findModel($id);
        $imageFile = UploadedFile::getInstanceByName('file');
        $directory = \Yii::getAlias("@webroot")."/uploads/".$id."/";
        if ($imageFile) {
            BaseFileHelper::createDirectory($directory);

            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;

            $filePath = $directory . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $thumb = $directory.'thumb_'.$fileName;
                Image::crop($filePath, 200, 200, [100, 100])
                    ->save($thumb, ['quality' => 70]);

                Image::frame($filePath, 0, '666', 0)
                    ->thumbnail(new Box(600,800))
                    ->save($filePath, ['quality' => 70]);

                $file = new ProductImage();
                $file->productId = $id;
                $file->file = $fileName;
                if($file->save()) {
                    return 1;
                }

            }
        }
        return 0;
    }

    /**
     * Сохраняем категории для продукта
     * @param int $id
     * @param array|null $categories
     */
    private function saveCategories($id, $categories){
        ProductCategory::deleteAll(['productId' => $id]);
        if(is_array($categories)) {
            foreach ($categories as $categoryId => $value) {
                $productCategory = new ProductCategory();
                $productCategory->productId = $id;
                $productCategory->categoryId = $categoryId;
                $productCategory->save();
            }
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
