<?php
namespace PvikAdminTools\Library\Fields;
use \Pvik\Database\ORM\ModelTable;
/**
 * Displays a select field.
 */
class Select extends Base {
    /**
     * Returns the preset value.
     * @param string $fieldName
     * @param string $value
     * @return string 
     */
    protected function getSelectPresetValue($fieldName, $value){
        if($this->isPOST($fieldName)){
            if($this->getPOST($fieldName)==$value){
                return 'selected="selected"';
            }
            return '';
        }
        elseif(!$this->isNewEntity()){
            if($this->entity->$fieldName == $value){
                return 'selected="selected"';
            }
            return '';
        }
        elseif($this->preset!=''&&$this->preset==$value){
            return 'selected="selected"';
        }
        elseif($this->configurationHelper->hasValueField($this->fieldName, 'Preset') &&
                ($this->configurationHelper->getValue($this->fieldName, 'Preset')==$value)){
            return 'selected="selected"';
        }
        else {
            return '';
        }
    }
    
    /**
     * Returns the html for the field.
     * @return string 
     */
    protected function addHtmlSingleControl(){
        if($this->fieldDefinitionHelper->isTypeForeignObject($this->fieldName)){
          

            $modelTableName = $this->fieldDefinitionHelper->getModelTableNameForForeignObject($this->fieldName);
            $foreignKeyFieldName = $this->fieldDefinitionHelper->getForeignKeyFieldName($this->fieldName);
            $field = $this->configurationHelper->getField($this->fieldName);
            $useField = $field['UseField'];
            $entityArray = ModelTable::get($modelTableName)->loadAll();

            $this->html .= '<select class="span8" name="'. strtolower($foreignKeyFieldName) .'">';
            if($this->configurationHelper->isNullable($this->fieldName)||!$this->isNewEntity){
                 $this->html .= '<option value="">(none)</option>';
            }
            foreach($entityArray as $entity){
                $this->html .= '<option value="' . $entity->getPrimaryKey() .'" '.$this->getSelectPresetValue($foreignKeyFieldName, $entity->getPrimaryKey()).'>';
                $this->html .= utf8_decode($entity->$useField);
                $this->html .= '</option>';
            }


            $this->html .= '</select>';
        }
        else {
            $this->html .= 'Error';
        }
    }
    
    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function htmlOverview() {
        $this->html = '';
        $fieldName = $this->fieldName;
        $field = $this->configurationHelper->getField($this->fieldName);
        $useField = $field['UseField'];
        if($this->entity->$fieldName!=null){
             $this->html = $this->entity->$fieldName->$useField;
        }
        return $this->html;
    }
    
    /**
     * Updates a entity.
     */
    public function update(){
        $foreignKeyFieldName = $this->fieldDefinitionHelper->getForeignKeyFieldName($this->fieldName);
        $this->entity->$foreignKeyFieldName = $this->getPOST($foreignKeyFieldName);
    }
    
    /**
     * Validates the field value.
     * @return ValidationState 
     */
    public function validation() {
      $message = '';
      $foreignKeyFieldName = $this->fieldDefinitionHelper->getForeignKeyFieldName($this->fieldName);
      if(!$this->configurationHelper->isNullable($this->fieldName)&&$this->getPOST($foreignKeyFieldName)==""){
          $this->validationState->setError($this->fieldName, 'Can not be empty');
      }
      return $this->validationState;
    }
}
