<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ricetta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RicettaRepository")
 * @UniqueEntity("nome", message="Esiste già una ricetta con questo nome")
 */
class Ricetta
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
     *
     * @ORM\Column(name="nome", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Il nome della ricetta dovrebbe essere di almeno {{ limit }} caratteri",
     *      maxMessage = "Il nome della ricetta non dovrebbe superare {{ limit }} caratteri"
     * )
     */
    private $nome;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"nome"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dataCreazione", type="datetime")
     */
    private $dataCreazione;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="dataModifica", type="datetime")
     */
    private $dataModifica;

    /**
     * @ORM\ManyToMany(targetEntity="Ingrediente", inversedBy="ricette", cascade={"persist"})
     * @ORM\JoinTable(name="ricette_ingredienti")
     * @Assert\Valid
     */
    private $ingredienti;

    public function __construct()
    {
        $this->ingredienti = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNome();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nome
     *
     * @return Ricetta
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $slug
     *
     * @return Ricetta
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param \DateTime $dataCreazione
     *
     * @return Ricetta
     */
    public function setDataCreazione($dataCreazione)
    {
        $this->dataCreazione = $dataCreazione;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataCreazione()
    {
        return $this->dataCreazione;
    }

    /**
     * @param \DateTime $dataModifica
     *
     * @return Ricetta
     */
    public function setDataModifica($dataModifica)
    {
        $this->dataModifica = $dataModifica;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataModifica()
    {
        return $this->dataModifica;
    }

    /**
     * @param Ingrediente $ingrediente
     *
     * @return Ricetta
     */
    public function addIngredienti(Ingrediente $ingrediente)
    {
        $this->ingredienti[] = $ingrediente;
        $ingrediente->addRicette($this);

        return $this;
    }

    /**
     * @param Ingrediente $ingrediente
     */
    public function removeIngredienti(Ingrediente $ingrediente)
    {
        $this->ingredienti->removeElement($ingrediente);
    }

    /**
     * @return Collection
     */
    public function getIngredienti()
    {
        return $this->ingredienti;
    }

    /**
     * @param ArrayCollection|PersistentCollection $ingredienti
     */
    public function setIngredienti($ingredienti)
    {
        $this->ingredienti = $ingredienti;
    }
}
