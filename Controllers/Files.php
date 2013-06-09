<?php
namespace PvikAdminTools\Controllers;
use \Pvik\Utils\ValidationState;
/**
 * Contains the logic for uploading a file.
 */
class Files extends Base{
    /**
     * Logic for uploading a file.
     */
    public function uploadFileAction(){
        if($this->checkPermission()){
            $validationState = new ValidationState();
            $uploaded = false;
            // post data send
            if($this->request->isPOST('submit')){
                $folders = \Pvik\Core\Config::$config['PvikAdminTools']['FileFolders'];
                $selectedFolder =$this->request->getPOST('folder');
                $folderValid = false;
                foreach($folders as $folder){
                    if($selectedFolder==$folder){
                        $folderValid = true;
                        break;
                    }
                }
                if ($folderValid&&isset($_FILES['file']) && $_FILES['file']['error'] == 0){
                    $fileName =  $_FILES['file']['name'];
                    if($this->request->isPOST('name')&&$this->request->getPOST('name')!=''){
                        $fileName = $this->request->getPOST('name');
                    }
                    $diretoryName = dirname(\Pvik\Core\Path::realPath($selectedFolder . $fileName));
                    if(!is_dir($diretoryName)){
                        if (!mkdir($diretoryName, 0777, true)){
                            $validationState->setError('File', 'error creating folder');
                        }
                    }
                    if($validationState->isValid()){
                        move_uploaded_file($_FILES['file']['tmp_name'],\Pvik\Core\Path::realPath($selectedFolder . $fileName));
                        $uploaded = true;
                    }
                    
                }
                else {
                    $validationState->setError('File', 'error uploading');
                }
            }
            $this->viewData->set('ValidationState', $validationState);
            $this->viewData->set('Uploaded', $uploaded);
            $this->executeView();
        }
    }
}
