<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a checkbox.
 */
class Checkbox extends Base {
    

   protected function addHtmlSingleControl(){
        $disabled = '';
        if($this->configurationHelper->fieldExists($this->fieldName)
                && $this->configurationHelper->isDisabled($this->fieldName)){
            $disabled = 'disabled="disabled"';
        }
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="checkbox" '. $this->getPresetValue() .' value="checked" '. $disabled .' />';
    }
    
    
    /**
     * Returns the preset value for the checkbox
     * @return string 
     */
    public function getPresetValue() {
        $fieldName = $this->fieldName;
        if($this->isPOST($fieldName)){
            if($this->getPOST()=='checked'){
               return 'checked="checked"';
            }
            else {
                return '';
            }
        }
        elseif(!$this->isNewEntity()){
            if($this->entity->$fieldName){
                return 'checked="checked"';
            }
            else {
                return '';
            }
        }
        elseif($this->configurationHelper->hasValueField($fieldName, 'Preset') &&
            $this->configurationHelper->getValue($fieldName, 'Preset')=='checked'){
            return 'checked="checked"';
        }
        else {
            return '';
        }
    }
    
    /**
     * Validates the checkbox.
     * @return ValidationState 
     */
    public function validation() {
        // ignore validation
        return $this->validationState;
    }
    
    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function htmlOverview() {
        $fieldName = $this->fieldName;
        $this->html = '';
        $checked = '';
        if($this->entity->$fieldName){
           $checked = 'checked="checked"';
        }
        $this->html .= '<input class="input-field" type="checkbox" '. $checked .' disabled="disabled" />';
        return  $this->html;
    }
    
    /**
     * Updates the model.
     */
    public function update(){
        $fieldName = $this->fieldName;
        $value = $this->getPost();
        if($value == 'checked'){
            $this->entity->$fieldName = true;
        }
        else {
            $this->entity->$fieldName = false;
        }
    }
}
