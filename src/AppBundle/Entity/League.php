<?php 
// src/appBundle/Entity/League.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="league")
 */
class League
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id; 
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank()
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $description;
	
	/**
     * @ORM\Column(type="boolean", name="active", options={"default": false})
     */
    protected $active=true;
	
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getActive(){
		return $this->active;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setDescription($description){
		$this->description = $description;
	}
	public function setActive($active){
		$this->active = $active;
	}
}
