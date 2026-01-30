<?php
namespace HostEuropeGmbh\HosteuropeFaq\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;
use TYPO3\CMS\Frontend\Typolink\AbstractTypolinkBuilder;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class RecordLinkBuilder extends AbstractTypolinkBuilder
{
    /**
     * @inheritdoc
     */
    public function build(array &$linkDetails, string $linkText, string $target, array $conf): LinkResultInterface
    {
        die();
        $tsfe = $this->getTypoScriptFrontendController();
        $pageTsConfig = BackendUtility::getPagesTSconfig($tsfe->id);
        $configurationKey = $linkDetails['identifier'] . '.';
        $frontendTypoScript = $this->contentObjectRenderer->getRequest()->getAttribute('frontend.typoscript');
        $typoScriptSetup = $frontendTypoScript->getSetupArray();
        $configuration = $typoScriptSetup['config.']['recordLinks.'] ?? [];
        $linkHandlerConfiguration = $pageTsConfig['TCEMAIN.']['linkHandler.'];

        if (!isset($configuration[$configurationKey], $linkHandlerConfiguration[$configurationKey])) {
            throw new UnableToLinkException(
                'Configuration how to link "' . $linkDetails['typoLinkParameter'] . '" was not found, so "' . $linkText . '" was not linked.',
                1490989149,
                null,
                $linkText
            );
        }

        $typoScriptConfiguration = $configuration[$configurationKey]['typolink.'];
        $linkHandlerConfiguration = $linkHandlerConfiguration[$configurationKey]['configuration.'];

        if (!empty($configuration[$configurationKey]['forceLink'])) {
            $record = $tsfe->sys_page->getRawRecord($linkHandlerConfiguration['table'], $linkDetails['uid']);
        } else {
            $record = $tsfe->sys_page->checkRecord($linkHandlerConfiguration['table'], $linkDetails['uid']);
        }
        if ($record === 0) {
            throw new UnableToLinkException(
                'Record not found for "' . $linkDetails['typoLinkParameter'] . '" was not found, so "' . $linkText . '" was not linked.',
                1490989659,
                null,
                $linkText
            );
        }


        if($linkDetails['identifier'] == "tx_hosteuropefaq_category"){
            $categoryRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
            $entity = $categoryRepository->findByUid($linkDetails['uid']);

            // tx_hosteuropefaq_main[slug][0]={field:slug}&tx_hosteuropefaq_main[controller]=Category&tx_hosteuropefaq_main[action]=router
            $link_array = array(
                'tx_hosteuropefaq_main' => array(
                    'slug' => $entity->getLinkarguments(),
                )
            );
            $typoScriptConfiguration['additionalParams'] = "&".urldecode(http_build_query($link_array));

        }elseif($linkDetails['identifier'] == "tx_hosteuropefaq_question"){
            $questionRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository::class);
            $entity = $questionRepository->findByUid($linkDetails['uid']);

            // tx_hosteuropefaq_main[slug][0]={field:slug}&tx_hosteuropefaq_main[controller]=Category&tx_hosteuropefaq_main[action]=router
            $link_array = array(
                'tx_hosteuropefaq_main' => array(
                    'slug' => $entity->getLinkarguments(),
                )
            );
            $typoScriptConfiguration['additionalParams'] = "&".urldecode(http_build_query($link_array));

        }
        unset($typoScriptConfiguration['additionalParams.']);

        $extTarget = $typoScriptSetup['config.']['extTarget'] ?? '';
        $typoScriptConfiguration['target'] = $target ?: $this->resolveTargetAttribute($conf, 'extTarget', true, $extTarget);

        // Build the full link to the record
        $localContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $localContentObjectRenderer->start($record, $linkHandlerConfiguration['table']);
        $localContentObjectRenderer->parameters = $this->contentObjectRenderer->parameters;
        $link = $localContentObjectRenderer->typoLink($linkText, $typoScriptConfiguration);

        // nasty workaround so typolink stops putting a link together, there is a link already built
        throw new UnableToLinkException(
            '',
            1491130170,
            null,
            $link
        );
    }
}