<?php
namespace App\Controller;

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
     * @Route("/timetracker", name="home")
     */
    public function startPage() {
       
        return $this->render('timetracker/timetracker.php.twig');
    }
    /**
     * @Route("/timetracker/testForm")
     */
    public function newRequest(Request $request){
        $task = new Task();
        
        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid()){
            $task = $form->getData();

            return $this->redirectToRoute('home');
        }
    
        return $this->render('timetracker/newRequest.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}