<?php

/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 2/4/2017
 * Time: 12:27 AM
 */
class Admin_group_model extends MY_Model {
    public $has_many = array( 'permissions_ids' => array( 'model' => 'Admin_groups_permission_model',
        'primary_key' => 'admin_group_id') );
}