<?php
namespace ant\member\backend\controllers;

use Yii;
use ant\user\models\User;

class MemberController extends \yii\web\Controller {
	public function actionIndex() {
		return $this->render($this->action->id, [

		]);
	}	
	
    public function actionSubscription($id, $type = null) {
		$user = User::findOne($id);
		
		if (!isset($user)) throw new \yii\web\NotFoundHttpException('User not exist. ');
		
        if ($post = Yii::$app->request->post()) {
			$id = Yii::$app->request->post('radioButtonSelection');
			$bundle = $user->subscribeMembershipPackage($id);
			$invoice = $bundle->invoice;

			if ($invoice) {
				return $this->redirect($invoice->route);
			} else {
				throw new \Exception('Failed to create invoice. ');
			}
		}

        return $this->render($this->action->id, [
        ]);
    }
}