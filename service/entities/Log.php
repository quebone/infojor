<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="logs")
 **/
class Log {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=40) **/
	private $username;
	/** @Column(type="datetime") **/
	private $date;
	/** @Column(type="boolean", options={"default": false}) **/
	private $logged;
	
	public function __construct($username, $date, $logged)
	{
		$this->username = $username;
		$this->date = $date;
		$this->logged = $logged;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function setDate($date) {
		$this->date = $date;
	}
	
	public function getLogged() {
		return $this->logged;
	}
	
	public function setLogged($logged) {
		$this->logged = $logged;
	}
}