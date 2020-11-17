<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    /**
     * @Route("/hello", name="hello_world")
     */
    public function helloWorld(): Response
    {
        return $this->render('hello_world/hello.html.twig');
    }

    /**
     * @Route("/hello/{name}", name="hello_dynamic")
     */
    public function helloDynamic(string $name): Response
    {
        return $this->render('hello_world/hello.html.twig', [
            'name' => $name,
        ]);
    }
}
