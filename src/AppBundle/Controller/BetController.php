<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;
use AppBundle\Entity\Bet;
use Appbundle\Entity\League;
use FOS\UserBundle\Model\User;

class BetController extends Controller{
	
	/**
	 * @Route("/bet/index", name="bet_index")
	 */
	public function indexAction(Request $request){
		
		$gamesRepository = $this->getDoctrine()->getRepository('AppBundle:Game');
		
		$now =  new \DateTime("now");
		
		$em = $this -> getDoctrine() -> getManager();
		
		// get only available games
		$qb = $em->createQueryBuilder();
		$qb->select('g')
		   ->from('AppBundle:Game', 'g')
		   ->where($qb->expr()->gte('g.scheduled', ':now'))
		   ->andWhere($qb->expr()->eq('g.active', true))
		   ->setParameter('now', new \DateTime('now'));
		$games = $qb->getQuery()->getResult();   
		
		// if user has already saved bets, display them
		$bets = array();
		$user = $this->getUser();
		foreach ($games as $game){
			$existingBet = $em -> getRepository('AppBundle:Bet') -> findOneBy(array(
				'user' => $user->getId(),
				'game' => $game->getId(),
			));
			if($existingBet){
				$bets[] = $existingBet;
			}
		}
		dump($bets);
		
		$gameRepository = $this -> getDoctrine() -> getRepository('AppBundle:Game'); 
		$latestResults = $gameRepository->findBy(array('finished'=>true), array('scheduled'=>'DESC'),5);
		
		$leagueRepository = $this -> getDoctrine() -> getRepository('AppBundle:League'); 
		$liga = $leagueRepository->findOneBy(array('name'=>'1. DOL'));
						
		$teamRepository = $this->getDoctrine() ->getRepository('AppBundle:Team');
		$lestvica = $teamRepository->findBy(array('league'=>$liga),array('points' => 'DESC'));
		
		$nostradamusRepository = $this->getDoctrine() ->getRepository('AppBundle:Nostradamus');
		$nostradamuses = $nostradamusRepository->findBy(array(),array('score'=>'DESC'));
		
		return $this->render('nostradamus/bet/index.html.twig', $params = array(
			'games'=>$games,
			'bets'=>$bets,
			'latestResults'=>$latestResults,
			'liga'=>$liga,
			'lestvica'=>$lestvica,
			'nostradamuses'=>$nostradamuses,
		));
	}
	
	/**
	 * @Route("/bet/save/", name="bet_save")
	 */
	 public function saveAction(Request $request){
	 	$request = $this->getRequest();
		$params = $request->query->all(); 
				
		$user = $this->getUser();
		
		$gamesRepository = $this->getDoctrine()->getRepository('AppBundle:Game');
		$games = $gamesRepository->findAll();
		$em = $this->getDoctrine()->getManager();
		$betSuccessCounter = 0;
		foreach($games as $game){
			$gid = $game->getId();
			$em = $this -> getDoctrine() -> getManager();
			$existingBet = $em -> getRepository('AppBundle:Bet') -> findOneBy(array(
				'user' => $user->getId(),
				'game' => $gid,
			));
			if($existingBet){
				if(array_key_exists('home'.$gid, $params)){
					$existingBet -> setBetHome($params['home'.$gid]);
				}else{
					$existingBet -> setBetHome(0);
				}
				if(array_key_exists('away'.$gid, $params)){
					$existingBet -> setBetAway($params['away'.$gid]);
				}else{
					$existingBet -> setBetAway(0);
				}
				$em->flush();
				$betSuccessCounter += 1;
				
			}else{
				$bet = new Bet();
				$bet -> setUser($user);
				$bet -> setGame($game);
				
				if(array_key_exists('home'.$gid, $params)){
				$bet -> setBetHome($params['home'.$gid]);
				}else{
					$bet -> setBetHome(0);	
				}
				if(array_key_exists('away'.$gid, $params)){
					$bet -> setBetAway($params['away'.$gid]);	
				}else{
					$bet -> setBetAway(0);	
				}
				
				$em->persist($bet);
				$em->flush();
				
			}
			continue;
		}
		if(count($games) == $betSuccessCounter){
			$this -> addFlash('success', 'Vaše napovedi so shranjene. Vso srečo!');	
		}else{
			$this -> addFlash('warning', 'Nekaj se je zalomilo pri shranjevanju napovedi. Prosimo, poskusite znova. Če se napaka ponovi, jo prijavite administratorju.');
		}
		return $this->redirectToRoute('bet_index');
	 }
}
