<?php
namespace tfg\presentation\model;

interface ISchoolViewModel
{
	public function getActiveCourse();
	public function getActiveTrimestre();
	public function getCurrentClassroomStudents($classroomId);
	public function getClassroom($classroomId);
	public function getArea($areaId);
	public function getReinforceClassroom($reinforceId);
	public function getScopes($classromId);
	public function getScopeAreas($scopeId, $areaId = null);
	
	}