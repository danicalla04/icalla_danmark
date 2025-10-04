<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/**
 * Required to execute neccessary functions
 */
require_once SYSTEM_DIR . 'kernel/Registry.php';
require_once SYSTEM_DIR . 'kernel/Routine.php';

/**
 * LavaLust BASE URL of your APPLICATION
 * If `base_url` isn't set in app config, derive it from server variables so
 * helpers like site_url() and redirect() produce valid absolute URLs on local
 * environments (e.g., XAMPP) where the user may not have configured Apache's
 * mod_rewrite or set BASE_URL manually.
 */
$base = config_item('base_url');
if (empty($base)) {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
	// Compute script folder (where index.php lives)
	$script = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'];
	$script_dir = rtrim(dirname($script), '\\/');
	$base = $scheme . '://' . $host . ($script_dir === '/' || $script_dir === '.' ? '' : $script_dir);
+    // ensure trailing slash
	$base = rtrim($base, '/') . '/';
}
define('BASE_URL', $base);

/**
 * Composer (Autoload)
 */
if ($composer_autoload = config_item('composer_autoload'))
{
	if ($composer_autoload === TRUE)
	{
		file_exists(APP_DIR.'vendor/autoload.php')
			? require_once(APP_DIR.'vendor/autoload.php')
			: show_404('404 Not Found', 'Composer config file not found.');
	}
	elseif (file_exists($composer_autoload))
	{
		require_once($composer_autoload);
	}
	else
	{
		show_404('404 Not Found', 'Composer config file not found.');
	}
}

/**
 * Instantiate the Benchmark class
 */
$performance =& load_class('performance', 'kernel');
$performance->start('lavalust');

/**
 * Deployment Environment
 */
switch (strtolower(config_item('ENVIRONMENT')))
{
	case 'development':
		_handlers();
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':		
		ini_set('display_errors', 0);
		error_reporting(0);
		_handlers();
	break;

	default :
		_handlers();
		error_reporting(-1);
		ini_set('display_errors', 1);
}

/**
 * Error Classes to show errors
 *
 * @return void
 */
function _handlers()
{
	set_error_handler('_error_handler');
	set_exception_handler('_exception_handler');
	register_shutdown_function('_shutdown_handler');
}

/**
 * Instantiate the config class
 */
$config =& load_class('config', 'kernel');

/**
 * Instantiate the logger class
 */
$logger =& load_class('logger', 'kernel');

/**
 * Instantiate the security class for xss and csrf support
 */
$security =& load_class('security', 'kernel');

/**
 * Instantiate the Input/Ouput class
 */
$io =& load_class('io', 'kernel');

/**
 * Instantiate the Language class
 */
$lang =& load_class('lang', 'kernel');

/**
 * Load BaseController
 */
require_once SYSTEM_DIR . 'kernel/Controller.php';

/**
 * Instantiate the routing class and set the routing
 */
$router =& load_class('router', 'kernel', array(new Controller));
require_once APP_DIR . 'config/routes.php';

/**
 * Instantiate LavaLust Controller
 *
 * @return object
 */
function &lava_instance()
{
  	return Controller::instance();
}
$performance->stop('lavalust');

// Handle the request
$url = $router->sanitize_url(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']));
$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : '';
$router->initiate($url, $method);
?>