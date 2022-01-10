<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Institution;
use App\Entity\Usuario;
use App\Form\DonationType;
use App\Form\UsuarioType;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->requestStack->getSession()->start();
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator){
        //creamos el formulario
        $user = new Usuario();
        $form = $this->createForm(UsuarioType::class, $user);
        $user = $form->handleRequest($request)->getData();
        if($form->isSubmitted() && $form->isValid()){
            $errors = $validator->validate($user);
            if(count($errors) == 0){

                $checkedUser= new Usuario();
                $checkedUser = $doctrine->getRepository(Usuario::class)->findOneBy(
                    ['email'=>$user->getEmail()
                    ]
                );
                //Comprobamos si el usuario existe
                if(password_verify($user->getPassword(),$checkedUser->getPassword()) && $checkedUser){
                    //Guarda el ID y el nombre del usuario
                    $this->requestStack->getSession()->set('id', $checkedUser->getId());
                    $this->requestStack->getSession()->set('name', $checkedUser->getName());
                    return $this->render('menu.html.twig',[
                        'name'=>$this->requestStack->getSession()->get('name')
                    ]);
                }else{
                    return new Response('Incorrect information');
                }
            }else{
                $errorsString = (string)$errors;
                return new Response($errorsString);
            }
        }else{
            $this->requestStack->getSession()->set('id',0);
            return $this->renderForm('/forms/login.html.twig',
                ['form' => $form
                ]
            );
        }
    }

    /**
     * @Route("/sign", name="app_sign")
     */
    public function sign(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator){
        //creamos el formulario
        $user = new Usuario();
        $form = $this->createForm(UsuarioType::class, $user);
        $user = $form->handleRequest($request)->getData();
        if($form->isSubmitted() && $form->isValid()){
            $errors = $validator->validate($user);        $errors = $validator->validate($user);
            if(count($errors) == 0){
                $checkUser = $doctrine->getManager()->getRepository(Usuario::class)->findOneBy(
                    ['email'=>$user->getEmail()
                    ]
                );
                if(!$checkUser){
                    $user->setPassword(password_hash($user->getPassword(),PASSWORD_BCRYPT));
                    $doctrine->getManager()->persist($user);
                    $doctrine->getManager()->flush();

                    //Guardar el ID y el nombre del usuario
                    $session = $this->requestStack->getSession();
                    $session->set('id', $user->getId());
                    $session->set('name', $user->getName());
                    return $this->render('menu.html.twig',[
                        'name'=>$session->get('name')
                    ]);
                }else{
                    return new Response('Email already exists');
                }
            }else{
                $errorsString = (string)$errors;
                return new Response($errorsString);
            }
        }else{
            $this->requestStack->getSession()->set('id',0);
            return $this->renderForm('/forms/sign.html.twig',
                ['form' => $form
                ]
            );
        }
    }

    /**
     *@Route("/institutions", name="app_inst", methods={"GET"})
     */
    public function checkInstitutions(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator){
        if($this->requestStack->getSession()->get('id') != 0){
            $institutions = $doctrine->getRepository(Institution::class)->findAll();
            return $this->render('institutions.html.twig',
                [
                    'institutions'=>$institutions,
                    'name' =>$this->requestStack->getSession()->get('name')
                ]);
        }else{
            return $this->login($doctrine,$request,$validator);
        }
    }

    /**
     *@Route("/institutions/donate/{idinsti}", name="app_donate")
     */
    public function donate(ManagerRegistry $doctrine, $idinsti, Request $request, ValidatorInterface $validator){
        if($this->requestStack->getSession()->get('id') != 0){
            $donation = new Donation();
            $form = $this->createForm(DonationType::class, $donation);
            if($idinsti == 0){
                if($_POST['amount'] != null){
                    $donation->setIdinsti($this->requestStack->getSession()->get('idinsti'));
                    $donation->setIduser($this->requestStack->getSession()->get('id'));
                    date_default_timezone_set('GMT');
                    $donation->setDate(date("m.d.y"));
                    $donation->setAmount($_POST['amount']);

                    $doctrine->getManager()->persist($donation);
                    $doctrine->getManager()->flush();
                    return $this->CheckDonations($doctrine, $request,$validator);
                }else{
                    return new Response('The amount value is null');
                }
            }else{
                $this->requestStack->getSession()->set('idinsti', $idinsti);
                return $this->renderForm('/forms/donation.html.twig',
                    [
                        'form'=>$form,
                        'name'=>strval($this->requestStack->getSession()->get('name')),
                    ]);
            }
        }else{
            return $this->login($doctrine,$request,$validator);
        }
    }

    /**
     *@ROUTE("/donations", name="app_donations",methods={"GET"})
     */
    public function CheckDonations(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator){
        if($this->requestStack->getSession()->get('id') != 0){
            $donations = $doctrine->getRepository(Donation::class)->findDonations($this->requestStack->getSession()->get('id'));
            return $this->render('donations.html.twig',
                [
                    'donations'=>$donations,
                    'name' =>$this->requestStack->getSession()->get('name')
                ]);
        }else{
            return $this->login($doctrine,$request,$validator);
        }
    }

}
