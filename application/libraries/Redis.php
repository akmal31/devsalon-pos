<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redis {
	public function config() {
		$client = new Predis\Client([
			'scheme' => 'tcp',
			'host'   => '127.0.0.1',
			'port'   => 6007 // redis default port
		]);
		return $client;
	}
}