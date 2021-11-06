<?php
/**
 * @project Sendelius Router
 * @author Sendelius <sendelius@gmail.com>
 */

namespace SendeliusRouter;

/**
 * Class Request
 * @package SendeliusRouter
 */
class Request {
    public $url;
    public $method;
    public $request_uri;
    public $server_ip;
    public $user_ip;
    public $host;
    public $protocol;
    public $all_query = array();

    function __construct(){
        $uri = (array_key_exists('REQUEST_URI',$_SERVER)) ? parse_url($_SERVER['REQUEST_URI']) : '';
        $this->url = (array('path',$uri)) ? urldecode($uri['path']) : null;
        $this->method = (array('REQUEST_METHOD',$_SERVER)) ? strtoupper($_SERVER['REQUEST_METHOD']) : null;
        $this->request_uri = (array('REQUEST_URI',$_SERVER)) ? $_SERVER['REQUEST_URI'] : null;
        $this->server_ip = (array('SERVER_ADDR',$_SERVER)) ? $_SERVER['SERVER_ADDR'] : null;
        $this->user_ip = (array('REMOTE_ADDR',$_SERVER)) ? $_SERVER['REMOTE_ADDR'] : null;
        $this->host = (array('SERVER_NAME',$_SERVER)) ? $_SERVER['SERVER_NAME'] : null;
        $this->protocol = (array('REQUEST_SCHEME',$_SERVER)) ? $_SERVER['REQUEST_SCHEME'] : null;

        switch ($this->method){
            case 'GET':
                foreach ($_GET as $key=>$val) $this->all_query[$key] = $val;
                break;
            case 'POST':
                foreach ($_POST as $key=>$val) $this->all_query[$key] = $val;
                break;
            default:
                foreach ($_REQUEST as $key=>$val) $this->all_query[$key] = $val;
                break;
        }
    }

    public function get($key){
        if(array_key_exists($key,$this->all_query)) return $this->all_query[$key];
        else return null;
    }

    public function exist($key){
        if(array_key_exists($key,$this->all_query)) return true;
        else return false;
    }
}