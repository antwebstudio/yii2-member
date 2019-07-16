<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use \common\helpers\Date;
use yii\widgets\ActiveForm;
use common\modules\subscription\models\SubscriptionPackage;
use kartik\select2\Select2;
use common\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\casey\models\SpecificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->blocks['content-header'] = '';
$this->title = 'Member';
$this->context->layout = '/member-dashboard';
$this->params['breadcrumbs'][] = $this->title;

$lastestSubscription = count($dataProvider->getModels()) ? $dataProvider->getModels()[0] : null;
?>

<div class="specification-index">

	<?php if (!\Yii::$app->user->can('company')): ?>
	<div class="patternHeader" style="margin-bottom: 10px;">
		<?php if (isset($lastestSubscription)): ?>
			<p>MEMBERSHIP EXPIRED ON:<strong><?= (new Date($lastestSubscription->expire_date))->format('d / m / Y') ?></strong></p>
		<?php else: ?>
			<p>&nbsp;</p>
		<?php endif; ?>
		
		<?php $modal = Modal::begin(['header' => '<strong>Payment Detail</strong>', 'toggleButton' => [
			'label' => Yii::t('app', ($lastestSubscription ? 'RENEW' : 'SUBSCRIBE')),
			'class' => 'renew links',
			'tag' => 'a',
			'style' => 'cursor:pointer;',
			]]);
			$form = ActiveForm::begin(['action' => ['/member/member/payment']]); 
		?>

			<?= GridView::widget([
		        'dataProvider' => $dataProvider2,
				'layout' => "{items}\n{pager}",
		        'columns' => [
		            ['class' => 'yii\grid\SerialColumn'],
		            'name',
		            //'subscription_identity',
		            [
		            	'label' => 'Subscripton Days',
		            	'attribute' => 'subscriptionPackageItems.subscription_days',
		            	'value' => function($model) {
		            		return isset($model->subscriptionPackageItems[0]->subscription_days) ? $model->subscriptionPackageItems[0]->subscription_days : '';
		            	}
		            ],
		            [
		            	'headerOptions' => ['style' => ['width' => '25%']],
		            	'label' => 'Price',
		            	'attribute' => 'price',
		            	'value' => function($model) {
		            		return '<div><div>RM</div><div>' . $model->price . '</div></div>';
		            	},
		            	'format' => 'html'
		            ],
		            [
		            	'class' => 'yii\grid\RadioButtonColumn',
		            	'radioOptions' => function ($model, $key, $index, $column) {
				            return [
				                'value' => $model['id'],
				                'checked' => $index == 0,
				            ];
         				}
		            ],
		        ],
    		]); ?>

			<div class="row">
				<?php 
					$items = 
					[
						'bankWire' => Html::img($this->theme->getUrl('images/ipay88.png'), ['style' => 'text-align:center; height: 80px']),
						//'paypal' => Html::img($this->theme->getUrl('images/paypal.png'), ['style' => 'text-align:center; height: 80px'])
					];
					$selected = 'bankWire';

					echo Html::radioList("paymentMethod", $selected, $items, [
					    'item' => function ($index, $label, $name, $checked, $value) {
					        return Html::radio($name, $checked, [
					            'value' => $value,
					            'label' => $label,
					            'labelOptions' => ['class' => 'col-sm-6 text-center'],
					        ]);
					    },
					]);
				?>
			</div>
		<div class="form-group text-right">
	        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>
		<?php Modal::end() ?>
		
	</div>
	<?php endif ?>
	
	<p class="paymentTitle">PAYMENT MADE</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'label' => 'Payment ID',
            //     'attribute' => 'invoice_id',
            // ],
            [
                'label' => 'Purchase Date',
                'attribute' => 'created_at',
            ],
            [ 
                'attribute' => 'expire_date',
                'value' => function($model) {
                    return date('Y-m-d', strtotime($model->expire_date));
                }
            ],
			[
				'attribute' => 'isExpired',
				'label' => 'Status',
				'value' => function($model) {
					return $model->isExpired ? 'Expired' : 'Active';
				}
			],
            // [
            //     'attribute' => 'price',
            // ]
            //'subscription_identity',
            'price',
            // 'price',
            // 'updated_by',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	
</div>
