<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;//package pour lire l'écriture (#...)

#[ORM\Entity(repositoryClass: AuthorRepository::class)]//classe entity relié à repository
class Author
{
    #[ORM\Id] //Id : écriture, annotation: clé primaire
    #[ORM\GeneratedValue] //auto icrémente dans la bd
    #[ORM\Column] 
    private ?int $id = null; //objet (si je veux ref comme clé primaire on supprime ligne 12: non auto increment)

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\Column]
    private ?int $nb_books = null;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    private ?Book $nb_book = null;

    #[ORM\ManyToOne(inversedBy: 'ok')]
    private ?Book $NbBooks1 = null;

    #[ORM\Column]
    private ?int $nb__books = null;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $NB_BOOKS = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nb_books;
    }

    public function setNbBooks(int $nb_books): static
    {
        $this->nb_books = $nb_books;

        return $this;
    }

    public function getNbBook(): ?Book
    {
        return $this->nb_book;
    }

    public function setNbBook(?Book $nb_book): static
    {
        $this->nb_book = $nb_book;

        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    
}
