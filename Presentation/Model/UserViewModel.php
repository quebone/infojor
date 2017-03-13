<?php
namespace Infojor\Presentation\Model;

final class UserViewModel extends ViewModel {
	
	public function __construct($model = null, \Doctrine\ORM\EntityManager $entityManager = null) {
		if ($model == null) $model = new \Infojor\Service\UserService($entityManager);
		parent::__construct($model, $entityManager);
	}
	
	public function login() {
		$username = $_POST['user'];
		$password = $_POST['password'];
		$teacher = $this->model->login($username, $password);
		if ($teacher != null) {
			$id = $teacher->getId();
			session_start();
			$_SESSION['userid'] = $id;
			return $id;
		}
		return false;
	}
	
	public function getTeacher($id)
	{
		$teacher = $this->model->getTeacher($id);
		$this->data->name = $teacher->getName();
		$this->data->surnames = $teacher->getSurnames();
		return $this->data;
	}
	
	private function getCurrentSection($teacherid, $main, $mapped)
	{
		$mainFunction = "getCurrent" . $main;
		$mappedFunction = "get" . $mapped;
		$items = $this->model->{$mainFunction}($teacherid);
		$data = array();
		foreach ($items as $item) {
			$data[$item->{$mappedFunction}()->getId()] = new \stdClass;
			$data[$item->{$mappedFunction}()->getId()]->name = $item->{$mappedFunction}()->getName();
			$data[$item->{$mappedFunction}()->getId()]->id = $item->{$mappedFunction}()->getId();
			$data[$item->{$mappedFunction}()->getId()]->link = $item->{$mappedFunction}()->getId();
		}
		return $data;
	}
	
	private function createSection($id, $name, $locale, $contents)
	{
		$section = array(
			'name'=>strtolower($name),
			'locale'=>$locale,
			'items'=>$this->getCurrentSection($id, $name, $contents));
		return $section;
	}
		
	public function getCurrentSections($teacherid)
	{
		$sections[] = $this->createSection($teacherid, 'Tutorings', 'Tutories', 'Classroom');
		$sections[] = $this->createSection($teacherid, 'Specialities', 'Especialitats', 'Area');
		$sections[] = $this->createSection($teacherid, 'Reinforcings', 'Reforç individual', 'ReinforceClassroom');
		for ($i = count($sections) - 1; $i >= 0; $i--) {
			if (!count($sections[$i]['items'])) {
				unset($sections[$i]);
			}
		}
		return $sections;
	}
	
	public function getThumbnail($personId)
	{
		$person = $this->model->getPerson($personId);
		return $person->getThumbnail();
	}
}