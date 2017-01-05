<?php
namespace tfg\service;

use tfg\service\Entities\Teacher;
use tfg\service\Entities\Student;
use tfg\service\Entities\Log;
use tfg\service\Entities\Enrollment;

final class UserService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function login($username, $password)
	{
		$teacher = $this->entityManager->getRepository('tfg\\service\\Entities\\Teacher')->findOneBy(array(
			'username'=>$username,
			'password'=>sha1($password),
			));
		//creates a new log
		$date = new \DateTime();
		$logged = $teacher != null;
		$log = new Log($username, $date, $logged);
		$this->entityManager->persist($log);
		$this->entityManager->flush($log);
		return $teacher;
	}
	
	public function getPerson($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Person', $id);
	}
	
	public function getTeacher($id):Teacher
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Teacher', $id);
	}
	
	public function isAdmin($id):bool
	{
		$teacher = $this->getTeacher($id);
		return $teacher->isAdmin();
	}
	
	public function getStudent($id):Student
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Student', $id);
	}
	
	public function getAllTeachers():array
	{
		return $this->entityManager->getRepository('tfg\\service\\Entities\\Teacher')->findBy([], ['surnames' => 'ASC']);
	}
	
	public function getCurrentTutorings($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentTutorings($this->getActiveCourse(), $this->getActiveTrimestre());
	}

	public function getCurrentSpecialities($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentSpecialities($this->getActiveCourse(), $this->getActiveTrimestre());
	}

	public function getCurrentReinforcings($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentReinforcings($this->getActiveCourse(), $this->getActiveTrimestre());
	}
	
	public function getMenuItems($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getMenuItems();
	}
	
	public function restorePassword($teacherId)
	{
		$teacher = $this->getTeacher($teacherId);
		$teacher->setPassword(sha1(DEFAULTPASSWORD));
		$this->entityManager->flush($teacher);
		return DEFAULTPASSWORD;
	}
	
	public function updateTeacher($teacherId, $name, $surnames, $email, $phone, $username, $isAdmin)
	{
		if ($teacherId == null) {
			$teacher = new Teacher($name, $surnames);
		} else {
			$teacher = $this->getTeacher($teacherId);
		}
		
		$teacher->setName($name);
		$teacher->setSurnames($surnames);
		$teacher->setEmail($email);
		$teacher->setPhone($phone);
		$teacher->setUsername($username);
		$teacher->setAdmin($isAdmin);
		if ($teacherId == null) $this->entityManager->persist($teacher);
		$this->entityManager->flush($teacher);
	}
	
	public function deleteTeacher($teacherId)
	{
		$teacher = $this->getTeacher($teacherId);
		//comprovem que no hagi entrat cap valoració
		$tutorings = $teacher->getTutorings();
		foreach ($tutorings as $tutoring) {
			$course = $tutoring->getCourse();
			$trimestre = $tutoring->getTrimestre();
			$classroom = $tutoring->getClassroom();
			$enrollments = $classroom->getEnrollments();
			foreach ($enrollments as $enrollment) {
				if ($enrollment->getCourse() == $course && $enrollment->getTrimestre() == $trimestre) {
					$student = $enrollment->getStudent();
					if ($student->getNumEvaluations($course, $trimestre) > 0)
						return false;
				}
			}
		}
		$this->entityManager->remove($teacher);
		$this->entityManager->flush($teacher);
		return true;
	}

	public function updatePersonalData($teacherId, $name, $surnames, $email, $phone, $username, $password)
	{
		$teacher = $this->getTeacher($teacherId);
		$teacher->setName($name);
		$teacher->setSurnames($surnames);
		$teacher->setEmail($email);
		$teacher->setPhone($phone);
		$teacher->setUsername($username);
		$teacher->setPassword(sha1($password));
		$this->entityManager->flush($teacher);
	}
	
	public function updateStudent($studentId, $name, $surnames, $classroomId)
	{
		$student = $this->getStudent($studentId);
		$student->setName($name);
		$student->setSurnames($surnames);
		//update enrollment
		$schoolModel = new SchoolService();
		$enrollment = $student->getEnrollment($schoolModel->getActiveCourse(), $schoolModel->getActiveTrimestre());
		$classroom = $schoolModel->getClassroom($classroomId);
		$enrollment->setClassroom($classroom);
		$this->entityManager->flush();
	}

	public function addStudent($name, $surnames, $classroomId)
	{
		$schoolModel = new SchoolService();
		$student = new Student($name, $surnames);
		$school = $schoolModel->getSchool();
		$classroom = $schoolModel->getClassroom($classroomId);
		$enrollment = new Enrollment($student, $classroom, $schoolModel->getActiveCourse(), $schoolModel->getActiveTrimestre());
		$student->addEnrollment($enrollment);
		$student->setSchool($school);
		$school->addStudent($student);
		$this->entityManager->persist($enrollment);
		$this->entityManager->persist($student);
		$this->entityManager->flush();
	}

	public function deleteStudent($studentId)
	{
		$schoolModel = new SchoolService();
		$ac = $schoolModel->getActiveCourse();
		$at = $schoolModel->getActiveTrimestre();
		$school = $schoolModel->getSchool();
		$student = $this->getStudent($studentId);
		if ($student->getNumEvaluations($ac, $at) > 0) {
			return false;
		}
		if ($student->getNumEvaluations() == 0) {
			//eliminar estudiant de la base de dades
			$this->entityManager->remove($student);
		} else {
			//eliminar estudiant del trimestre
			$enrollment = $student->getEnrollment($ac, $at);
			$this->entityManager->remove($enrollment);
		}
		$this->entityManager->flush();
		return true;
	}
	
	public function addTutoring($teacherId, $classroomId, $courseId, $trimestreId)
	{
		//TODO
	}

	public function removeTutoring($teacherId, $classroomId, $courseId, $trimestreId)
	{
		//TODO
	}

	public function addSpeciality($teacherId, $areaId, $courseId, $trimestreId)
	{
		//TODO
	}

	public function removeSpeciality($teacherId, $areaId, $courseId, $trimestreId)
	{
		//TODO
	}

	public function importStudentsFromFile($file)
	{
		//TODO
	}

	public function importStudentsFromLastCourse()
	{
		//TODO
	}
	
	public function importStudentsFromLastTrimestre()
	{
		//TODO
	}
	}
