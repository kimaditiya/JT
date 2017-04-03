<?php

namespace app\controllers;

use Yii;
use app\models\TOrder;
use app\models\TOrderSearch;
use app\models\TOrderDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TOrderController implements the CRUD actions for TOrder model.
 */
class TOrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TOrder model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TOrder();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('TOrder');

            $postTgl = $request['orderTgl'];
            $saveTgl = date('Y-m-d',strtotime($postTgl));
            $seconds = date('h:i:s');
            $userid = Yii::$app->user->id;
            
            $model->orderTgl = $saveTgl.' '.$seconds;
            $model->userId = $userid;
            $model->save();
            return $this->redirect(['detail','id' => $model->orderId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->orderId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDetail($id)
    {
        $searchModel = new TOrderSearch();
        $dataProvider = $searchModel->searchDetail($id);

        return $this->render('detail', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreateDetail()
    {
        $model = new TOrderDetail();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('TOrderDetail');

            
            $model->save();
            return $this->redirect(['detail','id' => $model->orderId]);
        } else {
            return $this->render('create-detail', [
                'model' => $model,
            ]);
        }
    }
}
