<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Resource {

	/**
	 * @var \Elasticsearch\Client() $elasticsearch
	 */

	public static $elasticsearch = null;

  /**
   * @var \Elasticsearch\Client() $elasticsearch for read requests
   */
  public static $elasticsearchRead = null;

	public static $es_type;

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 */
	public static $cObj = null;

	public static $indexes = array(
		'hosteurope.de',
	);

	public static function _init() {
		if ( is_null( self::$elasticsearch )  || is_null( self::$elasticsearchRead )) {

			self::$elasticsearch = \Elastic\Elasticsearch\ClientBuilder::create()// Instantiate a new ClientBuilder
			                                                   ->setHosts( array(getenv('ELASTICSEARCH_HOST')) )// Set the hosts
                                                               ->setApiKey( getenv('ELASTICSEARCH_APIKEY_WRITE') )
			                                                   ->build();               // Build the client object

      self::$elasticsearchRead = \Elastic\Elasticsearch\ClientBuilder::create()// Instantiate a new ClientBuilder
                                                        ->setHosts( array(getenv('ELASTICSEARCH_HOST')) )// Set the hosts
                                                        ->setApiKey( getenv('ELASTICSEARCH_APIKEY_READ') )
                                                          ->build();               // Build the client object
		}
	}

	public static function count( $index ) {
		static::_init();

		$params          = array();
		$params['index'] = $index;
		// Type parameter removed for Elasticsearch 7.x compatibility

		return static::$elasticsearchRead->count( $params );

	}

	public static function stats( $index ) {
		static::_init();

		$params          = array();
		$params['index'] = $index;

		return static::$elasticsearchRead->indices()->stats( $params );

	}

	public static function get( $index, $uid ) {
		static::_init();
		$params          = array();
		$params['index'] = $index;
		// Type parameter removed for Elasticsearch 7.x compatibility
		$params['id']    = intval( $uid );

		return static::$elasticsearchRead->get( $params );
	}

	public static function index( $index, $uid ) {
		static::_init();
		//Load Row
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
			->getQueryBuilderForTable(static::$table);

		$rows = $queryBuilder
			->select('*')
			->from(static::$table)
			->where(
				$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
			)
			->executeQuery()
			->fetchAllAssociative();

		if ( count( $rows ) > 0 ) {
			foreach ( $rows as $resource ) {

				static::_index( $index, $resource );
			}//foreach
		}//if
	}

	public static function delete( $index, $uid ) {
		static::_init();
		if ( strlen( $uid ) ) {
			static::_init();

			$params          = array();
			$params['index'] = $index;
			// Type parameter removed for Elasticsearch 7.x compatibility
			$params['id']    = $uid;
			static::$elasticsearch->delete( $params );

			try {
				$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
					->getQueryBuilderForTable(static::$table);

				$queryBuilder
					->update(static::$table)
					->set('es_tstamp', 0)
					->where(
						$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
					)
					->executeStatement();
			} catch (\Exception $e) {
				// es_tstamp column might not exist
			}
		}

		return;
	}

	public static function deleteAll( $index ) {

		try {
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


			try {
				$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
					->getQueryBuilderForTable(static::$table);

				$queryBuilder
					->update(static::$table)
					->set('es_tstamp', 0)
					->executeStatement();
			} catch (\Exception $e) {
				// es_tstamp column or table might not exist
			}
		} catch ( \Exception $e ) {
			//var_dump($e);
		}


	}


	public static function indexData( $index, $uid, $es_object ) {
		static::_init();
		$params          = array();
		$params['body']  = $es_object;
		$params['index'] = $index;
		// Type parameter removed for Elasticsearch 7.x compatibility
		$params['id']    = $uid;

		static::$elasticsearch->index( $params );

		//hier noch abfragen ob es geklappt hat?
		try {
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
				->getQueryBuilderForTable(static::$table);

			$queryBuilder
				->update(static::$table)
				->set('es_tstamp', time())
				->where(
					$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
				)
				->executeStatement();
		} catch (\Exception $e) {
			// es_tstamp column might not exist
		}

	}

	public static function _index( $index, $resource ) {


		return;
	}

	/**
	 * @param $query
	 * @param int $language_id
	 * @param null $filters
	 *
	 * @return array
	 */
	public static function search(
		$query,
		$label = "",
		$categories = array(),
		$s_language_uid = 0,
		$offset = 0,
		$limit = 25,
		$expand = 0,
		$sort_by = null,
		$sort = "asc",
		$highlight = true,
		$suggestion_size = 1
	) {

		static::_init();

		$q = $query;

		$params          = array();
		$params['index'] = 'hosteurope.de';


		if ( ( $expand == 1 ) and strlen( $query ) ) {
			$query_tmp   = array();
			$query_parts = explode( " ", $query );
			foreach ( $query_parts as $query_part ) {
				$query_tmp[] = $query_part . "*";
			}

			$query = implode( " ", $query_tmp );
		} elseif ( ( $expand == 2 ) and strlen( $query ) ) {
			$query_tmp   = array();
			$query_parts = explode( " ", $query );
			foreach ( $query_parts as $query_part ) {
				$query_tmp[] = $query_part . "~";
			}

			$query = implode( " ", $query_tmp );
		}


		if ( strlen( $query ) ) {
			$params['body']['query']['bool']['must'] = array(
				array(
					'query_string' => array(
						'fields' => array(
							"s_name^5",
							"s_description^3",
							"s_categories^2",
							"s_text^2",
							"*",
						),
						'query'            => $query,
						"default_operator" => "and",
					),
				),
			);
		}

		$params['body']['query']['bool']['must'][] = array(
			'match' => array(
				's_language_uid' => $s_language_uid,
			),
		);

		if ( strlen( $label ) ) {
			$params['body']['query']['bool']['must'][] = array(
				'match' => array(
					's_label' => $label,
				),
			);
		}

		if ( count( $categories ) ) {
			$params['body']['query']['bool']['filter'][] = array(
				"terms" => array(
					"s_categories" => $categories,
				),
			);
		}


		$params['body']["_source"] = array(
			"s_name",
			"s_description",
			"s_subline",
			"s_url",
			"s_label",
		);

		$params['body']['from'] = $offset;
		$params['body']['size'] = $limit;

		//Suggestor
		$params['body']["suggest"]['suggestion'] = array(
			'text' => $q,
			'term' => array(
				'field' => "s_name",
				'size'  => $suggestion_size,
			),

		);


		if ( $sort_by ) {
			$params['body']['sort'] = array(
				$sort_by . ".search" => array(
					'order' => $sort,
				),
			);
		}


		if ( $label == 'question' ) {
			$params['body']['sort'] = array(
				'prio' => 'desc',
				'_score' => 'desc',
			);
		}


		$result = static::$elasticsearchRead->search( $params );

		return $result;
	}


}
