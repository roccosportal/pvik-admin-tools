<?php
namespace PvikAdminTools\Library;
use \Pvik\Database\ORM\Entity;
use \Pvik\Database\ORM\EntityArray;
use \Pvik\Database\ORM\ModelTable;
use \PvikAdminTools\Library\ConfigurationHelper;
use \Pvik\Utils\ValidationState;
use \Pvik\Web\Request;
/**
 * Display html for a single entity entry.
 */
class SingleHtml{
    /**
     * The entity entry that is displayed.
     * @var Entity 
     */
    protected $entity;
    /**
     * A helper class for the PvikAdminTools configuration.
     * @var ConfigurationHelper 
     */
    protected $configurationHelper;
    /**
     * The ValidationState of the current entity.
     * @var ValidationState
     */
    protected $validationState;
    /**
     * Indicates if a model is new.
     * @var bool 
     */
    protected $isNewEntity;

    /**
     * 
     * @var \Pvik\Web\Request 
     */
    protected $request;
    
    /**
     * Contains an associative array of field preset values.
     * @var array 
     */
    protected $presetValues;
    
    /**
     * Contains the redirect back url for foreign tables after clicking new and submitting the form
     * @var string 
     */
    protected $foreignTableButtonRedirectBackUrl;
    
    /**
     * The html that is displayed.
     * @var string. 
     */
    protected $html;
    
    /**
     *
     * @param Entity $entity
     * @param ValidationState $validationState 
     */
    public function __construct(Entity $entity,ValidationState $validationState, Request $request){
        
        $this->entity = $entity;
        $this->request = $request;
        $this->configurationHelper = new ConfigurationHelper();
        $this->configurationHelper->setCurrentTable($this->entity->getModelTable()->getModelTableName());
        $this->validationState = $validationState;
        if($this->entity->getPrimaryKey()==null||$this->entity->getPrimaryKey()==''){
            $this->isNewEntity = true;
        }
        else {
            $this->isNewEntity = false;
        }
        $this->presetValues = array();
    }
    
    /**
     * Checks if the entity is new.
     * @return bool 
     */
    protected function isNewEntity(){
        return $this->isNewEntity;
    }
    
    /**
     * Set the preset values for fields.
     * Must be an associative array.
     * @param array $presetValues 
     */
    public function setPresetValues(array $presetValues){
        $this->presetValues = $presetValues;
    }
    
    /**
     * This functions set a redirect back url when you click on new in a foreign table.
     * After you submitted the new table entry the form redirects back to the the url.
     * @param string $url 
     */
    public function setForeignTableButtonRedirectBackUrl($url){
        $this->foreignTableButtonRedirectBackUrl = $url;
    }
    
    /**
     * Returns the html of the entry.
     * @return string 
     */
    public function toHtml(){
        $this->html = '<form class="form-vertical" method="post">';
        foreach($this->configurationHelper->getFieldList() as $fieldName){
            $type = $this->configurationHelper->getFieldType($fieldName);
            $fieldClassName = '\\PvikAdminTools\\Library\\Fields\\' . $type;
            if(!class_exists($fieldClassName)){
                throw new \Exception('PvikAdminTools: The type '.$type . ' does not exists. Used for the field '. $fieldName);
            }
            $field = new $fieldClassName($fieldName, $this->entity, $this->request, $this->validationState);
            /* @var $field \PvikAdminTools\Library\Fields\Base */
            if($field->isVisibleSingle()){
                if(isset($this->presetValues[strtolower($fieldName)])){
                    $field->setPreset($this->presetValues[strtolower($fieldName)]);
                }
               
                $this->html .= $field->htmlSingle();
              
            }
            
            
        }
       
        $this->addHtmlSubmit();
        $this->html .= '</form>';
        
        if(!$this->isNewEntity()&&$this->configurationHelper->hasForeignTables()){
             $this->html .= '<div class="foreign-tables-field">';
            foreach($this->configurationHelper->getForeignTables() as $foreignTable => $configuration){
                $primaryKey = $this->entity->getPrimaryKey();
                $foreignKey = $configuration['ForeignKey'];
                $modelTable = ModelTable::get($foreignTable);
                $entityArray =  $modelTable->loadAll();
                $entityArray = $entityArray->filterEquals($foreignKey, $primaryKey);
                $tableHtml =  new \PvikAdminTools\Library\TableHtml($entityArray,$this->request);
                // saerch for fields that we don't need to show
                $foreignObjectFieldNames = array ();
                $helper = $modelTable->getFieldDefinitionHelper();
                foreach($helper->getFieldList() as $fieldName){
                    // search for a foreign object that uses that refers to the original model
                    // we don't need to show this table column
                    if($helper->isTypeForeignObject($fieldName)){
                        if($helper->getForeignKeyFieldName($fieldName)==$foreignKey){
                            array_push($foreignObjectFieldNames,$fieldName);
                        }
                    }
                }
                
                $tableHtml->setHiddenFields($foreignObjectFieldNames);
                
                // set preset values
                $presetValues = array ();
                foreach($foreignObjectFieldNames as $foreignObjectFieldName){
                    $presetValues[$foreignObjectFieldName] = $primaryKey;
                }
                $tableHtml->setNewButtonPresetValues($presetValues);
                
                if($this->foreignTableButtonRedirectBackUrl!=null){
                    $tableHtml->setButtonRedirectBack($this->foreignTableButtonRedirectBackUrl);
                }
                $this->html .= '<div class="field">';
                $this->html .= '<label class="label-field">' . $foreignTable . '</label>';
                $this->html .= $tableHtml->toHtml();
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
        }
        
        return $this->html;
    }
    
    /**
     * Adds a submit button to the html.
     */
    protected function addHtmlSubmit(){
         $this->html .= '<div class="control-group">
                    <div class="controls">
                        <button type="submit" name="submit" class="btn">Submit</button>
                    </div>
                </div>';
    }
    
}
?>