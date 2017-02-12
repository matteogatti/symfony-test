<?php
namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Ingrediente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\RequestStack;

class IngredienteToIdTransformer implements DataTransformerInterface
{
    private $request;
    private $entityManager;

    public function __construct(RequestStack $request, EntityManagerInterface $entityManager)
    {
        $this->request       = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @param  Ingrediente|null $ingredienti
     * @return string
     */
    public function transform($ingredienti)
    {
        $result = array();

        if (!($ingredienti instanceof PersistentCollection)) {
            return new ArrayCollection();
        }

        foreach ($ingredienti as $key => $value) {
            $result[] = $value->getId();
        }

        return implode(';', $result);
    }

    /**
     * @param mixed $ingredienti
     * @return ArrayCollection|PersistentCollection
     */
    public function reverseTransform($ingredienti)
    {
        $request = $this->request->getCurrentRequest();
        if (isset($request->get('ricetta_form')['magicsuggest'])) {
            $newArray = array();
            $ingredienti = $request->get('ricetta_form')['magicsuggest'];

            foreach ($ingredienti as $key => $value) {
                $item = $this->entityManager->getRepository(Ingrediente::class)->findOneBy(array('id' => $value));

                if (!is_null($item)) {
                    $newArray[$key] = $item;
                }
            }

            return new PersistentCollection($this->entityManager, Ingrediente::class, new ArrayCollection($newArray));
        }

        return new ArrayCollection();
    }
}