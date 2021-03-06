<?php

namespace ant\member\migrations\rbac;

use yii\db\Schema;
use ant\rbac\Migration;
use ant\rbac\Role;

class M190714154412_permissions extends Migration
{
	protected $permissions;
	
	public function init() {
		$this->permissions = [
			\ant\member\controllers\MemberController::class => [
				'index' => ['View My Account', [Role::ROLE_USER]],
				'payment' => ['View My Account', [Role::ROLE_USER]],
			],
			\ant\member\backend\controllers\MemberController::class => [
				'index' => ['View Member Account', [Role::ROLE_ADMIN]],
				'payment' => ['View Member Account', [Role::ROLE_ADMIN]],
				'subscription' => ['View Member Account', [Role::ROLE_ADMIN]],
			],
			\ant\member\backend\controllers\DefaultController::class => [
				'index' => ['View Member Account', [Role::ROLE_ADMIN]],
			],
			\ant\member\backend\controllers\InvoiceController::class => [
				'index' => ['View Member Invoice', [Role::ROLE_ADMIN]],
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
