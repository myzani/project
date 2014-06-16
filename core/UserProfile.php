<?php
class UserProfile implements IUser {

    private $tableName = 'userprofile';
    private $db;

    function __construct() {
        $this->db = new dbClass();
    }

    public function get_user($id) {

    }

    public function get_info_user($email, $pwd) {

    }

    public function get_all_users() {

    }

    public static function count_users($id = null){
        if($id) {
            $users = $this->get_user($id);
        } else {
            $users = $this->get_all_users();
        }
        return (count($users) > 0) ? count($users) : 0;
    }

}


?>
