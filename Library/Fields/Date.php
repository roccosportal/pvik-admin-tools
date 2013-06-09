<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a date field.
 */
class Date extends Base{
   
     protected function addHtmlSingleControl(){
        $disabled = '';
        if($this->configurationHelper->fieldExists($this->fieldName)
                && $this->configurationHelper->isDisabled($this->fieldName)){
            $disabled = 'disabled="disabled"';
        }
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="text" value="' . $this->getPresetValue().'" '. $disabled .' />';
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
     * Validates if the field value is a date.
     * @return ValidationState 
     */
    public function validation() {
        parent::validation();
        if($this->validationState->getError($this->fieldName)==null && !$this->isEnglishDate($this->getPost($this->fieldName))){
              $this->validationState->setError($this->fieldName, 'Not a date.');
        }
        return $this->validationState;
    }
    
    protected function isEnglishDate($date){
        $dateArray = explode('-', $date);
        if(count($dateArray)==3){
            if(is_numeric($dateArray[0])&&is_numeric($dateArray[1])&&is_numeric($dateArray[2])){
                return checkdate($dateArray[1],$dateArray[2],$dateArray[1]);
            }
        }
        return false;
    }
    
    /**
     * Returns the preset type for the date field.
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
        elseif($this->configurationHelper->hasValueField($fieldName, 'Preset')){
            return $this->configurationHelper->getValue($fieldName, 'Preset');
        }
        else {
            return date('Y-m-d');
        }
    }
    
}