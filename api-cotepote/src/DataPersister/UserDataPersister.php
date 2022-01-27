<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        
    }

    /**
     * Is the data supported by the persister?
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data)
    {
        if ($data->getPassword()) {
            $data->setPassword(
                $this->userPasswordEncoderInterface->encodePassword($data, $data->getPassword())
            );
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * Removes the data.
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
