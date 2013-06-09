<?php
namespace PvikAdminTools\Library;
use \Pvik\Database\ORM\Entity;
use \Pvik\Database\ORM\EntityArray;
use \Pvik\Web\Request;
use \Pvik\Utils\ValidationState;
use \PvikAdminTools\Library\ConfigurationHelper;

/**
 * Displays html for a table list.
 */
class TableHtml {
    /**
     * The entries of the list. 
     * @var EntityArray 
     */
    protected $entityArray;
    /**
     * The html that is displayed.
     * @var type 
     */
    protected $html;
    /**
     * A helper class for the PvikAdminTools configuration.
     * @var ConfigurationHelper 
     */
    protected $configurationHelper;

    /**
     * 
     * @var \Pvik\Web\Request 
     */
    protected $request;
    /**
     * Fields that will be hidden in the list.
     * @var array 
     */
    protected $hiddenFields;

    
    /**
     * The count of columns
     * @var int 
     */
    protected $columns;
    
    /**
     * Contains the redirect back url for the buttons.
     * @var string 
     */
    protected $buttonRedirectBack;
    /**
     * Contains the preset values for the new button.
     * @var array 
     */
    protected $newButtonPresetValues;
    
    /**
     *
     * @param EntityArray $entityArray 
     */
    public function __construct(EntityArray $entityArray,Request  $request){
        $this->entityArray = $entityArray;
        $this->request = $request;
        $this->configurationHelper = new ConfigurationHelper();
        $this->configurationHelper->setCurrentTable($this->entityArray->getModelTable()->getModelTableName());
        $this->hiddenFields = array();
        $this->columns = 0;
    }
    
    /**
     * Sets the array of fields that will be hidden.
     * @param array $hiddenFields 
     */
    public function setHiddenFields(array $hiddenFields){
        $this->hiddenFields = $hiddenFields;
    }
    
    /**
     * This functions set a redirect back url when you click on new.
     * After you submitted the new table entry the form redirects back to the the url.
     * @param string $url 
     */
    public function setButtonRedirectBack($url){
        $this->buttonRedirectBack = $url;
    }
    
    /**
     * This functions set preset values for the new button url.
     * The new table entry form recognises the values and preset them.
     * @param array $presetValues 
     */
    public function setNewButtonPresetValues(array $presetValues){
        $this->newButtonPresetValues = $presetValues;
    }
    
    /**
     * Returns the html for the list.
     * @return type 
     */
    public function toHtml(){
        $this->html = '<table class="table table-hover table-bordered">';
        // create table header
        $this->addHtmlTableHeader();
        // create rows
        $this->html .= '<tbody>';
        foreach($this->entityArray as $entity){
            $this->addHtmlTableRow($entity);
        }
        $this->addHtmlTableFooter();
        $this->html .= '</tbody>';
        $this->html .= '</table>';
        return $this->html;
    }
    
    /**
     * Adds a table header to the html.
     */
    protected function addHtmlTableHeader(){
        $this->html .= '<thead><tr>';
        foreach($this->configurationHelper->getFieldList() as $fieldName){
            if($this->configurationHelper->showInOverView($fieldName)&&!in_array($fieldName, $this->hiddenFields)){
                    $this->columns++;
                    $this->html .= '<th>' . $fieldName . '</th>';
            }
        }
        if($this->configurationHelper->hasForeignTables()){
            foreach($this->configurationHelper->getForeignTables() as $foreignTableName => $foreignTable ){
                if(isset($foreignTable['ShowCountInOverview'])&&$foreignTable['ShowCountInOverview'] == true){
                    $this->columns++;
                    $this->html .= '<th>' . $foreignTableName . '</th>';
                }
            }
        }
        
        // add for options
        $this->columns++;
        $this->html .= '<th class="options"></th>';
        $this->html .= '</thead></tr>';
        
    }
    
    /**
     * Adds a table row to the html.
     * @param Entity $entity 
     */
    protected function addHtmlTableRow(Entity $entity){
        $this->html .= '<tr>';
        $modelTable = $entity->getModelTable();
        foreach($this->configurationHelper->getFieldList() as $fieldName){
            if($this->configurationHelper->showInOverView($fieldName)&&!in_array($fieldName, $this->hiddenFields)){
                $type = $this->configurationHelper->getFieldType($fieldName);
                $fieldClassName = 'PvikAdminTools\\Library\\Fields\\' . $type;
                if(!class_exists($fieldClassName)){
                    throw new \Exception('PvikAdminTools: The type '.$type . ' does not exists. Used for the field '. $fieldName);
                }
                $field = new $fieldClassName($fieldName, $entity, $this->request, new ValidationState());
              
                /* @var $field PvikAdminToolsBaseField */
                $this->html .= '<td>' . $field->htmlOverview() . '</td>';
          
            }
        }
        if($this->configurationHelper->hasForeignTables()){
            foreach($this->configurationHelper->getForeignTables() as $foreignTableName => $foreignTable){
                if(isset($foreignTable['ShowCountInOverview'])&&$foreignTable['ShowCountInOverview'] == true){
                    // much faster than accessing view $entity->$foreignTableName->count
                    // this would load the entire entries
                    $this->html .= '<td>' .  count($entity->getKeys(lcfirst($foreignTableName))) . '</td>';
                }
            }
        }
        // add options
        $this->html .= '<td class="options">[';
        
        $editButtonUrl = \Pvik\Core\Path::relativePath('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($modelTable->getModelTableName()) . ':edit:'. $entity->getPrimaryKey().'/');
        if($this->buttonRedirectBack!=null){
            $editButtonUrl .= '?redirect-back-url=' . urlencode($this->buttonRedirectBack);
        }
        
        $this->html .= '<a href="'. $editButtonUrl .'">edit</a>|';
        
        $deleteButtonUrl = \Pvik\Core\Path::relativePath('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($modelTable->getModelTableName()) . ':delete:'. $entity->getPrimaryKey().'/');
        if($this->buttonRedirectBack!=null){
            $deleteButtonUrl .= '?redirect-back-url=' . urlencode($this->buttonRedirectBack);
        }
        $this->html .= '<a href="'. $deleteButtonUrl .'" onclick="return confirm(\'Do you really want to delete this entry?\')">delete</a>';
        $this->html .= ']</td>';
        $this->html .= '</tr>';
    }
    
    /**
     * Adds a table footer to the html.
     */
    protected function addHtmlTableFooter(){
        $modelTable = $this->entityArray->getModelTable();
        $this->html .= '<tr>';
        
        $newButtonUrl =  \Pvik\Core\Path::relativePath('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url']) . 'tables/' .strtolower($modelTable->getModelTableName()) . ':new';
        
        if($this->newButtonPresetValues!=null){
            $newButtonUrl .= '/';
            $first = true;
            foreach($this->newButtonPresetValues as $key => $value){
                if($first){
                    $first = false;
                }
                else {
                    $newButtonUrl .= ':';
                }
                $newButtonUrl .= strtolower($key). ':' . $value;
            }
        }
        $newButtonUrl .= '/';
        
        if($this->buttonRedirectBack!=null){
            $newButtonUrl .= '?redirect-back-url=' . urlencode($this->buttonRedirectBack);
        }
        for ($index = 0; $index < $this->columns ; $index++) {
            if($index + 1 == $this->columns){
                // last column
                $this->html .= '<td class="options">[';
                $this->html .= '<a href="'. $newButtonUrl .'">new</a>';
                $this->html .= ']</td>';
            }
            else {
                $this->html .= '<td>';
                $this->html .= '</td>';
            }
        }
        $this->html .= '</tr>';
    }
    
}
