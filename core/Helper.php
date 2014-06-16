<?php
class Helper
{
    public static function sanitize($data, $type="") {
        switch($type) {
            case 'email':
                $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                break;
            case 'phone':
                $data = preg_match('/\(?[0-9]{3}\s?\)?\s?-?\s?[0-9]{3}\s?-?\s?[0-9]{4}\s?-?+/', $data);
                break;
            case 'url':
                $data = filter_var($data, FILTER_SANITIZE_URL);
                break;
            case 'string':
                $data = filter_var($data, FILTER_SANITIZE_STRING);
                break;
            default:
                $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
                break;
        }
        return $data;
    }

    public static function crypt_hash($hashStr, $type) {
        $hashStr = ($type=='256') ? crypt($hashStr, '$5$'.SALT_PWD)
                                  : crypt($hashStr, '$6$'.SALT_PWD);
        return $hashStr;
    }

    public static function gen_rand_str($pwdLen = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $output = '';
        for ($i = 0; $i < $pwdLen; $i++) {
            $output .= $characters[mt_rand(0, strlen($characters))];
        }
        return $output;
    }

    public static function mail_auth() {
        $mail = new PHPMailer();

        $mail->Host = HOST_EMAIL;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;

        return $mail;
    }

}
