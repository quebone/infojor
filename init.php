<?php
namespace Infojor;

if (!defined('BASEDIR')) define('BASEDIR', '');

require_once BASEDIR.'config/constants.php';
require_once BASEDIR.'Loader.php';
require_once BASEDIR."bootstrap.php";
require_once BASEDIR.'vendor/simi/tplengine/TplEngine.php';

new Loader();

// include 'header.php';

