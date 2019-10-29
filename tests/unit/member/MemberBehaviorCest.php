<?php 
namespace member;

use UnitTester;
use ant\helpers\DateTime;
use ant\payment\models\Invoice;
use ant\subscription\models\SubscriptionPackage;
use ant\subscription\models\SubscriptionPackageItem;

class MemberBehaviorCest
{
    public function _before(UnitTester $I)
    {
    }

    // tests
    public function testActivateMembership(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$user->activateMembership(2);
		
		$expectedDate = (new DateTime)->addDays(2)->setTimeAsEndOfDay();
		
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membershipExpireAt);
    }
	
	public function testSubscribeMembershipPackage(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$package = $this->createPackage();
		
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		
		$I->assertFalse($user->isMember);
		
		$expectedDate = (new DateTime)->addDays(365)->setTimeAsEndOfDay();
		
		$invoice->payManually($invoice->dueAmount);
		
		$invoice = Invoice::findOne($invoice->id);
		$I->assertTrue($invoice->id > 0);
		$I->assertTrue($user->isMember);
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membershipExpireAt);
		
		// Make it expire
		$subscription = $bundle->getSubscriptions()->one();
		$subscription->setExpireAtDays(-5);
		if (!$subscription->save()) throw new \Exception(print_r($subscription, 1));
		
		$I->assertTrue(isset($bundle->package_id));
		$I->assertTrue(isset($subscription->package_id));
		$I->assertFalse($user->isMember);
		
		$expectedDate = (new DateTime)->addDays(-5)->setTimeAsEndOfDay();
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membershipExpireAt);
    }
	
	// If subscribe after expire, the expiry date of membership will be started from the date of subscription
	public function testSubscribeMembershipPackageAgainAfterExpired(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$package = $this->createPackage();
		
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		
		//$expectedDate = (new DateTime)->addDays(365)->setTimeAsEndOfDay();
		
		$invoice->payManually($invoice->dueAmount);
		
		// Make it expire
		$subscription = $bundle->getSubscriptions()->one();
		$subscription->setExpireAtDays(-5);
		if (!$subscription->save()) throw new \Exception(print_r($subscription, 1));
		
		// Subscribe the package again after expire
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		
		$I->assertFalse($user->isMember);
		
		$expectedDate = (new DateTime)->addDays(365)->setTimeAsEndOfDay();
		
		$invoice->payManually($invoice->dueAmount);
		
		$I->assertTrue($user->isMember);
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membershipExpireAt);
	}
	
	// If subscribe before expire, the expiry date of membership will be started from the expiry date of subscription
	public function testSubscribeMembershipPackageAgainBeforeExpired(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$package = $this->createPackage();
		
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		
		$expectedDate = (new DateTime)->addDays(365)->setTimeAsEndOfDay();
		
		$invoice->payManually($invoice->dueAmount);
		
		// Subscribe the package again after expire
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		
		$I->assertTrue($user->isMember);
		
		$expectedDate = (new DateTime)->addDays(365 * 2)->setTimeAsEndOfDay();
		
		$invoice->payManually($invoice->dueAmount);
		
		$I->assertTrue($user->isMember);
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membershipExpireAt);
	}
	
    public function testGetIsMember(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		$I->assertFalse($user->isMember);
		
		$user->activateMembership(2);
		
		$I->assertTrue($user->isMember);
    }
	
	public function testMembershipExpireAtNull(UnitTester $I) {
		// Create subscription for user A
		$user = $I->grabFixture('user')->getModel(0);
		$package = $this->createPackage();
		
		$bundle = $user->subscribeMembershipPackage($package->id);
		$invoice = $bundle->invoice;
		$invoice->payManually($invoice->dueAmount);
		
		// User B should not have subscription data
		$user = $I->grabFixture('user')->getModel(1);
		$I->assertFalse(isset($user->membershipExpireAt));
	}
	
    public function testRenewDays(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$user->activateMembership(2);
		$user->membership->renewDays(2);
		
		$expectedDate = (new DateTime)->addDays(4)->setTimeAsEndOfDay();
		
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membership->expire_at);
    }
	
	protected function createPackage() {
		$package = new SubscriptionPackage;
		$package->attributes = [
			'name' => 'test package',
			'subscription_identity' => 'member',
			'price' => 360,
		];
		
		if (!$package->save()) throw new \Exception('Failed. '.print_r($package->errors, 1));
		
		$packageItem = new SubscriptionPackageItem();
		$packageItem->attributes = [
			'subscription_identity' => 'member',
			'name' => 'test subscription item',
			'unit' => 0,
			'valid_period' => 365,
			'valid_period_type' => 3,
			'content_valid_period' => 365,
			'content_valid_period_type' => 3,
		];
		$packageItem->package_id = $package->id;
		
		if (!$packageItem->save()) throw new \Exception('Failed. '.print_r($packageItem->errors, 1));
		
		return $package;
	}
	
	public function _fixtures()
    {
        return [
            'user' => [
                'class' => \tests\fixtures\UserFixture::className(),
                'dataFile' => '@tests/fixtures/data/user.php'
            ],
            'userProfile' => [
                'class' => \tests\fixtures\UserProfileFixture::className(),
                'dataFile' => '@tests/fixtures/data/user_profile.php'
            ],
			'subscription' => [
				'class' => \tests\fixtures\SubscriptionFixture::class,
			],
        ];
    }
}
