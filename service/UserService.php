<?php
namespace infojor\service;

use infojor\service\Entities\Teacher;
use infojor\service\Entities\Student;
use infojor\service\Entities\Log;
use infojor\service\Entities\Enrollment;
use infojor\service\Entities\Tutoring;
use infojor\service\Entities\Speciality;
use infojor\service\Entities\Reinforcing;
use infojor\service\DAO;
use infojor\service\Entities\Course;
use infojor\service\Entities\Trimestre;
use infojor\service\Entities\Classroom;

final class UserService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Retorna l'usuari si existeix, nul en cas contrari
	 * En qualsevol cas, registra l'intent de login
	 */
	public function login($username, $password)
	{
		$teachers = $this->dao->getByFilter("Teacher");
		foreach ($teachers as $teacher) {
			$found = !strcmp($username, $teacher->getUsername()) && !strcmp($password, $teacher->getPassword());
			if ($found) break;
		}
		//creates a new log
		$date = new \DateTime();
		$log = new Log($username, $date, $found);
		$this->dao->persist($log);
		$this->dao->flush();
		return $found ? $teacher : null;
	}
	
	public function isAdmin($id):bool
	{
		$teacher = $this->dao->getById("Teacher", $id);
		return $teacher->isAdmin();
	}
	
	/**
	 * Retorna les tutories actualment assignades a un mestre
	 */
	public function getCurrentTutorings($teacherId):array
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		return $teacher->getCurrentTutorings($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
	}

	/**
	 * Retorna les especialitats actualment assignades a un mestre
	 */
	public function getCurrentSpecialities($teacherId):array
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		return $teacher->getCurrentSpecialities($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
	}

	/**
	 * Retorna les classes de reforç actualment assignades a un mestre
	 */
	public function getCurrentReinforcings($teacherId):array
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		return $teacher->getCurrentReinforcings($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
	}
	
	/**
	 * Elements de menú corresponents a un mestre
	 */
	public function getMenuItems($teacherId):array
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		return $teacher->getMenuItems();
	}
	
	/**
	 * Assigna a un usuari el password per defecte
	 */
	public function restorePassword($teacherId)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$teacher->setPassword(sha1(DEFAULTPASSWORD));
		$this->dao->flush($teacher);
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
			$teacher = $this->dao->getById("Teacher", $teacherId);
		}
		
		$teacher->setName($name);
		$teacher->setSurnames($surnames);
		$teacher->setEmail($email);
		$teacher->setPhone($phone);
		$teacher->setUsername($username);
		$teacher->setAdmin($isAdmin);
		$teacher->setActive($isActive);
		if ($teacherId == null) $this->dao->persist($teacher);
		$this->dao->flush();
	}
	
	/**
	 * Elimina un mestre després de comprovar que no té cap valoració entrada a l'historial
	 */
	public function deleteTeacher($teacherId)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
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
		$this->dao->remove($teacher);
		$this->dao->flush();
		return true;
	}

	/**
	 * Actualitza les dades personals d'un usuari
	 */
	public function updatePersonalData($teacherId, $name, $surnames, $email, $phone, $username, $password)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$teacher->setName($name);
		$teacher->setSurnames($surnames);
		$teacher->setEmail($email);
		$teacher->setPhone($phone);
		$teacher->setUsername($username);
		if (strlen($password) > 0) $teacher->setPassword($password);
		$this->dao->flush($teacher);
	}
	
	/**
	 * Actualitza les dades d'un alumne
	 */
	public function updateStudent($studentId, $name, $surnames, $classroomId)
	{
		$student = $this->dao->getById("Student", $studentId);
		$student->setName($name);
		$student->setSurnames($surnames);
		//update enrollment
		$enrollment = $student->getEnrollment($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
		$classroom = $this->dao->getById("Classroom", $classroomId);
		$enrollment->setClassroom($classroom);
		$this->dao->flush();
	}

	/**
	 * Afegeix un nou estudiant i l'assigna al trimestre actual 
	 */
	public function addStudent($name, $surnames, $classroomId)
	{
		$student = new Student($name, $surnames);
		$school = $this->dao->getSchool();
		$classroom = $this->dao->getById("Classroom", $classroomId);
		$enrollment = new Enrollment($student, $classroom, $this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
		$student->addEnrollment($enrollment);
		$student->setSchool($school);
		$school->addStudent($student);
		$this->dao->persist($enrollment);
		$this->dao->persist($student);
		$this->dao->flush();
	}

	/**
	 * Elimina un estudiant després de comprovar que no té cap valoració assignada a l'històric
	 */
	public function deleteStudent($studentId)
	{
		$ac = $this->dao->getActiveCourse();
		$at = $this->dao->getActiveTrimestre();
		$school = $this->dao->getSchool();
		$student = $this->dao->getById("Student", $studentId);
		if ($student->getNumEvaluations($ac, $at) > 0) {
			return false;
		}
		if ($student->getNumEvaluations() == 0) {
			//eliminar estudiant de la base de dades
			$this->dao->remove($student);
		} else {
			//eliminar estudiant del trimestre
			$enrollment = $student->getEnrollment($ac, $at);
			$this->dao->remove($enrollment);
		}
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Assigna una tutoria a un mestre si no existeix
	 */
	public function addTutoring($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$classroom = $this->dao->getById("Classroom", $classroomId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$filter = ['classroom'=>$classroom, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		if (!$this->dao->getByFilter("Tutoring", $filter)) {
			$tutoring = new Tutoring($teacher, $classroom, $course, $trimestre);
			$this->dao->persist($tutoring);
			$this->dao->flush();
			return true;
		}
		return false;
	}

	/**
	 * Desassigna una tutoria a un mestre després de comprovar que no ha entrat cap valoració 
	 */
	public function removeTutoring($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$classroom = $this->dao->getById("Classroom", $classroomId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$students = $classroom->getStudents($course, $trimestre);
		foreach ($students as $student) {
			if ($student->getNumEvaluations($course, $trimestre) > 0) return false;
		}
		$filter = ['classroom'=>$classroom, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		$tutoring = $this->dao->getByFilter("Tutoring", $filter)[0];
		$this->dao->remove($tutoring);
		$this->dao->flush();
		return true;
	}

	/**
	 * Assigna una especialitat a un mestre
	 */
	public function addSpeciality($teacherId, $areaId, $courseId=null, $trimestreId=null)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$area = $this->dao->getById("Area", $areaId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$filter = ['area'=>$area, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		if (!$this->dao->getByFilter("Speciality", $filter)) {
			$speciality = new Speciality($teacher, $area, $course, $trimestre);
			$this->dao->persist($speciality);
			$this->dao->flush();
			return true;
		}
		return false;
	}

	/**
	 * Desassigna una especialitat a un mestre
	 */
	public function removeSpeciality($teacherId, $areaId, $courseId=null, $trimestreId=null)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$area = $this->dao->getById("Area", $areaId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$filter = ['area'=>$area, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		$speciality = $this->dao->getByFilter("Speciality", $filter)[0];
		$this->dao->remove($speciality);
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Assigna una classe de reforç a un mestre si no existeix
	 */
	public function addReinforcing($teacherId, $classroomId, $courseId=null, $trimestreId=null):bool
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$classroom = $this->dao->getById("ReinforceClassroom", $classroomId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$filter = ['reinforceClassroom'=>$classroom, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		if (!$this->dao->getByFilter("Reinforcing", $filter)) {
			$reinforcing = new Reinforcing($teacher, $classroom, $course, $trimestre);
			$this->dao->persist($reinforcing);
			$this->dao->flush();
			return true;
		}
		return false;
	}
	
	/**
	 * Desassigna una classe de reforç a un mestre
	 */
	public function removeReinforcing($teacherId, $classroomId, $courseId=null, $trimestreId=null)
	{
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$classroom = $this->dao->getById("ReinforceClassroom", $classroomId);
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$filter = ['reinforceClassroom'=>$classroom, 'teacher'=>$teacher, 'course'=>$course, 'trimestre'=>$trimestre];
		$reinforcing = $this->dao->getByFilter("Reinforcing", $filter)[0];
		$this->dao->remove($reinforcing);
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Importa els estudiants d'un arxiu excel
	 */
	public function importStudentsFromFile($inputFileName)
	{
		// comprovem que no hi ha qualificacions dels alumnes de P3 al curs actual
		$ac = $this->dao->getActiveCourse();
		$at = $this->dao->getActiveTrimestre();
		$classrooms = [$this->dao->getById("Classroom", 1), $this->dao->getById("Classroom", 2)];
		$students = array();
		foreach ($classrooms as $classroom) {
			$enrollments = $this->dao->getByFilter("Enrollment", ['course'=>$ac, 'classroom'=>$classroom]);
			foreach ($enrollments as $enrollment) {
				$students[] = $enrollment->getStudent();
			}
		}
		foreach ($students as $student) {
			if ($student->getNumEvaluations($ac, $at) > 0) return null;
		}
		// carreguem l'arxiu de dades
		require_once BASEDIR . 'vendor/PHPExcel/Classes/PHPExcel.php';
		$phpExcel = new \PHPExcel();
		$inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
		$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($inputFileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$nameCol = array_search("Nom", $sheetData[1]);
		$surnamesCol = array_search("Cognoms", $sheetData[1]);
		$groupCol = array_search("Grup", $sheetData[1]);
		if (($nameCol & $surnamesCol & $groupCol) == false) return null;
		unset($sheetData[1]);
		// eliminem els alumnes actuals de P3
		foreach ($students as $student) {
			$this->dao->remove($student);
		}
		$this->dao->flush();
		foreach ($sheetData as $row) {
			$student = new Student($row[$nameCol], $row[$surnamesCol], $this->dao->getSchool());
			$classroom = $this->dao->getById("Classroom", ($row[$groupCol] == 'A' ? 1 : 2));
			$enrollment = new Enrollment($student, $classroom, $this->dao->getActiveCourse(), $this->dao->getActiveTrimestre());
			$this->dao->persist($student);
			$this->dao->persist($enrollment);
		}
		$this->dao->flush();
		return count($sheetData);
	}

	/**
	 * Matricula els estudiants del curs passat de P3 a 5è a la classe d'un nivell superior  
	 */
	public function importStudentsFromLastCourse()
	{
		// comprovem que no hi ha qualificacions al curs actual
		$ac = $this->dao->getActiveCourse();
		if ($this->dao->getByFilter("PartialEvaluation", ['course'=>$ac]) || $this->dao->getByFilter("GlobalEvaluation", ['course'=>$ac]))
			return 0;
		// comprovem que hi ha algun curs per sota de l'actual
		$previousCourse = $this->getPreviousCourse($ac);
		if ($previousCourse == null) return 0;
		// eliminem les matriculacions actuals d'alumnes de nivell > P3
		$enrollments = $this->dao->getByFilter("Enrollment", ['course'=>$ac]);
		foreach ($enrollments as $enrollment) {
			if ($enrollment->getClassroom()->getLevel()->getId() > 1) $this->dao->remove($enrollment);
		}
		$enrollments = $this->dao->getByFilter("Enrollment", ['course'=>$previousCourse]);
		$at = $this->dao->getActiveTrimestre();
		$numStudents = 0;
		foreach ($enrollments as $enrollment) {
			if ($enrollment->getClassroom()->getLevel()->getId() < MAXLEVEL) {
				$newClassroom = $this->dao->getById("Classroom", $enrollment->getClassroom()->getId() + 2);
				$newEnrollment = new Enrollment($enrollment->getStudent(), $newClassroom, $ac, $at);
				$this->dao->persist($newEnrollment);
				$numStudents++;
			}
		}
		$this->dao->flush();
		return $numStudents;
	}
	
	/**
	 * Assigna als mestres les mateixes tutories que al trimestre anterior
	 */
	public function importTutoringsFromLastCourse()
	{
		$ac = $this->dao->getActiveCourse();
		$tutorings = $this->getSectionsFromLastCourse("Tutoring", $ac);
 		if ($tutorings == null) return false;
 		foreach ($tutorings as $tutoring) {
 			$newTutoring = new Tutoring($tutoring->getTeacher(), $tutoring->getClassroom(), $ac);
 			$this->dao->persist($newTutoring);
 		}
 		$this->dao->flush();
 		return count($tutorings);
	}
	
	/**
	 * Assigna als mestres les mateixes especialitats que al curs anterior
	 */
	public function importSpecialitiesFromLastCourse()
	{
		$ac = $this->dao->getActiveCourse();
		$specialities = $this->getSectionsFromLastCourse("Speciality", $ac);
		if ($specialities == null) return false;
		foreach ($specialities as $speciality) {
			$newSpeciality = new Speciality($speciality->getTeacher(), $speciality->getArea(), $ac);
			$this->dao->persist($newSpeciality);
		}
		$this->dao->flush();
		return count($specialities);
	}

	/**
	 * Assigna als mestres les mateixes classes de reforç que al curs anterior
	 */
	public function importReinforcingsFromLastTrimestre()
	{
		$ac = $this->dao->getActiveCourse();
		$reinforcings = $this->getSectionsFromLastCourse("Reinforcing", $ac);
		if ($reinforcings == null) return false;
		foreach ($reinforcings as $reinforcing) {
			$newReinforcing = new Reinforcing($reinforcing->getTeacher(), $reinforcing->getReinforceClassroom(), $ac);
			$this->dao->persist($newReinforcing);
		}
		$this->dao->flush();
		return count($reinforcings);
	}
	
	private function getSectionsFromLastTrimestre($name, $ac, $at)
	{
		if ($this->dao->getByFilter($name, ['course'=>$ac, 'trimestre'=>$at])) return null;
		$course = ($at->getNumber() == 1 ? $this->getPreviousCourse($ac) : $ac);
		$trimestre = $this->getPreviousTrimestre($at);
		// si estem al primer curs, primer trimestre, retorna fals
		if ($course == null) return null;
		return $this->dao->getByFilter($name, ['course'=>$course, 'trimestre'=>$trimestre]);
	}
	
	private function getSectionsFromLastCourse($name, $ac)
	{
		// comprova si ja hi ha algun tutor entrat
		if ($this->dao->getByFilter($name, ['course'=>$ac])) return null;
		$course = $this->getPreviousCourse($ac);
		// si estem al primer curs, retorna fals
		if ($course == null) return null;
		return $this->dao->getByFilter($name, ['course'=>$course]);
	}
	
	private function getPreviousCourse(Course $ac)
	{
		$courses = $this->dao->getByFilter("Course", [], ['year'=>'ASC']);
		for ($i = 0; $i < count($courses); $i++) {
			if ($courses[$i] == $ac) {
				return ($i > 0 ? $courses[$i-1] : null);
			}
		}
		return null;
	}
	
	private function getPreviousTrimestre(Trimestre $at)
	{
		$trimestres = $this->dao->getByFilter("Trimestre", [], ['number'=>'ASC']);
		for ($i = 0; $i < count($trimestres); $i++) {
			if ($trimestres[$i] == $at) {
				return ($i > 0 ? $trimestres[$i-1] : $trimestres[count($trimestres)-1]);
			}
		}
		return null;
	}
}
