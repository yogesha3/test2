<?php

/**
 * This is a content manager controller
 *
 */
class CmsController extends AppController 
{
    public $includePageJs='';
    public $uses = array('Page');

    /**
     * Used to update about Us page content
     * @author Laxmi Saini
     */
    public function admin_about() 
    {
        $this->pageContent('about-us',$this->action);
    }

    /**
     * Used to update privacy policy page content 
     * @author Laxmi Saini
     */
    public function admin_privacyPolicy() 
    {
        $this->pageContent('privacy-policy',$this->action);
    }

    /**
     * Used to update terms and conditions page content
     * @author Laxmi Saini
     */
    public function admin_termsConditions() 
    {
        $this->pageContent('terms-and-conditions',$this->action);
    }

    /**
     * Common function to fetch and update data by title
     * @param string $contentFor page title for which content to be fetched 
     * @param string $action calling method name
     * @author Laxmi Saini
     */
    public function pageContent($contentFor=NULL,$action)
    {        
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        $page = $this->Page->findByPageTitle($contentFor);

        if ($this->request->is('post')) {
            $this->Page->create();
            $this->request->data['Page']['id'] = $this->Encryption->decode($this->request->data['Page']['id']);
            if ($this->Page->save($this->request->data)) {
                $this->Session->setFlash(__('Page information updated successfully'), 'flash_good');
                $this->redirect(array('controller' => 'cms', 'action' => $action));
            } else {
                $this->Session->setFlash(__('Unable to save.'), 'flash_bad');
            }
        } else {
            $this->request->data = $page;
        }
        $this->set('includePageJs', $this->includePageJs);
    }
}