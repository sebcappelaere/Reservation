<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Flight
 *
 * @ORM\Table(name="flight")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlightRepository")
 */
class Flight
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
     * @var \DateTime
     * @Assert\Date(message = "Cette date n'est pas valide")
     * @ORM\Column(name="departureDate", type="date")
     */
    private $departureDate;

    /**
     * @var \DateTime
     * @Assert\Time (message = "Cette heure n'est pas valide")
     * @ORM\Column(name="departureTime", type="time")
     */
    private $departureTime;

    /**
     * @var \DateTime
     * @Assert\Date(message = "Cette date n'est pas valide")
     * @ORM\Column(name="arrivalDate", type="date")
     */
    private $arrivalDate;

    /**
     * @var \DateTime
     * @Assert\Time (message = "Cette heure n'est pas valide")
     * @ORM\Column(name="arrivalTime", type="time")
     */
    private $arrivalTime;

    /**
     * @var Airport
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport", inversedBy="departureFlights")
     */
    private $departureAirport;

    /**
     * @var Airport
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport", inversedBy="arrivalFlights")
     */
    private $arrivalAirport;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="flights")
     */
    private $company;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reservation", mappedBy="flight")
     */
    private $reservations;


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
     * Set departureDate
     *
     * @param \DateTime $departureDate
     *
     * @return Flight
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set departureTime
     *
     * @param \DateTime $departureTime
     *
     * @return Flight
     */
    public function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    /**
     * Get departureTime
     *
     * @return \DateTime
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     *
     * @return Flight
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set arrivalTime
     *
     * @param \DateTime $arrivalTime
     *
     * @return Flight
     */
    public function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    /**
     * Get arrivalTime
     *
     * @return \DateTime
     */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->airports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set departureAirport
     *
     * @param \AppBundle\Entity\Airport $departureAirport
     *
     * @return Flight
     */
    public function setDepartureAirport(\AppBundle\Entity\Airport $airport = null)
    {
        $this->departureAirport = $airport;

        return $this;
    }

    /**
     * Get departureAirport
     *
     * @return \AppBundle\Entity\Airport
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * Set arrivalAirport
     *
     * @param \AppBundle\Entity\Airport $arrivalAirport
     *
     * @return Flight
     */
    public function setArrivalAirport(\AppBundle\Entity\Airport $airport = null)
    {
        $this->arrivalAirport = $airport;

        return $this;
    }

    /**
     * Get arrivalAirport
     *
     * @return \AppBundle\Entity\Airport
     */
    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return Flight
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Flight
     */
    public function addReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }
}
