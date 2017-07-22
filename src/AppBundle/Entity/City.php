<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 */
class City
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
     *     minMessage = "Le nom de ville doit avoir au minimum {{ limit }} caractères",
     *     maxMessage = "Le nom de ville doit avoir au maximum {{ limit }} caractères"
     * )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\Regex(pattern="/\d{5}/", message="Ce code postal n'est pas valide"
     * @ORM\Column(name="zipCode", type="string", length=5)
     */
    private $zipCode;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Airport", mappedBy="city")
     */
    private $airports;


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
     * @return City
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
     * Set zipCode
     *
     * @param integer $zipCode
     *
     * @return City
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return int
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->airports = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add airport
     *
     * @param \AppBundle\Entity\Airport $airport
     *
     * @return City
     */
    public function addAirport(\AppBundle\Entity\Airport $airport)
    {
        $this->airports[] = $airport;

        return $this;
    }

    /**
     * Remove airport
     *
     * @param \AppBundle\Entity\Airport $airport
     */
    public function removeAirport(\AppBundle\Entity\Airport $airport)
    {
        $this->airports->removeElement($airport);
    }

    /**
     * Get airports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAirports()
    {
        return $this->airports;
    }
}
