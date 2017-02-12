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
 * @ORM\Table(name="ingrediente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IngredienteRepository")
 * @UniqueEntity("nome", message="Esiste già un ingrediente con questo nome")
 */
class Ingrediente
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
     *      minMessage = "Il nome dell'ingrediente dovrebbe essere di almeno {{ limit }} caratteri",
     *      maxMessage = "Il nome dell'ingrediente non dovrebbe superare {{ limit }} caratteri"
     * )
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "La descrizione dell'ingrediente dovrebbe essere di almeno {{ limit }} caratteri",
     *      maxMessage = "La descrizione dell'ingrediente non dovrebbe superare {{ limit }} caratteri"
     * )
     */
    private $descrizione;

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
     * @ORM\ManyToMany(targetEntity="Ricetta", mappedBy="ingredienti")
     * @Assert\Valid
     */
    private $ricette;

    public function __construct()
    {
        $this->ricette = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSlug();
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
     * @return Ingrediente
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
     * @param string $descrizione
     *
     * @return Ingrediente
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @param string $slug
     *
     * @return Ingrediente
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
     * @return Ingrediente
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
     * @return Ingrediente
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
     * @param Ricetta $ricetta
     *
     * @return Ingrediente
     */
    public function addRicette(Ricetta $ricetta)
    {
        $this->ricette[] = $ricetta;
        $ricetta->addIngredienti($this);

        return $this;
    }

    /**
     * @param Ricetta $ricetta
     */
    public function removeRicette(Ricetta $ricetta)
    {
        $this->ricette->removeElement($ricetta);
    }

    /**
     * @return Collection
     */
    public function getRicette()
    {
        return $this->ricette;
    }

    /**
     * @param ArrayCollection|PersistentCollection $ricette
     */
    public function setRicette($ricette)
    {
        $this->ricette = $ricette;
    }
}
