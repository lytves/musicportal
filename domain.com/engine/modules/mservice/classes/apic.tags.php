<?php
class TagValue {
	public $id;
	public $size;
	public $flag;
	public $data;

	function __construct(){
		$this->id = '';
		$this->size = 0;
		$this->flag = 0;
		$this->data = '';
	}
}
?>