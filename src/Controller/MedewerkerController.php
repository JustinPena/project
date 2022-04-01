<?php

namespace App\Controller;


use App\Entity\Activiteit;
use App\Entity\Soortactiviteit;
use App\Form\ActiviteitType;
use App\Form\SoortactiviteitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MedewerkerController extends AbstractController
{
    /**
     * @Route("/admin/activiteiten", name="activiteitenoverzicht")
     */
    public function activiteitenOverzichtAction()
    {

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();

        return $this->render('medewerker/activiteiten.html.twig', [
            'activiteiten'=>$activiteiten
        ]);
    }

    /**
     * @Route("/admin/soortactiviteiten", name="soortactiviteitenoverzicht")
     */
    public function soortactiviteitenOverzichtAction()
    {

        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/soortactiviteiten.html.twig', [
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/details/{id}", name="details")
     */
    public function detailsAction($id)
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $activiteit=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->find($id);

        $deelnemers=$this->getDoctrine()
            ->getRepository('App:User')
            ->getDeelnemers($id);


        return $this->render('medewerker/details.html.twig', [
            'activiteit'=>$activiteit,
            'deelnemers'=>$deelnemers,
            'aantal'=>count($activiteiten)
        ]);
    }

    /**
     * @Route("/admin/soortdetails/{id}", name="soortdetails")
     */
    public function soortdetailsAction($id)
    {
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();
        $soortactiviteit=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->find($id);

        $deelnemers=$this->getDoctrine()
            ->getRepository('App:User')
            ->getDeelnemers($id);


        return $this->render('medewerker/details2.html.twig', [
            'soortactiviteit'=>$soortactiviteit,
            'deelnemers'=>$deelnemers,
            'aantal'=>count($soortactiviteiten)
        ]);
    }

    /**
     * @Route("/admin/beheer", name="beheer")
     */
    public function beheerAction()
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();

        return $this->render('medewerker/beheer.html.twig', [
            'activiteiten'=>$activiteiten
        ]);
    }

    /**
     * @Route("/admin/beheer2", name="beheer2")
     */
    public function beheer2Action()
    {
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/beheer2.html.twig', [
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/add", name="add")
     */
    public function addAction(Request $request)
    {
        // create a user and a contact
        $a=new Activiteit();

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"voeg toe"));
        //$form->add('reset', ResetType::class, array('label'=>"reset"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();

            $this->addFlash(
                'notice',
                'activiteit toegevoegd!'
            );
            return $this->redirectToRoute('beheer');
        }
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        return $this->render('medewerker/add.html.twig',array('form'=>$form->createView(),'naam'=>'toevoegen','aantal'=>count($activiteiten)
            ));
    }

    /**
     * @Route("/admin/soortadd", name="soortadd")
     */
    public function addAction2(Request $request)
    {
        // create a user and a contact
        $s=new Soortactiviteit();

        $form = $this->createForm(Soortactiviteit::class, $s);
        $form->add('save', SubmitType::class, array('label'=>"voeg toe"));
        //$form->add('reset', ResetType::class, array('label'=>"reset"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($s);
            $em->flush();

            $this->addFlash(
                'notice',
                'soortactiviteit toegevoegd!'
            );
            return $this->redirectToRoute('beheer2');
        }
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();
        return $this->render('medewerker/add2.html.twig',array('form'=>$form->createView(),'naam'=>'toevoegen','aantal'=>count($soortactiviteiten)
        ));
    }

    /**
     * @Route("/admin/update/{id}", name="update")
     */
    public function updateAction($id,Request $request)
    {
        $a=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->find($id);

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"aanpassen"));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the contact (no queries yet)
            $em->persist($a);


            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            $this->addFlash(
                'notice',
                'activiteit aangepast!'
            );
            return $this->redirectToRoute('beheer');
        }

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();

        return $this->render('medewerker/add.html.twig',array('form'=>$form->createView(),'naam'=>'aanpassen','aantal'=>count($activiteiten)));
    }

    /**
     * @Route("/admin/soortupdate/{id}", name="soortupdate")
     */
    public function updateAction2($id,Request $request)
    {
        $s=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->find($id);

        $form = $this->createForm(SoortactiviteitType::class, $s);
        $form->add('save', SubmitType::class, array('label'=>"aanpassen"));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the contact (no queries yet)
            $em->persist($s);


            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            $this->addFlash(
                'notice',
                'soortactiviteit aangepast!'
            );
            return $this->redirectToRoute('beheer2');
        }

        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/add2.html.twig',array('form'=>$form->createView(),'naam'=>'aanpassen','aantal'=>count($soortactiviteiten)));
    }

    /**
     * @Route("/admin/delete/{id}", name="delete")
     */
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $a= $this->getDoctrine()
            ->getRepository('App:Activiteit')->find($id);
        $em->remove($a);
        $em->flush();

        $this->addFlash(
            'notice',
            'activiteit verwijderd!'
        );
        return $this->redirectToRoute('beheer');

    }

    /**
     * @Route("/admin/soortdelete/{id}", name="soortdelete")
     */
    public function deleteAction2($id)
    {
        $em=$this->getDoctrine()->getManager();
        $s= $this->getDoctrine()
            ->getRepository('App:Soortactiviteit')->find($id);
        $em->remove($s);
        $em->flush();

        $this->addFlash(
            'notice',
            'soortactiviteit verwijderd!'
        );
        return $this->redirectToRoute('beheer2');

    }
}
