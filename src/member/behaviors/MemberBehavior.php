<?php
namespace ant\member\behaviors;

use ant\helpers\DateTime;
use ant\member\models\Member;
use ant\subscription\models\Subscription;
use ant\subscription\models\SubscriptionPackage;

class MemberBehavior extends \yii\base\Behavior {
	public $subscriptionIdentity = 'member';
	protected $_membership;
	
	protected function _getMembership() {
		if (!isset($this->_membership)) {
			$this->_membership = $this->getMembership()->one();
		}
		return $this->_membership;
	}
	
	public function getMembership() {
		return $this->owner->hasOne(Member::class, ['user_id' => 'id']);
	}

	public function getMembershipStatusText() {
		return $this->isMember ? 'Active Member' : 'Membership Expired';
	}
	
	public function getMembershipDepositAmount() {
		$subscription = Subscription::find()->currentlyActiveForUser($this->owner->id)
			->type($this->subscriptionIdentity)
			//->isPaid()
			->orderBy('expire_at DESC')
			->one();
		
		return $subscription->subscriptionPackage->options['depositAmount'];
	}
	
	protected function getLastExpireAndActiveAndPaidSubscription($identity = null) {
		return Subscription::find()->currentlyActiveForUser($this->owner->id)
			->type($identity)
			->isPaid()
			->orderBy('expire_at DESC')
			->one();
	}
	
	protected function getLastExpireAndPaidSubscription($identity = null) {
		return Subscription::find()->ownedBy($this->owner->id)
			->type($identity)
			->isPaid()
			->orderBy('expire_at DESC')
			->one();
	}
	
	public function getMembershipExpireAt() {
		$subscription = $this->getLastExpireAndActiveAndPaidSubscription($this->subscriptionIdentity);
		
		if (isset($subscription)) {
			return $subscription->expire_at;
		} else if ($subscription = $this->getLastExpireAndPaidSubscription($this->subscriptionIdentity)) {
			return $subscription->expire_at;
		} else if ($this->_getMembership() != null) {
			return $this->_getMembership()->expire_at;
		}
	}
	
	public function subscribeMembershipPackage($subscriptionPackageId) {
		//$transaction = \Yii::$app->db->beginTransaction();
		//try {
			$package = SubscriptionPackage::findOne($subscriptionPackageId);

			if (!isset($package)) throw new \Exception('Package "'.$subscriptionPackageId.'" is not exist. ');
			
			// If not expire yet, than extend from expire date, if already expired then extend from now.
			$extendFrom = $this->isMember ? $this->getMembershipExpireAt() : null;
			$bundle = $package->subscribe($this->owner, $extendFrom);
			
		//	$transaction->commit();
			return $bundle;
		//} catch (\Exception $ex) {
		//	$transaction->rollback();
		//	throw $ex;
		//}
	}
	
	public function activateMembership($days = 365) {
		$membership = $this->getMembership()->one();
		
		if (!isset($membership)) {
			$membership = new Member;
			$membership->user_id = $this->owner->id;
			$membership->setExpireAtAfterDays($days); 
			
			if (!$membership->save()) throw new \Exception('Failed to activate membership. ');
			
			return true;
		}
		
		throw new \Exception('Membership is already activated before. ');
	}
	
	public function getIsMember() {
		$expireAt = $this->getMembershipExpireAt();
		if (!isset($expireAt)) return false;
		
		return $expireAt > new DateTime();
	}
}