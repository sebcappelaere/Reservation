<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Airport
 *
 * @ORM\Table(name="airport")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AirportRepository")
 */
class Airport
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\Length(
     *     min = 2,
     *     max = 100,
     *     minMessage = "Le nom de l'aéroport doit avoir au minimum {{ limit }} caractères",
     *     maxMessage = "Le nom de l'aéroport doit avoir au maximum {{ limit }} caractères"
     * )
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="airports")
     */
    private $city;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Flight", mappedBy="departureAirport")
     * @ORM\JoinTable(name="AirportFlight")
     */
    private $departureFlights;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Flight", mappedBy="arrivalAirport")
     * @ORM\JoinTable(name="AirportFlight")
     */
    private $arrivalFlights;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Airport
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return Airport
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flights = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add flight
     *
     * @param \AppBundle\Entity\Flight $flight
     *
     * @return Airport
     */
    public function addFlight(\AppBundle\Entity\Flight $flight)
    {
        $this->flights[] = $flight;

        return $this;
    }

    /**
     * Remove flight
     *
     * @param \AppBundle\Entity\Flight $flight
     */
    public function removeFlight(\AppBundle\Entity\Flight $flight)
    {
        $this->flights->removeElement($flight);
    }

    /**
     * Get flights
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlights()
    {
        return $this->flights;
    }
}
