<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Tests\Utils\Api;

use App\Utils\Api\Pagination;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaginationTest extends KernelTestCase
{
    public function testMustObtainOneNextLinkWhenNumberResultsIsThirtyFive()
    {
        $pagination = new Pagination();
        $pagesLink = $pagination->getPagination(1, 20, 35);

        $this->assertCount(3,
            $pagesLink
        );
        $this->assertArrayHasKey('next', $pagesLink);
    }

    public function testMustObtainOneLinkWhenNumberResultsGreatherThanResultsPerPage()
    {
        $pagination = new Pagination();
        $pagesLink = $pagination->getPagination(1, 20, 18);

        $this->assertCount(2,
            $pagesLink
        );
    }
}
