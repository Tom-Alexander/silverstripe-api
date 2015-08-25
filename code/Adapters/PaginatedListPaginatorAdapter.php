<?php

use League\Fractal\Pagination\PaginatorInterface;

class PaginatedListPaginatorAdapter implements PaginatorInterface
{

    /**
     * A paginator adapter for the silverstripe PaginatedList
     * @param PaginatedList $paginatedList
     */
    public function __construct(PaginatedList $paginatedList)
    {
        $this->paginatedList = $paginatedList;
    }

    /**
     * returns the total number of elements in the list
     * @return int
     */
    public function getCount()
    {
        return $this->paginatedList->getTotalItems();
    }

    /**
     * returns the index of the last page
     * @return int
     */
    public function getLastPage()
    {
        return $this->paginatedList->TotalPages();
    }

    /**
     * returns the number of elements per page
     * @return int
     */
    public function getPerPage()
    {
        return $this->paginatedList->getPageLength();
    }

    /**
     * returns the number of elements in the
     * current constrained list
     * @return int
     */
    public function getTotal()
    {
        $list = clone $this->paginatedList->getList();
        return count($list->limit(
            $this->getPerPage(),
            $this->paginatedList->getPageStart()
        ));
    }

    /**
     * returns the index of the current page
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->paginatedList->CurrentPage();
    }

    /**
     * returns the URL of the page with a specific
     * index
     * @param int $page
     * @return String
     */
    public function getUrl($page)
    {
        return HTTP::setGetVar(
            $this->paginatedList->getPaginationGetVar(),
            ($page - 1) * $this->paginatedList->getPageLength()
        );
    }

}