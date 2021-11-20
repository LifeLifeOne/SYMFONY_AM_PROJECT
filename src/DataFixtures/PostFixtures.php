<?php


namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $post = new Post();
            $post->setTitle("Recette : " . $i);
            $post->setContent("Contenu " . $i);
            $post->setDuration("Temps de préparation: " . rand(5, 60) . " minutes");
            $manager->persist($post);

            for ($j = 1; $j <= rand(5,10); $j++) {
                $comment = new Comment();
                $comment->setAuthor("Auteur: " . $j);
                $comment->setContent("Commentaire: " . $j);
                $comment->setPost($post);
                $manager->persist($comment);
            }

        }

        $manager->flush();
    }

}