<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Airport;
use AppBundle\Entity\City;
use AppBundle\Entity\Company;
use AppBundle\Form\AirportType;
use AppBundle\Form\CityType;
use AppBundle\Form\CompanyType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/admin", name="adminHome")
     */
    public function newEntities(Request $request){
        //Instance de l'entité Company
        $company = new Company();

        //Création du formulaire
        $form = $this->createForm(
            CompanyType::class,
            $company,
            ["method" => "post"]
        );

        //Injection des données postées dans le formulaire
        $form->handleRequest($request);

        //Persistence uniquement si le formulaire est soumis et si les tests de validation sont tous passés
        if ($form->isSubmitted() && $form->isValid()){
            try{
                //Persistence de l'entité Company
                $em = $this->getDoctrine()->getManager();
                $em->persist($company);
                $em->flush();

                //Ajout d'un message flash de création
                $this->addFlash("info", "La compagnie a bien été créée");

            } catch (UniqueConstraintViolationException $ex){
                $this->addFlash("error","Cette compagnie existe déjà");
            }

        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Company');
        $listCompany = $repo->createQueryBuilder('c')
            ->orderBy('c.name')->getQuery()->getResult();


        //Instance de l'entité City
        $city = new City();

        //Création du formulaire
        $formCity = $this->createForm(
            CityType::class,
            $city,
            ["method" => "post"]
        );

        //Injection des données postées dans le formulaire
        $formCity->handleRequest($request);

        //Persistence uniquement si le formulaire est soumis et si les tests de validation sont tous passés
        if ($formCity->isSubmitted() && $formCity->isValid()){
            try{
                $name = $formCity['name']->getData();
                $zipCode = $formCity['zipCode']->getData();

                //Recherche du couple (name,zipCode) dans la base de données
                $em = $this->getDoctrine()->getRepository('AppBundle:City');
                $qb = $em->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.name = :name')
                    ->andWhere('c.zipCode = :zipCode');
                $query = $qb->getQuery()
                    ->setParameter('name',$name)
                    ->setParameter('zipCode',$zipCode);
                $result = $query->getArrayResult();

                if(empty($result)){
                    //Persistence de l'entité City
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($city);
                    $em->flush();

                    //Ajout d'un message flash de création
                    $this->addFlash("info", "La ville a bien été créée");
                } else {
                    $this->addFlash("error","Cette ville existe déjà");
                }

            } catch (UniqueConstraintViolationException $ex){
                $this->addFlash("error","Cette ville existe déjà");
            }
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:City');
        $listCity = $repo->createQueryBuilder('c')
            ->orderBy('c.name')->getQuery()->getResult();


        //Instance de l'entité Airport
        $airport = new Airport();

        //Création du formulaire
        $formAirport = $this->createForm(
            AirportType::class,
            $airport,
            ["method" => "post"]
        );

        //Injection des données postées dans le formulaire
        $formAirport->handleRequest($request);

        //Persistence uniquement si le formulaire est soumis et si les tests de validation sont tous passés
        if ($formAirport->isSubmitted() && $formAirport->isValid()){
            try{
                //Persistence de l'entité City
                $em = $this->getDoctrine()->getManager();
                $em->persist($airport);
                $em->flush();

                //Ajout d'un message flash de création
                $this->addFlash("info", "L'aéroport a bien été créé");
            } catch (UniqueConstraintViolationException $ex){
            $this->addFlash("error","Cet aéroport existe déjà");
            }
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Airport');
        $listAirport = $repo->createQueryBuilder('a')
            ->orderBy('a.name')->getQuery()->getResult();


        //Affichage de la vue avec le formulaire
        return $this->render(
            "newEntities.html.twig",
            [
                "companyForm" => $form->createView(),
                "cityForm" => $formCity->createView(),
                "airportForm" => $formAirport->createView(),
                "listCompany" => $listCompany,
                "listCity" => $listCity,
                "listAirport" => $listAirport
            ]
        );

    }
}
