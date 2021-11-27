<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $limit = $request->get("limit", 6);
        $page = $request->get("page", 1);

        /** @var Paginator $recettes */
        $recettes = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );

        $pages = ceil($recettes->count() / $limit);
        $range = range(
            max($page - 2, 1),
            min($page + 2, $pages)
        );

        return $this->render("site/index.html.twig", [
            "recettes" => $recettes,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range
        ]);
    }

    /**
     * @Route("/recette/{id}", name="recette_read")
     * @param Request $request
     * @param Post $recette
     * @return Response
     * @throws \Exception
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

        return $this->render("site/recette.html.twig", [
            "recette" => $recette,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/publier-recette", name="recette_create")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger, string $uploadsAbsoluteDir, string $uploadsRelativeDir): Response
    {
        $recette = new Post();

        $form = $this->createForm(PostType::class, $recette, [
            "validation_groups" => ["Default", "create"]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();

            $fileName = sprintf(
                "%s_%s.%s",
                $slugger->slug($file->getClientOriginalName()),
                uniqid(),
                $file->getClientOriginalExtension()
            );

            $file->move($uploadsAbsoluteDir, $fileName);

            $recette->setImage($uploadsRelativeDir . "/" . $fileName);

            $this->getDoctrine()->getManager()->persist($recette);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("recette_read", [
                "id" => $recette->getId()
            ]);
        }

        return $this->render("site/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier-recette/{id}", name="recette_update")
     * @param Request $request
     * @param Post $recette
     * @param SluggerInterface $sluggerstring
     * @param $uploadsAbsoluteDir
     * @param string $uploadsRelativeDir
     * @return Response
     */
    public function update(Request $request, Post $recette, SluggerInterface $slugger, string $uploadsAbsoluteDir, string $uploadsRelativeDir): Response
    {
        $form = $this->createForm(PostType::class, $recette)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();

            if ($file !== null) {
                $fileName = sprintf(
                    "%s_%s.%s",
                    $slugger->slug($file->getClientOriginalName()),
                    uniqid(),
                    $file->getClientOriginalExtension()
                );

                $file->move($uploadsAbsoluteDir, $fileName);

                $recette->setImage($uploadsRelativeDir . "/" . $fileName);
            }


            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("recette_read", [
                "id" => $recette->getId()
            ]);
        }

        return $this->render("site/update.html.twig", [
            "form" => $form->createView()
        ]);
    }

}