<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Service\PaginateService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller {
	/**
	 * @Route(
	 *     "/timetracker/companies/{page}",
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
        $companyQuery=$companyRepository->getCompanyQuery();
        
        $pages = $paginateService->getPagesCount($companyQuery);
        $companies = $paginateService->paginate($companyQuery, $page /*, (optional) $pageSize */);
        
        return $this->render('timetracker/company.html.twig', [
            'companies'=>$companies,
	        'page' => $page,
	        'pages' => $pages,
        ]);
    }
    /**
     * @Route("/timetracker/companies/add", name="add_companies")
     */
    public function addCompany(Request $request) {
        $company = new Company();
        $options = [
            'action' => $this->generateUrl('add_companies'),
            'method' => 'post',
        ];
        $form = $this->createForm(CompanyType::class, null, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $company = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute('companies');
        }
        return $this->render('timetracker/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
	/**
	 * @Route("/timetracker/companies/edit/{company}", name="edit_company", requirements={"company"="\d+"})
	 */
	public function editCompany(Company $company, Request $request) {
//	    	$ort = $id;
//		    $repository = $this->getDoctrine()->getRepository(Ort::class);
//		    $ort = $repository->find($id);
		$options = [
			'attr' => [
				'class' => 'edit',
				'action' => $this->generateUrl('edit_company',['company' => $company->getId()]),
			]];
		$form = $this->createForm(CompanyType::class, $company, $options);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$company = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($company);
			
			$em->flush();
			
			$this->addFlash('success', 'Saved company changes.');
			
			return $this->redirectToRoute('companies');
		}
		return $this->render('timetracker/form.html.twig', array('form' => $form->createView(),));
	}
	/**
	 * @Route("/timetracker/companies/del", name="del_company")
	 */
	public function delCompany() {
		$repository = $this->getDoctrine()->getRepository(Company::class);
		$company = $repository->find($_POST['id']);
		if (!$company){
			throw $this->createNotFoundException('No company found');
		}
		$em = $this->getDoctrine()->getManager();
		$em->remove($company);
		$em->flush();
		$this->addFlash('success', 'company "'.$company->getname() . '" deleted succefully');
		
		return new Response();
		
	}
}
