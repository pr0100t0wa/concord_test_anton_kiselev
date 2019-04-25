<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setAttribute('created_at', date('Y-m-d H:i:s'));

        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $transaction=$model->db->beginTransaction();
            $model->save(false);
            if($model->id){
                $photo = UploadedFile::getInstance($model, 'photo');
                if ($photo) {
                    // store the source file name
                    $ext = end((explode(".", $photo->name)));
                    $path = Yii::getAlias('@webroot') . $model->photoPath . $model->id . ".{$ext}";
                    $model->photo = Yii::getAlias('@web') . $model->photoPath . $model->id . ".{$ext}";
                    $photo->saveAs($path);
                }
                if ($model->save(true)){
                    $transaction->commit();
                }elseif(isset($path)){
                    unlink($path);
                }
                return $this->redirect(['view', 'id'=>$model->id]);
            }else{
                $transaction->rollBack();
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->updated_at)) {
                $model->setAttribute('updated_at', date('Y-m-d H:i:s'));
            }
            if (empty($model->photo)){
                $model->setAttribute('photo', $model->oldRecord->photo);
            }
            $photo = UploadedFile::getInstance($model, 'photo');
            if ($photo) {
                // store the source file name
                $ext = end((explode(".", $photo->name)));


                $path = Yii::getAlias('@webroot') . $model->photoPath . $model->id . ".{$ext}";
                $model->photo = Yii::getAlias('@web') . $model->photoPath . $model->id . ".{$ext}";;
            }
            if($model->save()){
                if ($photo) {
                    $photo->saveAs($path);
                }
                return $this->redirect(['view', 'id'=>$model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
