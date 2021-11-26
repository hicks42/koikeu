<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @Vich\Uploadable
 */
class Attachment
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $attImage;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_attachments", fileNameProperty="attImage")
     * @Assert\Image(maxSize="8M", maxSizeMessage="Le fichier est trop gros")
     * @var File|null
     */
    private $attImageFile;

    /**
      * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="attachments")
      * @ORM\JoinColumn(nullable=false)
      */
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttImage(): ?string
    {
        return $this->attImage;
    }

    public function setAttImage(?string $attImage): self
    {
        $this->attImage = $attImage;

        return $this;
    }

    public function getAttImageFile(): ?File
    {
      return $this->attImageFile;
    }

    public function setAttImageFile(?File $attImageFile = null): void
    {
      $this->attImageFile = $attImageFile;

      if (null !== $attImageFile) {
        $this->setUpdatedAt(new \DateTimeImmutable);
      }
      $this->setCreatedAt(new \DateTimeImmutable);
    }

    public function getProduit(): ?produit
    {
        return $this->produit;
    }

    public function setProduit(?produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    #########################################
    // public function addProduit(Produit $produit): void
    // {
    //     if (!$this->attachments->contains($produit)) {
    //         $this->attachments->add($produit);
    //     }
    // }
}
