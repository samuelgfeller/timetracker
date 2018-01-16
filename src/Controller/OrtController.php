<?php

namespace App\Controller;

use App\Entity\Ort;
use App\Form\AddOrtType;
use App\Form\EditOrtType;
use App\Form\OrtType;
use App\Service\PaginateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class OrtController extends Controller {
	/**
	 * @Route("/orte/{page}", defaults={"page"=1}, name="orte", requirements={"page"="\d+"})
	 *
	 * @param int $page
	 * @param PaginateService $paginateService
	 *
	 * @return Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function getAllOrt(int $page, PaginateService $paginateService) {
		$entityManager = $this->getDoctrine()->getManager();
		$ortRepository = $entityManager->getRepository('App:Ort');
		$ortQuery = $ortRepository->getOrtQuery();
		
		$pages = $paginateService->getPagesCount($ortQuery);
		$orte = $paginateService->paginate($ortQuery, $page /*, (optional) $pageSize */);
		
		return $this->render('timetracker/ort.html.twig', [
			'orte' => $orte,
			'page' => $page,
			'pages' => $pages,
		]);
		
	}
	
	/**
	 * @Route("/orte/add", name="add_ort")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function addOrt(Request $request) {
		$options = ['action' => $this->generateUrl('add_ort'), 'method' => 'post', 'attr' => ['autocomplete' => 'off',]];
		$form = $this->createForm(OrtType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$ort = $form->getData();
			
			//check if exists
			$repository = $this->getDoctrine()->getRepository(Ort::class);
			$result = $repository->checkIfExists($ort->getPLZ());
			
			if (!$result) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($ort);
				
				$em->flush();
				$this->addFlash('success', 'Ort erfolgreich hinzugefügt.');
			} else {
				$this->addFlash('error', 'Ort mit der Postleitzahl ' . $ort->getPLZ() . ' existiert schon.');
			}
			return $this->redirectToRoute('orte');
			
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	
	/**
	 * @Route("/orte/edit/{ort}", name="edit_ort", requirements={"ort"="\d+"})
	 * @param Ort $ort
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function editOrt(Ort $ort, Request $request) {
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_ort', ['ort' => $ort->getId()]),
			]];
		$form = $this->createForm(OrtType::class, $ort, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$ort = $form->getData();
			
			//look in db if ort with new name exists
			$repository = $this->getDoctrine()->getRepository(Ort::class);
			$result = $repository->checkIfExists($ort->getPLZ());
			//if result is null it means that there is no ort. But if the name is not edited, there will be another I check if the Id matches
			if (!$result || $ort->getId() == $result->getId()) {
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($ort);
				$em->flush();
				
				$this->addFlash('success', 'Änderungen gespeichert');
			} else {
				$this->addFlash('error', 'Ort mit der Postleitzahl ' . $ort->getPLZ() . ' existiert schon.');
			}
			return $this->redirectToRoute('orte');
		}
		return $this->render('timetracker/form.html.twig', ['form' => $form->createView(),]);
	}
	
	/**
	 * @Route("/orte/del", name="del_ort")
	 */
	public function delOrt() {
		$repository = $this->getDoctrine()->getRepository(Ort::class);
		$ort = $repository->find($_POST['id']);
		if (!$ort) {
			throw $this->createNotFoundException('No ort found');
		}
		$em = $this->getDoctrine()->getManager();
		$em->remove($ort);
		$em->flush();
		
		return new Response();
		
	}
	/**
	 * @Route("/orte/find", name="find_ort")
	 */
	public function findOrt(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(Ort::class);
		$orte = $repository->getSearchResult($request->get('inputVal'));
		$orteArr = null;
		/** @var Ort $ort */
		foreach ($orte as $ort) {
			$arr = [
				'ort' => $ort->getOrt(),
				'PLZ' => $ort->getPLZ(),
				'id' => $ort->getId(),
			];
			$orteArr[] = $arr;
		}
//		echo json_encode($orteArr);
		return new JsonResponse($orteArr);
//		exit();
	}
	
}