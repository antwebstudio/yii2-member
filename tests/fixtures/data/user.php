<?php

return [
    [
        'id' => 1,
        'username' => 'webmaster',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
		//'password' => '1234',
		//'password_encryption' => '1234',
        'password_hash' => '$2y$13$VnLT62YdhKy.RHHDLN0MEezggGKZZKQFmPVu5d.5ODTwBGSx7WcW6',
        //'access_token' => 'yNxS5kDGC8GAXXbkewJ8Rf2qY5rEdy01Odtgt8vO',
        'created_at' => new \yii\db\Expression('NOW()'),
        'updated_at' => new \yii\db\Expression('NOW()'),
		'registered_ip' => '127.0.0.1',
        'email' => 'webmaster@example.org',
    ],
    [
        'id' => 2,
        'username' => 'manager',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
		//'password' => '1234',
		//'password_encryption' => '1234',
        'password_hash' => '$2y$13$N785qekuqJzo2CsP7K0g/.KtWZ8SwZtqITdTPrHBFITYjX9WYnl5i',
        //'access_token' => 'JJcEWMSq4IFxhEMUpYoJXx5ZNDW33t4OFS5tXSlP',
        'created_at' => new \yii\db\Expression('NOW()'),
        'updated_at' => new \yii\db\Expression('NOW()'),
		'registered_ip' => '127.0.0.1',
        'email' => 'manager@example.org'
    ],
    'user' => [
        'id' => 3,
        'username' => 'user',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
		//'password' => '1234',
		//'password_encryption' => '1234',
        'password_hash' => '$2y$13$tJKZTKUQ5DBFWNkkjd0goup.p8Tx5d9Mj/wWL6Vv8/Q038zk7g5.6',
        //'access_token' => 'Q1M6dPrGpzBWOnGf2NbkEMLntSCDhchuVKDGOUWC',
        'created_at' => new \yii\db\Expression('NOW()'),
        'updated_at' => new \yii\db\Expression('NOW()'),
		'registered_ip' => '127.0.0.1',
        'email' => 'user@example.org',
		'status' => \common\modules\user\models\User::STATUS_ACTIVATED,
    ],
    'inactiveUser' => [
		// Inactivated user
        'id' => 4,
        'username' => 'user2',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
		//'password' => '1234',
		//'password_encryption' => '1234',
        'password_hash' => '$2y$13$tJKZTKUQ5DBFWNkkjd0goup.p8Tx5d9Mj/wWL6Vv8/Q038zk7g5.6',
        //'access_token' => 'Q1M6dPrGpzBWOnGf2NbkEMLntSCDhchuVKDGOUWC',
        'created_at' => new \yii\db\Expression('NOW()'),
        'updated_at' => new \yii\db\Expression('NOW()'),
		'registered_ip' => '127.0.0.1',
        'email' => 'user2@example.org',
    ],
];