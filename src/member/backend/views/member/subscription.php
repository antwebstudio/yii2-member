<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use ant\web\Wizard;
use ant\subscription\models\SubscriptionPackage;

$dataProvider2 = new ActiveDataProvider([
	'query' => SubscriptionPackage::find()->andWhere(['subscription_identity' => 'member']),
]);

if (isset($selectedPackageId)) {
	$selectedPackage = SubscriptionPackage::findOne($selectedPackageId);
}
?>
<?php $this->beginBlock('actions') ?>
	<a class="float-right btn btn-dark" href="<?= Url::to(['/library/backend/member']) ?>">Back</a>
<?php $this->endBlock() ?>

<?php if (!isset($user->profile->contact)): ?>
	<div class="alert alert-danger">
		User do not have contact information, please update the contact information before the user can subscribe to any package.
	</div>
	<a href="<?= Url::to(['/user/backend/user/update', 'id' => $user->id]) ?>" class="btn btn-primary">Update user contact</a>
<?php else: ?>
	<?= \yii\widgets\DetailView::widget([
		'model' => $user,
		'attributes' => [
			'email',
			'membershipExpireAt',
			[
				'attribute' => 'isMember',
				'format' => 'html',
				'value' => '<span class="badge badge-'.($user->isMember ? 'success' : 'warning').'">'.$user->membershipStatusText.'</span>',
			],
		],
	]) ?>
	<?php $form = \yii\widgets\ActiveForm::begin() ?>
		<?= Wizard::formFields() ?>

		<?php if (Wizard::getCurrentStep() == 2): ?>
			<div class="p-3 p-md-5">
				<div><b>Selected Package: </b><?= $selectedPackage->name ?></div>
				<div><b>Price: </b><?= $selectedPackage->price ?></div>
			</div>
		<?php else: ?>
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
		<?php endif ?>
		
		<?= Wizard::backButton() ?>

		<?= Html::submitButton('Submit', ['class' => 'btn-primary']) ?>
	<?php \yii\widgets\ActiveForm::end() ?>
<?php endif ?>