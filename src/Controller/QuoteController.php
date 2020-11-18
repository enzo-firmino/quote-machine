<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuoteController extends AbstractController
{
    /**
     * @Route("/quotes", name="quote_index")
     */
    public function index(Request $request): Response
    {
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->createQueryBuilder('q');

        $search = $request->get('search');
        if (!empty($search)) {
            $queryBuilder = $queryBuilder->where('q.content LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        return $this->render('quote/index.html.twig', [
            'quotes' => $queryBuilder->getQuery()->getResult(),
        ]);
    }

    /**
     * @Route("/quotes/new", name="quote_new", methods={"GET", "POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request)
    {
        $quote = new Quote();

        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('quote_index');
        }

        return $this->render('quote/new.html.twig', [
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quotes/{id}/edit", name="quote_edit", methods={"GET", "POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Quote $quote): Response
    {
        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quote_index');
        }

        return $this->render('quote/edit.html.twig', [
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quotes/{id}/delete", name="quote_delete", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function delete(Quote $quote): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('quote_index');
    }
}
