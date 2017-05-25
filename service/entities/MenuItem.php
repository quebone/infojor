<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="menu_items")
 **/
class MenuItem
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/**
	 * @ManyToOne(targetEntity="Menu", inversedBy="items")
	 * @JoinColumn(name="menu_id", referencedColumnName="id")
	 */
	private $menu;
	/** @Column(type="integer") **/
	private $order;
	/** @Column(type="string", length=30) **/
	private $name;
	/** @Column(type="string", length=30) **/
	private $function;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getOrder()
	{
		return $this->order;
	}
	
	public function setOrder($order)
	{
		$this->order = $order;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getFunction()
	{
		return $this->function;
	}
	
	public function setFunction($function)
	{
		$this->function = $function;
	}
	
	public function toArray():array {
		$data = array();
		$data['id'] = $this->id;
		$data['order'] = $this->order;
		$data['name'] = $this->name;
		$data['function'] = $this->function;
		return $data;
	}
}