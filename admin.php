<?php
include "/functions/common.php";
initialize();

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == "GET") {
	include "functions/views.php";
	$isDatabaseInstalled = getDatabase();
	$args = array();
	if (!$isDatabaseInstalled) {
		$mainPanel = renderView('admin-install');
		$args = array('mainPanel' => $mainPanel, 'menu' => '');
	} else {
		$view = filterGET('view', '');
		if ('' != $view) {
			$mainPanel = renderView($view);
			$args = array('mainPanel' => $mainPanel);
		}
	}
	echo _renderAdminPage($args);
	return;
} else if ($requestMethod == "POST") {
	$action = $_POST['action'];
	unset($_POST['action']);
	$function = "_{$action}Action";
	if (_isInActionWhitelist($action) && function_exists($function)) {
		$result = call_user_func($function, $_POST);
		_displayResultNotification($result);
	} else {
		_displayResultNotification(false);
	}
	return;
}

function _addQuestionCategoryAction($data)
{
	include '/functions/question.php';
	$categoryData = getArrayValues($data, getQuestionCategoryTableColumns());
	return addQuestionCategory($categoryData);
}

function _addQuestionAction($data)
{
	include '/functions/question.php';
	$type = $data['type'];
	$options = array('TYPE' => $type);
	$mainTableColumns = getQuestionTableColumns();
	$secondaryTableColumns = getQuestionTableColumns($options);
	$columns = array_merge($mainTableColumns, $secondaryTableColumns);
	$questionData = getArrayValues($data, $columns);
	return addQuestion($type, $questionData);
}

function _addUserAction($data)
{
	include "functions/user.php";
	$userData = getArrayValues($data, getAccountsTableColumns());
	return addUser($userData);
}

function _addExamAction($data)
{
	include "functions/exam.php";
	$examData = getArrayValues($data, getExamTableColumns());
	$examData['start_date_time'] = $data["start_date"] . " " . $data["start_time"];
	$examData['end_date_time'] = $data["end_date"] . " " . $data["end_time"];
	
	return addExam($examData);
}

function _editQuestionCategoryAction($data)
{
	include '/functions/question.php';
	$categoryId = $data["category_id"];
	$categoryData = getArrayValues($data, getQuestionCategoryTableColumns());
	
	return editQuestionCategory($categoryId, $categoryData); 
}

function _editQuestionAction($data)
{
	include '/functions/question.php';
	$id = $data['question_id'];
	$type = $data['type'];
	$options = array('TYPE' => $type);
	$mainTableColumns = getQuestionTableColumns();
	$secondaryTableColumns = getQuestionTableColumns($options);
	$columns = array_merge($mainTableColumns, $secondaryTableColumns);
	$questionData = getArrayValues($data, $columns);
	
	return updateQuestion($id, $questionData);
}

function _editExamAction($data)
{
	include "functions/exam.php";
	$examData = getArrayValues($data, getExamTableColumns());
	$examData['start_date_time'] = $data["start_date"] . " " . $data["start_time"];
	$examData['end_date_time'] = $data["end_date"] . " " . $data["end_time"];
	
	$id = $data["exam_id"];
	return updateExam($id, $examData);
}

function _editUserAction($data)
{
	include "functions/user.php";
	$userData = getArrayValues($data, getAccountsTableColumns());
	$id = $data['id'];
	return updateUser($id, $userData);
}

function _deleteQuestionAction($data)
{
	include "functions/question.php";
	return deleteQuestion($data["questionId"]);
}

function _deleteExamAction($data)
{
	include "functions/exam.php";
	return deleteExam($data["examId"]);
}

function _deleteUserAction($data)
{
	include "functions/user.php";
	return deleteUser($data['id']);
}

function _installAction($data)
{
	include "functions/install.php";
	return installDatabase($data);
}

function _isValidationOk($validationResult)
{
	if ($validationResult === true) {
		return true;
	} else {
		return false;
	}
}

function _displayResultNotification($result)
{
	$notification = '';
	if (is_string($result)) {
		$message = json_decode($result, true);
		$notification = '<h2>Error</h2>';
		if (is_array($message) && isset($message['ERROR'])) {
			$errorMessage = nl2br($message['ERROR']['text']);
			$notification .= '<div>'.$errorMessage.'</div>';
		} elseif (is_array($message) && isset($message['OK'])) {
			$notification .= '<div>' . $message['ERROR']['text'] . '</div>';
		}
	} elseif (is_bool($result)) {
		if ($result === true) {
		$notification = "<h2>Success!</h2>";
		} elseif ($result === false) {
			$notification = "<h2>Error. Please try again.</h2>";
		}
	}
	$args = array('mainPanel' => $notification);
	echo _renderAdminPage($args);
}

function _renderAdminPage($args)
{
	$menu = renderView('admin-menu');
	$args = array_merge(array('menu' => $menu), $args);
	return renderView('admin-main', $args);
}

function _isInActionWhitelist($action)
{
	$list = array('addQuestionCategory', 'addQuestion', 'addUser', 'addExam',
				'editQuestionCategory', 'editQuestion', 'editUser', 'editExam',
				'deleteCategory', 'deleteQuestion', 'deleteUser', 'deleteExam', 'install');
	
	if (in_array($action, $list)) {
		return true;
	}
	return false;
}