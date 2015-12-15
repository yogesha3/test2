<?php

/**
 * Coupon Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Rohan
 */
App::uses('AppModel', 'Model');
class Coupon extends AppModel 
{
    public $validate = array(
        'coupon_code' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'message' => 'Letters and numbers only'
            ),
            'size' => array(
                'rule' => array('lengthBetween', 1, 9),
                'message' => 'Coupon name should be at least 9 chars long'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Coupon name already exist'
            )
        ),
        /* 'expiry_date' => array(
          'required' => array(
          'rule' => array('lessThanField', 'start_date'),
          'message' => 'End Date cannot be Earlier than Start Date'
          ),
          ), */
        'user_email' => array(
            'rule_1' => array(
                'required' => false,
                'rule' => array('userEmailValidate', 'coupon_types'),
                'message' => 'Email is empty or invalid',
            )
        ),
        'coupon_type' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
        ),
        'uasge_limit' => array(
            'required' => array(
                'rule' => array('usageLimitCheck', 'coupon_types'),
                'message' => 'This field is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Numbers only'
            ),
        ),
        'coupon_types' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
        ),
        'discount_amount' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Numbers only'
            ),
            'range' => array(
                'rule' => array('range', 0, 101),
                'message' => 'Discount% must be in range 1,100'
            ),
        ),
    );

    /**
     * to check coupons usage limit
     * @param array $arr
     * @param string $field
     * @return boolean
     * @author Rohan
     */
    public function usageLimitCheck($arr, $field) 
    {
        if ($this->data[$this->alias][$field] == 'email') {
            return true;
        } else {
            if ($this->data[$this->alias][key($arr)] != '')
                return true;
            else
                return false;
        }
    }

    /**
     * Validate user email
     * @param array $arr
     * @param string $field
     * @return boolean
     * @author Rohan
     */
    public function userEmailValidate($arr, $field) 
    {
        if ($this->data[$this->alias][$field] == 'email') {
            if ($this->data[$this->alias][key($arr)] != '')
                return true;
            else
                return false;
        } else
            return true;
    }

    /**
     * Validate discount amount
     * @param type $check
     * @return boolean
     * @author Rohan
     */
    public function validateDiscount($check) 
    {
        if ($check['discount_amount'] <= 100)
            return true;
        else
            return false;
    }

    /**
     * callback function to save method
     * @param array $options
     * @author Rohan
     */
    public function beforeSave($options = array()) 
    {
        if (isset($this->data[$this->alias]['start_date'])) {
            $t1 = strtotime(str_replace('-', '/', $this->data[$this->alias]['start_date']));
            $this->data[$this->alias]['start_date'] = date('Y-m-d', $t1);
        }
        if (isset($this->data[$this->alias]['expiry_date'])) {
            $t1 = strtotime(str_replace('-', '/', $this->data[$this->alias]['expiry_date']));
            $this->data[$this->alias]['expiry_date'] = date('Y-m-d', $t1);
        }
        if (isset($this->data[$this->alias]['coupon_types']) && $this->data[$this->alias]['coupon_types'] == 'email') {
            $this->data[$this->alias]['coupon_type'] = 'email';
            unset($this->data[$this->alias]['uasge_limit']);
        }
    }
}