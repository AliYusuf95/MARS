<?php

/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 2/4/2017
 * Time: 12:17 AM
 */
class Admin_groups_permission_model extends MY_Model {
    //public $belongs_to = array( 'author' => array( 'model' => 'author_m' ) );
    public $has_many = array( 'permissions' => array( 'model' => 'admin_permission_model',
        'primary_key' => 'id') );

    public function getGroupsPermissions ($userGroups) {
        $permissions = array();
        $this->load->model('Admin_permission_model', 'permission_model');
        foreach ($userGroups as $group){
            $result = $this->get_many_by('admin_group_id', $group->id);
            foreach ($result as $item) {
                $permissions[] = $this->permission_model->get_by('id', $item->admin_permission_id);
            }
        }
        return $permissions;
    }

    public function getPermissionGroups($permission) {
        $this->load->model('Admin_group_model', 'group_model');
        $groups = array();
        $results = $this->get_many_by('admin_permission_id',$permission->id);
        foreach ($results as $group)
            $groups[] = $this->group_model->get_by('id', $group->admin_group_id);
        return $groups;
    }

    public function validateUrlForGroups($userGroups, $url = null) {
        if($url === null)
            $url = uri_string();
        foreach ($userGroups as $group){
            if ($group->id == 1)
                return true;
        }
        foreach ($this->getGroupsPermissions($userGroups) as $permission){
            if (strpos($url, $permission->url) !== false || strpos($url.'/all', $permission->url) !== false)
                return true;
        }
        return false;
    }
}