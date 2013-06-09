<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a text field that creates a unique name from a other field.
 */
class UniqueName extends Base{
    /**
     * Returns the html for the field.
     * @return type 
     */
    protected function addHtmlSingleControl(){
       
       
        $disabled = 'disabled="disabled"';
        
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="text" value="'. $this->getPresetValue() .'" ' . $disabled . ' />';
    }
    
    /**
     * Returns the  html for the overview.
     * @return type 
     */
    public function htmlOverview() {
        $this->html = '';
        $fieldName = $this->fieldName;
        return $this->entity->$fieldName;
    }
    
    /**
     * Checks if the field value is valid.
     * @return ValidationState. 
     */
    public function validation() {
        // ignore 
        return $this->validationState;
    }
    
    public function update(){
        $field = $this->configurationHelper->getField($this->fieldName);
        // wrong 'UseField' configuration
        if(!isset($field['UseField'])||!$this->fieldDefinitionHelper->fieldExists($field['UseField'])){
            throw new \Exception('PvikAdminTools: UseField for '. $fieldName . ' is not set up correctly. UseField is missing or it the stated field does not exists.');
        }
        $useField = $field['UseField'];
        $fieldName = $this->fieldName;
        $this->entity->$fieldName = $this->createUniqueName( $useField);
    }
    
    protected function createUniqueName($useField){
        $name = $this->getPOST($useField);
        $isValid = false;
        $urlSafeName = \PvikAdminTools\Library\Help::makeUrlSafe($name);
        $uniqueName = $urlSafeName;
        if($this->checkIfUniqueName($uniqueName,  $useField)){
            $isValid = true;;
        }
        // add numbers till name is unique
        $i = 1;
        while(!$isValid){
           $uniqueName = $urlSafeName . '-' . $i;
           if($this->checkIfUniqueName($uniqueName, $useField)){
            $isValid = true;;
           }
           $i++;
         }
        return $uniqueName;
    }
    
    protected function checkIfUniqueName($name, $useField){
        $primaryKey = $this->entity->getPrimaryKey();
        $entityArray = $this->entity->getModelTable()->loadAll()
            ->filterEquals($useField, $name);

        if($primaryKey!=null&&$primaryKey!=''){         
            $entityArray = $entityArray->filterNotEquals($this->entity->getModelTable()->getPrimaryKeyName(), $primaryKey);
        }
            
        // if query return an empty object, than no other entry has this name
        if($entityArray->isEmpty()){
            return true;
        }
        else {
            return false;
        }

    }
    
    
}