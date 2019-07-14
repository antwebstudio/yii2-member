<?php

return [
    'id' => 'member',
    'class' => \ant\member\Module::class,
    'isCoreModule' => false,

	'depends' => ['user'],
];
?>