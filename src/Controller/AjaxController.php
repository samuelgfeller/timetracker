<?php
namespace App\Controller;

use App\Entity\Company;
use App\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Flex\Response;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
	/**
	 * @Route(
	 *     "/get_contacts/{company}",
	 *     name="ajax.get_contacts_for_company",
	 *     requirements={"company"="\d+"}
	 * )
	 *
	 * @param Company $company
	 * @return JsonResponse
	 */
	public function getContactsForCompanyAction(Company $company)
	{
		$contacts = $company->getContactsForSelect();
		
		return new JsonResponse($contacts);
	}
	
	/**
	 * @Route(
	 *     "/checkLog/{contact}",
	 *     name="ajax.check_log_started"
	 * )
	 * @param Contact $contact
	 * @return JsonResponse
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function checkIfLogAlreadyStartedForContact(Contact $contact) {
		$entityManager = $this->getDoctrine()->getManager();
		$timetrackerRepository = $entityManager->getRepository('App:Timetracker');
		$timetracker=$timetrackerRepository->findActiveByContact([$contact]);
		
		if($timetracker){
			return new JsonResponse([
				'status' => true
			]);
		}
		return new JsonResponse([
			'status' => false
		]);
	}
}