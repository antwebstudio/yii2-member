<?php
namespace ant\member\validators;

use common\modules\user\models\User;

class MembershipValidator extends \yii\validators\Validator {
    public $message = 'User is not member or membership expired.';

    public function init() {
       
    }
    
    public function validateAttribute($model, $attribute) {
        $user = User::findOne($model->{$attribute});

        $message = $this->message;

        if (isset($user)) {
            $valid = $user->isMember;

            if (!$valid) {
                $model->addError($attribute, $message);
            }
        } else {
            throw new \Exception('User with ID: '.$model->{$attribute} .' it not exist. ');
        }
    }
}