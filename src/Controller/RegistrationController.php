<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use function PHPSTORM_META\type;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/reg", name="reg")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passEncoder ): Response
    {
        $regForm = $this->createFormBuilder()
        ->add('username', TextType::class)
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'],
        ])
        ->add('sing_out', SubmitType::class)
        ->getForm();

        $regForm->handleRequest($request);

        if( $regForm->isSubmitted() ){

            $em = $this->getDoctrine()->getManager();

            $data = $regForm->getData();

            $user = new User;
            $user->setUsername($data['username']);

            
            // $user = $em->$VarName = $repo->findBy(['property'=>value]);

            // if (!$user) {
            //     $this->addFlash('danger', 'User not found for '. $data['username']);
            // }

            $user->setPassword(
                $passEncoder->encodePassword($user, $data['password'])
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'You have been successfully registered! Congratulations');

            return $this->redirect($this->generateUrl('main'));
        }

        return $this->render('registration/index.html.twig', [
            'regFrom' => $regForm->createView()
        ]);
    }
}
