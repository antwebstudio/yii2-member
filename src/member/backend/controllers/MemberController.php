<?php
namespace ant\member\backend\controllers;

use Yii;
use ant\web\Wizard;
use ant\user\models\User;
use ant\user\models\UserSearch;

class MemberController extends \yii\web\Controller {
    public function actionIndex() {
        $model = new UserSearch;
        $dataProvider = $model->search(\Yii::$app->request->queryParams);

        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    public function actionSubscription($id, $type = null) {
		$user = User::findOne($id);
		
		if (!isset($user)) throw new \yii\web\NotFoundHttpException('User not exist. ');
		
		$selectedPackageId = Yii::$app->request->post('radioButtonSelection');

        if ($post = Yii::$app->request->post() && Wizard::getCurrentStep() == 3) {
			$bundle = $user->subscribeMembershipPackage($selectedPackageId);
			$invoice = $bundle->invoice;

			if ($invoice) {
				$invoice->markAsPaid();
				
				return $this->redirect($invoice->adminPanelRoute);
			} else {
				throw new \Exception('Failed to create invoice. ');
			}
		}

        return $this->render($this->action->id, [
			'user' => $user,
			'selectedPackageId' => $selectedPackageId ?? null,
        ]);
    }
}