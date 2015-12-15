<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model 
{
    /**
    * Encrypted Id after find
    * @param array $result data
    * @param bool  $primary
    * @return array $result encrypted id
    * @author Laxmi Saini
    */
    public function afterFind($results, $primary = false) 
    {
        $collection = new ComponentCollection ();
        $Encryption = new EncryptionComponent($collection);
        foreach ($results as $key => $val) {
            if (isset($val [$this->alias] ['id'])) {
                $results [$key] [$this->alias] ['id'] = $Encryption->encode($val [$this->alias] ['id']);
            }
        }
        return $results;
    }
}
