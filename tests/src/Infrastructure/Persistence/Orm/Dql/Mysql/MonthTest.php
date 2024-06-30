<?php

declare(strict_types=1);

namespace Ranky\MediaBundle\Tests\Infrastructure\Persistence\Orm\Dql\Mysql;

use Ranky\MediaBundle\Tests\Dummy\Media\Domain\Media;

class MonthTest extends BaseDbMysqlTestCase
{
    /**
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     * @throws \Doctrine\DBAL\Exception
     */
    public function testItShouldGetMonthQuery(): void
    {
        $em    = self::getEntityManager();
        $query = $em->createQuery('SELECT MONTH(m.createdAt) from '.Media::class.' m');

        $this->assertSame(
            'SELECT MONTH(r0_.created_at) AS sclr_0 FROM ranky_media r0_',
            $query->getSQL()
        );
    }
}
