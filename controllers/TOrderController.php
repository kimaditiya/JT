<?php

namespace app\controllers;

use Yii;
use app\models\TOrder;
use app\models\TOrderSearch;
use app\models\TOrderDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

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
            return $this->redirect(['create-detail','id' => $model->orderId]);
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
    
    protected function findOrderDetail($id)
    {
        if (($model = TOrderDetail::findOne($id)) !== null) {
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
            $orderDetailTgl = $request['orderDetailTglKerja'];
            $toDate = date('Y-m-d',strtotime($orderDetailTgl));
            $model->orderId = Yii::$app->request->post('orderId');
            $model->orderDetailTglKerja = $toDate;
            
            $model->save();
            return $this->redirect(['detail','id' => $model->orderId]);
        } else {
            return $this->render('create-detail', [
                'model' => $model,
            ]);
        }
    }

    public function actionWo()
    {
        $searchModel = new TOrderSearch();
        $dataProvider = $searchModel->searchWo(Yii::$app->request->queryParams);

        return $this->render('wo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUpdateRekan($id)
    {
        $this->layout ='blank';
        $model = $this->findOrderDetail($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(Yii::$app->request->post('back','/t-order/wo'));
        } else {
            return $this->render('_formUpdateRekan', [
                'model' => $model,
                'back'=> Yii::$app->request->referrer
            ]);
        }
    }
    
    public function actionPrintWo($id,$orderid) {
        // get your HTML raw content without any layouts or scripts
        
        $content = $this->renderPartial('renderwo',['id'=>$id,'orderid' => $orderid]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            'filename' => 'WO',
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Work Order'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Jago Tukang'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
