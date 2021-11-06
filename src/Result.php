<?php
/**
 * @project Sendelius Router
 * @author Sendelius <sendelius@gmail.com>
 */

namespace SendeliusRouter;

/**
 * Class Result
 * @package SendeliusRouter
 */
class Result{
    public $code;
    public $rule = false;
    public $type;
    public $plugin;
    public $controller;
    public $action;
    public $symlink_dir;
    public $redirect_to;
    public $options = array();
}