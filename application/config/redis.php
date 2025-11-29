<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['redis_socket_type'] = 'tcp'; //`tcp` or `unix`
$config['redis_socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['redis_host'] = '127.0.0.1';
$config['redis_port'] = 6007;
$config['redis_password'] = '';
$config['redis_timeout'] = 0;

/* End of file redis.php */