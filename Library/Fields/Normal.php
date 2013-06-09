<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a text field.
 */
class Normal extends Base{
    /**
     * Returns the html.
     * @return string 
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
    
    protected function addHtmlSingleControl(){
        $disabled = '';
        if($this->configurationHelper->fieldExists($this->fieldName)
                && $this->configurationHelper->isDisabled($this->fieldName)){
            $disabled = 'disabled="disabled"';
        }
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="text" value="'. htmlentities(utf8_decode($this->getPresetValue())) .'" ' . $disabled . ' />';
    }
    
    /**
     * Returns the html for the overview.
     * @return type 
     */
    public function htmlOverview() {
        $this->html = '';
        $fieldName = $this->fieldName;
        return  htmlentities(utf8_decode($this->entity->$fieldName));
    }
    
    
    
}
