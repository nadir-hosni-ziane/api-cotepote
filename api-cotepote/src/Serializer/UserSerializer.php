<?php

namespace App\Serializer;

use App\Entity\User;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class UserSerializer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'AppUserSerializerAlreadyCalled';

    public function __construct(private StorageInterface $storage)
    {
        
    }

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof User;
    }


    /**
     * @param User $object
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        $object->setFileurl($this->storage->resolveUri($object, 'file'));
        $context[self::ALREADY_CALLED] = true; 
        return $this->normalizer->normalize($object, $format, $context);
    }

}
