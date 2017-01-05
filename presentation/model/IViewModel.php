<?php
namespace tfg\presentation\model;

interface IViewModel
{
	public function getModel();
	public function setModel($model);
	public function getData();
	public function setSession();
}