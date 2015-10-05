<?php

namespace AppBundle\Service;

use AppBundle\Entity\Nostradamus;
use AppBundle\Entity\Game;
use AppBundle\Entity\Bet;
use Symfony\Component\HttpFoundation\Response;

class Scoring {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function calculate($gameId) {
        //Get the array, hydrate the entity and save it, at last.
        //...
        //$entity = new Product();
        //...
        //$this->entityManager->persist($entity);
        //$this->entityManager->flush($entity);
        //return $entity;
		//dump('Hello world! Calculating for gameId:'.$gameId);
		return $this -> render('admin/team/manager.html.twig', $params = array());
		
    }

}