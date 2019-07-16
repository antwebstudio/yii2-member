<?php

namespace ant\member\migrations\rbac;

use yii\db\Schema;
use common\rbac\Migration;
use common\rbac\Role;

class M190714154412_permissions extends Migration
{
	protected $permissions;
	
	public function init() {
		$this->permissions = [
			\ant\member\controllers\MemberController::class => [
				'index' => ['View My Account', [Role::ROLE_USER]],
				'payment' => ['View My Account', [Role::ROLE_USER]],
			],
		];
		
		parent::init();
	}
	
	public function up()
    {
		$this->addAllPermissions($this->permissions);
    }

    public function down()
    {
		$this->removeAllPermissions($this->permissions);
    }
}
