<?php

namespace HostEuropeGmbh\HosteuropeFaq\Indexer;

use Tpwd\KeSearch\Indexer\IndexerBase;
use Tpwd\KeSearch\Indexer\IndexerRunner;
use Tpwd\KeSearch\Lib\Db;
use TYPO3\CMS\Core\Database\Connection;
use HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FaqIndexer
{
    public const INDEXER_TYPE = 'hosteurope_faq';

    /**
     * Called by ke_search hook for each indexer configuration.
     * Only processes configurations of our custom type.
     */
    public function customIndexer(array $indexerConfig, IndexerRunner $indexerRunner): string
    {
        if ($indexerConfig['type'] !== self::INDEXER_TYPE) {
            return '';
        }

        $table = 'tx_hosteuropefaq_domain_model_question';
        $questionRepository = GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository::class);

        $questions = $questionRepository->findAll();

        $counter = 0;
        foreach ($questions as $question) {
            $title = strip_tags($question->getHeadline() ?? '');
            if (empty($title)) {
                continue;
            }

            $rawContent = strip_tags($question->getContent() ?? '');
            $rawContent = preg_replace('/\s+/', ' ', $rawContent);
            $rawContent = trim($rawContent);

            $abstract = mb_substr($rawContent, 0, 400, 'UTF-8');
            if (mb_strlen($rawContent, 'UTF-8') > 400) {
                $abstract .= '...';
            }

            $fullContent = $title . "\n" . $rawContent;

            $link_array = array(
				'tx_hosteuropefaq_main' => array(
					'slug' => $question->getLinkarguments(),
				)
			);

            $parentCategories = $question->getParentCategories();
            $pathText = 'FAQ / ' . implode(' / ', $parentCategories);


            $params = '&tx_hosteuropefaq_main[action]=router'
                . '&tx_hosteuropefaq_main[controller]=Category'
                . '&' . urldecode(http_build_query($link_array));

            $additionalFields = [
                'sortdate' => $question->getTstamp() ?? 0,
                'orig_uid' => $question->getUid(),
                'orig_pid' => $question->getPid(),
                'hidden_content' => $pathText,
            ];

            $indexerRunner->storeInIndex(
                $indexerConfig['storagepid'],
                $title,
                self::INDEXER_TYPE,
                $indexerConfig['targetpid'],
                $fullContent,
                '',
                $params,
                $abstract,
                $row['sys_language_uid'] ?? 0,
                $row['starttime'] ?? 0,
                $row['endtime'] ?? 0,
                $row['fe_group'] ?? '',
                false,
                $additionalFields
            );
            $counter++;
        }

        return $counter . ' FAQ question records have been indexed.';
    }
}
