<?php
class UserProfile implements IUser {

    private $tbl_usr_prof   = 'userprofile d';
    private $tbl_usr        = 'user h';
    private $db;
    private $stmt;
    private $fld_usr_prof   = array('d.user_id', 'd.cId', 'd.fName', 'd.mName', 'd.lName',
                                'd.age', 'd.gender', 'd.contact', 'd.address', 'd.job');
    private $fld_usr        = array('h.email', 'h.ip', 'h.status');

    function __construct() {
        $this->db = new dbClass();
    }

    public function get_id($email) {}

    public function get_user($id) {
        $this->db->whereData(array("id"=>Helper::sanitize($id,'id')));
        $this->stmt = $this->db->selectMultiTbl(array('column'=>$this->fld_usr_prof, 'table'=>array($this->tbl_usr_prof)));

        return !empty($this->stmt) ? array_shift($this->stmt) : false;
    }

    public function get_info_user($email, $pwd) {

    }

    // Get all active and non-active users
    public function get_all_users() {
        $data = array_merge($this->fld_usr, $this->fld_usr_prof);
        $this->db->_pid = 'h.user_id=d.user_id';
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

    public function count_users($id = null){
        if($id) {
            $users = $this->get_user($id);
        } else {
            $users = $this->get_all_users();
        }
        return (count($users) > 0) ? count($users) : 0;
    }

}

?>
