<?php
/**
 * A class that contains the basic methods for a html table field 
 */
abstract class PvikAdminToolsBaseField {
    /**
     * The current model
     * @var Model
     */
    protected $Model;
    /**
     * Indicates if the model is a new model.
     * @var bool 
     */
    protected $IsNewModel;
    /**
     * Contains the html output.
     * @var type 
     */
    protected $Html;
    /**
     * Contains the helper class for the configuration.
     * @var PvikAdminToolsTablesConfigurationHelper 
     */
    protected $PvikAdminToolsTablesConfigurationHelper;
    /**
     * Contains the helper class for the model table field definition.
     * @var FieldDefinitionHelper
     */
    protected $FieldDefinitionHelper;
    /**
     * Contains the name of the field.
     * @var string 
     */
    protected $FieldName;
    /**
     * Contains the validation state of the current entry.
     * @var ValidationState
     */
    protected $ValidationState;
    /**
     * Contains the model table of the current entry.
     * @var ModelTable 
     */
    protected $ModelTable;
    
    /**
     * Contains a preset value for the field.
     * @var string 
     */
    protected $Preset;
    
    /**
     *
     * @param string $FieldName
     * @param Model $Model
     * @param ValidationState $ValidationState 
     */
    public function __construct($FieldName, Model $Model, ValidationState $ValidationState){
        $this->Model = $Model;
        $this->ModelTable = $this->Model->GetModelTable();
        $this->FieldName = $FieldName;
        $this->FieldDefinitionHelper = $this->ModelTable->GetFieldDefinitionHelper();
        $this->PvikAdminToolsTablesConfigurationHelper = new PvikAdminToolsTablesConfigurationHelper();
        $this->PvikAdminToolsTablesConfigurationHelper->SetCurrentTable($this->ModelTable->GetTableName());
        $this->ValidationState = $ValidationState;
        if($this->Model->GetPrimaryKey()==null||$this->Model->GetPrimaryKey()==''){
            $this->IsNewModel = true;
        }
        else {
            $this->IsNewModel = false;
        }
        $this->Preset = '';
    }
    
    /**
     * Sets the preset value of the current field.
     * @param string $Preset 
     */
    public function SetPreset($Preset){
        $this->Preset = $Preset;
    }
    
    /**
     * Checks if the model is a new model.
     * @return bool 
     */
    protected function IsNewModel(){
        return $this->IsNewModel;
    }
    
    /**
     * Returns the $_POST value of the current field if no field name given.
     * @param string $FieldName [optional]
     * @return string 
     */
    protected function GetPOST($FieldName = ''){
        if($FieldName==''){
            $FieldName = $this->FieldName;
        }
        return Core::GetPOST(strtolower($FieldName));
    }

    /**
     * Returns the preset value of the current field.
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
        elseif($this->Preset!=''){
            return $this->Preset;
        }
        elseif($this->PvikAdminToolsTablesConfigurationHelper->HasValueField($FieldName, 'Preset')){
            return $this->PvikAdminToolsTablesConfigurationHelper->GetValue($FieldName, 'Preset');
        }
        else {
            return '';
        }
    }
    
    /**
     * Returns a lowered name of the current field name.
     * @return string 
     */
    protected function GetLowerFieldName(){
        return strtolower($this->FieldName);
    }
    
    /**
     * Checks if this field is visible in single edit/new mode.
     * @return bool 
     */
    public function IsVisibleSingle(){
        if($this->PvikAdminToolsTablesConfigurationHelper->FieldExists($this->FieldName) 
                && $this->PvikAdminToolsTablesConfigurationHelper->IsTypeIgnore($this->FieldName)){
            return false;
        }
        return true;
    }
    
    /**
     * Adds a label to the html.
     */
    protected function AddHtmlLabel(){
        $this->Html .= '<label class="label-field">' . $this->FieldName . '</label>';
    }
    
    /**
     * Validates the current field.
     * @return ValidationState 
     */
    public function Validation(){
      $Message = '';
      if(!$this->PvikAdminToolsTablesConfigurationHelper->IsDisabled($this->FieldName) &&  !$this->PvikAdminToolsTablesConfigurationHelper->IsNullable($this->FieldName)&&($this->GetPOST($this->FieldName)===null||$this->GetPOST($this->FieldName)==="")){
          $this->ValidationState->SetError($this->FieldName, 'Can not be empty.');
      }
      return $this->ValidationState;
    }
    
    /**
     * Adds a validation field to the html.
     */
    protected function AddHtmlValidationField(){
        $Message = $this->ValidationState->GetError($this->FieldName);
        if($Message!=''){
            $this->Html .= '<span class="error-message">'. $Message .'</span>';
        }
    }
    
    /**
     * Adds a single field for the html in edit/update mode.
     */
    public abstract function HtmlSingle();
    
    /**
     * Adds a overview field for the htm in overview mode.
     */
    public abstract function HtmlOverview();
    
    /**
     * Updates a model field
     */
    public function Update(){
        $FieldName = $this->FieldName;
        if($this->PvikAdminToolsTablesConfigurationHelper->IsDisabled($this->FieldName)){
            if(!$this->PvikAdminToolsTablesConfigurationHelper->HasValueField($this->FieldName, 'Preset')){
                throw new Exception('PvikAdminTools: Field ' . $this->FieldName . ' is disabled but does not have a "Preset" value.');
            }
             $this->Model->$FieldName = $this->PvikAdminToolsTablesConfigurationHelper->GetValue($this->FieldName, 'Preset');
        }
        else {
            $this->Model->$FieldName = $this->GetPOST();
        }
    }
}
?>
