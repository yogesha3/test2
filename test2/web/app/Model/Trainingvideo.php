<?php

/**
 * Group Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Gaurav Bhandari
 */
App::uses('AppModel', 'Model');
class Trainingvideo extends AppModel 
{	
    public $validate = array(
        'video_name' => array(            
            'extension' => array(
                'rule' => array('extension', array('mp4')),
                'message' => 'Only MP4 format is allowed',
                ),
            'file-count' => array(
                'rule' => array('fileCount'),
                'message' => 'Only 5 training videos are allowed'
                ),
            'upload-file' => array(
                'rule' => array('uploadFile'),
                'message' => 'Video file size should be maximum of 10 MB'
                )
            )
        );

    /**
    * Check video count on server
    * @param $check array
    * @return bool
    * @author Gaurav
    */
    public function fileCount( $check ) 
    {
        if($this->find('count') > 4)
            return false;
        else
            return true;
    } 

    /**
    * Check video file size and upload to folder
    * @param $check array
    * @return bool
    * @author Gaurav
    */
    public function uploadFile( $check ) 
    {
        $uploadData = array_shift($check);
        if ( $uploadData['size'] > 10000000) {
            return false;
        }
        $folder_url = WWW_ROOT.'trainingvideo/';
        $full_url = $folder_url.str_replace(' ','_',$uploadData['name']);
        if (move_uploaded_file($uploadData['tmp_name'], $full_url)) {
            $this->set('video_name', str_replace(' ','_',$uploadData['name']));
            return true;
        }
        return false;
    }
}