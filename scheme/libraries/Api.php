<?php

class Api
{
    /**
     * LavaLust Super Object
     *
     * @var object
     */
    private $_lava;

    /**
     * Allow Origin
     *
     * @var string
     */
    protected $allow_origin;

    public function __construct()
    {
        $this->_lava =& lava_instance();

        $this->_lava->config->load('api');

        if(! config_item('api_helper_enabled')) {
            show_error('Api Helper is disabled or set up incorrectly.');
        }

        $this->allow_origin = config_item('allow_origin');

        //Handle CORS
        $this->handle_cors();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    // --------------------------
    // Basic Utilities
    // --------------------------
    /**
     * handle cors
     *
     * @return void
     */
    public function handle_cors()
    {
        header("Access-Control-Allow-Origin: {$this->allow_origin}");
        header("Access-Control-Allow-Headers: Authorization, Content-Control");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header ("Content-Type: application/json; charset=UTF-8");
    }

    /**
     * API body
     *
     * @return void
     */
    public function body()
    {
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        // JSON input
        if (stripos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents("php://input"), true);
            return is_array($input) ? $input : [];
        }

        // Form data fallback
        if ($_POST) {
            return $_POST;
        }

        // Raw fallback for form-encoded bodies
        parse_str(file_get_contents("php://input"), $formData);
        return $formData;
    }

    /**
     * get_query_params
     *
     * @return void
     */
    public function get_query_params()
    {
        return $_GET;
    }

    /**
     * require_method
     *
     * @param string $method
     * @return void
     */
    public function require_method(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            $this->respond_error("Method Not Allowed", 405);
        }
    }

    /**
     * respond
     *
     * @param mixed $data
     * @param integer $code
     * @return void
     */
    public function respond($data, $code = 200)
    {
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    public function respond_error($message, $code = 400)
    {
        $this->respond(['error' => $message], $code);
    }
}