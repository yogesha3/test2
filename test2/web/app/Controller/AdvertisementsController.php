<?php

/**
 * This is an advertisement controller
 */
class AdvertisementsController extends AppController 
{

    public $paginate = array(
        'order' => array('Advertisement.created' => 'desc')
    );
    public $components = array('Profession');

    /**
     * List advertisements
     * @author Laxmi Saini
     */
    public function admin_index() 
    {
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage',10,true);        
        $page = $this->Functions->get_param('page',1,false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);
        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $this->paginate['Advertisement']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Advertisement']['order'] = array('modified' => 'desc');
        }
        $this->paginate['Advertisement']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['Advertisement']['conditions'] = array(
                'Advertisement.title LIKE' => '%' . $search . '%'
            );
        }
        $this->set('advertisements', $this->paginate());
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_advertisement_ajax_list'); // View, Layout
        }
    }

    /**
     * add an advertisement
     * @author Laxmi Saini
     */
    public function admin_add()
    {
        $this->layout= 'admin';
        $this->includePageJs = array('admin_validation');        
        $professionList = $this->Profession->getAllProfessions();        
        if ($this->request->is('post')) {
        	$filepath = "";        	
        	$adPosition = $this->request->data['Advertisement']['position'];
        	$standardWidth = ($adPosition==1) ? "300" : "728";
        	$standardHeight = ($adPosition==1) ? "300" : "90";        	
        	$adInfo = getimagesize($this->request->data['Advertisement']['ad_image']["tmp_name"]);
        	$adWidth = $adInfo[0];
        	$adHeight = $adInfo[1];        	
        	if($adWidth != $standardWidth || $adHeight != $standardHeight){
        		$this->Session->setFlash(__('Image width and height must be '.$standardWidth.'x'.$standardHeight.'px'), 'flash_bad');
        	}else{
	            if (!empty($this->request->data['Advertisement']['ad_image']['name'])) {
	                $file = $this->request->data['Advertisement']['ad_image'];
	                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
	                $arr_ext = array('jpg', 'jpeg', 'gif', 'png');
	                $imageName = strtotime(date('h:i:s')).$file['name'];
	                $filepath = WWW_ROOT . 'img/uploads/ads/' . $imageName;
	                if (in_array($ext, $arr_ext)) {
	                    move_uploaded_file($file['tmp_name'], $filepath);
	                    $this->request->data['Advertisement']['ad_image'] = $imageName ;
	                }
	            }
	            $this->request->data['Advertisement']['profession_id'] = $this->Encryption->decode($this->request->data['Advertisement']['profession_id']);
	            if ($this->Advertisement->save($this->request->data)) {
	                $this->Session->setFlash(__('Advertisement has been added successfully'), 'flash_good');
	                $this->redirect(array('controller' => 'advertisements', 'action' => 'index', 'admin' => true));
	            } else {
	            	unlink($filepath);
	            }
        	}
        }
        $this->set('professionList', $professionList);
        $this->set('includePageJs', $this->includePageJs);
    }
    
    /**
     * Edit an advertisement
     * @param type $adId
     * @throws NotFoundException
     * @author Laxmi Saini
     */
    public function admin_edit($adId = NULL)
    {        
        $this->layout= 'admin';
        $this->includePageJs = array('admin_validation');
        
        if(!$adId) {
            $this->Session->setFlash(__('Invalid advertisement'), 'flash_bad');
            $this->redirect(array('controller' => 'advertisements', 'action' => 'index', 'admin' => true));
        }
        $id = $this->Encryption->decode($adId);        
        if(!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid advertisement'), 'flash_bad');
            $this->redirect(array('controller' => 'advertisements', 'action' => 'index', 'admin' => true));
        }
        $advertisement = $this->Advertisement->findById($id);
        if(!$advertisement) {
            throw new NotFoundException(__('Invalid Advertisement'));
        }
        $this->set('advertisement',$advertisement);
        if ($this->request->is(array('post', 'put'))) {
        	$filepath = "";
        	$adPosition = $this->request->data['Advertisement']['position'];
        	$standardWidth = ($adPosition==1) ? "300" : "728";
        	$standardHeight = ($adPosition==1) ? "300" : "90";
        	$adInfo = getimagesize($this->request->data['Advertisement']['upload']["tmp_name"]);
        	$adWidth = $adInfo[0];
        	$adHeight = $adInfo[1];
        	if($adWidth > $standardWidth || $adHeight > $standardHeight){
        		$this->Session->setFlash(__('Image width and height must be '.$standardWidth.'x'.$standardHeight.'px'), 'flash_bad');
        	}else{
	            if (!empty($this->request->data['Advertisement']['upload']['name'])) {
	                $file = $this->request->data['Advertisement']['upload'];
	                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
	                $arr_ext = array('jpg', 'jpeg', 'gif', 'png');
	                $imageName= strtotime(date('h:i:s')).$file['name'];
	            	if (in_array($ext, $arr_ext)) {
	                	$filepath = WWW_ROOT . 'img/uploads/ads/' . $imageName;
	                    move_uploaded_file($file['tmp_name'], $filepath);
	                    $this->request->data['Advertisement']['ad_image'] = $imageName;
	                    if(file_exists(WWW_ROOT . 'img/uploads/ads/'.$advertisement['Advertisement']['ad_image'])) {
                			unlink(WWW_ROOT . 'img/uploads/ads/'.$advertisement['Advertisement']['ad_image']);
                		}                 
	                }
	            }
	            $this->request->data['Advertisement']['id'] = $this->Encryption->decode($this->request->data['Advertisement']['id']);
	            $this->request->data['Advertisement']['profession_id'] = $this->Encryption->decode($this->request->data['Advertisement']['profession_id']);
	            if ($this->Advertisement->save($this->request->data)) {
	            	$this->Session->setFlash(__('Advertisement has been updated successfully'), 'flash_good');
	                $this->redirect(array('controller' => 'advertisements', 'action' => 'index', 'admin' => true));
	            } else {
	            	@unlink($filepath);
	            }
        	}            
        }
        $professionList = $this->Profession->getAllProfessions();
        $this->set('professionList', $professionList);
        $this->set('id',$adId);
        $this->set('includePageJs', $this->includePageJs);        
        if (!$this->request->data) {
            $this->request->data = $advertisement;
        }        
    }
    
    /**
    * Delete Advertisement
    * @param string $adId advertisement Id
    * @author Laxmi Saini
    */
    public function admin_delete($adId = null)
    {
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $this->set('id',$this->request->data['id']);           
            $this->set('info','Advertisement');
            $this->set('action','delete');
            $response = $this->parsePopupVars('delete','Advertisement');
            $this->set('popupData',$response);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } elseif ($this->request->is('post')) {
            $ad = $this->Advertisement->findById($this->Encryption->decode($adId));
           
            if (!$ad) {
                $this->Session->setFlash(__('Invalid Advertisement'),'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else{
                if($this->Advertisement->delete($this->Encryption->decode($adId))){
                    if(!empty($ad['Advertisement']['ad_image'])){
                      
                        if(file_exists(WWW_ROOT . 'img/uploads/ads/'.$ad['Advertisement']['ad_image'])) {
                           unlink(WWW_ROOT . 'img/uploads/ads/'.$ad['Advertisement']['ad_image']);
                        }
                    }
                    $this->Session->setFlash(__('Advertisement has been deleted successfully'),'flash_good');
                    $this->redirect(array('action' => 'index')); 
                }else{
                    $this->Session->setFlash(__('Unable to delete'), 'flash_bad');
                    $this->redirect(array('action' => 'index')); 

                }
                              
            }
        }       
    }
}