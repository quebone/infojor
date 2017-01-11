<?php
namespace tfg\service;

use tfg\service\Entities\Teacher;
use tfg\service\Entities\Student;
use tfg\service\Entities\Log;
use tfg\service\Entities\Enrollment;
use tfg\service\Entities\Tutoring;
use tfg\service\Entities\Speciality;
use tfg\service\Entities\Reinforcing;

final class UserService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getPerson($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Person', $id);
	}
	
	public function getTeacher($id):Teacher
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Teacher', $id);
	}
	
	public function getAllTeachers():array
	{
		return $this->entityManager->getRepository('tfg\\service\\Entities\\Teacher')->findBy([], ['surnames' => 'ASC']);
	}
	
	public function getStudent($id):Student
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Student', $id);
	}
	
	/**
	 * Retorna l'usuari si existeix, nul en cas contrari
	 * En qualsevol cas, registra l'intent de login
	 */
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
	
	public function isAdmin($id):bool
	{
		$teacher = $this->getTeacher($id);
		return $teacher->isAdmin();
	}
	
	/**
	 * Retorna les tutories actualment assignades a un mestre
	 */
	public function getCurrentTutorings($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentTutorings($this->getActiveCourse(), $this->getActiveTrimestre());
	}

	/**
	 * Retorna les especialitats actualment assignades a un mestre
	 */
	public function getCurrentSpecialities($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentSpecialities($this->getActiveCourse(), $this->getActiveTrimestre());
	}

	/**
	 * Retorna les classes de reforç actualment assignades a un mestre
	 */
	public function getCurrentReinforcings($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getCurrentReinforcings($this->getActiveCourse(), $this->getActiveTrimestre());
	}
	
	/**
	 * Elements de menú corresponents a un mestre
	 */
	public function getMenuItems($teacherId):array
	{
		$teacher = $this->getTeacher($teacherId);
		return $teacher->getMenuItems();
	}
	
	/**
	 * Assigna a un usuari el password per defecte
	 */
	public function restorePassword($teacherId)
	{
		$teacher = $this->getTeacher($teacherId);
		$teacher->setPassword(sha1(DEFAULTPASSWORD));
		$this->entityManager->flush($teacher);
		return DEFAULTPASSWORD;
	}
	
	/**
	 * Actualitza les dades d'un usuari. Si no existeix, en crea un de nou
	 */
	public function updateTeacher($teacherId, $name, $surnames, $email, $phone, $username, $isAdmin, $isActive)
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
		$teacher->setActive($isActive);
		if ($teacherId == null) $this->entityManager->persist($teacher);
		$this->entityManager->flush($teacher);
	}
	
	/**
	 * Elimina un mestre després de comprovar que no té cap valoració entrada a l'historial
	 */
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

	/**
	 * Actualitza les dades personals d'un usuari
	 */
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
	
	/**
	 * Actualitza les dades d'un alumne
	 */
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

	/**
	 * Afegeix un nou estudiant i l'assigna al trimestre actual 
	 */
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

	/**
	 * Elimina un estudiant després de comprovar que no té cap valoració assignada a l'històric
	 */
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
	
	/**
	 * Assigna una tutoria a un mestre si no existeix
	 */
	public function addTutoring($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$classroom = $schoolModel->getClassroom($classroomId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		if (!$schoolModel->getTutoring($classroom, $teacher, $course, $trimestre))
		{
			$tutoring = new Tutoring($teacher, $classroom, $course, $trimestre);
			$this->entityManager->persist($tutoring);
			$this->entityManager->flush();
			return true;
		}
		return false;
	}

	/**
	 * Desassigna una tutoria a un mestre després de comprovar que no ha entrat cap valoració 
	 */
	public function removeTutoring($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$classroom = $schoolModel->getClassroom($classroomId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		$students = $classroom->getStudents($course, $trimestre);
		foreach ($students as $student) {
			if ($student->getNumEvaluations($course, $trimestre) > 0) return false;
		}
		$tutoring = $schoolModel->getTutoring($classroom, $teacher, $course, $trimestre);
		$this->entityManager->remove($tutoring);
		$this->entityManager->flush();
		return true;
	}

	/**
	 * Assigna una especialitat a un mestre
	 */
	public function addSpeciality($teacherId, $areaId, $courseId=null, $trimestreId=null)
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$area = $schoolModel->getArea($areaId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		if (!$schoolModel->getSpeciality($area, $teacher, $course, $trimestre))
		{
			$speciality = new Speciality($teacher, $area, $course, $trimestre);
			$this->entityManager->persist($speciality);
			$this->entityManager->flush();
			return true;
		}
		return false;
	}

	/**
	 * Desassigna una especialitat a un mestre
	 */
	public function removeSpeciality($teacherId, $areaId, $courseId=null, $trimestreId=null)
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$area = $schoolModel->getArea($areaId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		$speciality = $schoolModel->getSpeciality($area, $teacher, $course, $trimestre);
		$this->entityManager->remove($speciality);
		$this->entityManager->flush();
		return true;
	}
	
	/**
	 * Assigna una classe de reforç a un mestre si no existeix
	 */
	public function addReinforcing($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$classroom = $schoolModel->getReinforceClassroom($classroomId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		if (!$schoolModel->getReinforcing($classroom, $teacher, $course, $trimestre))
		{
			$reinforcing = new Reinforcing($teacher, $classroom, $course, $trimestre);
			$this->entityManager->persist($reinforcing);
			$this->entityManager->flush();
			return true;
		}
		return false;
	}
	
	/**
	 * Desassigna una classe de reforç a un mestre
	 */
	public function removeReinforcing($teacherId, $classroomId, $courseId=null, $trimestreId=null)
	{
		$schoolModel = new SchoolService();
		$teacher = $this->getTeacher($teacherId);
		$classroom = $schoolModel->getReinforceClassroom($classroomId);
		$course = $courseId ? $schoolModel->getCourse($courseId) : $schoolModel->getActiveCourse();
		$trimestre = $trimestreId ? $schoolModel->getTrimestre($trimestreId) : $schoolModel->getActiveTrimestre();
		$reinforcing = $schoolModel->getReinforcing($classroom, $teacher, $course, $trimestre);
		$this->entityManager->remove($reinforcing);
		$this->entityManager->flush();
		return true;
	}
	
	/**
	 * Importa els estudiants d'un arxiu csv
	 */
	public function importStudentsFromFile($file)
	{
		//TODO
	}

	/**
	 * Matricula els estudiants de l'últim trimestre curs passat al primer trimestre d'aquest curs
	 * i els assigna la classe d'un nivell superior  
	 */
	public function importStudentsFromLastCourse()
	{
		//TODO
	}
	
	/**
	 * Matricula els estudiants del trimestre anterior al trimestre actual
	 */
	public function importStudentsFromLastTrimestre()
	{
		//TODO
	}

	/**
	 * Assigna als mestres les mateixes tutories, especialitats i classes de reforç que al trimestre anterior
	 */
	public function importTeachersFromLastTrimestre()
	{
		//TODO
	}
}
