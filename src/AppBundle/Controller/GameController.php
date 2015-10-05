<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;
use AppBundle\Entity\League;
use AppBundle\Entity\Game;
use AppBundle\Entity\Nostradamus;

class GameController extends Controller {

	/**
	 * @Route("/game/create", name="game_create")
	 */
	public function createAction(Request $request) {
		$game = new Game();
		
		$leagueRepository = $this->getDoctrine() ->getRepository('AppBundle:League');
		$activeLeagues = $leagueRepository->findBy(array('active'=>true));
		
		$form = $this -> createFormBuilder($game) -> add('homeTeam', 'entity', array('label' => 'Domači', 'class' => 'AppBundle:Team', 'property' => 'Name', 'expanded' => false, 'multiple' => false)) -> add('awayTeam', 'entity', array('label' => 'Gosti', 'class' => 'AppBundle:Team', 'property' => 'Name', 'expanded' => false, 'multiple' => false)) -> add('round', 'integer', array('label' => 'Krog')) -> add('league', 'entity', array('label' => 'Liga', 'class' => 'AppBundle:League', 'choices'=> $activeLeagues, 'property' => 'Name', 'expanded' => false, 'multiple' => false)) -> add('scheduled', 'datetime', array('label' => 'Datum in ura')) -> add('save', 'submit', array('label' => 'Dodaj')) -> getForm();

		$form -> handleRequest($request);

		if ($form -> isValid()) {
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($game);
			$em -> flush();

			$this -> addFlash('info', 'The match was created successfully.');
		}

		$repository = $this -> getDoctrine() -> getRepository('AppBundle:Game');
		$actives = $repository -> findBy(array('active' => true, 'finished'=>false), array('scheduled' => 'ASC'));
		$inactives = $repository -> findBy(array('active' => false), array('scheduled' => 'ASC'));
		return $this -> render('admin/game/create.html.twig', $params = array('form' => $form -> createView(), 'actives' => $actives, 'inactives' => $inactives, ));

	}

	/**
	 * @Route("/game/deactivate/{id}")
	 */
	public function deactivateAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$game = $em -> getRepository('AppBundle:Game') -> find($id);

		if (!$game) {
			throw $this -> createNotFoundException('No game found for id ' . $id);
		}

		$game -> setActive(false);
		$em -> flush();

		return $this -> redirectToRoute('game_create');
	}

	/**
	 * @Route("/game/activate/{id}")
	 */
	public function activateAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$game = $em -> getRepository('AppBundle:Game') -> find($id);

		if (!$game) {
			throw $this -> createNotFoundException('No game found for id ' . $id);
		}

		$game -> setActive(true);
		$em -> flush();

		return $this -> redirectToRoute('game_create');
	}

	/**
	 * @Route("/game/remove/{id}")
	 */
	public function removeAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$game = $em -> getRepository('AppBundle:Game') -> find($id);

		if (!$game) {
			throw $this -> createNotFoundException('No game found for id ' . $id);
		}

		$em -> remove($game);
		$em -> flush();

		return $this -> redirectToRoute('game_create');
	}
	/**
	 * @Route("/game/edit/{id}")
	 */
	public function editAction($id, Request $request) {
		$em = $this -> getDoctrine() -> getManager();
		$game = $em -> getRepository('AppBundle:Game') -> find($id);
		$leagueRepository = $this->getDoctrine() ->getRepository('AppBundle:League');
		$activeLeagues = $leagueRepository->findBy(array('active'=>true));
		
		if (!$game) {
			throw $this -> createNotFoundException('No game found for id ' . $id);
		}

		$form = $this -> createFormBuilder($game) -> add('homeTeam', 'entity', array('label' => 'Domači', 'class' => 'AppBundle:Team', 'property' => 'Name', 'expanded' => false, 'multiple' => false)) 
		-> add('awayTeam', 'entity', array('label' => 'Gosti', 'class' => 'AppBundle:Team', 'property' => 'Name', 'expanded' => false, 'multiple' => false)) 
		-> add('round', 'integer', array('label' => 'Krog'))
		-> add('scoreHome', 'integer', array('label' => 'Rezultat domači'))
		-> add('scoreAway', 'integer', array('label' => 'Rezultat gosti'))
		-> add('active', 'checkbox', array('label'=>'Aktivna tekma', 'required'=>false)) 
		-> add('finished', 'checkbox', array('label'=>'Zaključeno', 'required'=>false))
		-> add('league', 'entity', array('label' => 'Liga', 'class' => 'AppBundle:League', 'choices'=> $activeLeagues, 'property' => 'Name', 'expanded' => false, 'multiple' => false)) 
		-> add('scheduled', 'datetime', array('label' => 'Datum in ura'))
		-> add('recap', 'text', array('label'=>'Več o tekmi', 'required'=>false))  -> add('save', 'submit', array('label' => 'Shrani'))
		-> getForm();

		$form -> handleRequest($request);

		if ($form -> isValid()) {
			$em = $this -> getDoctrine() -> getManager(); // comment out
			$em -> persist($game);
			$em -> flush();
			
			if($game->getFinished()){
				$this->calculate($game);
				$this->addTeamPoints($game);
			}
			
			$this -> addFlash('info', 'The match was updated successfully.');
		}
		return $this -> render('admin/game/edit.html.twig', $params = array('form' => $form -> createView(), ));
		
	}

	/**
	 * @Route("/game/results/")
	 */
	public function resultsAction() {
		$gameRepository = $this -> getDoctrine() -> getRepository('AppBundle:Game'); 
		$finishedGames = $gameRepository->findBy(array('finished'=>true));
		return $this->render('/common/game/results.html.twig', $params = array('games'=>$finishedGames));
	}
	
	/**
	 * @Route("/game/finished", name="game_finished")
	 */
	public function finishedAction(Request $request) {

		$repository = $this -> getDoctrine() -> getRepository('AppBundle:Game');
		$finished = $repository -> findBy(array('finished' => true), array('scheduled' => 'DESC'));
		return $this -> render('admin/game/finished.html.twig', $params = array('finished'=>$finished));

	}
	
	public function addTeamPoints($game){
		dump("adding team points");
		$teamRepository = $this->getDoctrine()->getRepository('AppBundle:Team');
		$homeTeam = $teamRepository->findOneBy(array('id'=>$game->getHomeTeam()));
		$awayTeam = $teamRepository->findOneBy(array('id'=>$game->getAwayTeam()));
		$pointsHome=0; 
		$pointsAway=0;
		if($homeTeam && $awayTeam){
			$em = $this -> getDoctrine() -> getManager();
			if($game->getScoreHome() == 3){
				$homeTeam->addWin();
				$awayTeam->addLoss();
				if($game->getScoreAway() == 2){
					$pointsHome=2;
					$pointsAway=1;	
				}else{
					$pointsHome=3;
				}
			}
			if($game->getScoreAway() == 3){
				$homeTeam->addLoss();
				$awayTeam->addWin();
				if($game->getScoreHome() == 2){
					$pointsHome=1;
					$pointsAway=2;	
				}else{
					$pointsAway=3;
				}
			}
			$homeTeam->addPoints($pointsHome);
			$awayTeam->addPoints($pointsAway);
			$em->persist($homeTeam);
			$em->persist($awayTeam);
			$em->flush();
			dump('Točke za: '. $homeTeam->getName() .' '. $pointsHome .' točke za: '.$awayTeam->getName().' '.$pointsAway);
		}
	}
	
	
	public function calculate($game){
		dump("Kalkuliram");
		$betRepository = $this->getDoctrine() ->getRepository('AppBundle:Bet');
		$validBets = $betRepository->findBy(array('game'=>$game));
		$em = $this -> getDoctrine() -> getManager();
		if($validBets){
			dump($validBets);
			foreach($validBets as $bet){
				$points = $this -> compareScore($game, $bet);
				
				// add points to nostradamus score
				$nostradamusRepository = $this->getDoctrine() ->getRepository('AppBundle:Nostradamus');
				$nostradamus = $nostradamusRepository->findOneBy(array('user'=>$bet->getUser(), 'league'=>$game->getLeague()));
				if($nostradamus){
					$nostradamus->setScore($points);
					dump('Nostradamus '. $bet->getUser()->getUsername() . ' found and '. $points .' points added.');
				}else{
					$nostradamus = new Nostradamus();
					$nostradamus->setUser($bet->getUser());
					$nostradamus->setLeague($game->getLeague());
					$nostradamus->addScore($points); 
					dump('New nostradamus and '. $points .' points added.');
				}
				$em -> persist($nostradamus);
				$bet->setCalculated(true);
				$em->persist($bet);
				$em->flush();
			}
		}else{
			dump('No valid bets.');
		}
		//return $this -> render('admin/team/manager.html.twig', $params = array());
	}
	
	public function compareScore($game, $bet){
		$points = 0;	
		if(!($bet->getBetHome() == 0 && $bet->getBetAway() == 0)){
			if($game->getScoreHome() == 3 && $bet->getBetHome() == 3){
				$points += 1;
			}
			if($game->getScoreAway() == 3 && $bet->getBetAway() == 3){
				$points += 1;
			}
			if($game->getScoreAway() == $bet->getBetAway() && $game->getScoreHome() == $bet->getBetHome()){
				$points += 2;
			}
		}
		return($points);
	}
	
	
	
}
