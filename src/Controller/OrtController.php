<?php

namespace App\Controller ;
    
    use App\Entity\Ort;
    use App\Form\AddOrtType;
    use App\Form\EditOrtType;
    use App\Form\OrtType;
    use App\Service\PaginateService;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
		    $ortQuery=$ortRepository->getOrtQuery();
		
		    $pages = $paginateService->getPagesCount($ortQuery);
		    $orte = $paginateService->paginate($ortQuery, $page /*, (optional) $pageSize */);
		
		    return $this->render('timetracker/ort.html.twig', [
			    'orte'=>$orte,
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
		    $ort = new Ort();
		    $options = ['action' => $this->generateUrl('add_ort'), 'method' => 'post', 'attr' => ['autocomplete' => 'off',]];
		    $form = $this->createForm(OrtType::class, null, $options);
		    $form->handleRequest($request);
		    if ($form->isSubmitted() && $form->isValid()) {
			    $ort = $form->getData();
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($ort);
			
			    $em->flush();
			    $this->addFlash('success', 'Ort erfolgreich hinzugefügt.');
			
			    return $this->redirectToRoute('orte');
			
		    }
		    return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	    }
	
	    /**
	     * @Route("/orte/edit/{ort}", name="edit_ort", requirements={"ort"="\d+"})
	     * @param Ort $ort
	     * @param Request $request
	     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	     */
	    public function editOrt(Ort $ort, Request $request) {
		    $options = [
		    	'attr' => [
		    		'class' => 'edit',
				    'action' => $this->generateUrl('edit_ort',['ort' => $ort->getId()]),
				    ]];
		    $form = $this->createForm(OrtType::class, $ort, $options);
		    $form->handleRequest($request);
		    if ($form->isSubmitted() && $form->isValid()) {
		    	
			    $ort = $form->getData();
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($ort);
			    $em->flush();
			
//			    $this->addFlash('success', 'Saved ort as "' . $ort->getPLZ() . ' ' . $ort->getOrt() . '" id: ' . $ort->getId());
			    $this->addFlash('success', 'Änderungen gespeichert');
			
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
		    if (!$ort){
			    throw $this->createNotFoundException('No ort found');
		    }
		    $em = $this->getDoctrine()->getManager();
		    $em->remove($ort);
		    $em->flush();
		
		    return new Response();
		
	    }
    }