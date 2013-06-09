<?php
namespace PvikAdminTools\Controllers;
use \Pvik\Database\ORM\ModelTable;
use \Pvik\Utils\ValidationState;
/**
 * Logic for a table list or single entry.
 */
class Tables extends Base {

    /**
     * Contains a helper class for the PvikAdminTools configuration.
     * @var \PvikAdminTools\Library\ConfigurationHelper 
     */
    protected $configurationHelper;

    /**
     * Returns the name of a model table.
     * @param string $parameterTableName lower cased
     * @return string 
     */
    protected function getModelTableName($parameterTableName) {
        foreach (\Pvik\Core\Config::$config['PvikAdminTools']['Tables'] as $tableName => $tableConfiguration) {
            if (strtolower($parameterTableName) == strtolower($tableName)) {
                return $tableName;
            }
        }
        return null;
    }

    /**
     * Redirects to the root tables page.
     */
    protected function redirectToTables() {
        $url = '~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/';
        $this->redirectToPath($url);
    }

    /**
     * Displays a list of all tables.
     */
    public function indexAction() {
        if ($this->checkPermission()) {
            $this->executeView();
        }
    }

    /**
     * Redirects to the right action depending on the parameters.
     */
    public function indexWithParametersAction() {
        $this->configurationHelper = new \PvikAdminTools\Library\ConfigurationHelper();
        if ($this->checkPermission()) {
            $parameters = $this->getParameters('parameters');
            if (count($parameters) >= 2) {
                $parameterTableName = $parameters[0];
                $modelTableName = $this->getModelTableName($parameterTableName);
                $this->configurationHelper->setCurrentTable($modelTableName);
                if ($modelTableName != null) {
                    $action = $parameters[1];
                    switch ($action) {
                        case 'list':
                            $this->listTable($modelTableName);
                            break;
                        case 'new':
                            $this->newEntry($modelTableName);
                            break;
                        case 'edit':
                            if (count($parameters) == 3) {
                                $this->editEntry($modelTableName, $parameters[2]);
                            } else {
                                $this->redirectToTables();
                            }
                            break;
                        case 'delete':
                            if (count($parameters) == 3) {
                                $this->deleteEntry($modelTableName, $parameters[2]);
                            } else {
                                $this->redirectToTables();
                            }
                            break;
                        default:
                            $this->redirectToTables();
                            break;
                    }
                } else {
                    $this->redirectToTables();
                }
            } else {
                $this->redirectToTables();
            }
        }
    }

    /**
     * Lists table entries.
     * @param string $modelTableName 
     */
    protected function listTable($modelTableName) {
        $entityArray = ModelTable::get($modelTableName)->loadAll();

        $tableHtml = new \PvikAdminTools\Library\TableHtml($entityArray, $this->request);
        $this->viewData->set('TableHtml', $tableHtml);
        $this->executeViewByAction('ListTable');
    }

    /**
     * Logic for a new entry. 
     * @param string $modelTableName 
     */
    protected function newEntry($modelTableName) {
        $this->viewData->set('ModelTableName', $modelTableName);
        $parameterPresetValues = $this->getParameters('preset-values');

        $presetValues = array();
        if ($parameterPresetValues != null) {
            // preset values must be a even number
            if(count($parameterPresetValues) % 2 == 0){
                // convert to associative array
                for ($index = 0; $index < count($parameterPresetValues); $index++) {
                    $key = $parameterPresetValues[$index];
                    $index++;
                    $value = $parameterPresetValues[$index];
                    $presetValues[$key] = $value;
                }
                
            }
            else {
                $this->redirectToTables();
                exit();
            }
            
        } 
        $this->viewData->set('PresetValues',$presetValues);
        

        $modelTable = ModelTable::get($modelTableName);

        $entityClassName = $modelTable->getEntityClassName();
        $entity = new $entityClassName();

        
        // data send
        if ($this->request->isPOST('submit')) {
           

            $validationState = new ValidationState();
            $fields = array();
            foreach ($this->configurationHelper->getFieldList() as $fieldName) {
                $type = $this->configurationHelper->getFieldType($fieldName);
                $fieldClassName = '\\PvikAdminTools\\Library\\Fields\\' . $type;
                if (!class_exists($fieldClassName)) {
                    throw new \Exception('PvikAdminTools: The type ' . $type . ' does not exists. Used for the field ' . $fieldName);
                }
                $field = new $fieldClassName($fieldName, $entity, $this->request, $validationState);
                /* @var $field PvikAdminToolsBaseField */
                array_push($fields, $field);
                $validationState = $field->validation();
            }
            if ($validationState->isValid()) {
                // update all fields
                foreach ($fields as $field) {
                    /* @var $field PvikAdminToolsBaseField */
                    $field->update();
                }
                $entity->insert();
                
                $redirectBackUrl = $this->request->getGET('redirect-back-url');
                if($redirectBackUrl!=null){
                    // the user was was in edit mode of an table entry
                    // clicked on new in a foreign id and and created/updated/deleted a entry
                    // now we redirect back to the edit mode
                     $this->redirectToPath(urldecode($redirectBackUrl));
                }
                else {
                    // redirect to inserted entry
                    $this->redirectToPath('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' . strtolower($modelTableName) . ':edit:' . $entity->getPrimaryKey() . '/');
                }
                
            } else {
               

                $singleHtml = new \PvikAdminTools\Library\SingleHtml($entity, $validationState, $this->request);
                $singleHtml->setPresetValues($presetValues);
                $this->viewData->set('SingleHtml', $singleHtml);


                $this->executeViewByAction('NewEntry');
            }
        } else {
            $validationState = new ValidationState();
          
            $singleHtml = new \PvikAdminTools\Library\SingleHtml($entity, $validationState, $this->request);
            $singleHtml->setPresetValues($presetValues);

            $this->viewData->set('SingleHtml', $singleHtml);
            $this->executeViewByAction('NewEntry');
        }
    }

    /**
     * Logic for editing an entry.
     * @param string $modelTableName
     * @param string $entityPrimaryKey 
     */
    protected function editEntry($modelTableName, $entityPrimaryKey) {
        
        $this->viewData->set('ModelTableName', $modelTableName);
        $modelTable = ModelTable::get($modelTableName);
        $entity = $modelTable->loadByPrimaryKey($entityPrimaryKey);
        if ($entity != null) {
            // sets the redirect back url 
            // if somebody clicks on new in a foreign table and submit the form he gets redirect back to this entry
            $redirectBackUrl =  '~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' . strtolower($modelTableName) . ':edit:' . $entity->getPrimaryKey() . '/';
            $fields = array();

            // data send
            if ($this->request->isPOST('submit')) {
                $validationState = new ValidationState();
                $fields = array();
                foreach ($this->configurationHelper->getFieldList() as $fieldName) {
                    $type = $this->configurationHelper->getFieldType($fieldName);
                    $fieldClassName = '\\PvikAdminTools\\Library\\Fields\\' . $type;
                    if (!class_exists($fieldClassName)) {
                        throw new \Exception('PvikAdminTools: The type ' . $type . ' does not exists. Used for the field ' . $fieldName);
                    }
                    $field = new $fieldClassName($fieldName, $entity, $this->request, $validationState);
                    /* @var $field PvikAdminToolsBaseField */
                    array_push($fields, $field);
                    $validationState = $field->validation();
                }

                $this->viewData->set('ValidationState', $validationState);

                if ($validationState->isValid()) {
                    // update all fields
                    foreach ($fields as $field) {
                        /* @var $field PvikAdminToolsBaseField */
                        $field->update();
                    }
                    $entity->update();
                }
                
                $redirectBackUrlAsParameter = $this->request->getGET('redirect-back-url');
                if($redirectBackUrlAsParameter!=null){
                    // the user was was in edit mode of an table entry
                    // clicked on new in a foreign id and and created/updated/deleted a entry
                    // now we redirect back to the edit mode
                     $this->redirectToPath(urldecode($redirectBackUrlAsParameter));
                }
                else {

                    $singleHtml = new \PvikAdminTools\Library\SingleHtml($entity, $validationState, $this->request);
                    $singleHtml->setForeignTableButtonRedirectBackUrl($redirectBackUrl);
                    $this->viewData->set('SingleHtml', $singleHtml);

                    $this->executeViewByAction('EditEntry');
                }
            } else {
                $validationState = new ValidationState();

                $singleHtml = new \PvikAdminTools\Library\SingleHtml($entity, $validationState, $this->request);
                $singleHtml->setForeignTableButtonRedirectBackUrl($redirectBackUrl);
                $this->viewData->set('SingleHtml', $singleHtml);

                $this->executeViewByAction('EditEntry');
            }
        } else {
            $this->redirectToTables();
        }
    }

    /**
     * Logic for deleting an entry.
     * @param type $modelTableName
     * @param type $entityPrimaryKey 
     */
    protected function deleteEntry($modelTableName, $entityPrimaryKey) {
        $entity = ModelTable::get($modelTableName)->loadByPrimaryKey($entityPrimaryKey);
        if ($entity != null) {
            $entity->delete();
        }
        
        $redirectBackUrl = $this->request->getGET('redirect-back-url');
        if($redirectBackUrl!=null){
            // the user was was in edit mode of an table entry
            // clicked on new in a foreign id and and created/updated/deleted a entry
            // now we redirect back to the edit mode
             $this->redirectToPath(urldecode($redirectBackUrl));
        }
        else {
            $url = '~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' . strtolower($modelTableName) . ':list/';
            $this->redirectToPath($url);
        }
    }

}
