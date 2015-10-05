<?php 
// src/appBundle/Entity/Team.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\League;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="team")
 */
class Team
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
	 * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumn(name="league", referencedColumnName="id") 
	 */
	protected $league;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $location;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $wins=0;
	/**
	 * @ORM\Column(type="integer")
	 */ 
	protected $losses=0;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $points;
	
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getLeague(){
		return $this->league;
	}
	public function getLocation(){
		return $this->location;
	}
	public function getWins(){
		return $this->wins;
	}
	public function getLosses(){
		return $this->losses;
	}
	public function getPoints(){
		return $this->points;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setLocation($location){
		$this->location = $location;
	}
	public function setLeague($id){
		$this->league = $id;
	}
	public function setWins($wins){
		$this->wins = $wins;
	}
	public function setLosses($losses){
		$this->losses = $losses;
	}
	public function setPoints($points){
		$this->points = $points;
	}
	public function addPoints($points){
		$this->points += $points;
	}
	public function addWin(){
		$this->wins += 1;
	}
	public function addLoss(){
		$this->losses += 1;
	}
}
