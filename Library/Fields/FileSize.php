<?php
namespace PvikAdminTools\Library\Fields;
/**
 * Displays a disabled file size field that gets the file size of a file.
 */
class FileSize extends Normal{
   
    
    protected function addHtmlSingleControl(){
        $disabled = 'disabled="disabled"';
      
        $this->html .= '<input class="span8" name="'. $this->getLowerFieldName() .'" type="text" value="'. $this->getPresetValue() .'" ' . $disabled . ' />';    
    
    }
    /**
     * Validates the field value.
     * @return ValidationState 
     */
    public function validation() {
        // ignore 
        return $this->validationState;
    }
    
    /**
     * Updates the model.
     */
    public function update(){
        $fieldName = $this->fieldName;
        $field = $this->configurationHelper->getField($fieldName);
        // wrong 'UseField' configuration
        if(!isset($field['UseField'])||!$this->fieldDefinitionHelper->fieldExists($field['UseField'])){
            throw new \Exception('PvikAdminTools: UseField for '. $fieldName . ' is not set up correctly. UseField is missing or it the stated field does not exists.');
        }
        $useField = $field['UseField'];
        
        $this->model->$fieldName = filesize(\Pvik\Core\Path::realPath($this->getPOST($useField)));
    }
}

