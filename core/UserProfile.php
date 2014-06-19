<?php
class UserProfile implements IUser {

    private $tbl_usr_prof   = 'userprofile d';
    private $tbl_usr        = 'user h';
    private $db;
    private $stmt;
    private $user_id;
    private $fld_usr_prof   = array('d.user_id', 'd.cId', 'd.fName', 'd.mName', 'd.lName',
                                'd.age', 'd.gender', 'd.contact', 'd.address', 'd.job');
    private $fld_usr        = array('h.email', 'h.ip', 'h.status');
    private static $pwdType = '512';

    function __construct() {
        $this->db = new dbClass();
    }

    /* Toggle to create or update a user
     * @param int user id
    */
    public function user_save($id = null) {
        if(empty($id)) {
            $this->user_create();
        } else {
            $this->user_update();
        }
    }

    public function get_id($email) {
        $this->db->whereData(array("h.email"=>Helper::sanitize($email,'email'), "status"=>1), 'AND');
        $this->user_id = $this->db->selectMultiTbl(array('column'=>array('d.id'), 'table'=>array($this->tbl_usr_prof, $this->tbl_usr)));

        if(!empty($this->user_id)) {
            $this->user_id = array_shift($this->user_id);
        }
        return $this->user_id;
    }

    public function get_user($id) {
        $this->db->whereData(array("id"=>Helper::sanitize($id,'id'), "status"=>1), 'AND');
        $this->stmt = $this->db->selectMultiTbl(array('column'=>$this->fld_usr_prof, 'table'=>array($this->tbl_usr_prof)));

        return !empty($this->stmt) ? array_shift($this->stmt) : false;
    }

    public function get_info_user($email, $pwd) {
        $data = array_merge($this->fld_usr, $this->fld_usr_prof);
        $this->db->whereData(array(
                "h.email"  => Helper::sanitize($email,'email'),
                "h.pwd"    => Helper::crypt_hash($pwd, self::$pwdType),
                "h.status" => 1
        ), 'AND');

        $result = $this->db->selectMultiTbl(array('column'=>$data, 'table'=>array($this->tbl_usr, $this->tbl_usr_prof)));

        return !empty($result) ? array_shift($result) : false;
    }

    /* Get all active or non-active users; or both
     * @param bool determine if the user is active or not,
     *             default is null
     * return array list of users
    */
    public function get_all_users($active = null) {
        $data = array_merge($this->fld_usr, $this->fld_usr_prof);
        if($active) {
            $this->db->whereData(array("h.status"=>$active));
            $this->db->_pid = ' AND ';
        }
        $this->db->_pid .= 'h.user_id=d.user_id';
        $this->stmt = $this->db->selectMultiTbl(array(
                      'column'=>$data,
                      'table'=>array($this->tbl_usr_prof, $this->tbl_usr)
        ));

        return !empty($this->stmt) ? $this->stmt : false;
    }

    /* Get name for the current user
     * @param1 int user identification
     * return complete name of a user otherwise false
    */
    public function get_fullname($id) {
        $this->db->whereData(array("id"=>Helper::sanitize($id,'id')));
        $this->stmt = $this->db->selectMultiTbl(array(
                      'column'=>array('fName', 'mName', 'lName'),
                      'table'=>array($this->tbl_usr_prof)
        ));

        if(!empty($this->stmt)) {
            $fullName = array_shift($this->stmt);

            return $fullName->lName.', '. $fullName->fName;
        }
        return false;
    }

    public static function count_users($id = null){
        if($id) {
            $users = $this->get_user($id);
        } else {
            $users = $this->get_all_users();
        }
        return (count($users) > 0) ? count($users) : 0;
    }

    private function user_create() {
        try {
            $this->db->beginTransaction();
            $result = $this->db->insertData($this->tbl_usr_prof, $this->fld_usr_prof);
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
        }
        return $result;
    }

    private function user_update() {
        $this->db->whereData(array('user_id'=>$this->user_id));
        return $this->db->updateData($this->tbl_usr_prof, $this->fld_usr_prof);
    }

}

?>
