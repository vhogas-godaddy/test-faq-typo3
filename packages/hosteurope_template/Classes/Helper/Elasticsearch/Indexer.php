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
    
    
    /**
     * 
     * @param type $action "index" or "delete"
     * @param type $id "uid" of mysql table
     * @param type $table mysql table name
     */
    public static function handel($action, $id, $table){
        return;


        try{
            
            if(in_array($table, self::$allowed_tables) AND in_array($action, self::$allowed_actions)){
                $name = self::$config[$table]['name'];

                //get name
                $class = "Tx_HosteuropeTemplate_Helper_Elasticsearch_Resources_".ucfirst($name);

                //Do Request
                $class::$action($id);

            }
        }catch(\Exception $e){
           //Handle Error     
            if(ELASTICSEARCH){
                throw $e;
            }
        }
       
        return;
        
        
    }
}

