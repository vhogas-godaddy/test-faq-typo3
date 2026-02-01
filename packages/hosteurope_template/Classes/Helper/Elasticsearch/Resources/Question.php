<?php

namespace HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources;



use HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource;

class Question extends Resource  {

	public static $table = "tx_hosteuropefaq_domain_model_question";
	public static $es_type = "content";
	public static $mapping = array(
		'uid'              => 'uid',
		's_name'           => 'headline',
		's_text'           => 'content',
		'sys_language_uid' => 'sys_language_uid',
	);


	public static $indexes = array(
		'hosteurope.de',
	);

	public static $default_where = "!hidden AND !deleted";


	public static function initMapping( $index ) {
		static::_init();

		/**
		 * DELETE - In Elasticsearch 7.x, we delete the entire index instead of just the mapping
		 */
		$params          = array();
		$params['index'] = $index;
		try {
			static::$elasticsearch->indices()->delete( $params );
		} catch ( \Exception $e ) {
			// Index might not exist, which is fine
		}

		/**
		 * CREATE
		 */
		$params          = array();
		$params['index'] = $index;
		static::$elasticsearch->indices()->create( $params );


		/**
		 * PUT MAPPING - In Elasticsearch 7.x, we don't specify type parameter
		 */
		$params          = array();
		$params['index'] = $index;
		$params['body']  = '{
      "properties": {
        "pin": {
          "properties": {
            "location": {
              "type": "geo_point"
            }
          }
        },
        "s_name": {
          "type": "text",
          "analyzer": "german",
          "fields": {
            "search": {
              "type": "keyword"
            }
          }
        },
        "s_sort": {
          "type": "text",
          "analyzer": "german",
          "fields": {
            "search": {
              "type": "keyword"
            }
          }
        }
      }
    }';

		static::$elasticsearch->indices()->putMapping( $params );


	}



}
