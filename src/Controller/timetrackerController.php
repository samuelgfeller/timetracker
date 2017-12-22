<?php
namespace App\Controller;

use App\Entity\Log;
use App\Form\TimetrackerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
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
	 * @Route("/erfassen", name="timetracker")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function newLog(Request $request) {
		$log = new Log();
		$options = [
			'action' => $this->generateUrl('timetracker'),
			'method' => 'post',
		];
		$form = $this->createForm(TimetrackerType::class, null, $options);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$company = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($company);
			$em->flush();
			return $this->redirectToRoute('companies');
		}
		return $this->render('timetracker/timetracker.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
 
}