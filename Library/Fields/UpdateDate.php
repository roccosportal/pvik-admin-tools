<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a field that contains the date of the last update date.
 */
class UpdateDate extends Base{
    /**
     * Returns the html for the field.
     * @return string 
     */
     protected function addHtmlSingleControl(){
       
        $disabled = 'disabled="disabled"';
       
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="text" value="'. $this->getPresetValue() .'" ' . $disabled . ' />';
        
       
    }
    
    /**
     * Returns the html for the overview.
     * @return type 
     */
    public function htmlOverview() {
        $this->html = '';
        $fieldName = $this->fieldName;
        return  $this->entity->$fieldName;
    }
    
    /**
     * Checks if the field value is valid.
     * @return ValidationState 
     */
    public function validation() {
        // ignore 
        return $this->validationState;
    }
    
    /**
     *  Updates the model field.
     */
    public function update(){
        $fieldName = $this->fieldName;
        $this->entity->$fieldName =date('Y-m-d');
    }
    
    
    
}