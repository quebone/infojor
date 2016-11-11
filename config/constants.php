<?php
namespace Infojor\Config;

if (!defined('BASEDIR')) define('BASEDIR', '');
define('VIEWDIR', BASEDIR.'presentation/');
define('TPLDIR', BASEDIR.VIEWDIR.'template/');
define('VIEWMODELDIR', BASEDIR.VIEWDIR.'model/');
define('VIEWFCDIR', BASEDIR.VIEWMODELDIR.'frontcontroller/');
define('VIEWCONTROLLERDIR', BASEDIR.VIEWDIR.'controller/');
define('MODELDIR', BASEDIR.'service/');
define('ENTITIESDIR', BASEDIR.MODELDIR.'entities/');
define('IMAGEDIR', BASEDIR.'images/');
define('THUMBNAILDIR', IMAGEDIR.'thumbnails/');

define('AVATAR', 'avatar.jpg');