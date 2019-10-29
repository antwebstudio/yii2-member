<?php
use yii\helpers\Html;
use ant\user\models\User;

$dataProvider = new \yii\data\ActiveDataProvider([
	'query' => User::find(),
]);
?>
<?= \yii\grid\GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'id',
		'username',
		'email',
		[
			'label' => 'IC Number',
			'value' => function($model) {
				$userIc = $model->getIdentityId()->andWhere(['type' => 'ic'])->one();
				return isset($userIc) ? $userIc->value : null;
			},
		],
		[
			'attribute' => 'fullname',
		],
        [
            'label' => 'Membership',
            'format' => 'html',
            'value' => function($model) {
                $isMember = $model->isMember;
                $class = $isMember ? 'label-success' : 'label-warning';
                $label = $isMember ? 'Member' : 'Non-member';
                $expireWord = $isMember ? 'Expire' : 'Expired';
                $text = $model->membershipExpireAt ? $expireWord.' at '.$model->membershipExpireAt : '';
                return '<span class="label '.$class.'">'.$label.'</span><p>'.$text.'</p>';
            }
        ],
        [
            'label' => 'Role',
			'value' => function($model) {
				$roles = [];
				foreach (\Yii::$app->authManager->getRolesByUser($model->id) as $role) {
					if (!in_array($role->name, ['guest'])) {
						$roles[] = $role->name;
					}
				}
				return implode(', ', $roles);
			},
        ],
        [
			'class' => 'yii\grid\ActionColumn',
            'template' => '{renew}',
			'buttons' => [
				'renew' => function($url, $model, $key) {
					$url = ['/member/backend/member/subscription', 'id' => $model->id];
					return Html::a('Renew', $url, ['class' => 'btn-sm btn btn-default']);
				},
			],
		],
	],
]) ?>