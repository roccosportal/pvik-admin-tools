<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-normal-field.php');
/**
 * Displays a disabled file size field that gets the file size of a file.
 */
class PvikAdminToolsFileSizeField extends PvikAdminToolsNormalField{
    /**
     * Returns the html for the field.
     * @return string 
     */
     public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        
        $Disabled = 'disabled="disabled"';
        if($this->IsNewModel){
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="text" value="'. $this->GetPresetValue() .'" ' . $Disabled . ' />';
        }
        else {
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="text" value="'. $this->GetPresetValue() .'" ' . $Disabled . ' />';
        }
        $this->AddHtmlValidationField();
        return $this->Html;
    }
    
    
    /**
     * Validates the field value.
     * @return ValidationState 
     */
    public function Validation() {
        // ignore 
        return $this->ValidationState;
    }
    
    /**
     * Updates the model.
     */
    public function Update(){
        $FieldName = $this->FieldName;
        $Field = $this->PvikAdminToolsTablesConfigurationHelper->GetField($FieldName);
        // wrong 'UseField' configuration
        if(!isset($Field['UseField'])||!$this->FieldDefinitionHelper->FieldExists($Field['UseField'])){
            throw new Exception('PvikAdminTools: UseField for '. $FieldName . ' is not set up correctly. UseField is missing or it the stated field does not exists.');
        }
        $UseField = $Field['UseField'];
        
        $this->Model->$FieldName = filesize(Core::RealPath($this->GetPOST($UseField)));
    }
}
?>
