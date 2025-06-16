<?php
declare(strict_types=1);

namespace Ranky\MediaBundle\Application\ListFilter\ListAvailableDatesFilter;


use Ranky\MediaBundle\Application\ListFilter\ListAvailableDatesFilter\Factory\ListAvailableDatesFilterResponseFactoryInterface;
use Ranky\MediaBundle\Domain\Contract\AvailableDatesMediaRepositoryInterface;

class ListAvailableDatesFilter
{
    public function __construct(
        private readonly AvailableDatesMediaRepositoryInterface $availableDatesMediaRepository,
        private readonly ListAvailableDatesFilterResponseFactoryInterface $responseFactory,
    )
    {
    }

    /**
     * @return array<ListAvailableDatesFilterResponse>
     */
    public function __invoke(): array
    {
        return \array_map(
            fn($mediaData) => $this->responseFactory->create($mediaData),
            $this->availableDatesMediaRepository->getAll(),
            []
        );
    }
}
