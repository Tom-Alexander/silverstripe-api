<?php

class SiteTreeTransformer extends DataObjectTransformer
{

    protected $availableIncludes = array(
        'metaData'
    );

    public function transform($siteTree)
    {
        return array_merge(
            parent::transform($siteTree),
            array(
                'title' => $siteTree->Title,
                'content' => $siteTree->Content,
                'url_segment' => $siteTree->URLSegment,
            )
        );
    }

    public function includeMetaData($siteTree)
    {
        return $this->item($siteTree, function ($object) {
            return array(
                'meta_description' => $object->MetaDescription,
                'extra_meta' => $object->ExtraMeta,
            );
        });
    }

}