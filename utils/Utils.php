<?php
namespace tfg\utils;

class Utils
{
	private static $em;
	
	public static function setEm(\Doctrine\ORM\EntityManager $entityManager)
	{
		self::$em = $entityManager;
	}
	
	public static function getEm():\Doctrine\ORM\EntityManager
	{
		return self::$em;
	}
	
	public static function decode($inputText)
	{
		setlocale(LC_ALL, 'es_CA');
		return $str = iconv('UTF-8', 'cp1252', $inputText);
	}
	
	public static function parse($text) {
		// Damn pesky carriage returns...
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\r", "\n", $text);
	
		// JSON requires new line characters be escaped
		$text = str_replace("\n", "\\n", $text);
		return $text;
	}
}