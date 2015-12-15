<?php

/* 
 * AdminsController 
 */
class AdminsController extends AppController 
{    
    /**
     * landing page for admin after login
     * @author Laxmi Saini
     */
    public function dashboard()
    {
        $this->layout='admin';
    }
}