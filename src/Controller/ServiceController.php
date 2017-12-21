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
	 *     "/timetracker/services/{page}",
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
	 * @Route("/timetracker/services/add", name="add_service")
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
			$em = $this->getDoctrine()->getManager();
			$em->persist($service);
			$em->flush();
			return $this->redirectToRoute('services');
		}
		return $this->render('timetracker/form.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/timetracker/services/edit/{service}", name="edit_service", requirements={"service"="\d+"})
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
			$em = $this->getDoctrine()->getManager();
			$em->persist($service);
			
			$em->flush();
			
			$this->addFlash('success', 'Saved service changes.');
			
			return $this->redirectToRoute('services');
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	/**
	 * @Route("/timetracker/services/del", name="del_service")
	 */
	public function delService() {
		$repository = $this->getDoctrine()->getRepository(Service::class);
		$service = $repository->find($_POST['id']);
		$em = $this->getDoctrine()->getManager();
		$em->remove($service);
		$em->flush();
		$this->addFlash('success', 'service "'.$service->getname() . '" deleted succefully');
		
		return new Response();
		
	}
}
