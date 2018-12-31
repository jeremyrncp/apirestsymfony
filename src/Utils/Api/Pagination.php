<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Utils\Api;

class Pagination
{
    /**
     * @var array
     */
    private $links = array();

    /**
     * @param int $offset
     * @param int $resultsPerPage
     * @param int $numberResults
     *
     * @return array
     */
    public function getPagination(int $offset, int $resultsPerPage, int $numberResults)
    {
        $this->addLink('last', (floor($numberResults / $resultsPerPage)) * $resultsPerPage);

        if ($numberResults > $resultsPerPage) {
            $this->addLink('next', ($offset + $resultsPerPage));
        }

        $this->addLink('start', 0);

        return $this->links;
    }

    /**
     * @param string $name
     * @param int $offset
     */
    private function addLink(string $name, int $offset)
    {
        $this->links[$name] = '?offset=' . $offset;
    }
}
