<?php

/**
*	This controller is used for training video management
*/
App::uses('AppController', 'Controller');
class TrainingvideosController extends AppController 
{
    public $includePageJs = '';
    
    /**
     * callback function
     * @author Gaurav Bhandari 
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('reset_password'));
    }

    /**
    * List of all videos
    * @author Gaurav
    */
    public function admin_index() 
    {
    	$this->set('title_for_layout','Training Videos');
        $this->layout = 'admin';
        $videos = $this->Trainingvideo->find('all',array('order'=>'created DESC'));
        $this->set(array(
            'videos' => $videos,
            '_serialize' => array('videos')
        ));
    }

    /**
    * add videos
    * @author Gaurav
    */
    public function admin_add() 
    {
        $this->set('title_for_layout', 'Training Videos');
        $this->includePageJs = array('admin_validation');
        $this->layout = 'admin';
        if ($this->request->is('post')) {
            $fileData = $this->request->data;
            $uploadFile = array_shift($fileData);
            if ($uploadFile['video_name']['size'] > 10000000 || empty($uploadFile)) {
                $this->Session->setFlash(__('Video file size should be maximum of 10 MB'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            }
            $this->request->data['Trainingvideo']['video_name']['name'] =str_replace(array('%','#'), "_", $uploadFile['video_name']['name']);
            $this->Trainingvideo->set($this->request->data);
            if ($this->Trainingvideo->save($this->request->data)) {
                $lastId = $this->Trainingvideo->getLastInsertId();
                $this->Trainingvideo->updateAll(array('Trainingvideo.is_active' => 0), array('Trainingvideo.id !=' => $lastId));
				$this->Trainingvideo->updateAll(array('Trainingvideo.is_active' => 1), array('Trainingvideo.id' => $lastId));
                $this->Session->setFlash(__('Training video has been added successfully'), 'flash_good');
                $this->redirect(array('action' => 'index'));
            } else {
                $validationErrors=$this->compileErrors('Trainingvideo');
                if($validationErrors != NULL) {
                    $this->Session->setFlash($validationErrors, 'flash_bad');
                }                
            }
        }
        $this->set('includePageJs', $this->includePageJs);
    }

    /**
    * Activate Deactivate Training Video
    * @param string $videoId Video Id 
    * @author Gaurav
    */
    public function admin_activate($videoId = null)
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'activate');
            $this->set('info', 'Video');
            $this->set('popupData',$this->parsePopupVars('activate','Video'));
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $video = $this->Trainingvideo->findById($this->Encryption->decode($videoId));
            if (!$video) {
                $this->Session->setFlash(__('Invalid Video'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Trainingvideo->updateAll(array('Trainingvideo.is_active' => 0), array('Trainingvideo.id !=' => $this->Encryption->decode($videoId)));
                $this->Trainingvideo->updateAll(array('Trainingvideo.is_active' => 1), array('Trainingvideo.id ' => $this->Encryption->decode($videoId)));
                $this->Session->setFlash(__('Training video has been activated successfully'), 'flash_good');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    /**
    * Play video in popup (ajax)
    * @author Gaurav
    */
    public function admin_play()
    {
        if ($this->request->is('ajax')) {
            $this->set('videoName', $this->request->data['id']);
        }
    }

    /**
    * Delete Training Video
    * @param string $videoId Video Id 
    * @author Gaurav
    */
    public function admin_delete($videoId = null)
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'delete');
            $this->set('info', 'Video');
            $this->set('popupData',$this->parsePopupVars('delete','Video'));
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $video = $this->Trainingvideo->findById($this->Encryption->decode($videoId));
            if (!$video) {
                $this->Session->setFlash(__('Invalid Video'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else {
                $videoCount = $this->Trainingvideo->find('count');
                if ($videoCount <= 1) {
                    $this->Session->setFlash(__('Training video cannot be deleted'), 'flash_bad');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Trainingvideo->delete($this->Encryption->decode($videoId));
                    $checkActivateVideo = $this->Trainingvideo->find('first', array('conditions' => array('Trainingvideo.is_active' => 'yes')));
                    if (empty($checkActivateVideo)) {
                        $lastId = $this->Trainingvideo->find('first', array('order' => array('created' => 'desc'), 'limit' => 1));
                        $this->Trainingvideo->updateAll(array('Trainingvideo.is_active' => 1), array('Trainingvideo.id ' => $this->Encryption->decode($lastId['Trainingvideo']['id'])));
                    }
                    $this->Session->setFlash(__('Training video has been deleted successfully'), 'flash_good');
                    $this->redirect(array('action' => 'index'));
                }
            }
        }
    }
}