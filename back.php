// Darren Gomez
// 5/7/17
// CS490 - Design in Software Engineering 
// Spring 2017 - Theodore L. Nicholson
// Exam Creation Tool - Backend

<?php


//Parameters:
//username is the login UCID
//password is the login password
//Returns:
//Returns an array of loginSuccessful and role
//Loginsuccessful is whether or not that username/password is valid
//Role is Student or Instructor (or null if !Loginsuccessful)
function checkLogin($conn, $username, $password){

  $sql = "SELECT `Password`,`Role` FROM `UCID/Password` WHERE UCID = \"$username\"";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $dbPass = $row["Password"];
  $role = $row["Role"];

  if( $dbPass == $password && $dbPass != NULL){
      $data = array("loginSuccessful" => true, "role" => $role);
      header('Content-Type: application/json');
      echo json_encode( $data );
  } else {
    $data = array("loginSuccessful" => false);
    header('Content-Type: application/json');
    echo json_encode( $data );
  }

}

//Parameters:
//text is the JSON encoding of the question to be stored
//Returns:
//If successful: Returns the questionID associated with this questionID
//If unsuccessful: Returns false
function storeQuestion($conn, $text){


  $sql = "INSERT INTO `dg344`.`examQuestions` (`questionID`, `jsonEncoding`) VALUES (NULL, '".$text."');";


  $result = $conn->query($sql);
  header('Content-Type: application/json');
  if(!$result ){
    echo json_encode( False );
  } else {
    echo json_encode( $conn->insert_id );
  }

}

//This is when a student writes an answer on his exam and it gets stored
//Parameters:
//username is the students UCID
//text is the answer the student wrote
//questionID is what questionID this is
//examID is what examID this is
//Returns:
//Returns True if works and False if not
function storeAnswer($conn, $username, $text, $questionID, $examID){

  $sql = "INSERT INTO `dg344`.`storedAnswers` (`UCID`, `Answer`, `questionID`, `examID`) VALUES (\"$username\", \"$text\", \"$questionID\", \"$examID\");";

  $result = $conn->query($sql);
  header('Content-Type: application/json');
  if(!$result ){
    echo json_encode( False );
  } else {
    echo json_encode( True );
  }

}

//Parameters:
//username is the students username
//questionID is what question this pertains to
//examID is what exam this question is on
//Returns:
//returns the answer that the student wrote so it can be graded
function getAnswer($conn, $username, $questionID, $examID){
  $sql = "SELECT `Answer` FROM `storedAnswers` WHERE UCID = \"$username\" AND questionID = \"$questionID\" AND examID = \"$examID\"";

  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $array = $row[0];


  header('Content-Type: application/json');
  echo json_encode( $row[0] );

}

//Parameter:
//Reads in the questionID of a question
//Returns:
//JSON Encoding regarding that questionID
function getSingleQuestion($conn, $questionID) {

  $sql = "SELECT * FROM `examQuestions` WHERE questionID = \"$questionID\"";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  header('Content-Type: application/json');
  echo json_encode( $row[1] );

}

//Parameters
//username is the students UCID
//questionID is the questionID of the question we are reporting the score of
//ExamID is what exam this is
//grade is what their score is
function storeScore($conn, $username, $questionID, $examID, $grade){

  $sql = "INSERT INTO `dg344`.`questionScores` (`UCID`, `questionID`, `examID`, `gradeReceived`) VALUES (\"$username\", \"$questionID\", \"$examID\", \"$grade\");";
  $result = $conn->query($sql);
  header('Content-Type: application/json');
  if(!$result ){
    echo json_encode( False );
  } else {
    echo json_encode( True );
  }

}

//Parameters:
//username is UCID of the student
//questionID is the question ID of the question we want to know the score of
//ExamID is what exam the student took
//Returns the grade as a decimal with 2 decimal places
function getScore($conn, $username, $questionID, $examID){
  $sql = "SELECT `gradeReceived` FROM `questionScores` WHERE UCID = \"$username\" AND questionID = \"$questionID\" AND examID = \"$examID\"";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $array = $row[0];
  header('Content-Type: application/json');
  echo json_encode( $row[0] );

}

//Loops through the database and returns all of the question JSON encodings
//returns a list of JSON Encodings
function getQuestionBank($conn){

  $sql = "SELECT * FROM `examQuestions`";
  $result = $conn->query($sql);

  $questionBank = array();
  $i = 0;
  while($row = $result->fetch_row()){
      $questionBank[$i] = $row[1];
      $i++;
  }

  header('Content-Type: application/json');
  echo json_encode( $questionBank );

}

//Loops through the database and returns all of the question IDs
//Returns a list of question IDs
function getQuestionIDs($conn){

  $sql = "SELECT * FROM `examQuestions`";
  $result = $conn->query($sql);

  $questionBank = array();
  $i = 0;
  while($row = $result->fetch_row()){
      $questionBank[$i] = $row[0];
      $i++;
  }

  header('Content-Type: application/json');
  echo json_encode( $questionBank );


}

//createExam stores an exam in the Database
//Parameters:
//$questionIDs is an array of question IDs that make up the exam
//$instructorName is the name of the instructor (Primary Key for the Database)
//Returns the examID stored with this exam
function createExam($conn, $text, $examName){

 $sql = "INSERT INTO `dg344`.`Exam` (`ExamID`, `QuestionIDs`, `examName`) VALUES (NULL, '$text', $examName);";


 $result = $conn->query($sql);


 if(!$result ){
   echo json_encode( false );
 } else {
   echo json_encode( $examID = $conn->insert_id );
 }

}

//Returns a list of examIDs
function getExamList($conn){

  $sql = "SELECT * FROM `Exam`";
  $result = $conn->query($sql);

  $array = array();
  $i = 0;
  while($row = $result->fetch_row()){
      $array[$i] = $row[0];
      $i++;
  }

  header('Content-Type: application/json');
  echo json_encode( $array );

}

//Returns name of an exam
//Takes in the examID as a Parameter
function getExamName($conn, $text){

  $sql = "SELECT `examName` FROM `Exam` WHERE ExamID = \"$text\"";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $array = $row[0];

  echo json_encode( $array );

}

//Parameter:
//text is the ExamID
//Returns:
//The JSON Encoding of that exam
function getSingleExam($conn, $text){

  $sql = "SELECT `QuestionIDs` FROM `Exam` WHERE ExamID = \"$text\"";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $array = $row[0];

  header('Content-Type: application/json');
  echo json_encode( $array );

}

function getFinalGrade($conn, $username, $examID){
  $sql = "SELECT `grade` FROM `FinalExamGrades` WHERE UCID = \"$username\" AND examID = \"$examID\"";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $array = $row[0];
  header('Content-Type: application/json');
  echo json_encode( $row[0] );
}

function storeFinalGrade($conn, $username, $examID, $grade){

  $sql = "INSERT INTO `dg344`.`FinalExamGrades` (`UCID`, `examID`, `grade`) VALUES (\"$username\", \"$examID\", \"$grade\");";
  $result = $conn->query($sql);
  header('Content-Type: application/json');
  if(!$result ){
    echo json_encode( False );
  } else {
    echo json_encode( True );
  }
}

function getStudentNames($conn){
  $sql = "SELECT `UCID` FROM `UCID/Password` WHERE `Role`= \"Student\"";
  $result = $conn->query($sql);

  $array = array();
  $i = 0;
  while($row = $result->fetch_row()){
      $array[$i] = $row[0];
      $i++;
  }

  $data = array("array" => $array);
  header('Content-Type: application/json');
  echo json_encode( $data );

}


$conn = mysqli_connect("sql1.njit.edu", "dg344", "1vZKKBPa", "dg344");
if( !$conn ){
  die("Connection Failed: " . mysqli_connect_error());
}

$data = NULL;

$action = $_GET["action"];
//Action is read in and the appropriate post variables are saved and passed to
//appropriate methods
switch ($action) {
  case "login":
    $username = $_POST["user"];
    $password = $_POST["pass"];
    checkLogin($conn, $username, $password);
    break;

  case "createQuestion":
    $text = $_POST["text"];
    storeQuestion($conn, $text);
    break;

  case "getSingleQuestion":
    $text = $_POST["text"];
    storeQuestion($conn, $text);
    break;

  case "getSingleExam":
    $text = $_POST["text"];
    getSingleExam( $conn, $text );
    break;

  case "storeAnswer":
    $username = $_POST["user"];   //The students UCID
    $text = $_POST["text"];       //The students written answer
    $questionID = $_POST["questionID"];   //QuestionID of the answered Question
    $examID = $_POST["examID"];   //ExamID of the taken exam
    storeAnswer($conn, $username, $text, $questionID, $examID);
    break;

  case "storeScore":
    username = $_POST["user"];   //The students UCID
    $grade = $_POST["grade"];       //The students written answer
    $questionID = $_POST["questionID"];     //The students grade
    $examID = $_POST["examID"];   //ExamID of the taken exam
    storeScore($conn, $username, $questionID, $examID, $grade);
    break;

  case "getAnswer":
    $username = $_POST["user"];   //The students UCID
    $questionID = $_POST["questionID"];     //The students grade
    $examID = $_POST["examID"];   //ExamID of the taken exam
    getAnswer($conn, $username, $questionID, $examID);
    break;

  case "getScore" :
    username = $_POST["user"];   //The students UCID
    $questionID = $_POST["questionID"];     //The students grade
    $examID = $_POST["examID"];   //ExamID of the taken exam
    getScore($conn, $username, $questionID, $examID);
    break;

  case "getQuestionBank":
    getQuestionBank($conn);
    break;

  case "getQuestionIDs":
    getQuestionIDs($conn);
    break;

  case "createExam":
    $text = $_POST["text"];
    $examName = $_POST["examName"];
    createExam($conn, $text, $examName);
    break;

  case "getExamList":
    getExamList($conn);
    break;

  case "getExamName":
    $text = $_POST["text"];
    getExamName($conn, $text);
    break;

  case "storeFinalGrade":
    $username = $_POST["user"];
    $examID = $_POST["examID"];
    $grade = $_POST["grade"];
    storeFinalGrade( $conn, $username, $examID, $grade );
    break;

  case "getFinalGrade"
    $username = $_POST["user"];
    $examID = $_POST["examID"];
    getFinalGrade( $conn, $username, $examID );
    break;

  case "getStudentNames":
    getStudentNames( $conn );
    break;

  default:
    $data = array("loginSuccessful" => false, "error" => "CANNOT READ POST");
    header('Content-Type: application/json');
    echo json_encode( $data );
    break;
}

mysqli_close($conn);

?>
