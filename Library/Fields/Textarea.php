<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a textarea.
 */
class Textarea extends Base {
    
    /**
     * Returns the html for the field.
     * @return type 
     */
    protected function addHtmlSingleControl(){
        $this->html .= '<textarea class="span8" name="'. $this->getLowerFieldName() .'" cols="50" rows="15" >'.htmlentities(utf8_decode($this->getPresetValue())) .'</textarea>';
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
}