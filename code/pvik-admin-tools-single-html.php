<?php
/**
 * Display html for a single model entry.
 */
class PvikAdminToolsSingleHtml{
    /**
     * The model entry that is displayed.
     * @var Model 
     */
    protected $Model;
    /**
     * A helper class for the PvikAdminTools configuration.
     * @var PvikAdminToolsTablesConfigurationHelper 
     */
    protected $PvikAdminToolsTablesConfigurationHelper;
    /**
     * The ValidationState of the current model entry.
     * @var ValidationState
     */
    protected $ValidationState;
    /**
     * Indicates if a model is new.
     * @var bool 
     */
    protected $IsNewModel;
    
    /**
     * Contains an associative array of field preset values.
     * @var array 
     */
    protected $PresetValues;
    
    /**
     * Contains the redirect back url for foreign tables after clicking new and submitting the form
     * @var string 
     */
    protected $ForeignTableButtonRedirectBackUrl;
    
    /**
     * The html that is displayed.
     * @var string. 
     */
    protected $Html;
    
    /**
     *
     * @param Model $Model
     * @param ValidationState $ValidationState 
     */
    public function __construct(Model $Model,ValidationState $ValidationState){
        
        $this->Model = $Model;
        $this->PvikAdminToolsTablesConfigurationHelper = new PvikAdminToolsTablesConfigurationHelper();
        $this->PvikAdminToolsTablesConfigurationHelper->SetCurrentTable($this->Model->GetModelTable()->GetTableName());
        $this->ValidationState = $ValidationState;
        if($this->Model->GetPrimaryKey()==null||$this->Model->GetPrimaryKey()==''){
            $this->IsNewModel = true;
        }
        else {
            $this->IsNewModel = false;
        }
        $this->PresetValues = array();
    }
    
    /**
     * Checks if the model is new.
     * @return bool 
     */
    protected function IsNewModel(){
        return $this->IsNewModel;
    }
    
    /**
     * Set the preset values for fields.
     * Must be an associative array.
     * @param array $PresetValues 
     */
    public function SetPresetValues(array $PresetValues){
        $this->PresetValues = $PresetValues;
    }
    
    /**
     * This functions set a redirect back url when you click on new in a foreign table.
     * After you submitted the new table entry the form redirects back to the the url.
     * @param string $Url 
     */
    public function SetForeignTableButtonRedirectBackUrl($Url){
        $this->ForeignTableButtonRedirectBackUrl = $Url;
    }
    
    /**
     * Returns the html of the entry.
     * @return string 
     */
    public function ToHtml(){
        $this->Html = '<form method="post">';
        foreach($this->PvikAdminToolsTablesConfigurationHelper->GetFieldList() as $FieldName){
            $Type = $this->PvikAdminToolsTablesConfigurationHelper->GetFieldType($FieldName);
            $FieldClassName = 'PvikAdminTools' . $Type . 'Field';
            if(!class_exists($FieldClassName)){
                throw new Exception('PvikAdminTools: The type '.$Type . ' does not exists. Used for the field '. $FieldName);
            }
            $Field = new $FieldClassName($FieldName, $this->Model, $this->ValidationState);
            /* @var $Field PvikAdminToolsBaseField */
            if($Field->IsVisibleSingle()){
                if(isset($this->PresetValues[strtolower($FieldName)])){
                    $Field->SetPreset($this->PresetValues[strtolower($FieldName)]);
                }
                $this->Html .= '<div class="field">';
                $this->Html .= $Field->HtmlSingle();
                $this->Html .= '</div>';
            }
            
            
        }
       
        $this->AddHtmlSubmit();
        $this->Html .= '</form>';
        
        if(!$this->IsNewModel()&&$this->PvikAdminToolsTablesConfigurationHelper->HasForeignTables()){
             $this->Html .= '<div class="foreign-tables-field">';
            foreach($this->PvikAdminToolsTablesConfigurationHelper->GetForeignTables() as $ForeignTable => $Configuration){
                $PrimaryKey = $this->Model->GetPrimaryKey();
                $ForeignKey = $Configuration['ForeignKey'];
                $ModelTable = ModelTable::Get($ForeignTable);
                $ModelArray =  $ModelTable->LoadAll();
                $ModelArray = $ModelArray->FilterEquals($ForeignKey, $PrimaryKey);
                $PvikAdminToolsTableHtml =  new PvikAdminToolsTableHtml($ModelArray);
                // saerch for fields that we don't need to show
                $ForeignObjectFieldNames = array ();
                $Helper = $ModelTable->GetFieldDefinitionHelper();
                foreach($Helper->GetFieldList() as $FieldName){
                    // search for a foreign object that uses that refers to the original model
                    // we don't need to show this table column
                    if($Helper->IsTypeForeignObject($FieldName)){
                        if($Helper->GetForeignKeyFieldName($FieldName)==$ForeignKey){
                            array_push($ForeignObjectFieldNames,$FieldName);
                        }
                    }
                }
                
                $PvikAdminToolsTableHtml->SetHiddenFields($ForeignObjectFieldNames);
                
                // set preset values
                $PresetValues = array ();
                foreach($ForeignObjectFieldNames as $ForeignObjectFieldName){
                    $PresetValues[$ForeignObjectFieldName] = $PrimaryKey;
                }
                $PvikAdminToolsTableHtml->SetNewButtonPresetValues($PresetValues);
                
                if($this->ForeignTableButtonRedirectBackUrl!=null){
                    $PvikAdminToolsTableHtml->SetButtonRedirectBack($this->ForeignTableButtonRedirectBackUrl);
                }
                $this->Html .= '<div class="field">';
                $this->Html .= '<label class="label-field">' . $ForeignTable . '</label>';
                $this->Html .= $PvikAdminToolsTableHtml->ToHtml();
                $this->Html .= '</div>';
            }
            $this->Html .= '</div>';
        }
        
        return $this->Html;
    }
    
    /**
     * Adds a submit button to the html.
     */
    protected function AddHtmlSubmit(){
        $this->Html .= '<input class="submit-field" type="submit" name="submit" value="submit" />';
    }
    
}
?>