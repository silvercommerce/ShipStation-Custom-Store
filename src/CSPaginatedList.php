<?php

namespace SilverCommerce\ShipStationCS;

use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\HTTPRequest;

/**
 * Shipstation passes a "Page Number" via the URL (not a start point)
 * so this custom list needs to do some extra work to calculate the
 * start point
 */
class CSPaginatedList extends PaginatedList
{
    const PARAM_PAGE_NUM = 'page';

    protected $getVar = self::PARAM_PAGE_NUM;

    /**
     * Convert a page number from the request to
     * an offset that can be used by paginated list
     *
     * @return int
     */
    public function getPageStart()
    {
        if (!empty($this->pageStart)) {
            return $this->pageStart;
        }

        /** @var HTTPRequest */
        $request = $this->getRequest();
        $page = (int)$request->getVar($this->getPaginationGetVar());
        $length = $this->getPageLength();

        $start = $page * $length - $length;

        return $start;
    }
}
