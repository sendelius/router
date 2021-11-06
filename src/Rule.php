<?php
/**
 * @project Sendelius Router
 * @author Sendelius <sendelius@gmail.com>
 */

namespace SendeliusRouter;

/**
 * Class Rule
 * @package SendeliusRouter
 */
class Rule{
    private $data = array(
        'code'=>200, // код ответа
        'controller'=>null, // контроллер
        'action'=>null, // метод
        'plugin'=>null, // плагин
        'symlink_dir'=>null, // назначение симлинка
        'redirect_to'=>null, // назначение редиректа
        'type'=>'html', // тип правила, варианты: html, json, xml, symlink, redirect
        'pattern'=>null, // шаблон обработки
        'options'=>array(), // массив передаваемых опций
    );

    /**
     * @param $name
     * @return $this
     */
    function controller($name){
        $this->data['controller'] = $name;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    function action($name){
        $this->data['action'] = $name;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    function plugin($name){
        $this->data['plugin'] = $name;
        return $this;
    }

    /**
     * @return $this
     */
    function is_html(){
        $this->data['type'] = 'html';
        return $this;
    }

    /**
     * @return $this
     */
    function is_json(){
        $this->data['type'] = 'json';
        return $this;
    }

    /**
     * @return $this
     */
    function is_xml(){
        $this->data['type'] = 'xml';
        return $this;
    }

    /**
     * @param $dir
     * @return $this
     */
    function is_symlink($dir){
        $this->data['type'] = 'symlink';
        $this->data['symlink_dir'] = $dir;
        return $this;
    }

    /**
     * @param $to
     * @param int $code
     * @return $this
     */
    function redirect($to,$code=301){
        $this->data['type'] = 'redirect';
        $this->data['code'] = $code;
        $this->data['redirect_to'] = $to;
        return $this;
    }

    /**
     * @param $key
     * @param $val
     * @return $this
     */
    function set_option($key,$val){
        $this->data['options'][$key] = $val;
        return $this;
    }

    /**
     * @param $url
     */
    function parse($url){
        preg_match_all("/{(?<key>[0-9a-z_:]+)}/msiu", $url, $matches);
        $pattern = $url;
        if(!empty($matches['key'])){
            foreach($matches['key'] as $key=>$val){
                $val_array = explode(':',$val);
                $cfg = '([а-яa-z0-9\-\_\.]+)';
                $val_key = $val;
                if(count($val_array)>1){
                    switch($val_array[1]){
                        case 'all': $cfg = '(.*)'; break;
                        case 'optional': $cfg = '([а-яa-z0-9\-\_\./]+)?'; break;
                        case 'num': $cfg = '([0-9]+)'; break;
                        case 'cyr': $cfg = '([а-я\-\_\.]+)'; break;
                        case 'cyrnum': $cfg = '([а-я0-9\-\_\.]+)'; break;
                        case 'lat': $cfg = '([a-z\-\_\.]+)'; break;
                        case 'latnum': $cfg = '([a-z0-9\-\_\.]+)'; break;
                        case 'cyrlat': $cfg = '([а-яa-z\-\_\.]+)'; break;
                        case 'cyrlatnum': $cfg = '([а-яa-z0-9\-\_\.]+)'; break;
                    }
                    $val_key = $val_array[0];
                }
                if($this->data['type']=='symlink' and !empty($this->data['symlink_dir'])) $cfg = '(.*)';
                $this->data['options'][$val_key] = false;
                $pattern = preg_replace("/{(?<key>[".$val."]+)}/",$cfg, $pattern);
            }
        }
        $this->data['pattern'] = "@^" . $pattern . "$@Diu";
    }

    /**
     * @param $key
     * @return mixed|null
     */
    function get($key){
        return (array_key_exists($key,$this->data)) ? $this->data[$key] : null;
    }
}