<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Airport;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('departureDate', DateType::class,
                [
                    "label" => "Date de départ",
                    "widget" => "single_text"
                ])
            ->add('departureTime', TimeType::class,
                [
                    "label" => "Heure de départ",
                    "widget" => "choice"
                ])
            ->add('arrivalDate', DateType::class,
                [
                    "label" => "Date d'arrivée",
                    "widget" => "single_text"
                ])
        ->add('arrivalTime', TimeType::class,
            [
                "label" => "Heure d'arrivée",
                "widget" => "choice"
            ])
            ->add('departureAirport', EntityType::class,
                [
                    "class" => 'AppBundle\Entity\Airport',
                    "query_builder" => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                    "choice_label" => "name",
                    "label" => "Aéroport de départ"
                ])
            ->add('arrivalAirport', EntityType::class,
                [
                    "class" => 'AppBundle\Entity\Airport',
                    "query_builder" => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                    "choice_label" => "name",
                    "label" => "Aéroport d'arrivée"
                ])
            ->add('company', EntityType::class,
                [
                    "class" => "AppBundle\Entity\Company",
                    "query_builder" => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                    "choice_label" => "name",
                    "label" => "Compagnie du vol"
                ])
        ->add('submit', SubmitType::class, ["label" => "Créer le vol"]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Flight'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_flight';
    }


}
