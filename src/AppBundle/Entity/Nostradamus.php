<?php 
// src/appBundle/Entity/Nostradamus.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\League;
use AppBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="nostradamus")
 */
class Nostradamus
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
	protected $user;
	
	/**
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     **/
	protected $league;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $score=0;
	
	public function getId(){
		return $this->id;
	}
	public function getUser(){
		return $this->user;
	}
	public function getLeague(){
		return $this->league;
	}
	public function getScore(){
		return $this->score;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	public function setUser($user){
		$this->user = $user;
	}
	public function setLeague($league){
		$this->league = $league;
	}
	public function setScore($score){
		$this->score = $score;
	}
	public function addScore($points){
		$this->score += $points;
	}
}
