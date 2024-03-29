<?php

namespace App\Entity;

use App\Entity\Attachment;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ORM\Table(name="produits")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Produit
{
  use Timestampable;

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank(message="Un nom doit être renseigné")
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $slug;

  /**
   * @ORM\Column(type="text")
   * @Assert\NotBlank(message="Une description doit être renseigné")
   * @Assert\Length(min=20, minMessage="Une description doit faire au moins 20 caractères")
   */
  private $description;

  /**
   * @ORM\Column(type="float")
   * @Assert\NotBlank(message="Un prix doit être renseigné")
   */
  private $price;

  /**
   * NOTE: This is not a mapped field of entity metadata, just a simple property.
   *
   * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
   * @Assert\Image(maxSize="8M", maxSizeMessage="Le fichier est trop gros")
   * @var File|null
   */
  private $imageFile;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $imageName;

  /**
   * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="produit", orphanRemoval=true, cascade={"persist"})
   * @var Collection
   */
  protected $attachments;

  /**
   * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="produit_id")
   * @ORM\JoinColumn(nullable=true)
   */
  private $category;

  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="produits")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;

  public function __construct()
  {
    $this->attachments = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setImageFile(?File $imageFile = null): void
  {
    $this->imageFile = $imageFile;

    if (null !== $imageFile) {
      $this->setUpdatedAt(new \DateTimeImmutable);
    }
  }

  public function getImageFile(): ?File
  {
    return $this->imageFile;
  }

  public function setImageName(?string $imageName): void
  {
    $this->imageName = $imageName;
  }

  public function getImageName(): ?string
  {
    return $this->imageName;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getPrice(): ?float
  {
    return $this->price;
  }

  public function setPrice(float $price): self
  {
    $this->price = $price;

    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): self
  {
    $this->user = $user;

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



  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(?string $slug): self
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * @return Collection|Attachment[]
   */
  public function getAttachments(): Collection
  {
    return $this->attachments;
  }

  public function addAttachment(Attachment $attachment): self
  {
    if (!$this->attachments->contains($attachment)) {
      $this->attachments[] = $attachment;
      $attachment->setProduit($this);
    }

    return $this;
  }

  public function removeAttachment(Attachment $attachment): self
  {
    if ($this->attachments->removeElement($attachment)) {
      // set the owning side to null (unless already changed)
      if ($attachment->getProduit() === $this) {
        $attachment->setProduit(null);
      }
    }

    return $this;
  }
}
