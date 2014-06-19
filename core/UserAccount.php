<?php
class UserAccount extends dbClass implements IUser
{
    private $tableName = 'user';
    private $user_id = null;
    private $email;
    private $pwd;
    private $ip;
    private $status;
    private $remember;
    private $created;
    private $modTime;
    private $stmt;
    private $config;
    private static $pwdType = '512';
    private static $forgotType = '256';
    public  $data = array();
    public  $iniObj;

    function __construct() {
        parent::__construct();
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
        parent::whereData(array("email"=>Helper::sanitize($email,'email'), "status"=>1), 'AND');
        $this->user_id = parent::selectMultiTbl(array('column'=>array('user_id'), 'table'=>array($this->tableName)));

        if(!empty($this->user_id)) {
            $this->user_id = array_shift($this->user_id);
        }
        return $this->user_id;
    }

    public function get_user($id) {
        parent::whereData(array("id"=>Helper::sanitize($id,'email'), "status"=>1), 'AND');
        $this->email = parent::selectMultiTbl(array('column'=>array('email'), 'table'=>array($this->tableName)));

        if(!empty($this->email)) {
            $this->email = array_shift($this->email);
        }
        return $this->email;
    }

    /* Get current user info
     * @param1 string email address of the user
     * @param2 string user password
     * return  array list of user information
    */
    public function get_info_user($email, $pwd) {
        parent::whereData(array(
                          "email"=>Helper::sanitize($email,'email'),
                          "pwd"=>Helper::crypt_hash($pwd, self::$pwdType)
                         ), 'AND');
        $result = parent::selectMultiTbl(array('column'=>array('user_id','email', 'ip', 'status'), 'table'=>array('user')));

        return $result;
    }

    /* Get user login info
     * @param1 string email account
     * @param2 string account password
     * return  boolean
    */
    public function get_login($email, $pwd) {
        if(!empty($this->getUserInfo($email, $pwd))) {
            return true;
        }
        return false;
    }

    public function get_all_users($active = null) {
        if($active)
            parent::whereData(array('status'=>$active));
        return parent::selectMultiTbl(array('column'=>array('*'), 'table'=>array('user')));
    }

    public static function count_users($id = null){
        if($id) {
            $users = $this->get_user($id);
        } else {
            $users = $this->get_all_users();
        }
        return (count($users) > 0) ? count($users) : 0;
    }

    public static function user_exist($email) {
        if(self::count_users($this->user_id) > 0) {
            return true;
        }
        return false;
    }

    public function get_forgot_pwd($email, $code) {
        $this->get_id(Helper::sanitize($email,'email'));

        if(!empty($code) && $this->chk_forgot_pwd(Helper::sanitize($code, 'string'))) {
            $this->data = array('forgotpass'=>'', 'pwd_temp'=>'');
            if ($this->save($this->user_id)) {
                return "Your password has been successfully changed!";
            }
        }
        return "Error in changing your password.";
    }

    /* Send an email to user to reset his password
     * @param string email account
     * return a string to inform a user if success or failure.
    */
    public function set_forgot_pwd($email) {
        $email      = Helper::sanitize($email, 'email');
        $pwd_tmp    = Helper::gen_rand_str();
        $code       = Helper::crypt_hash('p@sSw0rdRes3t', self::$forgotType);
        $this->data = array('forgotpass'=>$pwd_tmp , 'pwd_temp'=>$code);

        $this->get_id($email);

        if($this->user_save($this->user_id)) {
            $mail = Helper::mail_auth();
            $mail->From = 'myzani_creed@hotmail.com';
            $mail->FromName = 'Mailer';
            $mail->addAddress($email, 'Alexis');

            $mail->Subject = 'New Password ('. $SITE_URL .')';
            $mail->Body    = "Hello $email, <br /><br />";
            $mail->Body   .= "It looks like you requested a new password. You'll need to use the following password and link to activate it.
                              If you didn't request a new password, please ignore this email. <br /><br />";
            $mail->Body   .= "New Password: ". $pwd_tmp. "<br /><br />";
            $mail->Body   .= "<a href=".SITE_URL.DS."pwdchange?email=".$email."&code=".$code;

            if(!$mail->send()) {
                return 'Message could not be sent.';
            }
            // log this action
            $msg  = "Request to reset password (". $email .")";
            Helper::log($msg, 'client.txt');

            return 'We have sent you a password reset. Please check your email.';
        }
    }

    private function user_create() {
        try {
            $this->stmt->beginTransaction();
            $result = $this->stmt->insertData($this->tableName, $this->data);
            $this->stmt->commit();
        } catch (PDOException $e) {
            $this->stmt->rollback();
        }
        return $result;
    }

    private function user_update() {
        parent::whereData(array('user_id'=>$this->user_id));
        return $this->stmt->updateData($this->tableName, $this->data);
    }

    private function chk_forgot_pwd($code) {
        $result = parent::directQuery("SELECT forgotpass FROM user WHERE forgotpass='". $code ."'");

        if(count($result)) {
            return true;
        }
        return false;
    }

    /* Instantiate all of the properties
     * @param obj individual record for a user
     * return object of data
    */
    private static function instantiate($records) {
        $obj = new self;
        foreach($records as $attribute => $record) {
            if($obj->has_attribute($attribute)) {
                $obj->$attribute = $record;
            }
        }
        return $obj;
    }

    private function has_attribute($attribute) {
        $obj_vars = get_object_vars($this);
        return array_key_exists($attribute, $obj_vars);
    }

}
