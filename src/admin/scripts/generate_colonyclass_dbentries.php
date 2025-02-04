<?php

use Stu\Orm\Entity\DatabaseCategoryInterface;
use Stu\Orm\Entity\DatabaseTypeInterface;
use Stu\Orm\Repository\DatabaseCategoryRepositoryInterface;
use Stu\Orm\Repository\DatabaseEntryRepositoryInterface;
use Stu\Orm\Repository\DatabaseTypeRepositoryInterface;
use Stu\Orm\Repository\PlanetTypeRepositoryInterface;

include_once(__DIR__.'/../../inc/config.inc.php');

$repository = $container->get(DatabaseEntryRepositoryInterface::class);
/** @var DatabaseTypeInterface $type */
$type = $container->get(DatabaseTypeRepositoryInterface::class)->find(DATABASE_TYPE_PLANET);
/** @var DatabaseCategoryInterface $category */
$category = $container->get(DatabaseCategoryRepositoryInterface::class)->find(DATABASE_CATEGORY_PLANET_TYPE);

$result = $container->get(PlanetTypeRepositoryInterface::class)->getWithoutDatabaseEntry();
foreach ($result as $obj) {
    $db = $repository->prototype();
	$db->setCategory($category);
	$db->setData('');
	$db->setDescription($obj->getName());
    $db->setTypeObject($type);
	$db->setSort($obj->getId());
	$db->setObjectId($obj->getId());
    $repository->save($db);

	$obj->setDatabaseId($db->getId());
	$obj->save();
}
