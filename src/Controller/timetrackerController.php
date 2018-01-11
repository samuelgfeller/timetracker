<?php
namespace App\Controller;

use App\Entity\Timetracker;
use App\Form\TimetrackerType;
use Proxies\__CG__\App\Entity\Ort;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Flex\Response;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class timetrackerController extends Controller{
    /**
     * @Route("/", name="home")
     */
    public function startPage() {
       
        return $this->render('timetracker/index.html.twig');
    }
	
	/**
	 * @Route("/timetracker", name="timetracker")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function newLog(Request $request) {
		//make connection to repository
		
		$entityManager = $this->getDoctrine()->getManager();
		$timetrackerRepository = $entityManager->getRepository('App:Timetracker');
		
		
		if($this->container->get('session')->get('log')){
//			$oldTimetracker=$timetrackerRepository->findActiveByContact([/*$session->get('log')->getContact()*/]);
			return $this->render('timetracker/stop_form.html.twig', [
				'log' => $this->container->get('session')->get('log')
			]);
		}
		
		$options = [
			'action' => $this->generateUrl('timetracker'),
			'method' => 'post',
			'attr' => [
				'id' => 'timeForm'
			]
		];
		$form = $this->createForm(TimetrackerType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			
			$newTimetracker = $form->getData();
			$session = new Session();
			if (!$this->container->get('session')){
				$session->start();
			}
			
			if ($request->request->has('exists')) {
				//continue log
				//get the active log from db
				$oldTimetracker=$timetrackerRepository->findActiveByContact([$newTimetracker->getContact()]);
				$session->set('log', $oldTimetracker);
				
				return $this->render('timetracker/stop_form.html.twig', [
					'log' => $this->container->get('session')->get('log')
				]);
			}
			//else
			$newTimetracker->setVon(new \DateTime());
			$em = $this->getDoctrine()->getManager();
			$em->persist($newTimetracker);
			
			$session->set('log', $newTimetracker);
			$em->flush();
			
			return $this->render('timetracker/stop_form.html.twig', [
				'log' => $this->container->get('session')->get('log')
			]);
		}
		return $this->render('timetracker/timetracker.html.twig', [
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/timetracker/stop", name="stop_time")
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function stopTimer() {

		
		//check if session exists and get object
		if ($timetracker=$this->container->get('session')->get('log')) {
			//make connection to repository
			$entityManager = $this->getDoctrine()->getManager();
			$timetrackerRepository = $entityManager->getRepository('App:Timetracker');
			//check if time already stopped
			$check=$timetrackerRepository->checkIfStopped($timetracker->getContact(),$timetracker->getVon());
			if ($check === true){
				//modify object
				$timetracker->setBis(new \DateTime());
				//get doctrine manager
				$em = $this->getDoctrine()->getManager();
				//prepare and flush
				$em->merge($timetracker);
				$em->flush();
				$this->addFlash('success', 'Zeiterfassung erfolgreich gespeichert.');
			}else{
				$this->addFlash('error', 'Zeit wurde schon angehalten');
			}
			$this->container->get('session')->remove('log');
		}else{
			$this->addFlash('error', 'Zeit wurde schon angehalten');
		}
		return $this->redirectToRoute('home');
	}
	

	
}