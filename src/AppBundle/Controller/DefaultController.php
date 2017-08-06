<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Airport;
use AppBundle\Entity\City;
use AppBundle\Entity\Company;
use AppBundle\Entity\Flight;
use AppBundle\Form\AirportType;
use AppBundle\Form\CityType;
use AppBundle\Form\CompanyType;
use AppBundle\Form\FlightType;
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

    /**
     * @Route("/addFlight", name="addFlight")
     */
    public function addFlight(Request $request){
        //Instance de l'entité Flight
        $flight = new Flight();

        //Création du formulaire
        $form = $this->createForm(
            FlightType::class,
            $flight,
            ["method" => "post"]
        );

        //Injection des données postées dans le formulaire
        $form->handleRequest($request);

        //Persistence uniquement si le formulaire est soumis et si les tests de validation sont tous passés
        if ($form->isSubmitted() && $form->isValid()){
            $errors = false;
            //Contrôle de l'arrivée postérieure au départ
            $departureDate = $form['departureDate']->getData();
            $departureTime = $form['departureTime']->getData();
            $arrivalDate = $form['arrivalDate']->getData();
            $arrivalTime = $form['arrivalTime']->getData();
            if ($departureDate>$arrivalDate or ($departureDate==$arrivalDate and $departureTime>=$arrivalTime)){
                $this->addFlash("error", "Le départ doit être antérieur à l'arrivée");
                $errors = true;
            }

            //Contrôle des aéroports de départ et d'arrivée différents
            if ($form['departureAirport']->getData()==$form['arrivalAirport']->getData()){
                $this->addFlash("error", "Les aéroports de départ et d'arrivée doivent être différents");
                $errors = true;
            }

            //Contrôle de l'unicité du vol en base de données
            $departureAirport = $form['departureAirport']->getData();
            $arrivalAirport = $form['arrivalAirport']->getData();
            $company = $form['company']->getData();
            $repo = $this->getDoctrine()->getRepository('AppBundle:Flight');
            $qb = $repo->createQueryBuilder('f')
               ->select('f')
                ->where("f.departureDate= :depDate 
                        AND f.departureTime= :depTime
                        AND f.arrivalDate= :arrDate
                        AND f.arrivalTime= :arrTime
                        AND f.departureAirport= :depAirport
                        AND f.arrivalAirport= :arrAirport
                        AND f.company= :company")
                ->setParameters(
                    [
                        'depDate' => date_format($departureDate,'Y-m-d'),
                        'depTime' => date_format($departureTime,'H:i:s'),
                        'arrDate' => date_format($arrivalDate,'Y-m-d'),
                        'arrTime' => date_format($arrivalTime,'H:i:s'),
                        'depAirport' => $departureAirport,
                        'arrAirport' => $arrivalAirport,
                        'company' => $company
                    ]
                );
            $flightBDD = $qb->getQuery()->getArrayResult();

            if ($flightBDD != []){
                $this->addFlash("error","Ce vol existe déjà");
                $errors = true;
            }

            //Persistence s'il n'y a pas d'erreurs
            if (!$errors){
                try{
                    //Persistence de l'entité Flight
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($flight);
                    $em->flush();

                    //Ajout d'un message flash de création
                    $this->addFlash("info", "Le vol a bien été créé");

                } catch (UniqueConstraintViolationException $ex){
                    $this->addFlash("error","Ce vol existe déjà");
                }
            }
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Flight');
        $listFlight = $repo->createQueryBuilder('f')
            ->orderBy('f.departureDate')
            ->addOrderBy('f.departureTime')
            ->addOrderBy('f.arrivalDate')
            ->addOrderBy('f.arrivalTime')
            ->getQuery()->getResult();

        //Affichage de la vue avec le formulaire
        return $this->render(
            "addFlight.html.twig",
            [
                "flightForm" => $form->createView(),
                "listFlight" => $listFlight
            ]
        );
    }
}
