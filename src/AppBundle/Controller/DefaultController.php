<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
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
     * @Route("/admin/addCompany", name="addCompany"
     */
    public function newCompany(Request $request){
        //Instance de l'entité Company
        $company = new Company();

        //Création du formulaire
        $form = $this->createForm(
            Company::class,
            $company,
            ["method" => "post"]
        );

        //Injection des données postées dans le formulaire
        $form->handleRequest($request);

        //Persistence uniquement si le formulaire est soumis et si les tests de validation sont tous passés
        if ($form->isSubmitted() && $form->isValid()){
            //Persistence de l'entité Company
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            //Ajout d'un message flash de création
            $this->addFlash("info", "La compagnie a bien été créée");
        }

        //Affichage de la vue avec le formulaire
        return $this->render(
            "newCompany.html.twig",
            ["companyForm" => $form->createView()]
        );

    }
}
