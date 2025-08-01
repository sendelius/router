<?php

namespace Sendelius\Router;

class Request {
	/**
	 * url запроса
	 * @var string
	 */
	public string $url;

	/**
	 * метод запроса
	 * @var string
	 */
	public string $method;

	/**
	 * ip адрес сервера
	 * @var string
	 */
	public string $serverIp;

	/**
	 * ip адрес пользователя
	 * @var string
	 */
	public string $userIp;

	/**
	 * хост
	 * @var string
	 */
	public string $host;

	/**
	 * протокол запроса
	 * @var string
	 */
	public string $protocol;

	/**
	 * массив параметров запроса
	 * @var array
	 */
	public array $query = [
		'get' => [],
		'post' => [],
		'json' => [],
		'route' => [],
	];

	/**
	 * конструктор класса
	 */
	function __construct() {
		$uri = (array_key_exists('REQUEST_URI', $_SERVER)) ? parse_url($_SERVER['REQUEST_URI']) : '';
		$this->url = (is_array($uri) and array_key_exists('path', $uri)) ? trim(urldecode($uri['path'])) : '';
		$this->method = (array_key_exists('REQUEST_METHOD', $_SERVER)) ? trim(strtoupper($_SERVER['REQUEST_METHOD'])) : '';
		$this->serverIp = (array_key_exists('SERVER_ADDR', $_SERVER)) ? trim(strval($_SERVER['SERVER_ADDR'])) : '';
		$this->userIp = (array_key_exists('REMOTE_ADDR', $_SERVER)) ? trim(strval($_SERVER['REMOTE_ADDR'])) : '';
		$this->host = (array_key_exists('SERVER_NAME', $_SERVER)) ? trim(strval($_SERVER['SERVER_NAME'])) : '';
		$this->protocol = (array_key_exists('REQUEST_SCHEME', $_SERVER)) ? trim(strval($_SERVER['REQUEST_SCHEME'])) : '';

		// запись данных в массив из запроса пользователя
		if (is_array($_GET) and count($_GET) > 0) foreach ($_GET as $key => $val) $this->query['get'][$key] = trim((string)$val);
		if (is_array($_POST) and count($_POST) > 0) foreach ($_POST as $key => $val) $this->query['post'][$key] = trim((string)$val);

		// запись JSON тела запроса в массив
		$input = file_get_contents('php://input');
		if (strlen($input) > 0 and json_validate($input)) {
			$jsonData = json_decode($input, true);
			if (is_array($jsonData)) foreach ($jsonData as $key => $val) $this->query['json'][$key] = $val;
		}
	}

	/**
	 * получение параметра запроса
	 * @param string $key
	 * @return mixed|null
	 */
	public function get(string $key): mixed {
		if (array_key_exists($key, $this->query['get'])) return $this->query['get'][$key];
		else return null;
	}

	/**
	 * получение данных из тела запроса
	 * @param string $key
	 * @return mixed
	 */
	public function post(string $key): mixed {
		if (array_key_exists($key, $this->query['post'])) return $this->query['post'][$key];
		else return null;
	}

	/**
	 * получение данных из переданного json
	 * @param string $key
	 * @return mixed
	 */
	public function json(string $key): mixed {
		if (array_key_exists($key, $this->query['json'])) return $this->query['json'][$key];
		else return null;
	}

	/**
	 * получение данных из url чпу
	 * @param string $key
	 * @return mixed
	 */
	public function route(string $key): mixed {
		if (array_key_exists($key, $this->query['route'])) return $this->query['route'][$key];
		else return null;
	}
}