<?php 
namespace member;

use UnitTester;
use common\helpers\DateTime;

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
		
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membership->expire_at);
    }
	
    public function testGetIsMember(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		$I->assertFalse($user->isMember);
		
		$user->activateMembership(2);
		
		$I->assertTrue($user->isMember);
    }
	
    public function testRenewDays(UnitTester $I)
    {
		$user = $I->grabFixture('user')->getModel(0);
		
		$user->activateMembership(2);
		$user->membership->renewDays(2);
		
		$expectedDate = (new DateTime)->addDays(4)->setTimeAsEndOfDay();
		
		$I->assertEquals($expectedDate->format(DateTime::FORMAT_MYSQL), $user->membership->expire_at);
    }
	
	public function _fixtures()
    {
        return [
            'user' => [
                'class' => \tests\fixtures\UserFixture::className(),
                'dataFile' => '@tests/fixtures/data/user.php'
            ],
        ];
    }
}
