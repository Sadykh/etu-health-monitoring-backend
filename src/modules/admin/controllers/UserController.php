<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

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

    public function actionChangeRole(int $user_id, int $role_id) {
        $user = $this->findModel($user_id);
        if ($user->isRoleAdmin()) {
            Yii::$app->session->addFlash('error', 'Нельзя изменять роль администратора');
        }
        $user->role_id = $role_id;
        $user->generateAuthKey();
        $user->save();
        Yii::$app->session->addFlash('success', 'Роль успешно изменена');
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
