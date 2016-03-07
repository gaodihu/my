<?php
class Log {
	private $filename;
	
	public function __construct($filename) {
		$this->filename = date('Y-m-d') . '-' .$filename;
	}
	
	public function write($message) {
		$file = DIR_LOGS . $this->filename;
		
		$handle = fopen($file, 'a+'); 
		if(is_array($message)){
			fwrite($handle, date('Y-m-d G:i:s') . ' - ' . var_export($message ,TRUE). "\n");
		}
		else{
			fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
		}
		
			
		fclose($handle); 
	}
    
    public function file_write($file_path,$message){
		$handle = fopen($file_path, 'a+'); 
		if(is_array($message)){
			fwrite($handle, date('Y-m-d G:i:s') . ' - ' . var_export($message ,TRUE). "\n");
		}
		else{
			fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
		}
		
			
		fclose($handle); 
    }
}
?>