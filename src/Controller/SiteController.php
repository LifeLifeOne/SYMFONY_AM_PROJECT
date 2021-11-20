<?php


namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $recettes = $this->getDoctrine()->getRepository(Post::class)->findAll();
        return $this->render("index.html.twig", [
            "recettes" => $recettes
        ]);
    }

    /**
     * @Route("/recette-{id}", name="recette_read")
     * @param Post $recette
     * @return Response
     */
    public function read(Post $recette): Response
    {
//      $recette = $this->getDoctrine()->getRepository(Post::class)->find($id);
        return $this->render("recette.html.twig", [
            "recette" => $recette
        ]);
    }

}