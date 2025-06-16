<?php

namespace Ranky\MediaBundle\Application\ListFilter\ListAvailableDatesFilter\Factory;

use Ranky\MediaBundle\Application\ListFilter\ListAvailableDatesFilter\ListAvailableDatesFilterResponse;
use Ranky\MediaBundle\Domain\Model\MediaInterface;
use Ranky\SharedBundle\Application\Dto\ResponseFormFilterDtoInterface;

class ListAvailableDatesFilterResponseFactory implements ListAvailableDatesFilterResponseFactoryInterface
{
    public function create(array|MediaInterface $media): ResponseFormFilterDtoInterface
    {
        return ListAvailableDatesFilterResponse::fromArray((array)$media);
    }
}