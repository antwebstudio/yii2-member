<?php
namespace ant\member\backend\controllers;

class DefaultController extends \yii\web\Controller {

    public function actionIndex($type = null) {

        return $this->render($this->action->id, [
        ]);
    }
}