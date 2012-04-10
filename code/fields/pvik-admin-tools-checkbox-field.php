<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a checkbox.
 */
class PvikAdminToolsCheckboxField extends PvikAdminToolsBaseField {
    /**
     * Returns the html for the checkbox.
     * @return string 
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
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="checkbox" '.$this->GetPresetValue().' value="checked" '. $Disabled .' />';
        }
        else {
            $this->Html .= '<input class="input-field" name="'. $this->GetLowerFieldName() .'" type="checkbox" '. $this->GetPresetValue() .' value="checked" '. $Disabled .' />';
        }
        $this->AddHtmlValidationField();
        return $this->Html;
    }
    
    /**
     * Returns the preset value for the checkbox
     * @return string 
     */
    public function GetPresetValue() {
        $FieldName = $this->FieldName;
        if(Core::IsPOST(strtolower($FieldName))){
            if($this->GetPOST()=='checked'){
               return 'checked="checked"';
            }
            else {
                return '';
            }
        }
        elseif(!$this->IsNewModel()){
            if($this->Model->$FieldName){
                return 'checked="checked"';
            }
            else {
                return '';
            }
        }
        elseif($this->PvikAdminToolsTablesConfigurationHelper->HasValueField($FieldName, 'Preset') &&
            $this->PvikAdminToolsTablesConfigurationHelper->GetValue($FieldName, 'Preset')=='checked'){
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
    public function Validation() {
        // ignore validation
        return $this->ValidationState;
    }
    
    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function HtmlOverview() {
        $FieldName = $this->FieldName;
        $this->Html = '';
        $Checked = '';
        if($this->Model->$FieldName){
           $Checked = 'checked="checked"';
        }
        $this->Html .= '<input class="input-field" type="checkbox" '. $Checked .' disabled="disabled" />';
        return  $this->Html;
    }
    
    /**
     * Updates the model.
     */
    public function Update(){
        $FieldName = $this->FieldName;
        $Value = $this->GetPost();
        if($Value == 'checked'){
            $this->Model->$FieldName = true;
        }
        else {
            $this->Model->$FieldName = false;
        }
    }
}
?>