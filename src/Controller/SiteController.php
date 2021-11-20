<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        $limit = $request->get("limit", 6);
        $page = $request->get("page", 1);
        $total = $this->getDoctrine()->getRepository(Post::class)->count([]);
        $recettes = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($total / $limit);
        $range = range(
            max($page - 2, 1),
            min($page + 2, $pages)
        );

        return $this->render("index.html.twig", [
            "recettes" => $recettes,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range
        ]);
    }

    /**
     * @Route("/recette-{id}", name="recette_read")
     * @param Request $request
     * @param Post $recette
     * @return Response
     */
    public function read(Request $request, Post $recette): Response
    {
        $comment = new Comment();
        $comment->setPost($recette);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("recette_read", [
                "id" => $recette->getId()
            ]);
        }

        return $this->render("recette.html.twig", [
            "recette" => $recette,
            "form" => $form->createView()
        ]);
    }

}