<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BetRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 * @ApiResource(
 *    normalizationContext = {"groups" = {"read:bet"}},
 *    denormalizationContext = {"groups" = {"create:bet"}},
 *    itemOperations = {
 *       "get",
 *       "delete",
 *    }
 * ),
 * @ApiFilter(SearchFilter::class, properties = {"title" : "partial"})
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
     * @ORM\Column(type="boolean")
     * @Groups({"read:bet", "change:expired"})
     */
    private $expired;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bets")
     * @Groups({"read:bet", "create:bet"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Option::class, mappedBy="bet", cascade={"persist"})
     * @Groups({"read:bet", "create:bet", "change:salut"})
     */
    private $options;

    public function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $this->generateRandomString())->toString();
        $this->expired = false;
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


}
