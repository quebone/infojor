<?php
namespace infojor\config;

if (!defined('BASEDIR')) define('BASEDIR', '');
define('VIEWDIR', BASEDIR.'presentation/');
define('TPLDIR', BASEDIR.VIEWDIR.'template/');
define('VIEWMODELDIR', BASEDIR.VIEWDIR.'model/');
define('VIEWFCDIR', BASEDIR.VIEWMODELDIR.'frontcontroller/');
define('VIEWCONTROLLERDIR', BASEDIR.VIEWDIR.'controller/');
define('MODELDIR', BASEDIR.'service/');
define('ENTITIESDIR', BASEDIR.MODELDIR.'entities/');
define('IMAGEDIR', BASEDIR.'images/');
define('THUMBNAILDIR', IMAGEDIR.'thumbnails/');
define('FILESDIR', BASEDIR.'files/');

define('DEFAULTCLASSROOMID', 7);
define('MAXLEVEL', 9);
define('AVATAR', 'avatar.jpg');

define('DEFAULTPASSWORD', '1234');

//variables de sessió i post
define('USER_ID', 'userId');
define('USERNAME', 'username');
define('PASSWORD', 'password');
define('NAME', 'name');
define('SURNAMES', 'surnames');
define('EMAIL', 'email');
define('PHONE', 'phone');
define('ISADMIN', 'isAdmin');
define('TEACHER_ID', 'teacherId');
define('CLASSROOM_ID', 'classroomId');
define('STUDENT_ID', 'studentId');
define('AREA_ID', 'areaId');
define('REINFORCE_ID', 'reinforceId');
define('SECTION', 'section');
define('DIMENSION_ID', 'dimensionId');
define('MARK_ID', 'markId');
define('OBSERVATION', 'observation');
define('COURSE_ID', 'courseId');
define('YEAR', 'year');
define('ISACTIVE', 'isActive');
define('TRIMESTRE_ID', 'trimestreId');
define('DEGREE_ID', 'degreeId');
define('CYCLE_ID', 'cycleId');
define('CYCLES', 'cycles');
define('DESCRIPTION', 'description');
define('FILENAME', 'file');

define('SUMMARY_CELL_COLORS', ['AE' => 'FFAAFFAA', 'AN' => 'FF9BC2C2', 'AS' => 'FFD4D46A', 'NA' => 'FFD4886A']);
