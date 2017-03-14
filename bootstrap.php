<?php
// bootstrap.php
namespace infojor;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

if (!defined('BASEDIR')) define('BASEDIR', '');
if (!defined('ENTITIESDIR')) define('ENTITIESDIR', BASEDIR . 'service/entities/');

require_once BASEDIR.'vendor/autoload.php';

$paths = array(ENTITIESDIR);
$isDevMode = true;
$dbParams = unserialize(file_get_contents(BASEDIR.'config/dbparams.config'));

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

\infojor\utils\Utils::setEm($entityManager);

// $config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);