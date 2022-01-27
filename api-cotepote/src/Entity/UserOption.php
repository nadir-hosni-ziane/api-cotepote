<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserOptionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=UserOptionRepository::class)
 * @ApiResource(
 *     normalizationContext = {"groups" = {"read:userOption"}},
 *     denormalizationContext = {"groups" = {"choose:Options"}},
 *     attributes={"pagination_enabled"=false},
 * )
 * @ApiFilter(SearchFilter::class, properties = {"user" : "exact", "options" : "exact"})
 */
class UserOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userOptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:userOption", "choose:Options"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class, inversedBy="userOptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:userOption", "choose:Options"})
     */
    private $options;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOptions(): ?option
    {
        return $this->options;
    }

    public function setOptions(?option $options): self
    {
        $this->options = $options;

        return $this;
    }
}
