<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      security="is_granted('ROLE_USER')",
 *      openapiContext={
 *          "security"={
 *              {"bearerAuth"={}}
 *          }
 *      },
 *      collectionOperations={
 *          "get"={
 *              "security" = "is_granted('ROLE_USER')",
 *          },
 *          "post"= {
 *              "method" = "POST",
 *              "denormalization_context"={"groups"={"recipe:write"}},
 *          }
 *      },
 *     itemOperations={
 *         "get"={
 *             "security" = "(is_granted('ROLE_USER')",
 *         },
 *         "put"={
 *             "security" = "(is_granted('ROLE_USER') and object.getAuthor() == user) or is_granted('ROLE_ADMIN')",
 *         },
 *         "delete"={
 *             "security" = "(is_granted('ROLE_USER') and object.getAuthor() == user) or is_granted('ROLE_ADMIN')",
 *          }
 *     },
 *      normalizationContext={"groups"={"recipe:read"}},
 *      denormalizationContext={"groups"={"recipe:write"}})
 * @ORM\Entity
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"recipe:read", "recipe:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"recipe:read", "recipe:write"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"recipe:read", "recipe:write"})
     */
    private ?Category $category = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"recipe:read", "recipe:write"})
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Groups({"recipe:read"})
     */
    private $createdAt;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            // On vérifie si un setter correspondant existe pour chaque clé
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                // Appel du setter avec la valeur correspondante
                $this->$setter($value);
            }
        }
        if(!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory($category): self
    {
        if (is_numeric($category)) {
            $repository = new CategoryRepository();
            $category = $repository->find($category);
        }
        $this->category = $category;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}