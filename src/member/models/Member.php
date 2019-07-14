<?php

namespace ant\member\models;

use Yii;
use common\modules\user\models\User;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string $renewed_at
 * @property string $expire_at
 *
 * @property User $user
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
    }
	
	public function behaviors() {
		return [
			[
				'class' => 'ant\member\behaviors\Expirable',
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['renewed_at', 'expire_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'renewed_at' => 'Renewed At',
            'expire_at' => 'Expire At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
