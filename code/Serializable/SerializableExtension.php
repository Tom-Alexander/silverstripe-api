<?php

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

class SerializableExtension extends DataExtension implements SerializableModel
{

    /**
     * @param int $id
     * @param array $options
     * @return \League\Fractal\Scope
     */
    public function getSerializedData($id, array $options = array())
    {
        $manager = new Manager();
        $item = DataObject::get_by_id($this->owner->className, $id);
        $manager->setSerializer($this->getSerializer());
        $resource = new Item($item, $this->getTransformer());

        if(isset($options['include'])) {
            $manager->parseIncludes($options['include']);
        }

        return $manager->createData($resource);
    }

    /**
     * Serialize a List of DataObject
     * @param $options
     * @return \League\Fractal\Scope
     */
    public function getSerializedList(array $options = array())
    {
        $manager = new Manager();
        $manager->setSerializer($this->getSerializer());
        $list = DataObject::get($this->owner->className);
        $decoratedList = $this->getDecoratedList($list, $options);
        $resource = new Collection($decoratedList, $this->getTransformer());
        $paginator = new PaginatedListPaginatorAdapter($decoratedList);
        $resource->setPaginator($paginator);

        if(isset($options['include'])) {
            $manager->parseIncludes($options['include']);
        }

        return $manager->createData($resource);
    }


    /**
     * returns the decorated list from the owner. Decorates the list
     * with pagination, filtering and sorting by default
     * @param SS_List $list
     * @param array $options
     * @return PaginatedList
     */
    public function getDecoratedList(SS_List $list, array $options = array())
    {
        return PaginatedList::create(
            FilterableList::create(
                SortableList::create($list, $options)->getList()
            )->getList(), $options
        );
    }

    /**
     * Get the transformer defined in config
     * defaults to PageTransformer or DataObjectTransformer
     * @return \League\Fractal\TransformerAbstract
     */
    protected function getTransformer()
    {
        $transformer = $this->owner->config()->get('transformer');
        return Injector::inst()->create($transformer);
    }

    /**
     * Get the serializer defined in config
     * defaults to ArraySerializer
     * @return \League\Fractal\Serializer\SerializerAbstract
     */
    protected function getSerializer()
    {
        $serializer = $this->owner->config()->get('serializer');
        return Injector::inst()->create($serializer);
    }

}