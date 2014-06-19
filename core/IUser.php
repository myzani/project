<?php
interface IUser {

    public function get_id($email);
    public function get_user($id);
    public function get_info_user($email, $pwd);
    public function get_all_users();
    public static function count_users();
}
?>
