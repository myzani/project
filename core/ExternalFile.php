<?php
class ExternalFile
{
	protected $files;
	protected $mode;
	protected $result;
	protected $data;
	protected $handler;
	protected $writeMode = ["w", "wb", "w+", "wb+", "r+", "rb+", "a", "a+", "ab", "ab+", "x", "xb", "x+", "xb+"];

	function __construct($files, $mode = "") {
        $this->files = $files;
        $this->mode = strtolower($mode);
	}

	/* Determine a mode whether it is read or write a file
	 * @param1 string data to write
	*/
	public function fileHandler($data = "") {
		try {
			$this->fileOpen();

			if($data != "") {
				$this->data = $data;
			}
			switch ($this->mode) {
				case ($this->mode == 'r' || $this->mode == 'rb'):
					$this->result = $this->fileRead();
					break;
                case ($this->mode == 'w' || $this->mode == 'wb'):
                    $this->result = $this->fileWrite();
					break;
                case ($this->mode == 'a' || $this->mode == 'ab'):
                    $this->result = $this->fileWrite();
					break;
				default:
                    $this->result = $this->fileReadWrite();
					break;
			}
		} catch (Exception $e) {
            $this->result = "Problem with reading or writing the file. Please try again or contact your System Administrator.";
		}
		return $this->result;
	}

	protected function fileOpen() {
		$this->handler = fopen($this->files, $this->mode);
		return $this->handler;
	}

	protected function fileRead() {
		while(!feof($this->handler)) {
            $output[] = fgets($this->handler);
		}

        return $output;
	}

	private function fileWrite() {
        echo $this->mode;
        return fwrite($this->handler, $this->data);
	}

	// Handles a file in read and write mode
	private function fileReadWrite() {
        fwrite($this->handler, $this->data);
		if($this->mode != 'w+' && $this->mode != 'wb+') {
            $this->__destruct();
            $this->fileOpen();
            while(!feof($this->handler)) {
                $output[] = fgets($this->handler);
            }
            return $output;
		}
	}

	public function fileDelete() {
		unlink($this->handler);
	}

    public function __destruct() {
		fclose($this->handler);
	}
}

?>
