<?php
namespace PvikAdminTools\Library\Fields;
use \PvikAdminTools\Library\ConfigurationHelper;
use \Pvik\Database\ORM\FieldDefinition\Helper as FieldDefinitionHelper;
use \Pvik\Database\ORM\ModelTable;
use \Pvik\Database\ORM\Entity;
use \Pvik\Utils\ValidationState;
use \Pvik\Web\Request;
/**
 * A class that contains the basic methods for a html table field 
 */
abstract class Base {
    /**
     * The current entity
     * @var \Pvik\Database\ORM\Entity
     */
    protected $entity;
    /**
     * Indicates if the entity is a new entity.
     * @var bool 
     */
    protected $isNewEntity;
    /**
     * Contains the html output.
     * @var type 
     */
    protected $html;
    /**
     * Contains the helper class for the configuration.
     * @var ConfigurationHelper 
     */
    protected $configurationHelper;
    /**
     * Contains the helper class for the model table field definition.
     * @var FieldDefinitionHelper
     */
    protected $fieldDefinitionHelper;
    /**
     * Contains the name of the field.
     * @var string 
     */
    protected $fieldName;
    /**
     * Contains the validation state of the current entry.
     * @var ValidationState
     */
    protected $validationState;
    /**
     * Contains the model table of the current entry.
     * @var ModelTable 
     */
    protected $modelTable;
    
    /**
     * Contains a preset value for the field.
     * @var string 
     */
    protected $preset;
    
    /**
     * 
     * @var Request 
     */
    protected $request;
    
    /**
     *
     * @param string $fieldName
     * @param Entity $entity
     * @param ValidationState $validationState 
     */
    public function __construct($fieldName, Entity $entity, Request $request, ValidationState $validationState){
        $this->fieldName = $fieldName;
        $this->entity = $entity;
        $this->modelTable = $this->entity->getModelTable();
        $this->request = $request;
        $this->fieldDefinitionHelper = $this->modelTable->getFieldDefinitionHelper();
        $this->configurationHelper = new \PvikAdminTools\Library\ConfigurationHelper();
        $this->configurationHelper->setCurrentTable($this->modelTable->getModelTableName());
        $this->validationState = $validationState;
        if($this->entity->getPrimaryKey()==null||$this->entity->getPrimaryKey()==''){
            $this->isNewEntity = true;
        }
        else {
            $this->isNewEntity = false;
        }
        $this->preset = '';
    }
    
    /**
     * Sets the preset value of the current field.
     * @param string $preset 
     */
    public function setPreset($preset){
        $this->preset = $preset;
    }
    
    /**
     * Checks if the model is a new model.
     * @return bool 
     */
    protected function isNewEntity(){
        return $this->isNewEntity;
    }
    
    /**
     * Returns the $_POST value of the current field if no field name given.
     * @param string $fieldName [optional]
     * @return string 
     */
    protected function getPOST($fieldName = ''){
        if($fieldName==''){
            $fieldName = $this->fieldName;
        }
        return $this->request->getPOST(strtolower($fieldName));
    }

    protected function isPOST($fieldName = ''){
        if($fieldName==''){
            $fieldName = $this->fieldName;
        }
        return $this->request->isPOST(strtolower($fieldName));
    }

    /**
     * Returns the preset value of the current field.
     * @return string 
     */
    protected function getPresetValue(){
        $fieldName = $this->fieldName;
        if($this->isPOST($fieldName)){
            return $this->getPOST();
        }
        elseif(!$this->isNewEntity()){
            return $this->entity->$fieldName;
        }
        elseif($this->preset!=''){
            return $this->preset;
        }
        elseif($this->configurationHelper->hasValueField($fieldName, 'Preset')){
            return $this->configurationHelper->getValue($fieldName, 'Preset');
        }
        else {
            return '';
        }
    }
    
    /**
     * Returns a lowered name of the current field name.
     * @return string 
     */
    protected function getLowerFieldName(){
        return strtolower($this->fieldName);
    }
    
    /**
     * Checks if this field is visible in single edit/new mode.
     * @return bool 
     */
    public function isVisibleSingle(){
        if($this->configurationHelper->fieldExists($this->fieldName) 
                && $this->configurationHelper->isTypeIgnore($this->fieldName)){
            return false;
        }
        return true;
    }
    
    /**
     * Adds a label to the html.
     */
    protected function addHtmlLabel(){
        $this->html .= '<label class="control-label" >' . $this->fieldName . '</label>';
    }
    
    /**
     * Validates the current field.
     * @return ValidationState 
     */
    public function validation(){
      $message = '';
      if(!$this->configurationHelper->isDisabled($this->fieldName) &&  !$this->configurationHelper->isNullable($this->fieldName)&&($this->getPOST($this->fieldName)===null||$this->getPOST($this->fieldName)==="")){
          $this->validationState->setError($this->fieldName, 'Can not be empty.');
      }
      return $this->validationState;
    }
    
    /**
     * Adds a validation field to the html.
     */
    protected function addHtmlValidationField(){
        $message = $this->validationState->getError($this->fieldName);
        if($message!=''){
            $this->html .= '<span  class="help-inline">'. $message .'</span>';
        }
    }
    
    /**
     * Adds a single field for the html in edit/update mode.
     */
    public function htmlSingle(){
        $this->html = '';
        $this->html .= '<div class="control-group ';
        
        $message = $this->validationState->getError($this->fieldName);
        if($message!=''){
            $this->html .= 'error';
        }
        $this->html .= '">';
        $this->addHtmlLabel();
        $this->html .= '<div class="controls">';
        $this->addHtmlSingleControl();
        $this->addHtmlValidationField();
        $this->html .= '</div>';
        $this->html .= '</div>';
       
        return $this->html;
    }
    /**
     * 
     */
    protected abstract function addHtmlSingleControl();
    
    /**
     * Adds a overview field for the htm in overview mode.
     */
    public abstract function htmlOverview();
    
    /**
     * Updates a model field
     */
    public function update(){
        $fieldName = $this->fieldName;
        if($this->configurationHelper->isDisabled($this->fieldName)){
            if(!$this->configurationHelper->hasValueField($this->fieldName, 'Preset') && !$this->configurationHelper->isNullable($this->fieldName)){
                throw new \Exception('PvikAdminTools: Field ' . $this->fieldName . ' is not nullable and is disabled but does not have a "Preset" value.');
            }
            $this->entity->$fieldName = $this->configurationHelper->getValue($this->fieldName, 'Preset');
        }
        else {
            $this->entity->$fieldName = $this->getPOST();
        }
    }
}
