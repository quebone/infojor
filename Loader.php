<?php
namespace Infojor;

class Loader
{
	// individual files to include
	private $files = array(
		VIEWMODELDIR.'ViewModel.php',
		VIEWFCDIR.'FrontController.php',
		MODELDIR.'MainService.php',
	);
	
	// initial folders to include
	private $folders = array(
		VIEWMODELDIR,
		VIEWFCDIR,
		VIEWCONTROLLERDIR,
		MODELDIR,
	);
	
	private $excluded = array(
		'AjaxController.php',
	);

	function __construct()
	{
		if (!defined('REQUIRED')) {
			define('REQUIRED', "");
		}
		$this->includeIndividualFiles();
		$this->includeAllFolders();
	}
	
	private function includeIndividualFiles()
	{
		foreach($this->files as $file) {
			$this->includeFile($file);
		}
	}
	
	// includes all files from all folders
	private function includeAllFolders()
	{
		foreach($this->folders as $folder) {
			$this->includeFolder($folder);
		}
	}
	
	// includes all files from one folder
	private function includeFolder($folder)
	{
		$path = __DIR__ . "/" . $folder . "/";
		$files = scandir($path);
		foreach($files as $file) {
			if(!is_dir($file) && $this->getExtension($file)=='php') {
				$this->includeFile($path . $file);
			}
		}
	}
	
	// returns file extension
	private function getExtension($file)
	{
		$type = explode('.', $file);
		$type = array_reverse($type);
		return $type[0];
	}

	// adds all files from one folder
	public function addFolder($folder)
	{
		$this->folders[] = $folder;
		$this->includeFolder($folder);
	}
	
	// includes a file
	public function includeFile($file)
	{
//		var_dump(basename($file));
		if (!in_array(basename($file), $this->excluded)) {
			require_once $file;
		}
	}
	
	// @override
	public function __toString()
	{
		$text = __CLASS__ . ": ";
		$text .= __DIR__ . "<br/>\n";
		return $text;
	}
}

