<?php
namespace App\Controller;

use App\Entity\Log;
use App\Entity\Timetracker;
use App\Form\TimetrackerType;
use Proxies\__CG__\App\Entity\Ort;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
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
	 */
	public function newLog(Request $request) {
		$log = new Timetracker();
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
			$timetracker = $form->getData();

			if ($request->request->has('exists')) {
				//session weiter machen
				return $this->render('timetracker/stop_form.html.twig', [
				]);
			}
			//else
			$timetracker->setVon(new \DateTime());
			$em = $this->getDoctrine()->getManager();
			$em->persist($timetracker);
			$em->flush();
			$session = new Session();
			$session->start();
			$session->set('log', $timetracker);
			var_dump($session->get('log'));
			die();
			
			return $this->render('timetracker/stop_form.html.twig', [
			]);
		}
		return $this->render('timetracker/timetracker.html.twig', [
			'form' => $form->createView(),
		]);
	}
	
	

	
}