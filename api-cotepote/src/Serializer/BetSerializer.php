<?php

namespace App\Serializer;

use App\Entity\Bet;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class BetSerializer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    private const ALREAD_CALLED = 'AppBetSerializerAlreadyCalled';

    public function __construct(private StorageInterface $storage)
    {
        
    }

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return !isset($context[self::ALREAD_CALLED]) && $data instanceof Bet;
    }


    /**
     * @param Bet $object
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        $object->setFileurl($this->storage->resolveUri($object, 'file'));
        $context[self::ALREAD_CALLED] = true; 
        return $this->normalizer->normalize($object, $format, $context);
    }


}


