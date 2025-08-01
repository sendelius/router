<?php

namespace Sendelius\Router;

class Result {
	/**
	 * контроллер
	 * @var string
	 */
	public string $controller = '';

	/**
	 * метод
	 * @var string
	 */
	public string $method = '';

	/**
	 * плагин
	 * @var string
	 */
	public string $plugin = '';

	/**
	 * путь
	 * @var string
	 */
	public string $path = '';

	/**
	 * линк на папку со стилями и скриптами
	 * @var string
	 */
	public string $assets = '';

	/**
	 * поддиректория контроллера
	 * @var string
	 */
	public string $subPath = '';

	/**
	 * правило роутера
	 * @var Rule|null
	 */
	public ?Rule $rule = null;
}