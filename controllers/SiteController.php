<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductCategory;
use app\models\ProductImage;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @param string $productSort
     * @param string $productCategory
     * @return string
     */
    public function actionIndex($productSort = "", $productCategory = "")
    {
        $query = Product::find()->andWhere([
            Product::tableName().'.active' => true,
        ])->orderBy([Product::tableName().'.id' => SORT_DESC]);

        if($productSort) {
            switch ($productSort) {
                case 'sort_price_asc':
                    $query->orderBy([Product::tableName().'.price' => SORT_ASC]);
                    break;
                case 'sort_price_decs':
                    $query->orderBy([Product::tableName().'.price' => SORT_DESC]);
                    break;
            }
        }

        if($productCategory) {
            $query->leftJoin(
                ProductCategory::tableName(),
                ProductCategory::tableName().'.productId = '.Product::tableName().'.id' )
            ->andWhere([ProductCategory::tableName().'.categoryId' => $productCategory]);
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $query->offset($pages->offset)
            ->select([
                Product::tableName().'.id',
                Product::tableName().'.name',
                Product::tableName().'.price',
                ])
            ->limit($pages->limit)
            ->all();

        $products = $query->all();


        return $this->render('index', ['products' => $products, 'pages' => $pages,]);
    }

    public function actionView($id)
    {
        $product = Product::findOne(['id' => $id, 'active' => true]);
        if($product === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $productImages = ProductImage::findAll(['productId' => $id]);
        return $this->render('view', ['product' => $product, 'productImages' => $productImages]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }



    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
