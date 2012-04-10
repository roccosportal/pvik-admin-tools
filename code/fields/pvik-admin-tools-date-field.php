<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a date field.
 */
class PvikAdminToolsDateField extends PvikAdminToolsBaseField{
    /**
     * Returns the html for the date field
     * @return type 
     */
    public function HtmlSingle(){
        $this->Html = '';
        $this->AddHtmlLabel();
        
        $Disabled = '';
        if($this->PvikAdminToolsTablesConfigurationHelper->FieldExists($this->FieldName)
                && $this->PvikAdminToolsTablesConfigurationHelper->IsDisabled($this->FieldName)){
            $Disabled = 'disabled="disabled"';
        }
        
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
     * Validates if the field value is a date.
     * @return ValidationState 
     */
    public function Validation() {
        parent::Validation();
        if($this->ValidationState->GetError($this->FieldName)==null && !$this->IsEnglishDate($this->GetPost($this->FieldName))){
              $this->ValidationState->SetError($this->FieldName, 'Not a date.');
        }
        return $this->ValidationState;
    }
    
    protected function IsEnglishDate($Date){
        $DateArray = explode('-', $Date);
        if(count($DateArray)==3){
            if(is_numeric($DateArray[0])&&is_numeric($DateArray[1])&&is_numeric($DateArray[2])){
                return checkdate($DateArray[1],$DateArray[2],$DateArray[1]);
            }
        }
        return false;
    }
    
    /**
     * Returns the preset type for the date field.
     * @return string 
     */
    protected function GetPresetValue(){
        $FieldName = $this->FieldName;
        if(Core::IsPOST(strtolower($FieldName))){
            return $this->GetPOST();
        }
        elseif(!$this->IsNewModel()){
            return $this->Model->$FieldName;
        }
        elseif($this->PvikAdminToolsTablesConfigurationHelper->HasValueField($FieldName, 'Preset')){
            return $this->PvikAdminToolsTablesConfigurationHelper->GetValue($FieldName, 'Preset');
        }
        else {
            return date('Y-m-d');
        }
    }
    
}
?>