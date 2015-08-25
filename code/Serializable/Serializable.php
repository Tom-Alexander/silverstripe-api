<?php

interface SerializableModel
{

    /**
     * @param $id
     * @param array $options
     * @return mixed
     */
    public function getSerializedData($id, array $options);


    /**
     * @param array $options
     * @return mixed
     */
    public function getSerializedList(array $options);

}