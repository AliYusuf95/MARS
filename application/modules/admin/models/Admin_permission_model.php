<?php

/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 2/4/2017
 * Time: 12:19 AM
 * @property Admin_groups_permission_model groups_permission
 */
class Admin_permission_model extends MY_Model {

    public function getPagesAuth(){
        $this->load->model('Admin_groups_permission_model', 'groups_permission');
        $permissions = $this->get_all();
        $page_auth = array();
        foreach ($permissions as $permission) {
            $page_auth[$permission->url] = array_map(function($g){return $g->name;},
                $this->groups_permission->getPermissionGroups($permission));
            // Always allow webmaster
            $page_auth[$permission->url][] = 'webmaster';
        }
        return $page_auth;
    }

}