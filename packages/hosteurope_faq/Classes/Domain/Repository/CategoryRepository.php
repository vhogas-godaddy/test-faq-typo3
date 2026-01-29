<?php
namespace HostEuropeGmbh\HosteuropeFaq\Domain\Repository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category;

/**
 * The repository for Categories
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
	);

	// Example for repository wide settings
	public function initializeObject() {
		/** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
		$querySettings = $this->objectManager->get( 'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings' );
		// go for $defaultQuerySettings = $this->createQuery()->getQuerySettings(); if you want to make use of the TS persistence.storagePid with defaultQuerySettings(), see #51529 for details

		// don't add the pid constraint
		$querySettings->setRespectStoragePage( false );
		$this->setDefaultQuerySettings( $querySettings );
	}


	/**
	 * @param $slug
	 *
	 * @return Category
	 */
	public function findRootCategory( $slug ) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals( 'parent', 0 ),
				$query->equals( 'slug', $slug )
			)
		);

		$category = $query->execute();
		if ( count( $category ) ) {
			return $category->getFirst();
		} else {
			return false;
		}

	}


	public function findByParent( $parentUid ) {

		$query  = $this->createQuery();

		$query = $query->statement( '
SELECT 
	c.* 
FROM 
	tx_hosteuropefaq_domain_model_category as c, 
	pages as p
WHERE 
	c.parent = '.intval($parentUid).' AND 
	c.pid = p.uid 
  
	AND NOT c.deleted
     AND NOT c.hidden
     AND (c.starttime<='.time().')
     AND (c.endtime=0 OR c.endtime>'.time().')
ORDER BY 
	p.sorting ASC'
		);



		return $query->execute();
	}

}