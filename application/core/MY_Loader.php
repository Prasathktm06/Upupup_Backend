<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	public function template($template_name, $vars = array(), $return = FALSE)
	{
		$content  = $this->view('templates/header', $vars, $return); // header
		//$content  = $this->view('templates/admin/sidebar', $vars, $return); // sidebar
		$content .= $this->view($template_name, $vars, $return); // view
		$content .= $this->view('templates/footer', $vars, $return); // footer
		if ($return)
		{
			return $content;
		}
	}
}