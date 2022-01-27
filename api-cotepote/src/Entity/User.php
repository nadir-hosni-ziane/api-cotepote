<?php

namespace App\Entity;


use App\Controller\Me;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\UserImageController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @ApiResource(
 *     normalizationContext = {"groups" = {"read:user"}},
 *     denormalizationContext = {"groups" = {"create:user"}},
 *     collectionOperations = {
 *        "get",
 *        "post"
 *     },
 *     itemOperations = {
 *         "get",
 *         "put" = {
 *             "denormalization_context" = {"groups" = {"change:user"}},
 *          },
 *         "patch" = {
 *             "denormalization_context" = {"groups" = {"change:relation"}},
 *         },
 *         "me" = {
 *            "pagination_enabled" = false,
 *            "path" = "/me",
 *            "method" = "get",
 *            "controller" = Me::class,
 *            "read" = false,
 *            "security" = "is_granted('ROLE_USER')",
 *            "openapi_context" = {
 *                "security" = {{"bearerAuth" = {}}} 
 *             },
 *         },
 *         "image" = {
 *              "method" = "post",
 *              "path" = "/users/{id}/image",
 *              "deserialize" = false,
 *              "controller" = UserImageController::class,
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
 *     }
 * )
 *  @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({ "read:bet", "read:user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:user", "change:user", "create:user"})
     * @Assert\Length(
     *     min = 5,
     *     max = 255
     * )
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read:user"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"change:user", "create:user"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:user", "read:bet", "change:user", "create:user"})
     * @Assert\Length(
     *     min = 2,
     *     max = 255
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:user", "read:bet", "change:user", "create:user"})
     * @Assert\Length(
     *     min = 2,
     *     max = 255
     * )
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Bet::class, mappedBy="user")
     */
    private $bets;

    /**
     * @ORM\OneToMany(targetEntity=UserOption::class, mappedBy="user", orphanRemoval=true)
     */
    private $userOptions;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="users_image", fileNameProperty="filePath")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $filePath;

    /**
     * @var string|null
     * @Groups({"read:user"})
     */
    private $fileurl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
        $this->userOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self 
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
         $this->password = $password;
        
         return $this;
        // $this->password = $password;
        
        // return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public static function createFromPayload($id, array $payload)
    {
        return (new User())->setId($id)->setEmail($payload['username'] ?? '');
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Bet[]
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets[] = $bet;
            $bet->setUser($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getUser() === $this) {
                $bet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserOption[]
     */
    public function getUserOptions(): Collection
    {
        return $this->userOptions;
    }

    public function addUserOption(UserOption $userOption): self
    {
        if (!$this->userOptions->contains($userOption)) {
            $this->userOptions[] = $userOption;
            $userOption->setUser($this);
        }

        return $this;
    }

    public function removeUserOption(UserOption $userOption): self
    {
        if ($this->userOptions->removeElement($userOption)) {
            // set the owning side to null (unless already changed)
            if ($userOption->getUser() === $this) {
                $userOption->setUser(null);
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
    public function setFile(?File $file): User
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
    public function setFileurl(?string $fileurl): User
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
