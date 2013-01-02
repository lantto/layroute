<?php

class Layroute {
	
	public $title;
	
	public $layout;
	
	private $page;
	
	public $content;
	
	public $routes = array();
	
	private $defaultOptions = array(
		'data' => false,
		'autoload_libs' => false,
		'default_layout' => 'layout'
	);
	
	public $options = array();
	
	public function __construct() {
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

		for($i= 0; $i < sizeof($scriptName); $i++) {
			if ($requestURI[$i] == $scriptName[$i]) {
				unset($requestURI[$i]);
			}
		}

		$request = array_values($requestURI);
		
		if (isset($request[0]) && $request[0] != '') {
			foreach ($request as $parameter) {
				$this->page .= $parameter . '/';
			}
			$this->page = rtrim($this->page,'/');
		} else {
			$this->page = 'index';
		}
	}
	
	public function run() {
		global $layroute;
		
		$this->options = array_merge($this->defaultOptions, $this->options);
		
		foreach ($this->routes as $in => $out) {
			if ($this->page == $in) {
				$this->page = $out;
			}
		}
		
		if ($this->options['autoload_libs']) $this->autoloadLibs();
		
		if ($this->options['data'] && file_exists('data/' . $this->page . '.php')) {
			include_once('data/' . $this->page . '.php');
		}
		
		ob_start();
		include_once('pages/' . $this->page . '.php');
		$this->content = ob_get_contents();
		ob_end_clean();
		
		$layout = (isset($this->layout) ? $this->layout : $this->options['default_layout']);
		
		include_once('layout/' . $layout . '.php');
	}
	
	private function autoloadLibs() {
		if ($handle = opendir('lib')) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "Layroute.php" && $entry != "." && $entry != "..") {
					include_once('lib/' . $entry);
				}
			}
			closedir($handle);
		}
	}
}

?>
