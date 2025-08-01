<?php

namespace Sendelius\Router;

class Router {
	/**
	 * экземпляр класса результатов
	 * @var Result
	 */
	public Result $result;

	/**
	 * экземпляр класса запросов
	 * @var Request
	 */
	public Request $request;

	/**
	 * массив правил
	 * @var array
	 */
	private array $rules = [];

	/**
	 * Router constructor.
	 */
	function __construct() {
		$this->request = new Request();
		$this->result = new Result();
	}

	/**
	 * назначение правила
	 * @param string|array $path
	 * @return Rule
	 */
	public function rule(string|array $path): Rule {
		$pathItems = [];
		if (is_string($path)) $pathItems[] = $path;
		else $pathItems = $path;
		$rule = new Rule();
		foreach ($pathItems as $pathItem) {
			$this->rules[$pathItem] = $rule;
		}
		return $rule;
	}

	/**
	 * запуск роутера
	 */
	public function start(): void {
		foreach ($this->rules as $url => $rule) {
			if ($rule->data['assets']) $url .= '{file}';
			preg_match_all("/{(?<key>[0-9a-z_:]+)}/miu", $url, $matches);
			$pattern = $url;
			$attr = [];
			if (isset($matches['key']) and is_array($matches['key']) and count($matches['key']) > 0) {
				foreach ($matches['key'] as $val) {
					$valArray = explode(':', $val);
					$cfg = '(.*)';
					$valKey = $val;
					if (count($valArray) > 1) {
						$p = '';
						if (str_contains($valArray[1], 'c')) $p .= 'а-я';
						if (str_contains($valArray[1], 'l')) $p .= 'a-z';
						if (str_contains($valArray[1], 'n')) $p .= '0-9';
						if (str_contains($valArray[1], 's')) $p .= '\-\_\.';
						if (strlen($p)) $cfg = '([' . $p . ']+)';
						$valKey = $valArray[0];
					}
					$attr[$valKey] = null;
					$pattern = preg_replace("/{(?<key>[" . $val . "]+)}/", $cfg, $pattern);
				}
			}
			$pattern = "@^" . $pattern . "$@Diu";
			if (preg_match($pattern, $this->request->url, $matches)) {
				if (is_array($matches) and count($matches) > 1) {
					$i = 1;
					foreach ($attr as $k1 => $v1) {
						if (isset($matches[$i])) {
							switch ($k1) {
								case 'controller':
									$rule->controller($matches[$i]);
									break;
								case 'method':
									$rule->method($matches[$i]);
									break;
								case 'plugin':
									$rule->plugin($matches[$i]);
									break;
								default:
									$this->request->query['route'][$k1] = $matches[$i];
									break;
							}
						}
						$i++;
					}
				}
				foreach ($rule->data['attr'] as $k => $v) {
					if (!array_key_exists($k, $this->request->query['route'])) $this->request->query['route'][$k] = $v;
				}
				$setRule = true;
				if (strlen((string)$rule->data['controller'])) $this->result->controller = $rule->data['controller'];
				if (strlen((string)$rule->data['method'])) $this->result->method = $rule->data['method'];
				if (strlen((string)$rule->data['plugin'])) $this->result->plugin = $rule->data['plugin'];
				if (strlen((string)$rule->data['subPath'])) $this->result->subPath = $rule->data['subPath'];
				if ($rule->data['assets'] and isset($this->request->query['route']['file'])) {
					$assetsFile = $rule->data['assets'] . str_replace(['\\', '/'], DS, $this->request->query['route']['file']);
					if (file_exists($assetsFile) and is_file($assetsFile)) $this->result->assets = $assetsFile;
					else $setRule = false;
				}
				if ($setRule) {
					$this->result->rule = $rule;
					$this->result->path = $url;
				}
				break;
			}
		}
	}
}