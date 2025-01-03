<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $comments;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: "likedArticle")]
    #[ORM\JoinTable(name:"user_article")]
    private Collection $hasLiked;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Image $image = null;

    #[ORM\PrePersist]
    public function setCreateTime()
    {
        $this->date = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->hasLiked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComments(Message $comments): static
    {
        if (!$this->comments->contains($comments)) {
            $this->comments->add($comments);
            $comments->setArticle($this);
        }

        return $this;
    }

    public function removeComments(Message $comments): static
    {
        if ($this->comments->removeElement($comments)) {
            // set the owning side to null (unless already changed)
            if ($comments->getArticle() === $this) {
                $comments->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getHasLiked(): Collection
    {
        return $this->hasLiked;
    }

    public function addHasLiked(User $hasLiked): static
    {
        if (!$this->hasLiked->contains($hasLiked)) {
            $this->hasLiked->add($hasLiked);
        }

        return $this;
    }

    public function removeHasLiked(User $hasLiked): static
    {
        $this->hasLiked->removeElement($hasLiked);

        return $this;
    }

    public function getImage(): ?Image
    {
        if (empty($this->image)) {
            // Returns a broken image if the image isn't set, which should avoid errors
            $image = new Image();
            $image->setPath("article.webp");
            return $image;
        }
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}
