<?php
Core::Depends(Core::$Config['PvikAdminTools']['BasePath'] . 'code/fields/pvik-admin-tools-base-field.php');
/**
 * Displays a select field.
 */
class PvikAdminToolsSelectField extends PvikAdminToolsBaseField {
    /**
     * Returns the preset value.
     * @param string $FieldName
     * @param string $Value
     * @return string 
     */
    protected function GetSelectPresetValue($FieldName, $Value){
        if(Core::IsPOST(strtolower($FieldName))){
            if($this->GetPOST($FieldName)==$Value){
                return 'selected="selected"';
            }
            return '';
        }
        elseif(!$this->IsNewModel()){
            if($this->Model->$FieldName == $Value){
                return 'selected="selected"';
            }
            return '';
        }
        elseif($this->Preset!=''&&$this->Preset==$Value){
            return 'selected="selected"';
        }
        elseif($this->PvikAdminToolsTablesConfigurationHelper->HasValueField($this->FieldName, 'Preset') &&
                ($this->PvikAdminToolsTablesConfigurationHelper->GetValue($this->FieldName, 'Preset')==$Value)){
            return 'selected="selected"';
        }
        else {
            return '';
        }
    }
    
    /**
     * Returns the html for the field.
     * @return string 
     */
    public function HtmlSingle(){
        $this->Html = '';
        if($this->FieldDefinitionHelper->IsTypeForeignObject($this->FieldName)){
            $this->AddHtmlLabel();

            $ModelTableName = $this->FieldDefinitionHelper->GetModelTableNameForForeignObject($this->FieldName);
            $ForeignKeyFieldName = $this->FieldDefinitionHelper->GetForeignKeyFieldName($this->FieldName);
            $Field = $this->PvikAdminToolsTablesConfigurationHelper->GetField($this->FieldName);
            $UseField = $Field['UseField'];
            $ModelArray = ModelTable::Get($ModelTableName)->LoadAll();

            $this->Html .= '<select class="select-field" name="'. strtolower($ForeignKeyFieldName) .'">';
            if($this->PvikAdminToolsTablesConfigurationHelper->IsNullable($this->FieldName)){
                 $this->Html .= '<option value="">(none)</option>';
            }
            foreach($ModelArray as $Model){
                $this->Html .= '<option value="' . $Model->GetPrimaryKey() .'" '.$this->GetSelectPresetValue($ForeignKeyFieldName, $Model->GetPrimaryKey()).'>';
                $this->Html .= $Model->$UseField;
                $this->Html .= '</option>';
            }


            $this->Html .= '</select>';
            $this->AddHtmlValidationField();
        }
        else {
            $this->Html = 'Error';
        }
        return $this->Html;

    }
    
    /**
     * Returns the html for the overview.
     * @return string 
     */
    public function HtmlOverview() {
        $this->Html = '';
        $FieldName = $this->FieldName;
        $Field = $this->PvikAdminToolsTablesConfigurationHelper->GetField($this->FieldName);
        $UseField = $Field['UseField'];
        if($this->Model->$FieldName!=null){
             $this->Html = $this->Model->$FieldName->$UseField;
        }
        return $this->Html;
    }
    
    /**
     * Updates a model.
     */
    public function Update(){
        $ForeignKeyFieldName = $this->FieldDefinitionHelper->GetForeignKeyFieldName($this->FieldName);
        $this->Model->$ForeignKeyFieldName = $this->GetPOST($ForeignKeyFieldName);
    }
    
    /**
     * Validates the field value.
     * @return ValidationState 
     */
    public function Validation() {
      $Message = '';
      $ForeignKeyFieldName = $this->FieldDefinitionHelper->GetForeignKeyFieldName($this->FieldName);
      if(!$this->PvikAdminToolsTablesConfigurationHelper->IsNullable($this->FieldName)&&$this->GetPOST($ForeignKeyFieldName)==""){
          $this->ValidationState->SetError($this->FieldName, 'Can not be empty');
      }
      return $this->ValidationState;
    }
}
?>