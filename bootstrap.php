<?php
// bootstrap.php
namespace Infojor;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$basedir = defined('AJAX') ? '../../' : '';
if (!defined('ENTITIESDIR')) define('ENTITIESDIR', 'service/entities/');

require_once $basedir.'vendor/autoload.php';

$paths = array($basedir.ENTITIESDIR);
$isDevMode = true;
$dbParams = unserialize(file_get_contents($basedir.'config/dbparams.config'));

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

