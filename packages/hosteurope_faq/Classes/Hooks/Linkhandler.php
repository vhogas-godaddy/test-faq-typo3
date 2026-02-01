<?php
namespace HostEuropeGmbh\HosteuropeFaq\Hooks;

use Cobweb\Linkhandler\Exception\MissingConfigurationException;
use Cobweb\Linkhandler\Exception\RecordNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;
use Cobweb\Linkhandler\TypolinkHandler;
use Cobweb\Linkhandler\ProcessLinkParametersInterface;

class Linkhandler implements ProcessLinkParametersInterface {



	/**
	 * @param \Cobweb\Linkhandler\TypolinkHandler $linkHandler Back-reference to the calling object
	 * @return void
	 */
	public function process($linkHandler){
		$link_config = $linkHandler->getTypolinkConfiguration();
		$configurationKey = ($linkHandler->getConfigurationKey());

		if($configurationKey == "tx_hosteuropefaq_category."){
			$categoryRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
			$entity = $categoryRepository->findByUid($linkHandler->getUid());

			// tx_hosteuropefaq_main[slug][0]={field:slug}&tx_hosteuropefaq_main[controller]=Category&tx_hosteuropefaq_main[action]=router
			$link_array = array(
				'tx_hosteuropefaq_main' => array(
					'slug' => $entity->getLinkarguments(),
				)
			);
			$link_config['additionalParams.'] = "&".urldecode(http_build_query($link_array));

			$linkHandler->setTypolinkConfiguration($link_config);
		}elseif($configurationKey == "tx_hosteuropefaq_question."){
			$questionRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository::class);
			$entity = $questionRepository->findByUid($linkHandler->getUid());

			// tx_hosteuropefaq_main[slug][0]={field:slug}&tx_hosteuropefaq_main[controller]=Category&tx_hosteuropefaq_main[action]=router
			$link_array = array(
				'tx_hosteuropefaq_main' => array(
					'slug' => $entity->getLinkarguments(),
				)
			);
			$link_config['additionalParams.'] = "&".urldecode(http_build_query($link_array));

			$linkHandler->setTypolinkConfiguration($link_config);
		}

	}


}