<?php
class FileCSV extends ExternalFile {

    function __contruct($file, $mode) {
        parent::__contruct($file, $mode);
    }

    /* Create and format data to csv file
     * @param1 array     list of data
     * @param2 character a delimiter to changed
    */
    public function csvCreate($data, $delimiter) {
        if(in_array($this->mode, $this->writeMode)) {
            $strData = implode($delimiter, $data);
            $data = explode($delimiter, $strData);
            $data = implode(',', $data);
            parent::fileHandler($data);
        }
    }

    // Read and format a csv file
    public function csvRead() {
        if(parent::fileOpen()){
            $i = 0;
            while(!feof($this->handler)){
                $contents[$i++] = fgetcsv($this->handler);
            }
            return $contents;
        }
        return "File was not able to open. Check if it exist or you have permission to open.";
    }
}

?>
