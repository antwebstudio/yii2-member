<?php
namespace ant\member\behaviors;

use ant\helpers\DateTime;

class Expirable extends \yii\base\Behavior {
	public $expireAtAttribute = 'expire_at';
	
	public function renew($seconds) {
		$date = new DateTime($this->owner->{$this->expireAtAttribute});
		$date->addSeconds($seconds);
		$this->owner->{$this->expireAtAttribute} = $date;
		
		return $this->owner->save();
	}
	
	public function renewDays($days) {
		$date = new DateTime($this->owner->{$this->expireAtAttribute});
		$date->addDays($days)->setTimeAsEndOfDay();
		$this->owner->{$this->expireAtAttribute} = $date;
		
		return $this->owner->save();
	}
	
	public function setExpireAtAfterDays($days) {
		$date = new DateTime;
		$date->addDays($days)->setTimeAsEndOfDay();
		$this->owner->{$this->expireAtAttribute} = $date;
	}
	
	public function isExpired() {
		//return 
	}
}