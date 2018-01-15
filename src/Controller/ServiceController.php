<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Service\PaginateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
	/**
	 * @Route(
	 *     "/services/{page}",
	 *     defaults={"page" = 1},
	 *     name="services",
	 *     requirements={"page"="\d+"}
	 * )
	 *
	 * @param int $page
	 * @param PaginateService $paginateService
	 *
	 * @return Response
	 */
	public function allServices(int $page, PaginateService $paginateService) {
		$entityManager = $this->getDoctrine()->getManager();
		
		$serviceRepository = $entityManager->getRepository('App:Service');
		$serviceQuery=$serviceRepository->getServiceQuery();
		
		$pages = $paginateService->getPagesCount($serviceQuery);
		$services = $paginateService->paginate($serviceQuery, $page /*, (optional) $pageSize */);
		
		return $this->render('timetracker/service.html.twig', [
			'services'=>$services,
			'page' => $page,
			'pages' => $pages,
		]);
	}
	
	/**
	 * @Route("/services/add", name="add_service")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function addService(Request $request) {
		$service = new Service();
		$options = [
			'action' => $this->generateUrl('add_service'),
			'method' => 'post',
		];
		$form = $this->createForm(ServiceType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$service = $form->getData();
			//check if exists
			$repository = $this->getDoctrine()->getRepository(Service::class);
			$result = $repository->checkIfExists($service->getName());
			//if there is no result that means that it doesnt exist
			if (!$result) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($service);
			$em->flush();
			$this->addFlash('success', 'Service erfolgreich hinzugefügt.');
			} else {
				$this->addFlash('error', 'Service mit dem Namen ' . $service->getName() . ' existiert schon.');
			}
			return $this->redirectToRoute('services');
		}
		return $this->render('timetracker/form.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/services/edit/{service}", name="edit_service", requirements={"service"="\d+"})
	 * @param Service $service
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editService(Service $service, Request $request) {
//	    	$ort = $id;
//		    $repository = $this->getDoctrine()->getRepository(Ort::class);
//		    $ort = $repository->find($id);
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_service',['service' => $service->getId()]),
			]];
		$form = $this->createForm(ServiceType::class, $service, $options);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$service = $form->getData();
			//look in db if Service with new name exists
			$repository = $this->getDoctrine()->getRepository(Service::class);
			$result = $repository->checkIfExists($service->getName());
			//if result is null it means that there is no Service. But if the name is not edited, there will be another I check if the Id matches
			if (!$result || $service->getId() == $result->getId()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($service);
			
			$em->flush();
			
			$this->addFlash('success', 'Änderungen gespeichert');
			} else {
				$this->addFlash('error', 'Service mit dem Namen ' . $service->getName() . ' existiert schon.');
			}
			return $this->redirectToRoute('services');
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	/**
	 * @Route("/services/del", name="del_service")
	 */
	public function delService() {
		$repository = $this->getDoctrine()->getRepository(Service::class);
		$service = $repository->find($_POST['id']);
		$em = $this->getDoctrine()->getManager();
		$em->remove($service);
		$em->flush();
		
		return new Response();
		
	}
}
