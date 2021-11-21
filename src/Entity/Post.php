<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @var int/null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un titre"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Votre titre doit contenir minimum {{ limit }} caractères",
     *      maxMessage = "Votre titre doit contenir maximum {{ limit }} caractères"
     * )
     */
    private string $title;

    /**
     * @var string|null
     * @ORM\Column
     */
    private ?string $picture = null;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner une recette"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 1000,
     *      minMessage = "La recette doit contenir minimum {{ limit }} caractères",
     *      maxMessage = "La recette doit contenir minimum {{ limit }} caractères",
     * )
     */
    private string $content;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un temps de préparation"
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 3,
     *      minMessage = "La durée de préparation minimum est de 5 minutes",
     *      maxMessage = "La durée de préparation maximum est de 180 minutes"
     * )
     */
    private string $duration;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $publishedAt;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private Collection $comments;

    /**
     * Post constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->publishedAt = new DateTimeImmutable();
        $this->comments = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
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
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration(string $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

}
