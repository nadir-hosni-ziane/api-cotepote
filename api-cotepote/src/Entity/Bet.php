<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\BetSetExpired;
use App\Repository\BetRepository;
use App\Controller\BetImageController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 * @ApiResource(
 *    normalizationContext = {"groups" = {"read:bet"}},
 *    denormalizationContext = {"groups" = {"create:bet"}},
 *    collectionOperations = {
 *        "post",
 *        "get" = {
 *           "openapi_context" = {
 *                "security" = {{"bearerAuth" = {}}} 
 *            },
 *         }
 *    },
 *    itemOperations = {
 *       "get",
 *       "delete" = {
 *          "normalization_context" = {"delete:bet"},
 *       },
 *       "patch" = {
 *          "denormalization_context" = {"change:trueBet"},
 *       },
 *       "setExpired" = {
 *          "method" = "POST",
 *          "path" = "/bets/{id}/setExpired",
 *          "controller" =BetSetExpired::class,
 *          "denormalization_context" = {"groups" = {"aucun"}},
 *          "openapi_context" = {
 *              "summary" = "Permet de set un pari en expiré",
 *          },
 *        },
 *       "image" = {
 *              "method" = "post",
 *              "path" = "/bets/{id}/image",
 *              "deserialize" = false,
 *              "controller" = BetImageController::class,
 *              "openapi_context" = {
 *                  "requestBody" = {
 *                      "content" = {
 *                          "multipart/form-data" = {
 *                              "schema" = {
 *                                  "type" = "object",
 *                                  "properties" = {
 *                                      "file" = {
 *                                          "type" = "string",
 *                                          "format" = "binary"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          }
 *    }
 * ),
 * @Vich\Uploadable()
 * @ApiFilter(SearchFilter::class, properties = {"title" : "partial", "user" : "exact", "expired" : "exact"})
 */
class Bet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:bet"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:bet"})
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:bet", "create:bet"})
     * @Assert\Length(
     *     min = 5,
     *     max = 255
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:bet", "create:bet"})
     * @Assert\Length(
     *     min = 5,
     *     max = 255
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:bet"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", options={"default": "0"})
     * @Groups({"read:bet", "change:expired", "change:trueBet"})
     */
    private $expired;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bets")
     * @Groups({"read:bet", "create:bet"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Option::class, mappedBy="bet", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups({"read:bet", "create:bet", "change:salut"})
     */
    private $options;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="bets_image", fileNameProperty="filePath")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $filePath;

    /**
     * @var string|null
     * @Groups({"read:bet"})
     */
    private $fileurl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $this->generateRandomString())->toString();
        $this->options = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getExpired(): ?bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setBet($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getBet() === $this) {
                $option->setBet(null);
            }
        }

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }
    
    

    /**
     * Get the value of file
     *
     * @return  File|null
     */ 
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @param  File|null  $file
     *
     * @return User
     */ 
    public function setFile(?File $file): Bet
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of fileurl
     *
     * @return  string|null
     */ 
    public function getFileurl(): ?string
    {
        return $this->fileurl;
    }

    /**
     * Set the value of fileurl
     *
     * @param  string|null  $fileurl
     *
     * @return  self
     */ 
    public function setFileurl(?string $fileurl): Bet
    {
        $this->fileurl = $fileurl;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
