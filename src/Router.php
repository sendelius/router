<?php
/**
 * @project Sendelius Router
 * @author Sendelius <sendelius@gmail.com>
 */

namespace SendeliusRouter;

/**
 * Class Router
 * @package SendeliusRouter
 */
class Router{
    public $web_root = '/';

    /**
     * @var Result
     */
    public $result;

    /**
     * @var Request
     */
    public $request;

	private $rules = array();

    function __construct(){
        $this->request = new Request();
        $this->result = new Result();
    }

    /**
     * @param $rule
     * @return Rule
     */
    public function set_rule($rule){
        $rule_class = new Rule();
        $this->rules[$this->web_root.$rule] = $rule_class;
        return $rule_class;
    }

    public function start(){
        foreach ($this->rules as $url=>$rule) $rule->parse($url);
		$this->find_rule();
	}

	private function find_rule(){
		foreach($this->rules as $key=>$rule){
		    $url = $this->request->url;
            if(substr($key, -1)=='/' and substr($url, -1)!='/') $url .= '/';
            if(preg_match($rule->get('pattern'), $url, $matches)) {
				if(count($matches)>1) {
					$i = 1;
					foreach ($rule->get('options') as $k1=>$v1) {
						if (isset($matches[$i])) {
							switch($k1) {
								case 'controller': $rule->controller($matches[$i]); break;
								case 'action': $rule->action($matches[$i]); break;
								case 'plugin': $rule->plugin($matches[$i]); break;
								default:
								    $this->result->options[$k1] = $matches[$i];
                                    $this->request->all_query[$k1] = $matches[$i];
								    break;
							}
						}
						$i++;
					}
				}
                foreach ($rule->get('options') as $k2=>$v2) {
                    if(!array_key_exists($k2,$this->result->options)) $this->result->options[$k2] = $v2;
                }
				if(!empty($rule->get('plugin'))) $this->result->plugin = $rule->get('plugin');
				if(!empty($rule->get('controller'))) $this->result->controller = $rule->get('controller');
				if(!empty($rule->get('action'))) $this->result->action = $rule->get('action');
                if($rule->get('type')=='redirect' and !empty($rule->get('redirect_to'))) $this->result->redirect_to = $rule->get('redirect_to');
                if($rule->get('type')=='symlink' and !empty($rule->get('symlink_dir'))) $this->result->symlink_dir = $rule->get('symlink_dir');
				$this->result->rule = $key;
				$this->result->type = $rule->get('type');
                $this->result->code = $rule->get('code');
				break;
			}
		}
		if(!$this->result->rule) {
		    $this->result->code = 404;
		    $this->result->type = 'html';
        }
	}
}