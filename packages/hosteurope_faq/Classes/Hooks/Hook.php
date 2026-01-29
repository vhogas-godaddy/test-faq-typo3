<?php
namespace HostEuropeGmbh\HosteuropeFaq\Hooks;

class Hook {

    public function processDatamap_preProcessFieldArray(
        array &$fieldArray,
        $table,
        $id,
        \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj
    ) {
        switch ($table) {

            case 'tx_hosteuropefaq_domain_model_question':

                if (strlen($fieldArray['slug'])) {
                    //get 1. Abschluss

                    $slugs_entity = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
                        'slug',
                        'tx_hosteuropefaq_domain_model_slugs',
                        "slug = '".$fieldArray['slug']."' AND categoryid = 0"
                    );

                    if(!$slugs_entity){
                        $GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_hosteuropefaq_domain_model_slugs",array('slug' => $fieldArray['slug'],'questionid' => $id,'tstamp' => time(),'crdate' => time()));
                    }else{
                        $GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_hosteuropefaq_domain_model_slugs","slug = '".$fieldArray['slug']."' AND categoryid = 0",array('questionid' => $id,'tstamp' => time()));
                    }
                }
            break;

            case 'tx_hosteuropefaq_domain_model_category':

                if (strlen($fieldArray['slug'])) {
                    //get 1. Abschluss

                    $slugs_entity = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
                        'slug',
                        'tx_hosteuropefaq_domain_model_slugs',
                        "slug = '".$fieldArray['slug']."' AND questionid = 0"
                    );

                    if(!$slugs_entity){
                        $GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_hosteuropefaq_domain_model_slugs",array('slug' => $fieldArray['slug'],'categoryid' => $id,'tstamp' => time(),'crdate' => time()));
                    }else{
                        $GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_hosteuropefaq_domain_model_slugs","slug = '".$fieldArray['slug']."' AND questionid = 0", array('categoryid' => $id,'tstamp' => time()));
                    }
                }
                break;
        }
    }



}