<?php
  class Staff{
    // declrase some variables for class
    var $name;
    var $gender;
    var $birthday;
    var $dayatwork;
    var $coefficient_salary;
    var $num_children;
    var $seniority;
    var $basic_salary = 450000;

    function getSalary(){
      return $this->basic_salary * $this->coefficient_salary;
    }

    function getGrant(){
      return $this->num_children * 100000;
    }

    function getAward(){
      return $this->seniority * 500000;
    }

    function initialize_information($name, $gender, $birthday, $dayatwork, $coefficient_salary, $num_children, $seniority){
      $this->name = $name;
      $this->gender = $gender;
      $this->birthday = $birthday;
      $this->dayatwork = $dayatwork;
      $this->coefficient_salary = $coefficient_salary;
      $this->num_children = $num_children;
      $this->seniority;
    }

    // get and set methods for each variable
    function getName(){
      return $this->name;
    }

    function setName($new_value){
      $this->name = $new_value;
    }

    function getGender(){
      return $this->gender;
    }

    function setGender($new_value){
      $this->gender = $new_value;
    }

    function getBirthday(){
      return $this->birthday;
    }

    function setBirthday($new_value){
      $this->birthday = $new_value;
    }

    function getDayAtWork(){
      return $this->dayatwork;
    }

    function setDayAtWork($new_value){
      $this->dayatwork = $new_value;
    }

    function getCoefficientSalary(){
      return $this->coefficient_salary;
    }

    function setCoefficientSalary($new_value){
      $this->coefficient_salary = $new_value;
    }

    function getNumChildren(){
      return $this->num_children;
    }

    function setNumChildren($new_value){
      $this->num_children = $new_value;
    }

    function getSeniority(){
      return $this->seniority;
    }

    function setSeniority($new_value){
      $this->seniority = $new_value;
    }
  }

  class OfficeStaff extends Staff{
    var $absence_norm;
    var $number_of_days_absent;
    var $penalty_unit_price = 25000;

    function getNumberOfDayAbsence(){
      return $this->number_of_days_absent;
    }

    function setNumberOfDayAbsence($new_value){
      $this->number_of_days_absent = $new_value;
    }

    function getAbsenceNorm(){
      return $this->absence_norm;
    }

    function setAbsenceNorm($new_value){
      $this->absence_norm = $new_value;
    }

    function getPenaltyUnitPrice(){
      return $this->penalty_unit_price;
    }

    function setPenaltyUnitPrice($new_value){
      $this->penalty_unit_price = $new_value;
    }

    function getPenalty(){
      if($this->number_of_days_absent > $this->absence_norm){
        return ($this->number_of_days_absent - $this->absence_norm) * $this->penalty_unit_price;
      }
    }

    function getGrant(){
      if($this->gender == "1"){
        return $this->num_children * 100000 * 1.2;
      }else{
        return $this->num_children * 100000;
      }
    }

    function getSalary(){
      return ($this->basic_salary * $this->coefficient_salary) - $this->getPenalty();
    }
  }

  class ManufacturingStaff extends Staff{
    var $num_product;
    var $threadshold_product = 100;
    var $cost = 12000;
    var $overtime;

    function setOverTime($new_value){
      $this->overtime = $new_value;
    }

    function getCost(){
      return $this->cost;
    }

    function setCost($new_value){
      $this->cost = $new_value;
    }

    function getThreadsholdProduct(){
      return $this->threadshold_product;
    }

    function setThreadsholdProduct($new_value){
      $this->threadshold_product = $new_value;
    }

    function getProductNumber(){
      return $this->num_product;
    }

    function setProductNumber($new_value){
      $this->num_product = $new_value;
    }

    function getAward(){
      if($this->num_product > $this->threadshold_product){
        return (($this->num_product - $this->threadshold_product) * $this->cost * 0.05);
      }else{
        return 0;
      }
    }

    function getGrant(){
      if($this->overtime == "yes"){
        return ($this->num_children * 100000) + 200000;
      }
      if($this->overtime == "no"){
        return ($this->num_children * 100000);
      }
    }

    function getSalary(){
      return ($this->num_product * $this->cost) + $this->getAward();
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Staff Manager</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <?php

    // get value of Form
    $name = $_POST["name"];
    $birthday = $_POST["birthday_1"];
    $num_children = $_POST["num_children"];
    $dayatwork = $_POST["dayatwork_1"];
    $gender = $_POST["gender"];
    $coefficient_salary = $_POST["coefficient_salary"];
    $type = $_POST["employeseType"];
    $absence = $_POST["absence"];
    $num_product = $_POST["num_product"];
    $overtime = $_POST["overtime"];
    
    if($type == "manufacturing"){
      $manufacturing = new ManufacturingStaff();
      $manufacturing->initialize_information($name, $gender, $birthday, $dayatwork, $coefficient_salary, $num_children, $seniority);
      $manufacturing->setProductNumber($num_product);
      $manufacturing->setOverTime($overtime);
      $salary = $manufacturing->getSalary();
      $grant = $manufacturing->getGrant();
      $total = $salary + $grant;
    }

    if($type == "office"){
      $staff = new OfficeStaff();
      $staff->initialize_information($name, $gender, $birthday, $dayatwork, $coefficient_salary, $num_children, $seniority);
      $staff->setNumberOfDayAbsence($absence);

      $salary = $staff->getSalary();
      $grant = $staff->getGrant();
      $total = $salary + $grant;
    }
    
    $salary = number_format($salary,0,',','.'). " VND";
    $grant = number_format($grant,0,',','.'). " VND";
    $total = number_format($total,0,',','.'). " VND";
  ?>
  <form action="StaffManager.php" method="post" id="formStaff">
    <table align="center" width="800px" border="1">
      <tr>
        <th colspan="4" class="headline">STAFF MANAGER</th>
      </tr>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="name" value="<?php echo $name; ?>"></td> 
        <td>Number Children:</td>
        <td><input type="number" min="0" max="10" name="num_children" value="<?php echo $num_children; ?>"></td>
      </tr>
      <tr>
        <td><label for="birthday">Birthday:</label></td>
        <td>
          <input type="date" id="birthday" name="birthday_1" value="<?php echo $birthday; ?>" min="1990-01-01" max="2019-12-31">
        </td>
        <td><label for="dayofwork">Day at work:</label></td>
        <td>
          <input type="date" id="dayatwork" name="dayatwork_1" value="<?php echo $dayatwork; ?>" min="1990-01-01" max="2019-12-31">
        </td>
      </tr>
      <tr>
        <td>Gender</td>
        <td>
          <input type="radio" id="male" name="gender" value="0" checked="True">
          <label for="male">Male</label>
          <input type="radio" id="female" name="gender" value="1">
          <label for="female">Female</label>
        </td>
        <td>Coefficients Salary</td>
        <td><input type="number" min="0" max="10" name="coefficient_salary" value="<?php echo $coefficient_salary; ?>"></td>
      </tr>
      <tr>
        <td>Employee Type</td>
        <td>
          <input type="radio" id="office" name="employeseType" value="office" checked>
          <label for="office">Office</label>
        </td>
        <td colspan="2">
          <input type="radio" id="manufacturing" name="employeseType" value="manufacturing">
          <label for="manufacturing">Manufacturing</label>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          Day absence:
          <input type="number" step="1" min="0" max="20" name="absence" value="<?php echo $absence; ?>">
        </td>
        <td>Number of Products<input type="number" step="1" min="0" max="100" name="num_product" value="<?php echo $num_product; ?>">
        </td>
        <td>
          Overtime:
          <input type="radio" id="yes" name="overtime" value="yes">
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="overtime" value="no">
          <label for="no">No</label>
        </td>
      </tr>
      <tr>
        <td>Salary</td>
        <td><input type="text" name="salary" value="<?php echo $salary; ?>"></td>
        <td>Grant</td>
        <td><input type="text" name="grant" value="<?php echo $grant; ?>"></td>
      </tr>
      <tr>
        <td colspan="4">Total<input type="text" name="total" value="<?php echo $total; ?>"></td>
      </tr>
      <tr>
        <td colspan="4">
        <button type="submit" form="formStaff" value="Submit">Compute Salary</button>
        </td>
      </tr>

    </table>
  </form>
</body>
</html>