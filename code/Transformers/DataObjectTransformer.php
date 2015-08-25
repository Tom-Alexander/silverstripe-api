<?php

use League\Fractal\TransformerAbstract;

class DataObjectTransformer extends TransformerAbstract
{

    public function transform($dataObject)
    {
        return array(
            'id' => $dataObject->ID,
            'created' => $dataObject->Created,
            'last_edited' => $dataObject->LastEdited
        );
    }

}