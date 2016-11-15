<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\LoginFrontController;

if (session_status() != PHP_SESSION_NONE) {
	session_unset();
	session_destroy();
}
session_start();

set_error_handler('\Infojor\\myErrorHandler');
register_shutdown_function("\Infojor\\fatal_handler");

function myErrorHandler($code, $message, $file, $line) {
	echo "$code: $message ($file line $line)\n";
	exit(1);
}

function fatal_handler() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
    // fatal error
		myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

?>
<!doctype html>
<?php
if (isset($_SESSION['userid'])) {
	unset($_SESSION['userid']);
}

require_once 'init.php';

$frontController = new LoginFrontController($entityManager);
$data = $frontController->getData();

$template = new \Transphporm\Builder(TPLDIR.'login.xml', TPLDIR.'login.tss');

echo $template->output($data)->body;
