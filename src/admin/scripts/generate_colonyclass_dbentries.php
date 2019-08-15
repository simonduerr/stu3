<?php

use Stu\Orm\Repository\DatabaseEntryRepositoryInterface;
use Stu\Orm\Repository\DatabaseTypeRepositoryInterface;

include_once(__DIR__.'/../../inc/config.inc.php');

$repository = $container->get(DatabaseEntryRepositoryInterface::class);
$type = $container->get(DatabaseTypeRepositoryInterface::class)->find(DATABASE_TYPE_PLANET);

$result = ColonyClass::getObjectsBy("WHERE id NOT IN (SELECT object_id FROM stu_database_entrys WHERE category_id=5)");
foreach ($result as $key => $obj) {
    $db = $repository->prototype();
	$db->setCategoryId(5);
	$db->setData('');
	$db->setDescription($obj->getName());
    $db->setTypeObject($type);
	$db->setSort($obj->getId());
	$db->setObjectId($obj->getId());
    $repository->save($db);

	$obj->setDatabaseId($db->getId());
	$obj->save();
}
