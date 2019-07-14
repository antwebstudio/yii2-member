<?php
namespace ant\member\behaviors;

use ant\member\models\Member;

class MemberBehavior extends \yii\base\Behavior {
	protected $_membership;
	
	public function getMembership() {
		return $this->owner->hasOne(Member::class, ['user_id' => 'id']);
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
		if (isset($this->_membership)) {
			return true;
		} else{
			$this->_membership = $this->getMembership()->one();
			if (isset($this->_membership)) {
				return true;
			}
		}
		return false;
	}
}