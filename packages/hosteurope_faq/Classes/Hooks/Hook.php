<?php
namespace HostEuropeGmbh\HosteuropeFaq\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Hook {

    private const SLUGS_TABLE = 'tx_hosteuropefaq_domain_model_slugs';

    public function processDatamap_preProcessFieldArray(
        array &$fieldArray,
        $table,
        $id,
        \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj
    ) {
        switch ($table) {

            case 'tx_hosteuropefaq_domain_model_question':

                if (strlen($fieldArray['slug'])) {
                    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                        ->getQueryBuilderForTable(self::SLUGS_TABLE);

                    $slugs_entity = $queryBuilder
                        ->select('slug')
                        ->from(self::SLUGS_TABLE)
                        ->where(
                            $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($fieldArray['slug'])),
                            $queryBuilder->expr()->eq('categoryid', 0)
                        )
                        ->executeQuery()
                        ->fetchAssociative();

                    if (!$slugs_entity) {
                        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                            ->getConnectionForTable(self::SLUGS_TABLE);

                        $connection->insert(
                            self::SLUGS_TABLE,
                            [
                                'slug' => $fieldArray['slug'],
                                'questionid' => $id,
                                'tstamp' => time(),
                                'crdate' => time()
                            ]
                        );
                    } else {
                        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                            ->getQueryBuilderForTable(self::SLUGS_TABLE);

                        $queryBuilder
                            ->update(self::SLUGS_TABLE)
                            ->set('questionid', $id)
                            ->set('tstamp', time())
                            ->where(
                                $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($fieldArray['slug'])),
                                $queryBuilder->expr()->eq('categoryid', 0)
                            )
                            ->executeStatement();
                    }
                }
            break;

            case 'tx_hosteuropefaq_domain_model_category':

                if (strlen($fieldArray['slug'])) {
                    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                        ->getQueryBuilderForTable(self::SLUGS_TABLE);

                    $slugs_entity = $queryBuilder
                        ->select('slug')
                        ->from(self::SLUGS_TABLE)
                        ->where(
                            $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($fieldArray['slug'])),
                            $queryBuilder->expr()->eq('questionid', 0)
                        )
                        ->executeQuery()
                        ->fetchAssociative();

                    if (!$slugs_entity) {
                        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                            ->getConnectionForTable(self::SLUGS_TABLE);

                        $connection->insert(
                            self::SLUGS_TABLE,
                            [
                                'slug' => $fieldArray['slug'],
                                'categoryid' => $id,
                                'tstamp' => time(),
                                'crdate' => time()
                            ]
                        );
                    } else {
                        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                            ->getQueryBuilderForTable(self::SLUGS_TABLE);

                        $queryBuilder
                            ->update(self::SLUGS_TABLE)
                            ->set('categoryid', $id)
                            ->set('tstamp', time())
                            ->where(
                                $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($fieldArray['slug'])),
                                $queryBuilder->expr()->eq('questionid', 0)
                            )
                            ->executeStatement();
                    }
                }
                break;
        }
    }



}