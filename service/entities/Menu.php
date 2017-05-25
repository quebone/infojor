<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="menus")
 **/
class Menu
{
	const BASIC = 1;
	const TEACHER = 2;
	const ADMIN = 3;
	const PRINT = 4;

	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=15) **/
	private $name;
	/**
	 * @OneToMany(targetEntity="MenuItem", mappedBy="menu")
	 */
	private $items;
	/**
	 *  @ManyToMany(targetEntity="Role", mappedBy="menus")
	**/
	private $roles;
	/** @Column(type="boolean", name="is_dropdown") **/
	private $isDropdown;
	/** @Column(type="integer") **/
	private $order;
	
	public function __construct() {
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function getItems() {
		return $this->items;
	}

	public function getRoles() {
		return $this->roles;
	}
	
	public function isDropdown() {
		return $this->isDropdown;
	}
	
	public function setDropdown($isDropdown) {
		$this->isDropdown = $isDropdown;
	}
	
	public function getOrder() {
		return $this->order;
	}
	
	public function setOrder($order) {
		$this->order = $order;
	}
	
	public function toArray():array {
		$data = array();
		$data['id'] = $this->id;
		$data['name'] = $this->name;
		$data['items'] = array();
		foreach ($this->items as $item) {
			array_push($data['items'], $item->toArray());
		}
		usort($data['items'], function ($a, $b) {
			return ($a['order'] == $b['order']) ? 0 : (($a['order'] < $b['order']) ? -1 : 1);
		});
		$data['isDropdown'] = $this->isDropdown;
		$data['order'] = $this->order;
		return $data;
	}
}