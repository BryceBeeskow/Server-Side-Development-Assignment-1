<?php
/* 
File:index.php
Purpose: Data entry form for maintaining a list of employees using PHP sessions and arrays
Course: CSC235 Web form 
Author: Bryce Beeskow (Beeskowb@csp.edu)
Date Created: 10/31/2025
Revisions: 
    10/31/2025 - Initial creation
    11/1/2025   - clean up code
*/

//Session & Initial setup//
session_start();

// initialize employee list (2D array)
if (!isset($_SESSION['employeeList'])){
    $_SESSION['employeeList'] = [];
}

//Functios//

//adding a new employee to the session array
function addEmployee($firstName, $lastName, $companyId, $phoneNumber){
    $newEmployee = [
        "firstName"  => htmlspecialchars($firstName),
        "lastName" => htmlspecialchars($lastName),
        "companyId" => htmlspecialchars($companyId),
        "phoneNumber" => htmlspecialchars($phoneNumber),
    ];
    $_SESSION['employeeList'][] = $newEmployee;
}

//deleting a employee from the session array
function deleteEmployee($index){
    if (isset($_SESSION['employeeList'][$index])) {
        unset($_SESSION['employeeList'][$index]);
        $_SESSION['employeeList'] = array_values($_SESSION['employeeList']); // Reindex array
    }
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['formAction'];

    if ($action === 'add') {
        addEmployee($_POST['txtFirstName'], $_POST['txtLastName'], $_POST['txtCompanyId'], $_POST['txtPhoneNumber']);
    } elseif ($action === 'delete') {
        deleteEmployee($_POST['lstEmployee']);

    }
}

//DEBUG OUTPUT
echo "<pre style='background:#89cff0;padding:10px;border:1px solid black;'>";
echo "DEBUG: \$_POST Array:\n";
print_r($_POST);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Entry Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Employee Information Entry</h1>

<!-- Section 1: ADD NEW EMPLOYEE FORM -->
<form method="post" action="index.php">
    <fieldset>
        <legend>Enter New Employee</legend>

        <label for="txtFirstName">First Name:</label>
        <input type="text" id="txtFirstName" name="txtFirstName" required>

        <label for="txtLastName">Last Name:</label>
        <input type="text" id="txtLastName" name="txtLastName" required>

        <label for="txtCompanyId">Company ID:</label>
        <input type="text" id="txtCompanyId" name="txtCompanyId" required>

        <label for="txtPhoneNumber">Phone:</label>
        <input type="text" id="txtPhoneNumber" name="txtPhoneNumber" required>

        <!-- Hidden field to track form action -->
        <input type="hidden" name="formAction" value="add">

        <input type="submit" name="btnAdd" value="Add Employee">
    </fieldset>
</form>

<!-- Section 2: DISPLAY EMPLOYEE LIST -->
<hr>
<h2>Current Employees</h2>

<?php if (!empty($_SESSION['employeeList'])): ?>
    <table>
        <tr>
            <th>#</th>
            <th>First</th>
            <th>Last</th>
            <th>Company ID</th>
            <th>Phone</th>
        </tr>
        <?php foreach ($_SESSION['employeeList'] as $index => $emp): ?>
            <tr>
                <td><?= $index ?></td>
                <td><?= $emp['firstName'] ?></td>
                <td><?= $emp['lastName'] ?></td>
                <td><?= $emp['companyId'] ?></td>
                <td><?= $emp['phoneNumber'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No employees added yet.</p>
<?php endif; ?>

<!-- Section 3: DELETE EMPLOYEE FORM -->
<form method="post" action="index.php">
    <fieldset>
        <legend>Delete an Employee</legend>

        <label for="lstEmployee">Select Employee:</label>
        <select id="lstEmployee" name="lstEmployee" size="5">
            <?php
            foreach ($_SESSION['employeeList'] as $index => $employee) {
                echo "<option value='$index'>{$employee['firstName']} {$employee['lastName']} ({$employee['companyId']})</option>";
            }
            ?>
        </select>

        <input type="hidden" name="formAction" value="delete">
        <input type="submit" name="btnDelete" value="Delete Employee">
    </fieldset>
</form>

</body>
</html>