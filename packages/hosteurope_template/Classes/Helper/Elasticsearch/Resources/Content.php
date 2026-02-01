<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources;


use HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\Page\PageRepository;

class Content extends Resource {


	public static $index_cache = array();

	public static $table = "index_phash";
	public static $es_type = "content";
	public static $mapping = array(
		'uid'              => 'phash',
		's_name'             => 'item_title',
		's_language_uid' => 'sys_language_uid',
		'fulltextdata' => 'fulltextdata'
	);

	public static $categories = array(
		'2' => 'Webhosting',
		'3' => 'Server',
		'4' => 'Domains',
		'5' => 'SSL & Security',
		'6' => 'E-Mail & Office',
		'7' => 'Homepage & Shop',
	);

	public static $categories_root = array(
		'2' => 'products',
		'3' => 'products',
		'4' => 'products',
		'5' => 'products',
		'6' => 'products',
		'7' => 'products',
	);


	public static $indexes_mapping = array(
		'1' => 'hosteurope.de',
	);

	public static $inverse_indexes_mapping = array();


	public static $default_where = "!hidden AND !deleted";

	public static function _init() {


		foreach ( self::$indexes_mapping as $id => $label ) {
			self::$inverse_indexes_mapping[ $label ] = $id;
		}


		parent::_init();
	}

	public static function deleteindex( $index ) {
		static::_init();


		$params          = array();
		$params['index'] = $index;
		try {
			static::$elasticsearch->indices()->delete( $params );
		} catch ( \Exception $e ) {

		}
	}

	public static function deleteMapping( $index ) {
		static::_init();

		// In Elasticsearch 7.x, deleteMapping is not available
		// Instead, we delete the entire index
		$params          = array();
		$params['index'] = $index;
		try {
			static::$elasticsearch->indices()->delete( $params );
		} catch ( \Exception $e ) {
			// Index might not exist, which is fine
		}
	}

	public static function initMapping( $index ) {
		static::_init();

		// In Elasticsearch 7.x, we delete the entire index instead of just the mapping
		$params          = array();
		$params['index'] = $index;
		try {
			static::$elasticsearch->indices()->delete( $params );
		} catch ( \Exception $e ) {
			// Index might not exist, which is fine
		}

		// Create the index
		$params          = array();
		$params['index'] = $index;
		static::$elasticsearch->indices()->create( $params );

		// Put mapping without type parameter (Elasticsearch 7.x)
		$params          = array();
		$params['index'] = $index;
		$params['body']  = '{
	    "properties": {
	      "title": {
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

	public static function delete( $uid, $index = "hosteurope.de" ) {
		static::_init();
		if ( strlen( $uid ) ) {
			static::_init();

			$params          = array();
			$params['index'] = $index;
			// Type parameter removed for Elasticsearch 7.x compatibility
			$params['id']    = $uid;
			try {
				static::$elasticsearch->delete( $params );
			} catch ( \Exception $e ) {

			}

		}

		return;
	}

	public static function deleteAll( $index ) {
		static::_init();


		$json = '{
		    "query" : {
		        "match_all" : {
		        }
		    }
		}';


		$params          = array();
		$params['index'] = $index;
		// Type parameter removed for Elasticsearch 7.x compatibility
		$params['body']  = $json;

		try {

			static::$elasticsearch->deleteByQuery( $params );
		} catch ( \Exception $e ) {

		}


		//$GLOBALS['TYPO3_DB']->exec_UPDATEquery(static::$table, "", array('es_tstamp' => 0), $no_quote_fields = false);
	}




}
