<?php
class UploadFile
{
    private $file = "";
    private $path = "";
    private $upload_dir  = "uploads/";
    private $maxsize;
    private $valid_types = [];
    private $errors = [1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                       2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                       3 => 'The uploaded file was only partially uploaded.',
                       4 => 'No file was uploaded.',
                       6 => 'Missing a temporary folder.',
                       7 => 'Failed to write file to disk.',
                       8 => 'A PHP extension stopped the file upload.'];
    public  $f_errors = [];

    function __construct($path, $types, $maxsize = 102400) {
        $this->upload_dir = $path;
        $this->valid_types = $types;
        $this->maxsize = $maxsize;
    }

    /* Upload a file
     * @param1 array  list of data to a file
     * @param2 string new file name
     * return a message if the was successful or fail to upload
    */
    public function upload_file($file, $new_file_name = "") {
        $this->file = $file;
        $this->path = $this->upload_dir . $file['name'];
        if($this->chk_file()) {
            $file_name = !empty($new_file_name) ? Helper::sanitize($new_file_name, 'string') : $this->file['name'];
            if(move_uploaded_file($this->file["tmp_name"], $this->upload_dir . $file_name)) {
                return true;
            }
        }
        Helper::log("Error in uploading the file ". $this->errors[$this->file['error']]);
        return false;
    }

    private function chk_file() {
        if($this->file['size'] > $this->maxsize || !$this->chk_type()) {
            $this->f_errors[] = "File is higher than the upload size or file type is not valid!";
            return false;
        }
        return true;
    }

    private function chk_type() {
        if(in_array($this->file['type'], $this->valid_types)) {
            return true;
        }
        return false;
    }
}
?>
