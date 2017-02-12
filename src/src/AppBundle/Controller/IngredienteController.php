<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingrediente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\IngredienteFormType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ingrediente")
 */
class IngredienteController extends Controller
{
    const PAGINATION_RESULT_PER_PAGE = 20;

    /**
     * @Route("/", name="app_bundle.ingrediente.index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $ingredienti = $this->getDoctrine()->getManager()->getRepository(Ingrediente::class)->getPaginationQuery();

        $pagination = $this->get('knp_paginator')->paginate(
            $ingredienti,
            $request->query->getInt('page', 1),
            self::PAGINATION_RESULT_PER_PAGE
        );

        return [
            'title'         => 'Ingrediente',
            'pagination'    => $pagination
        ];
    }

    /**
     * @Route("/{slug}/edit", name="app_bundle.ingrediente.edit")
     * @Template()
     */
    public function editAction($slug, Request $request)
    {
        $ingrediente = $this->getDoctrine()->getRepository(Ingrediente::class)->findOneBy(['slug' => $slug]);

        $form = $this->createForm(IngredienteFormType::class, $ingrediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->setFlash('ingrediente_edit', sprintf('Ingrediente %s aggiornato', $ingrediente->getNome()));

            return $this->redirectToRoute('app_bundle.ingrediente.edit', array('slug' => $slug));
        }

        return [
            'title'       => $ingrediente->getNome(),
            'ingrediente' => $ingrediente,
            'form'        => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/delete", name="app_bundle.ingrediente.delete")
     * @Template()
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ingrediente = $em->getRepository(Ingrediente::class)->find($id);

        $em->remove($ingrediente);
        $em->flush();

        $this->setFlash('ingrediente_delete', sprintf('Ingrediente %s cancellato', $ingrediente->getNome()));

        return $this->redirectToRoute('app_bundle.ingrediente.index');
    }

    /**
     * @Route("/new", name="app_bundle.ingrediente.new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $ingediente = new Ingrediente();

        $form = $this->createForm(IngredienteFormType::class, $ingediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingediente);
            $em->flush();

            $this->setFlash('ingrediente_new', 'Ingrediente creato');

            return $this->redirectToRoute('app_bundle.ingrediente.index');
        }

        return [
            'title' => 'Creazione ingediente',
            'form'  => $form->createView()
        ];
    }

    /**
     * @Route("/ajax")
     */
    public function ajaxSearchAction()
    {
        $response = new JsonResponse();
        $ingredienti = $this->getDoctrine()->getRepository(Ingrediente::class)->findAllByArray();

        return $response->setData($ingredienti);
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->get('session')->getFlashBag()->set($action, $value);
    }
}