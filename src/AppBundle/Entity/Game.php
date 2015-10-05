<?php 
// src/appBundle/Entity/Match.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use AppBundle\Entity\League;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     **/
	protected $homeTeam;
	
	/**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     **/
	protected $awayTeam;
	
	 /** @ORM\Column(type="datetime") */
	protected $scheduled;
	
	/**
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     **/
	protected $league;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $round=0;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $scoreHome=0;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $scoreAway=0;
	
	/**
     * @ORM\Column(type="integer")
     */
	protected $winner=0;
	
	/**
     * @ORM\Column(type="boolean", name="active", options={"default": true})
     */
    protected $active=true;
	
	/**
     * @ORM\Column(type="boolean", name="finished", options={"default": false})
     */
    protected $finished=false;
	
	/**
	 * 
	 * @ORM\Column(type="string")
     * @Assert\Url(
     *    protocols = {"http", "https"},
	 * 	  message = "The url '{{ value }}' is not a valid url",	
     * )
     */
	protected $recap="";
	
	public function getId(){
		return $this->id;
	}
	public function getHomeTeam(){
		return $this->homeTeam;
	}
	public function getAwayTeam(){
		return $this->awayTeam;
	}
	public function getScheduled(){
		return $this->scheduled;
	}
	public function getLeague(){
		return $this->league;
	}
	public function getRound(){
		return $this->round;
	}
	public function getScoreHome(){
		return $this->scoreHome;
	}
	public function getScoreAway(){
		return $this->scoreAway;
	}
	public function getWinner(){
		return $this->winner;
	}
	public function getActive(){
		return $this->active;
	}
	public function getFinished(){
		return $this->finished;
	}
	public function getRecap(){
		return $this->recap;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	public function setHomeTeam($homeTeam){
		$this->homeTeam = $homeTeam;
	}
	public function setAwayTeam($awayTeam){
		$this->awayTeam = $awayTeam;
	}
	public function setScheduled($scheduled){
		$this->scheduled = $scheduled;
	}
	public function setLeague($league){
		$this->league = $league;
	}
	public function setRound($round){
		$this->round = $round;
	}
	public function setScoreHome($scoreHome){
		$this->scoreHome = $scoreHome;
	}
	public function setScoreAway($scoreAway){
		$this->scoreAway = $scoreAway;
	}
	public function setWinner($winner){
		$this->winner = $winner;
	}
	public function setActive($active){
		$this->active = $active;
	}
	public function setFinished($finished){
		$this->finished = $finished;
	}
	public function setRecap($link){
		$this->recap = $link;
	}
}
