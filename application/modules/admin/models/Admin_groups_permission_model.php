<?php

/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 2/4/2017
 * Time: 12:17 AM
 * @property Admin_permission_model permission_model
 * @property Admin_group_model group_model
 */
class Admin_groups_permission_model extends MY_Model {
    //public $belongs_to = array( 'author' => array( 'model' => 'author_m' ) );
    public $has_many = array( 'permissions' => array( 'model' => 'admin_permission_model',
        'primary_key' => 'id') );

    private $permissions = null;
    private $permissions_groups = null;

    public function getGroupsPermissions ($userGroups) {
        // get loaded permissions
        if ($this->permissions !== null && $this->permissions_groups == $userGroups)
            return $this->permissions;

        // load permissions from database
        $this->permissions = array();
        $this->permissions_groups = $userGroups;
        $this->load->model('Admin_permission_model', 'permission_model');
        foreach ($userGroups as $group){
            $result = $this->get_many_by('admin_group_id', $group->id);
            foreach ($result as $item) {
                $this->permissions[] = $this->permission_model->get_by('id', $item->admin_permission_id);
            }
        }
        return $this->permissions;
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

    public function getGroupsWithPermission($url)
    {
        if ($url == null || !is_string($url))
            return false;

        return array_map( function ($a) {return $a->id;},
            $this->db->select('admin_group_id as id')
            ->from('admin_groups_permissions')
            ->join('admin_permissions','admin_groups_permissions.admin_permission_id = admin_permissions.id')
            ->where('url',$url)
            ->get()->result());
    }
}