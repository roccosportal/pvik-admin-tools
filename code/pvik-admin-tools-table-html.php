<?php
/**
 * Displays html for a table list.
 */
class PvikAdminToolsTableHtml {
    /**
     * The entries of the list. 
     * @var ModelArray 
     */
    protected $ModelArray;
    /**
     * The html that is displayed.
     * @var type 
     */
    protected $Html;
    /**
     * A helper class for the PvikAdminTools configuration.
     * @var PvikAdminToolsTablesConfigurationHelper 
     */
    protected $PvikAdminToolsTablesConfigurationHelper;
    /**
     * Fields that will be hidden in the list.
     * @var array 
     */
    protected $HiddenFields;

    
    /**
     * The count of columns
     * @var int 
     */
    protected $Columns;
    
    /**
     * Contains the redirect back url for the buttons.
     * @var string 
     */
    protected $ButtonRedirectBack;
    /**
     * Contains the preset values for the new button.
     * @var array 
     */
    protected $NewButtonPresetValues;
    
    /**
     *
     * @param ModelArray $ModelArray 
     */
    public function __construct(ModelArray $ModelArray){
        $this->ModelArray = $ModelArray;
        $this->PvikAdminToolsTablesConfigurationHelper = new PvikAdminToolsTablesConfigurationHelper();
        $this->PvikAdminToolsTablesConfigurationHelper->SetCurrentTable($this->ModelArray->GetModelTable()->GetTableName());
        $this->HiddenFields = array();
        $this->Columns = 0;
    }
    
    /**
     * Sets the array of fields that will be hidden.
     * @param array $HiddenFields 
     */
    public function SetHiddenFields(array $HiddenFields){
        $this->HiddenFields = $HiddenFields;
    }
    
    /**
     * This functions set a redirect back url when you click on new.
     * After you submitted the new table entry the form redirects back to the the url.
     * @param string $Url 
     */
    public function SetButtonRedirectBack($Url){
        $this->ButtonRedirectBack = $Url;
    }
    
    /**
     * This functions set preset values for the new button url.
     * The new table entry form recognises the values and preset them.
     * @param array $PresetValues 
     */
    public function SetNewButtonPresetValues(array $PresetValues){
        $this->NewButtonPresetValues = $PresetValues;
    }
    
    /**
     * Returns the html for the list.
     * @return type 
     */
    public function ToHtml(){
        $this->Html = '<table class="table">';
        // create table header
        $this->AddHtmlTableHeader();
        // create rows
        foreach($this->ModelArray as $Model){
            $this->AddHtmlTableRow($Model);
        }
        $this->AddHtmlTableFooter();
        $this->Html .= '</table>';
        return $this->Html;
    }
    
    /**
     * Adds a table header to the html.
     */
    protected function AddHtmlTableHeader(){
        $this->Html .= '<tr>';
        foreach($this->PvikAdminToolsTablesConfigurationHelper->GetFieldList() as $FieldName){
            if($this->PvikAdminToolsTablesConfigurationHelper->ShowInOverView($FieldName)&&!in_array($FieldName, $this->HiddenFields)){
                    $this->Columns++;
                    $this->Html .= '<th>' . $FieldName . '</th>';
            }
        }
        // add for options
        $this->Columns++;
        $this->Html .= '<th class="options"></th>';
        $this->Html .= '</tr>';
        
    }
    
    /**
     * Adds a table row to the html.
     * @param Model $Model 
     */
    protected function AddHtmlTableRow(Model $Model){
        $this->Html .= '<tr>';
        $ModelTable = $Model->GetModelTable();
        foreach($this->PvikAdminToolsTablesConfigurationHelper->GetFieldList() as $FieldName){
            if($this->PvikAdminToolsTablesConfigurationHelper->ShowInOverView($FieldName)&&!in_array($FieldName, $this->HiddenFields)){
                $Type = $this->PvikAdminToolsTablesConfigurationHelper->GetFieldType($FieldName);
                $FieldClassName = 'PvikAdminTools' . $Type . 'Field';
                if(!class_exists($FieldClassName)){
                    throw new Exception('PvikAdminTools: The type '.$Type . ' does not exists. Used for the field '. $FieldName);
                }
                $Field = new $FieldClassName($FieldName, $Model, new ValidationState());
              
                /* @var $Field PvikAdminToolsBaseField */
                $this->Html .= '<td>' . $Field->HtmlOverview() . '</td>';
          
            }
        }
        // add options
        $this->Html .= '<td class="options">[';
        
        $EditButtonUrl = Core::RelativePath('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' .strtolower($ModelTable->GetTableName()) . ':edit:'. $Model->GetPrimaryKey().'/');
        if($this->ButtonRedirectBack!=null){
            $EditButtonUrl .= '?redirect-back-url=' . urlencode($this->ButtonRedirectBack);
        }
        
        $this->Html .= '<a href="'. $EditButtonUrl .'">edit</a>|';
        
        $DeleteButtonUrl = Core::RelativePath('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' .strtolower($ModelTable->GetTableName()) . ':delete:'. $Model->GetPrimaryKey().'/');
        if($this->ButtonRedirectBack!=null){
            $DeleteButtonUrl .= '?redirect-back-url=' . urlencode($this->ButtonRedirectBack);
        }
        $this->Html .= '<a href="'. $DeleteButtonUrl .'" onclick="return confirm(\'Do you really want to delete this entry?\')">delete</a>';
        $this->Html .= ']</td>';
        $this->Html .= '</tr>';
    }
    
    /**
     * Adds a table footer to the html.
     */
    protected function AddHtmlTableFooter(){
        $ModelTable = $this->ModelArray->GetModelTable();
        $this->Html .= '<tr>';
        
        $NewButtonUrl =  Core::RelativePath('~' . Core::$Config['PvikAdminTools']['Url']) . 'tables/' .strtolower($ModelTable->GetTableName()) . ':new';
        
        if($this->NewButtonPresetValues!=null){
            $NewButtonUrl .= '/';
            $First = true;
            foreach($this->NewButtonPresetValues as $Key => $Value){
                if($First){
                    $First = false;
                }
                else {
                    $NewButtonUrl .= ':';
                }
                $NewButtonUrl .= strtolower($Key). ':' . $Value;
            }
        }
        $NewButtonUrl .= '/';
        
        if($this->ButtonRedirectBack!=null){
            $NewButtonUrl .= '?redirect-back-url=' . urlencode($this->ButtonRedirectBack);
        }
        for ($Index = 0; $Index < $this->Columns ; $Index++) {
            if($Index + 1 == $this->Columns){
                // last column
                $this->Html .= '<td class="options">[';
                $this->Html .= '<a href="'. $NewButtonUrl .'">new</a>';
                $this->Html .= ']</td>';
            }
            else {
                $this->Html .= '<td>';
                $this->Html .= '</td>';
            }
        }
        $this->Html .= '</tr>';
    }
    
}
?>