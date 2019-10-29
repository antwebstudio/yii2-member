<?php

namespace ant\member\controllers;

use Yii;
use ant\casey\models\Specification;
use ant\order\models\Order;
use ant\payment\models\Payment;
use ant\contact\models\Contact;
use ant\casey\models\SpecificationSearch;
use ant\subscription\models\SubscriptionPackage;
use ant\subscription\models\Subscription;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * SpecificationController implements the CRUD actions for Specification model.
 */
class MemberController extends Controller
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
     * Lists all Specification models.
     * @return mixed
     */

    public function actionPayment(){
        //$transaction = Yii::$app->db->beginTransaction();
        if ($post = Yii::$app->request->post()) {
            if (isset($post) && isset($post['radioButtonSelection']) && isset($post['paymentMethod']) ) {
                $paymentMethod = $post['paymentMethod'];
                $id = $post['radioButtonSelection'];

                $user = Yii::$app->user->identity;
                $bundle = $user->subscribeMembershipPackage($id);
                $invoice = $bundle->invoice;

                if ($invoice) {
                    return $this->redirect(['/payment/default/pay', 'payMethod' => $paymentMethod, 'payId' => $invoice->id, 'cancelUrl' => Url::to(['/member/member/index']), 'type' => 'invoice']);
                } else {
                    throw new \Exception('Failed to create invoice. ');
                }
            } else {
                throw new \Exception("Missing Required parameters.", 1);
            }
        } else {
            throw new \Exception("Method Request is not expected.", 1);
        }
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Subscription::find()->ownedBy(Yii::$app->user->id)
                ->orderBy('expire_date DESC')->limit(10),
            'sort' => false,
        ]);
        $dataProvider->pagination = false;
        $dataProvider2 = new ActiveDataProvider([
            'query' => SubscriptionPackage::find()->andWhere(['subscription_identity' => 'Member']),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
        ]);
    }
}
