<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuoteRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 *
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"quote:read"}},
 * )
 */
class Quote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("quote:read")
     * @ApiFilter(SearchFilter::class, strategy="partial")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     * @Groups("quote:read")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $meta;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="quotes")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @Groups("quote:read")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    private $auteur;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
