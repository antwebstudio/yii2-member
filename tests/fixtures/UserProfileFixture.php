<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class UserProfileFixture extends ActiveFixture
{
    public $modelClass = 'common\modules\user\models\UserProfile';
	public $depends = [
        'tests\fixtures\UserFixture',
        'tests\fixtures\ContactFixture',
    ];
}
