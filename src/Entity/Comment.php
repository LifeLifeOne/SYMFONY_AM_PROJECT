<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un pseudo"
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Votre pseudo doit contenir minimum {{ limit }} caractères",
     *      maxMessage = "Votre pseudo doit contenir maximum {{ limit }} caractères"
     * )
     */
    private string $author;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message = "Le commentaire ne peut pas être vide"
     * )
     * @Assert\Length(
     *      min = 10,
     *      max = 200,
     *      minMessage = "Votre commentaire doit contenir minimum {{ limit }} caractères",
     *      maxMessage = "Votre commentaire doit contenir maximum {{ limit }} caractères"
     * )
     */
    private string $content;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $publishedAt;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     */
    private Post $post;

    /**
     * Comment constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->publishedAt = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTimeImmutable $publishedAt
     */
    public function setPublishedAt(DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

}
