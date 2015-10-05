<?php 
// src/appBundle/Entity/Bet.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Game;

/**
 * @ORM\Entity
 * @ORM\Table(name="bet")
 */
class Bet
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
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     **/
	protected $game;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $betHome=0;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $betAway=0;
	
	/**
	 * @ORM\Column(type="boolean", name="calculated", options={"default":false})
	 */
	protected $calculated=false; // is calculated into nostradamus result
	
	public function getId(){
		return $this->id;
	}
	public function getUser(){
		return $this->user;
	}
	public function getGame(){
		return $this->game;
	}
	public function getBetHome(){
		return $this->betHome;
	}
	public function getBetAway(){
		return $this->betAway;
	}
	public function getCalculated(){
		return $this->calculated;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function setUser($user){
		$this->user = $user;
	}
	public function setGame($game){
		$this->game = $game;
	}
	public function setBetHome($betHome){
		$this->betHome = $betHome;
	}
	public function setBetAway($betAway){
		$this->betAway = $betAway;
	}
	public function setCalculated($calculated){
		$this -> calculated = $calculated;
	}
}
