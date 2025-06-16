<?php

namespace Ranky\MediaBundle\Application\ListFilter\ListAvailableDatesFilter\Factory;

use Ranky\MediaBundle\Domain\Model\MediaInterface;
use Ranky\SharedBundle\Application\Dto\ResponseFormFilterDtoInterface;

interface ListAvailableDatesFilterResponseFactoryInterface
{
    public function create(array|MediaInterface $media): ResponseFormFilterDtoInterface;
}