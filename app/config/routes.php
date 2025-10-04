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

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

$router->get('/', 'UserController::show');
$router->get('/author', 'UserController::show');

// Authentication Routes
$router->get('/auth/login', 'AuthController::login');
$router->post('/auth/login_process', 'AuthController::login_process');
$router->get('/auth/register', 'AuthController::register');
$router->post('/auth/register_process', 'AuthController::register_process');
$router->get('/auth/forgot_password', 'AuthController::forgot_password');
$router->post('/auth/forgot_password_process', 'AuthController::forgot_password_process');
$router->get('/auth/reset_password/{token}', 'AuthController::reset_password');
$router->post('/auth/reset_password_process', 'AuthController::reset_password_process');
$router->get('/auth/verify_email/{token}', 'AuthController::verify_email');
$router->get('/auth/logout', 'AuthController::logout');
$router->get('/auth/profile', 'AuthController::profile');
$router->post('/auth/update_profile', 'AuthController::update_profile');
$router->post('/auth/change_password', 'AuthController::change_password');

// CRUD Routes (can be protected later)
$router->match('/create', 'UserController::create' , ['GET', 'POST']);
$router->match('/edit/{id}', 'UserController::edit' , ['GET', 'POST']);
$router->get('/delete/{id}', 'UserController::delete');