<?php

class Loader
{
	private $dirs = array (
		BASEDIR,
		VIEWDIR,
		TPLDIR,
		VIEWMODELDIR,
		VIEWFCDIR,
		VIEWCONTROLLERDIR,
		MODELDIR,
		ENTITIESDIR,
		IMAGEDIR,
		THUMBNAILDIR,
	);
	
	function __construct($className)
	{
		$this->loadClass($className);
	}
	
	private function loadClass($className)
	{
		$parts = explode('\\', $className);
		foreach ($this->dirs as $dir) {
			$fullPath = $dir . "/" . end($parts) . '.php'; 
			if (file_exists($fullPath)) {
				include $fullPath;
				return;
			}
		}
	}
}

function myAutoloader($className)
{
	new Loader($className);
}

spl_autoload_register('myAutoloader');
