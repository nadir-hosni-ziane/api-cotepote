<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations = {"get", "post"},
 *     itemOperations = {
 *         "get",
 *         "delete",
 *         "put" = {
 *             "denormalization_context" = {
 *                  "groups" = {"put:dependencies"}
 *              }
 *         }
 *     },
 *     paginationEnabled = false,
 * )
 */

class Dependency
{

    /**
     * @ApiProperty(
     *     identifier = true,
     * )
     */
    private string $uuid;

    /**
     * @ApiProperty(
     *     description = "Nom de la dépendance"
     * ),
     * @Assert\Length(
     *     min = 2
     * )
     * @NotBlank()
     */    
    private string $name;
    
    /**
     * @ApiProperty(
     *     description = "Version de la dépendance",
     *     openapiContext = {
     *         "exemple" = "5.2.*"
     *     }
     * )
     * @Assert\Length(
     *    min = 2
     * )
     * @NotBlank()
     * @Groups({"put:dependencies"})
     */    
    private string $version;

    public function __construct(
        string $name,
        string $version   
    ){
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
        $this->name = $name;
        $this->version = $version;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void 
    {
        $this->version = $version;
    }

}
