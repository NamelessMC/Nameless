<?php
class Paginator{
	private $_limit,
			$_page,
			$_total,
			$_class;
	
	public function __construct($class = array()){
		// Constructor
		if(!count($class)) $this->_class = array('ul' => 'pagination', 'li' => 'page-item', 'a' => 'page-link');
		else $this->_class = $class;
	}
	
	public function getLimited($data, $limit = 10, $page = 1, $total = 10){
		$this->_limit   = $limit;
		$this->_page    = (int)$page;
		
		$return = array();
		
		for($i = ($this->_page != 1 ? (($this->_page - 1) * $limit) : 0); $i < ($this->_page * $limit); $i++){			
			if(!isset($data[$i])) break;
			
			$return[] = $data[$i];
		}

		$this->_total = $total;
		
		$result         = new stdClass();
		$result->page   = $this->_page;
		$result->limit  = $this->_limit;
		$result->total  = $this->_total;
		$result->data   = $return;
	 
		return $result;
	}
	
	public function generate($links, $href = '?'){
		$last = ceil($this->_total / $this->_limit);
	 
		$start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;
		$end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;
	 
		$html       = '<ul class="' . $this->_class['ul'] . '">';
	 
		$class = ($this->_page == 1) ? " disabled" : "";
		$html .= '<li class="' . $this->_class['li'] . $class . '"><a class="' . $this->_class['a'] . '" href="';
		if($this->_page == 1) $html .= '#'; else $html .= $href . 'p=' . ($this->_page - 1);
		$html .= '">&laquo;</a></li>';
	 
		if($start > 1){
			$html   .= '<li class="' . $this->_class['li'] . '"><a class="' . $this->_class['a'] . '" href="' . $href . 'p=1">1</a></li>';
			$html   .= '<li class="' . $this->_class['li'] . ' disabled"><span>...</span></li>';
		}
	 
		for($i = $start; $i <= $end; $i++){
			$class  = ($this->_page == $i) ? " active" : "";
			$html   .= '<li class="' . $this->_class['li'] . $class . '"><a class="' . $this->_class['a'] . '" href="' . $href . 'p=' . $i . '">' . $i . '</a></li>';
		}
	 
		if($end < $last ){
			$html   .= '<li class="' . $this->_class['li'] . ' disabled"><span>...</span></li>';
			$html   .= '<li class="' . $this->_class['li'] . '"><a class="' . $this->_class['a'] . '" href="' . $href . 'p=' . $last . '">' . $last . '</a></li>';
		}
	 
		$class      = ($this->_page == $last) ? " disabled" : "";
		
		$html       .= '<li class="' . $this->_class['li'] . $class . '"><a class="' . $this->_class['a'] . '" href="';
		if($this->_page == $last) $html .= '#'; else $html .= $href . 'p=' . ($this->_page + 1);
		$html .= '">&raquo;</a></li>';
	 
		$html       .= '</ul>';
	 
		return $html;
	}
 
}