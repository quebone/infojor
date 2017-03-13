<?php
namespace tfg;

use infojor\presentation\model\HeaderViewModel;

if (session_status() != PHP_SESSION_NONE) {
	session_unset();
	session_destroy();
}
session_start();

set_error_handler("\\infojor\\myErrorHandler");
register_shutdown_function("\\infojor\\fatal_handler");

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
require_once 'init.php';

if (isset($_SESSION[USER_ID])) {
	unset($_SESSION[USER_ID]);
}

$header = new HeaderViewModel();
$data['header'] = $header->output();

$template = new \Transphporm\Builder(TPLDIR.'login.xml', TPLDIR.'login.tss');

echo $template->output($data)->body;
