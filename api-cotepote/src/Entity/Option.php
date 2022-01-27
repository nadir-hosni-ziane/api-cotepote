<?php

namespace App\Entity;

use App\Controller\PostSetTrue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OptionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OptionRepository::class)
 * @ORM\Table(name="`option`")
 * @ApiResource(
 *    collectionOperations={"get"},
 *    itemOperations = {
 *       "get",
 *       "delete",
 *       "patch" = {
 *          "denormalization_context" = {"groups" = {"change:option"}}
 *       },
 *       "setTrue" = {
 *          "method" = "POST",
 *          "path" = "/options/{id}/setTrue",
 *          "controller" =PostSetTrue::class,
 *          "denormalization_context" = {"groups" = {"aucun"}},
 *          "openapi_context" = {
 *              "summary" = "Permet de set le résultat juste d'un pari",
 *          },
 *       }
 *    }
 * )
 */
class Option
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:alloptions","read:userOption"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "read:bet", "create:bet"})
     * @Assert\Length(
     *     min = 3,
     *     max = 255
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default": "0"})
     * @Groups({"read:bet", "change:option"})
     */
    private $isTrue = false;

    /**
     * @ORM\ManyToOne(targetEntity=Bet::class, inversedBy="options")
     * @Groups({"read:userOption"})
     */
    private $bet;

    /**
     * @ORM\OneToMany(targetEntity=UserOption::class, mappedBy="options", orphanRemoval=true)
     * @Groups({"read:bet"})
     */
    private $userOptions;




    public function __construct()
    {
        $this->userOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsTrue(): ?bool
    {
        return $this->isTrue;
    }

    public function setIsTrue(bool $isTrue): self
    {
        $this->isTrue = $isTrue;

        return $this;
    }

    public function getBet(): ?bet
    {
        return $this->bet;
    }

    public function setBet(?bet $bet): self
    {
        $this->bet = $bet;

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
            $userOption->setOptions($this);
        }

        return $this;
    }

    public function removeUserOption(UserOption $userOption): self
    {
        if ($this->userOptions->removeElement($userOption)) {
            // set the owning side to null (unless already changed)
            if ($userOption->getOptions() === $this) {
                $userOption->setOptions(null);
            }
        }

        return $this;
    }

}
