<?php
namespace tfg\presentation\model;

interface IUserViewModel
{
	public function login($username, $password);
	public function getTeacher($id);
	public function getStudent($id);
	public function getCurrentSections($teacherid);
	public function getThumbnail($personId);
}