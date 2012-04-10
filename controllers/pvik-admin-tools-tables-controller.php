<?php
/**
 * Logic for a table list or single entry.
 */
class PvikAdminToolsTablesController extends PvikAdminToolsBaseController {

    /**
     * Contains a helper class for the PvikAdminTools configuration.
     * @var PvikAdminToolsTablesConfigurationHelper 
     */
    protected $PvikAdminToolsTablesConfigurationHelper;

    /**
     * Returns the name of a model table.
     * @param string $ParameterTableName lower cased
     * @return string 
     */
    protected function GetModelTableName($ParameterTableName) {
        foreach (Core::$Config['PvikAdminTools']['Tables'] as $TableName => $TableConfiguration) {
            if (strtolower($ParameterTableName) == strtolower($TableName)) {
                return $TableName;
            }
        }
        return null;
    }

    /**
     * Redirects to the root tables page.
     */
    protected function RedirectToTables() {
        $Url = '~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/';
        $this->RedirectToPath($Url);
    }

    /**
     * Displays a list of all tables.
     */
    public function Index() {
        if ($this->CheckPermission()) {
            $this->ExecuteView();
        }
    }

    /**
     * Redirects to the right action depending on the parameters.
     */
    public function IndexWithParameters() {
        $this->PvikAdminToolsTablesConfigurationHelper = new PvikAdminToolsTablesConfigurationHelper();
        if ($this->CheckPermission()) {
            $Parameters = $this->GetParameters('parameters');
            if (count($Parameters) >= 2) {
                $ParameterTableName = $Parameters[0];
                $ModelTableName = $this->GetModelTableName($ParameterTableName);
                $this->PvikAdminToolsTablesConfigurationHelper->SetCurrentTable($ModelTableName);
                if ($ModelTableName != null) {
                    $Action = $Parameters[1];
                    switch ($Action) {
                        case 'list':
                            $this->ListTable($ModelTableName);
                            break;
                        case 'new':
                            $this->NewEntry($ModelTableName);
                            break;
                        case 'edit':
                            if (count($Parameters) == 3) {
                                $this->EditEntry($ModelTableName, $Parameters[2]);
                            } else {
                                $this->RedirectToTables();
                            }
                            break;
                        case 'delete':
                            if (count($Parameters) == 3) {
                                $this->DeleteEntry($ModelTableName, $Parameters[2]);
                            } else {
                                $this->RedirectToTables();
                            }
                            break;
                        default:
                            $this->RedirectToTables();
                            break;
                    }
                } else {
                    $this->RedirectToTables();
                }
            } else {
                $this->RedirectToTables();
            }
        }
    }

    /**
     * Lists table entries.
     * @param string $ModelTableName 
     */
    protected function ListTable($ModelTableName) {
        $ModelArray = ModelTable::Get($ModelTableName)->LoadAll();
        $this->ViewData->Set('ModelTableName', $ModelTableName);
        $this->ViewData->Set('ModelArray', $ModelArray);
        $this->ExecuteViewByAction('ListTable');
    }

    /**
     * Logic for a new entry. 
     * @param string $ModelTableName 
     */
    protected function NewEntry($ModelTableName) {
        $this->ViewData->Set('ModelTableName', $ModelTableName);
        $ParameterPresetValues = $this->GetParameters('preset-values');

        $PresetValues = array();
        if ($ParameterPresetValues != null) {
            // preset values must be a even number
            if(count($ParameterPresetValues) % 2 == 0){
                // convert to associative array
                for ($Index = 0; $Index < count($ParameterPresetValues); $Index++) {
                    $Key = $ParameterPresetValues[$Index];
                    $Index++;
                    $Value = $ParameterPresetValues[$Index];
                    $PresetValues[$Key] = $Value;
                }
                
            }
            else {
                $this->RedirectToTables();
                exit();
            }
            
        } 
        $this->ViewData->Set('PresetValues',$PresetValues);
        
        
        // data send
        if (Core::IsPOST('submit')) {
            $ModelTable = ModelTable::Get($ModelTableName);

            $ModelName = $ModelTable->GetModelClassName();
            $Model = new $ModelName();

            $ValidationState = new ValidationState();
            $Fields = array();
            foreach ($this->PvikAdminToolsTablesConfigurationHelper->GetFieldList() as $FieldName) {
                $Type = $this->PvikAdminToolsTablesConfigurationHelper->GetFieldType($FieldName);
                $FieldClassName = 'PvikAdminTools' . $Type . 'Field';
                if (!class_exists($FieldClassName)) {
                    throw new Exception('PvikAdminTools: The type ' . $Type . ' does not exists. Used for the field ' . $FieldName);
                }
                $Field = new $FieldClassName($FieldName, $Model, $ValidationState);
                /* @var $Field PvikAdminToolsBaseField */
                array_push($Fields, $Field);
                $ValidationState = $Field->Validation();
            }
            if ($ValidationState->IsValid()) {
                // update all fields
                foreach ($Fields as $Field) {
                    /* @var $Field PvikAdminToolsBaseField */
                    $Field->Update();
                }
                $Model->Insert();
                
                $RedirectBackUrl = Core::GetGET('redirect-back-url');
                if($RedirectBackUrl!=null){
                    // the user was was in edit mode of an table entry
                    // clicked on new in a foreign id and and created/updated/deleted a entry
                    // now we redirect back to the edit mode
                     $this->RedirectToPath(urldecode($RedirectBackUrl));
                }
                else {
                    // redirect to inserted entry
                    $this->RedirectToPath('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' . strtolower($ModelTableName) . ':edit:' . $Model->GetPrimaryKey() . '/');
                }
                
            } else {
                $this->ViewData->Set('ValidationState', $ValidationState);
                $this->ExecuteViewByAction('NewEntry');
            }
        } else {
            $ValidationState = new ValidationState();
            $this->ViewData->Set('ValidationState', $ValidationState);
            $this->ExecuteViewByAction('NewEntry');
        }
    }

    /**
     * Logic for editing an entry.
     * @param string $ModelTableName
     * @param string $ModelPrimaryKey 
     */
    protected function EditEntry($ModelTableName, $ModelPrimaryKey) {
        
        $this->ViewData->Set('ModelTableName', $ModelTableName);
        $ModelTable = ModelTable::Get($ModelTableName);
        $Model = $ModelTable->LoadByPrimaryKey($ModelPrimaryKey);
        if ($Model != null) {
            // sets the redirect back url 
            // if somebody clicks on new in a foreign table and submit the form he gets redirect back to this entry
            $this->ViewData->Set('RedirectBackUrl', '~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' . strtolower($ModelTableName) . ':edit:' . $Model->GetPrimaryKey() . '/');
            $this->ViewData->Set('Model', $Model);
            $Fields = array();

            // data send
            if (Core::IsPOST('submit')) {
                $ValidationState = new ValidationState();
                $Fields = array();
                foreach ($this->PvikAdminToolsTablesConfigurationHelper->GetFieldList() as $FieldName) {
                    $Type = $this->PvikAdminToolsTablesConfigurationHelper->GetFieldType($FieldName);
                    $FieldClassName = 'PvikAdminTools' . $Type . 'Field';
                    if (!class_exists($FieldClassName)) {
                        throw new Exception('PvikAdminTools: The type ' . $Type . ' does not exists. Used for the field ' . $FieldName);
                    }
                    $Field = new $FieldClassName($FieldName, $Model, $ValidationState);
                    /* @var $Field PvikAdminToolsBaseField */
                    array_push($Fields, $Field);
                    $ValidationState = $Field->Validation();
                }

                $this->ViewData->Set('ValidationState', $ValidationState);

                if ($ValidationState->IsValid()) {
                    // update all fields
                    foreach ($Fields as $Field) {
                        /* @var $Field PvikAdminToolsBaseField */
                        $Field->Update();
                    }
                    $Model->Update();
                }
                
                $RedirectBackUrl = Core::GetGET('redirect-back-url');
                if($RedirectBackUrl!=null){
                    // the user was was in edit mode of an table entry
                    // clicked on new in a foreign id and and created/updated/deleted a entry
                    // now we redirect back to the edit mode
                     $this->RedirectToPath(urldecode($RedirectBackUrl));
                }
                else {
                    $this->ExecuteViewByAction('EditEntry');
                }
            } else {
                $ValidationState = new ValidationState();
                $this->ViewData->Set('ValidationState', $ValidationState);
                $this->ExecuteViewByAction('EditEntry');
            }
        } else {
            $this->RedirectToTables();
        }
    }

    /**
     * Logic for deleting an entry.
     * @param type $ModelTableName
     * @param type $ModelPrimaryKey 
     */
    protected function DeleteEntry($ModelTableName, $ModelPrimaryKey) {
        $Model = ModelTable::Get($ModelTableName)->LoadByPrimaryKey($ModelPrimaryKey);
        if ($Model != null) {
            $Model->Delete();
        }
        
        $RedirectBackUrl = Core::GetGET('redirect-back-url');
        if($RedirectBackUrl!=null){
            // the user was was in edit mode of an table entry
            // clicked on new in a foreign id and and created/updated/deleted a entry
            // now we redirect back to the edit mode
             $this->RedirectToPath(urldecode($RedirectBackUrl));
        }
        else {
            $Url = '~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' . strtolower($ModelTableName) . ':list/';
            $this->RedirectToPath($Url);
        }
    }

}

?>