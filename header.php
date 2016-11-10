<?php
namespace Infojor;

use Infojor\Presentation\Model\HeaderViewModel;
use Infojor\Presentation\Controller\Controller;

$template = new \Transphporm\Builder(TPLDIR.'header.xml', TPLDIR.'header.tss');

$headerViewModel = new HeaderViewModel(null, $entityManager);
//$controller = new Controller($headerViewModel);

$header = new \stdClass;
$header->header = $template->output($headerViewModel->output())->body;
