<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Service\PaginateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller {
	/**
	 * @Route(
	 *     "/companies/{page}",
	 *     defaults={"page" = 1},
	 *     name="companies",
	 *     requirements={"page"="\d+"}
	 * )
	 *
	 * @param int $page
	 * @param PaginateService $paginateService
	 *
	 * @return Response
	 */
	public function allCompanies(int $page, PaginateService $paginateService) {
		$entityManager = $this->getDoctrine()->getManager();
		/** @var CompanyRepository $companyRepository */
		$companyRepository = $entityManager->getRepository('App:Company');
		$companyQuery = $companyRepository->getCompanyQuery();
		$pages = $paginateService->getPagesCount($companyQuery);
		$companies = $paginateService->paginate($companyQuery, $page /*, (optional) $pageSize */);
		
		return $this->render('timetracker/company.html.twig', [
			'companies' => $companies,
			'page' => $page,
			'pages' => $pages,
		]);
	}
	
	/**
	 * @Route("/companies/add", name="add_companies")
	 */
	public function addCompany(Request $request) {
		
		$options = [
			'action' => $this->generateUrl('add_companies'),
			'method' => 'post',
		];
		$form = $this->createForm(CompanyType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$company = $form->getData();
			
			//check if exists
			$repository = $this->getDoctrine()->getRepository(Company::class);
			$result = $repository->checkIfExists($company->getName());
			//if there is no result that means that it doesnt exist
			if (!$result) {
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($company);
				$em->flush();
				$this->addFlash('success', 'Firma erfolgreich hinzugefügt.');
			} else {
				$this->addFlash('error', 'Firma mit dem Namen ' . $company->getName() . ' existiert schon.');
			}
			return $this->redirectToRoute('companies');
		}
		return $this->render('timetracker/form.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/companies/edit/{company}", name="edit_company", requirements={"company"="\d+"})
	 */
	public function editCompany(Company $company, Request $request) {
		
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_company', ['company' => $company->getId()]),
			]];
		$form = $this->createForm(CompanyType::class, $company, $options);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$company = $form->getData();
			//look in db if company with new name exists
			$repository = $this->getDoctrine()->getRepository(Company::class);
			$result = $repository->checkIfExists($company->getName());
			//if result is null it means that there is no company. But if the name is not edited, there will be another I check if the Id matches
			if (!$result || $company->getId() == $result->getId()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($company);
				$em->flush();
				$this->addFlash('success', 'Änderungen gespeichert');
			} else {
				$this->addFlash('error', 'Firma mit dem Namen ' . $company->getName() . ' existiert schon.');
			}
			
			return $this->redirectToRoute('companies');
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	
	/**
	 * @Route("/companies/del", name="del_company")
	 */
	public function delCompany() {
		$repository = $this->getDoctrine()->getRepository(Company::class);
		$company = $repository->find($_POST['id']);
		
		if (!$company) {
			throw $this->createNotFoundException('No company found');
		}
		$em = $this->getDoctrine()->getManager();
		$em->remove($company);
		$em->flush();
		
		return new Response();
		
	}
	/**
	 * @Route("/companies/find")
	 */
	public function findCompany(Request $request) {
		$repository = $this->getDoctrine()->getRepository(Company::class);
		$companies = $repository->getSearchResult($request->get('inputVal'));
		$compArr = null;
		/** @var Company $company */
		foreach ($companies as $company) {
			$arr = [
				'id' => $company->getId(),
				'name' => $company->getName(),
				'address' => $company->getAddress(),
				'ort' => $company->getOrtId()->getPLZ() . ' ' . $company->getOrtId()->getOrt(),
			];
			$compArr[] = $arr;
		}
		return new JsonResponse($compArr);
	}

}
