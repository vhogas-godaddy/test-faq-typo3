<?php

namespace HostEuropeGmbh\HosteuropeFaq\Indexer;

use Tpwd\KeSearch\Indexer\IndexerRunner;

class FaqIndexerConfiguration
{
    public function __construct(IndexerRunner $indexerRunner)
    {
    }

    /**
     * Registers the FAQ indexer type in the ke_search backend dropdown.
     */
    public function registerIndexerConfiguration(array &$params, $pObj): void
    {
        $newArray = [
            'label' => 'FAQ Questions (hosteurope_faq)',
            'value' => FaqIndexer::INDEXER_TYPE,
        ];

        $params['items'][] = $newArray;
    }
}
