<?php

namespace Sendelius\Router;

class Result {
	/**
	 * Контроллер
	 * @var string
	 */
	public string $controller = '';

	/**
	 * Метод
	 * @var string
	 */
	public string $method = '';

	/**
	 * Плагин
	 * @var string
	 */
	public string $plugin = '';

	/**
	 * Путь
	 * @var string
	 */
	public string $path = '';

	/**
	 * Ссылка на папку со стилями и скриптами
	 * @var string
	 */
	public string $assets = '';

	/**
	 * Поддиректория контроллера
	 * @var string
	 */
	public string $subPath = '';

	/**
	 * Правило роутера
	 * @var Rule|null
	 */
	public ?Rule $rule = null;
}