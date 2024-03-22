<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$autoload['packages'] = array();


$autoload['libraries'] = array('session','database','form_validation','common');


$autoload['drivers'] = array();


$autoload['helper'] = array('html', 'url', 'file', 'form');
//$autoload['helper'] = array('form','url','file');


$autoload['config'] = array('acl');


$autoload['language'] = array();


$autoload['model'] = array('acl/acl_model');
