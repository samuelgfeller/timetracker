<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 11.01.2018
 * Time: 10:22
 */

namespace App\Controller;


use App\Entity\Timetracker;
use App\Form\LogType;
use App\Service\PaginateService;
use Proxies\__CG__\App\Entity\Ort;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
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

class HistoryController extends Controller {
	/**
	 * @Route("/history/{page}", defaults={"page"=1}, name="history", requirements={"page"="\d+"})
	 * @param int $page
	 * @param PaginateService $paginateService
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function history(int $page, PaginateService $paginateService){
		$entityManager = $this->getDoctrine()->getManager();
		$historyRepository = $entityManager->getRepository('App:Timetracker');
		$historyQuery=$historyRepository->getHistoryQuery();
		
		$pages = $paginateService->getPagesCount($historyQuery);
		$logs = $paginateService->paginate($historyQuery, $page /*, (optional) $pageSize */);
		
		foreach ($logs as $log) {
			if (!empty($bis = $log->getBis())) {
				$von = $log->getVon();
				$log->setTotalTime($von->diff($bis));
			}
		}
		return $this->render('timetracker/history.html.twig', [
 			'logs'=>$logs,
			'page' => $page,
			'pages' => $pages,
		]);
	}
	
	/**
	 * @Route("/history/add", name="add_log")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addLog(Request $request) {
		$options = [
			'action' => $this->generateUrl('add_log'),
			'method' => 'post',
			'attr' => [
				'autocomplete' => 'off',
				'class' => 'timeForm'
				]
		];
		$form = $this->createForm(LogType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$log = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($log);
//			echo gettype($log->getVon());
//			var_dump($log->getVon());
//			die();
			$em->flush();
			$this->addFlash('success', 'Ort erfolgreich hinzugefügt.');
			
			return $this->redirectToRoute('history');
			
		}
		return $this->render('timetracker/log_form.html.twig', array('form' => $form->createView(),));
	}
	
	/**
	 * @Route("/history/edit/{log}", name="edit_log", requirements={"log"="\d+"})
	 * @param Timetracker $log
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editLog(Timetracker $log, Request $request) {
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_log',[
					'log' => $log->getId()
				]),
			]];
		$form = $this->createForm(LogType::class, $log, $options);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$log = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($log);
			
			$em->flush();

//			    $this->addFlash('success', 'Saved ort as "' . $ort->getPLZ() . ' ' . $ort->getOrt() . '" id: ' . $ort->getId());
			$this->addFlash('success', 'Änderungen gespeichert');
			
			return $this->redirectToRoute('history');
		}
		return $this->render('timetracker/log_form.html.twig', ['form' => $form->createView(),]);
	}
	/**
	 * @Route("/history/del", name="del_log")
	 */
	public function delLog() {
		$repository = $this->getDoctrine()->getRepository(Timetracker::class);
		$log = $repository->find($_POST['id']);
		
		if (!$log){
			throw $this->createNotFoundException('No log found');
		}
		$em = $this->getDoctrine()->getManager();
		$em->remove($log);
		$em->flush();
		
		return new JsonResponse();
		
	}
}