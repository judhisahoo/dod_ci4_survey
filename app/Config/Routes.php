<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Adminpanel\MajorgrupController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/survey', 'Home::show');
$routes->get('survey-form', 'SurveyController::showSurveyForm');
$routes->post('/getSubMajorGroup', 'SurveyController::getSubMajorGroup');
$routes->post('/getTask', 'SurveyController::getTasks');
$routes->post('/getSubTask', 'SurveyController::getSubTask');

$routes->post('/getAllSubTask', 'SurveyController::getAllSubTask');
$routes->post('/getSubTaskDetails', 'SurveyController::subTaskDetails');

$routes->post('/submit-survey','SurveyController::submitSurvey');

//$routes->resource('adminpanel/MajorgrupController');

$routes->get('/admin-login', 'AuthController::login');
$routes->get('/admin-register', 'AuthController::register');
$routes->post('/loginProcess', 'AuthController::loginProcess');
$routes->post('/registerProcess', 'AuthController::registerProcess');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/getCsrfCode','SurveyController::getCSRFToken');

$routes->group("",['filter'=> 'auth'],function($routes){
    $routes->get('/adminpanel',function(){
        $data['title']='Dashboard';
        return view('adminpanel/admindashbord',$data);
    });

    $routes->get('/adminpanel/logout', 'AuthController::logout');

    $routes->get('/adminpanel/get-all-survey','SurveyController::listAllEmployerSuervey');

    $routes->get('/adminpanel/export-excel', 'SurveyController::exportToExcel');

    $routes->get('/adminpanel/majorgrup', 'MajorgrupController::index');
    $routes->get('/adminpanel/majorgrup/create', 'MajorgrupController::create');
    $routes->post('adminpanel/majorgrup/store', 'MajorgrupController::store');
    $routes->get('/adminpanel/majorgrup/edit/(:num)', 'MajorgrupController::edit/$1');
    $routes->post('/adminpanel/majorgrup/update/(:num)', 'MajorgrupController::update/$1');
    $routes->post('/adminpanel/majorgrup/delete/(:num)', 'MajorgrupController::delete/$1');

    $routes->get('/adminpanel/submajorgroup', 'SubMajorGroupController::index');
    $routes->get('/adminpanel/submajorgroup/create', 'SubMajorGroupController::create');
    $routes->post('/adminpanel/submajorgroup/store', 'SubMajorGroupController::store');
    $routes->get('/adminpanel/submajorgroup/edit/(:num)', 'SubMajorGroupController::edit/$1');
    $routes->post('/adminpanel/submajorgroup/update/(:num)', 'SubMajorGroupController::update/$1');
    $routes->post('/adminpanel/submajorgroup/delete/(:num)', 'SubMajorGroupController::delete/$1');


    $routes->get('/adminpanel/task', 'TaskController::index');
    $routes->get('/adminpanel/task/create', 'TaskController::create');
    $routes->post('/adminpanel/task/store', 'TaskController::store');
    $routes->get('/adminpanel/task/edit/(:num)', 'TaskController::edit/$1');
    $routes->post('/adminpanel/task/update/(:num)', 'TaskController::update/$1');
    $routes->post('/adminpanel/task/delete/(:num)', 'TaskController::delete/$1');

    // Subtask Routes
    $routes->get('/adminpanel/subtask', 'SubtaskController::index'); // View all subtasks
    $routes->get('/adminpanel/subtask/create', 'SubtaskController::create'); // View the form to create a new subtask
    $routes->post('/adminpanel/subtask/store', 'SubtaskController::store'); // Handle the form submission to store a new subtask
    $routes->get('/adminpanel/subtask/edit/(:num)', 'SubtaskController::edit/$1'); // View the form to edit an existing subtask
    $routes->post('/adminpanel/subtask/update/(:num)', 'SubtaskController::update/$1'); // Handle the form submission to update an existing subtask
    $routes->post('/adminpanel/subtask/delete/(:num)', 'SubtaskController::delete/$1'); // Handle the request to delete an existing subtask


    // Subtask Ratting Routes
    $routes->get('/adminpanel/subtaskratting', 'SubtaskRattingController::index'); // View all subtask rattings
    $routes->get('/adminpanel/subtaskratting/create', 'SubtaskRattingController::create'); // View the form to create a new subtask ratting
    $routes->post('/adminpanel/subtaskratting/store', 'SubtaskRattingController::store'); // Handle form submission to store a new subtask ratting
    $routes->get('/adminpanel/subtaskratting/edit/(:num)', 'SubtaskRattingController::edit/$1'); // View the form to edit an existing subtask ratting
    $routes->post('/adminpanel/subtaskratting/update/(:num)', 'SubtaskRattingController::update/$1'); // Handle form submission to update a subtask ratting
    $routes->post('/adminpanel/subtaskratting/delete/(:num)', 'SubtaskRattingController::delete/$1'); // Handle deletion of a subtask ratting

    // survey users list
    $routes->get('/adminpanel/survey-users','SurveyController::getSurveyUsers');

    // Admin Report list
    $routes->get('/adminpanel/survey-report','AdminReportController::index');
    $routes->post('/adminpanel/survey-generate-report','AdminReportController::showReport');
    $routes->get('/adminpanel/survey-generate-graph/(:num)','AdminReportController::showGraph/$1');

});

$routes->get('/login','Home::login');
$routes->get('/registration','Home::register');
$routes->post('/survey-register','Home::registerProcess');
$routes->post('/survey-login','Home::loginProcess');
$routes->get('/survey-logout', 'Home::logout');

//$routes->get('/adminpanel/login',)


//getAllSubTask
