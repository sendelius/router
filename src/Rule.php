<?php

namespace Sendelius\Router;

class Rule {
	/**
	 * Массив с данными для правила
	 * @var array
	 */
	public array $data = [
		'controller' => '', // контроллер
		'method' => '', // метод
		'plugin' => '', // плагин
		'assets' => '', // линк на папку со стилями и скриптами
		'subPath' => '', // поддиректория контроллера
		'attr' => [], // массив передаваемых атрибутов
	];

	/**
	 * Назначение контроллера
	 * @param string $name
	 * @return $this
	 */
	function controller(string $name): Rule {
		$this->data['controller'] = ucfirst($name);
		return $this;
	}

	/**
	 * Назначение поддиректории контроллера
	 * @param string $path
	 * @return $this
	 */
	function subPath(string $path): Rule {
		$this->data['subPath'] = $path;
		return $this;
	}

	/**
	 * Назначение метода
	 * @param string $name
	 * @return $this
	 */
	function method(string $name): Rule {
		$this->data['method'] = $name;
		return $this;
	}

	/**
	 * Назначение линка на папку со стилями и скриптами
	 * @param string $dir
	 * @return $this
	 */
	function assets(string $dir): Rule {
		if (file_exists($dir)) $this->data['assets'] = $dir;
		return $this;
	}

	/**
	 * Назначение плагина
	 * @param string $name
	 * @return $this
	 */
	function plugin(string $name): Rule {
		$this->data['plugin'] = ucfirst($name);
		return $this;
	}

	/**
	 * Назначение атрибутов
	 * @param string $key
	 * @param mixed $val
	 * @return $this
	 */
	function attr(string $key, mixed $val): Rule {
		$this->data['attr'][$key] = $val;
		return $this;
	}
}