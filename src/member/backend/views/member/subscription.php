<?php
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use ant\subscription\models\SubscriptionPackage;

$dataProvider2 = new ActiveDataProvider([
	'query' => SubscriptionPackage::find()->andWhere(['subscription_identity' => 'member']),
]);
?>
<?php $form = \yii\widgets\ActiveForm::begin() ?>

	<?= \yii\grid\GridView::widget([
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
	]) ?>
	
	<?= Html::submitButton('Submit', ['class' => 'btn-primary']) ?>
<?php \yii\widgets\ActiveForm::end() ?>