<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\PaginateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
	/**
	 * @Route(
	 *     "/contacts/{page}",
	 *     defaults={"page" = 1},
	 *     name="contacts",
	 *     requirements={"page"="\d+"}
	 * )
	 *
	 * @param int $page
	 * @param PaginateService $paginateService
	 *
	 * @return Response
	 */
	public function allContacts(int $page, PaginateService $paginateService) {
		$entityManager = $this->getDoctrine()->getManager();
		$contactRepository = $entityManager->getRepository('App:Contact');
		$contactQuery=$contactRepository->getContactQuery();
		
		$pages = $paginateService->getPagesCount($contactQuery);
		$contacts = $paginateService->paginate($contactQuery, $page /*, (optional) $pageSize */);
		
		return $this->render('timetracker/contact.html.twig', [
			'contacts'=>$contacts,
			'page' => $page,
			'pages' => $pages,
		]);
	}
	
	/**
	 * @Route("/contacts/add", name="add_contact")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function addContacts(Request $request) {
		$contacts = new Contact();
		$options = [
			'action' => $this->generateUrl('add_contact'),
			'method' => 'post',
		];
		$form = $this->createForm(ContactType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$contact = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($contact);
			$em->flush();
			$this->addFlash('success', 'Kontakt erfolgreich hinzugefügt.');
			return $this->redirectToRoute('contacts');
		}
		return $this->render('timetracker/form.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/contacts/edit/{contact}", name="edit_contact", requirements={"contact"="\d+"})
	 * @param Request $request
	 * @param Contact $contact
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editContact(Request $request, Contact $contact) {
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_contact',['contact' => $contact->getId()]),
			]];
		$form = $this->createForm(ContactType::class, $contact, $options);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$contact = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($contact);
			
			$em->flush();
			
			$this->addFlash('success', 'Änderungen gespeichert');
			
			return $this->redirectToRoute('contacts');
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	
	/**
	 * @Route("/contacts/del", name="del_contact")
	 */
	public function delContact() {
		$repository = $this->getDoctrine()->getRepository(Contact::class);
		$contact = $repository->find($_POST['id']);
		if (!$contact){
			throw $this->createNotFoundException('No contact found');
		}
		$em = $this->getDoctrine()->getManager();
		$em->remove($contact);
		$em->flush();
		
		return new Response();
		
	}
	/**
	 * @Route("/contacts/find", name="find_company")
	 */
	public function findContact(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(Contact::class);
		$contacts = $repository->getSearchResult($request->get('inputVal'));
		$contactArr = null;
		/** @var Contact $contact */
		foreach ($contacts as $contact) {
			$arr = [
				'id' => $contact->getId(),
				'name' => $contact->getName(),
				'address' => $contact->getAddress(),
				'ort' => $contact->getOrtId()->getPLZ().' '.$contact->getOrtId()->getOrt(),
				'taetigkeit' => $contact->getAddress(),
			];
			$contactArr[] = $arr;
		}
//		echo json_encode($compArr);
		return new JsonResponse($contactArr);
//		exit();
	}
}
