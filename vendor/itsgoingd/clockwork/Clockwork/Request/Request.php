<?php
namespace Clockwork\Request;

/**
 * Data structure representing a single application request
 */
class Request
{
	/**
	 * Unique request ID
	 */
	public $id;

	/**
	 * Data protocol version
	 */
	public $version = 1;

	/**
	 * Request time
	 */
	public $time;

	/**
	 * Request method
	 */
	public $method;

	/**
	 * Request URI
	 */
	public $uri;

	/**
	 * Request headers
	 */
	public $headers = array();

	/**
	 * Textual representation of executed controller
	 */
	public $controller;

	/**
	 * GET data array
	 */
	public $getData = array();

	/**
	 * POST data array
	 */
	public $postData = array();

	/**
	 * Session data array
	 */
	public $sessionData = array();

	/**
	 * Cookies array
	 */
	public $cookies = array();

	/**
	 * Response time
	 */
	public $responseTime;

	/**
	 * Response status code
	 */
	public $responseStatus;

	/**
	 * Database queries array
	 */
	public $databaseQueries = array();

	/**
	 * Timeline data array
	 */
	public $timelineData = array();

	/**
	 * Log messages array
	 */
	public $log = array();

	/**
	 * Application routes array
	 */
	public $routes = array();

	/**
	 * Emails data array
	 */
	public $emailsData = array();

	/**
	 * Views data array
	 */
	public $viewsData = array();

	/**
	 * Custom user data (not used by Clockwork app)
	 */
	public $userData;

	/**
	 * Create a new request, if optional data array argument is provided, it will be used to populate the request object,
	 * otherwise empty request with autogenerated ID will be created
	 */
	public function __construct(array $data = null)
	{
		if ($data) {
			foreach ($data as $key => $val)
				$this->$key = $val;
		} else {
			$this->id = $this->generateRequestId();
		}
	}

	/**
	 * Compute and return sum of duration of all database queries
	 */
	public function getDatabaseDuration()
	{
		$duration = 0;

		foreach ($this->databaseQueries as $query)
			if (isset($query['duration']))
				$duration += $query['duration'];

		return $duration;
	}

	/**
	 * Compute and return response duration in milliseconds
	 */
	public function getResponseDuration()
	{
		return ($this->responseTime - $this->time) * 1000;
	}

	/**
	 * Return request data as an array
	 */
	public function toArray()
	{
		return array(
			'id'               => $this->id,
			'time'             => $this->time,
			'method'           => $this->method,
			'uri'              => $this->uri,
			'headers'          => $this->headers,
			'controller'       => $this->controller,
			'getData'          => $this->getData,
			'postData'         => $this->postData,
			'sessionData'      => $this->sessionData,
			'cookies'          => $this->cookies,
			'responseTime'     => $this->responseTime,
			'responseStatus'   => $this->responseStatus,
			'responseDuration' => $this->getResponseDuration(),
			'databaseQueries'  => $this->databaseQueries,
			'databaseDuration' => $this->getDatabaseDuration(),
			'timelineData'     => $this->timelineData,
			'log'              => array_values($this->log),
			'routes'           => $this->routes,
			'emailsData'       => $this->emailsData,
			'viewsData'        => $this->viewsData,
			'userData'         => $this->userData
		);
	}

	/**
	 * Return request data as a JSON string
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}

	/**
	 * Generate unique request ID in form <current time>.<random number>
	 */
	protected function generateRequestId()
	{
		return sprintf('%.4F', microtime(true)) . '.' . mt_rand();
	}
}
