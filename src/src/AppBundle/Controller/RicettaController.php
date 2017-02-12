<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingrediente;
use AppBundle\Entity\Ricetta;
use AppBundle\Form\Type\RicettaFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ricetta")
 */
class RicettaController extends Controller
{
    const PAGINATION_RESULT_PER_PAGE = 20;

    /**
     * @Route("/", name="app_bundle.ricetta.index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filterArgs = $this->normalizeFilterArgs($request->query->all());

        $ricette = $this->getDoctrine()->getRepository(Ricetta::class)->getPaginationQuery($filterArgs);
        $ingredienti = $this->getDoctrine()->getRepository(Ingrediente::class)->findAll();

        $pagination = $this->get('knp_paginator')->paginate(
            $ricette,
            $request->query->getInt('page', 1),
            self::PAGINATION_RESULT_PER_PAGE
        );

        return [
            'title'       => 'Ricetta',
            'ingredienti' => $ingredienti,
            'pagination'  => $pagination
        ];
    }

    /**
     * @Route("/{slug}/edit", name="app_bundle.ricetta.edit")
     * @Template()
     */
    public function editAction($slug, Request $request)
    {
        $ricetta = $this->getDoctrine()->getRepository(Ricetta::class)->findOneBy(['slug' => $slug]);

        $form = $this->createForm(RicettaFormType::class, $ricetta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->setFlash('ricetta_edit', sprintf('Ricetta %s aggiornata', $ricetta->getNome()));

            return $this->redirectToRoute('app_bundle.ricetta.edit', array('slug' => $slug));
        }

        return [
            'title'   => $ricetta->getNome(),
            'ricetta' => $ricetta,
            'form'    => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/delete", name="app_bundle.ricetta.delete")
     * @Template()
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ricetta = $em->getRepository(Ricetta::class)->find($id);

        $em->remove($ricetta);
        $em->flush();

        $this->setFlash('ricetta_delete', sprintf('Ricetta %s cancellata', $ricetta->getNome()));

        return $this->redirectToRoute('app_bundle.ricetta.index');
    }

    /**
     * @Route("/new", name="app_bundle.ricetta.new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $ricetta = new Ricetta();

        $form = $this->createForm(RicettaFormType::class, $ricetta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ricetta);
            $em->flush();

            $this->setFlash('ricetta_new', 'Ricetta creata');

            return $this->redirectToRoute('app_bundle.ricetta.index');
        }

        return [
            'title' => 'Creazione ricetta',
            'form'  => $form->createView()
        ];
    }

    /**
     * @Route("/ajax")
     */
    public function ajaxSearchAction()
    {
        $response = new JsonResponse();
        $utenti = $this->getDoctrine()->getRepository(Ricetta::class)->findAllValidArray();

        return $response->setData($utenti);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function normalizeFilterArgs(array $args) {
        /** remove knp paginator args */
        unset($args['sort']);
        unset($args['direction']);
        unset($args['page']);

        return $args;
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