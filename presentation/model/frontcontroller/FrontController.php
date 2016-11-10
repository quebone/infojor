<?php
namespace Infojor\Presentation\Model\FrontController;

class FrontController
{
	protected  $em;
	protected  $data;
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
		$this->data = new \stdClass();
		$this->data->header = $this->getHeaderData();
	}
	
	protected function addData($newData)
	{
		$this->data = (object) array_merge((array) $newData, (array) $this->data);
	}

	protected function getHeaderData()
	{
		$model = new \Infojor\Presentation\Model\HeaderViewModel(null, $this->em);
		return $model->output();
	}
	
	public function getData()
	{
		return $this->data;
	}
}