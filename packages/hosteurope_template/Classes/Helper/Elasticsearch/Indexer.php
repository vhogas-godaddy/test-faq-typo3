<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch;



class Indexer {

    protected static $allowed_tables = array(
        'tx_hosteuropefaq_domain_model_question'
    );

    protected static $allowed_actions = array(
        'index',
        'delete',
    );

    protected static $config = array(
        'tx_hosteuropefaq_domain_model_question' => array(
            'name' => 'Question',
        )
     );
}

