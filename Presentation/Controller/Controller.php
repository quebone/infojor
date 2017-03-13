<?php
namespace Infojor\Presentation\Controller;

use Infojor\Presentation\Model\UserViewModel;

class Controller {
	private $viewModel;

	public function __construct(UserViewModel $viewModel) {
		$this->viewModel = $viewModel;
	}
	
	public function textClicked() {
		$this->viewModel->data['link'] = "Text clicked";
	}
	
	public function getTeacher($id) {
		$this->viewModel->getTeacher($id);
	}
}