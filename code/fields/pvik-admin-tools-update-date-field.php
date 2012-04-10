<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a field that contains the date of the last update date.
 */
class PvikAdminToolsUpdateDateField extends PvikAdminToolsBaseField{
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
     * Returns the html for the overview.
     * @return type 
     */
    public function HtmlOverview() {
        $this->Html = '';
        $FieldName = $this->FieldName;
        return  $this->Model->$FieldName;
    }
    
    /**
     * Checks if the field value is valid.
     * @return ValidationState 
     */
    public function Validation() {
        // ignore 
        return $this->ValidationState;
    }
    
    /**
     *  Updates the model field.
     */
    public function Update(){
        $FieldName = $this->FieldName;
        $this->Model->$FieldName =date('Y-m-d');
    }
    
    
    
}
?>