<?php
session_start();

$file = "traffic.xml";
$xml = simplexml_load_file($file);

$message = "";

// USER REGISTRATION
if (isset($_POST['register'])) {
    $user = $xml->users->addChild("user");
    $user->addChild("username", $_POST['username']);
    $user->addChild("password", $_POST['password']);
    $user->addChild("vehicle", $_POST['vehicle']);

    $xml->asXML($file);
    $message = "Registration successful!";
}

// LOGIN
if (isset($_POST['login'])) {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($role == "user") {
        foreach ($xml->users->user as $u) {
            if ((string)$u->username == $username &&
                (string)$u->password == $password) {
                $_SESSION['role'] = "user";
                $_SESSION['username'] = $username;
                $_SESSION['vehicle'] = (string)$u->vehicle;
            }
        }
    } else {
        if (($role == "police" && $username == "police" &&
             $password == "123") ||
            ($role == "admin" && $username == "admin" &&
             $password == "123")) {
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $username;
        }
    }

    if (isset($_SESSION['role'])) {
        $message = "Login successful!";
    } else {
        $message = "Invalid username or password!";
    }
}

// POLICE CREATE CASE
if (isset($_POST['create'])) {
    $case = $xml->cases->addChild("case");
    $case->addChild("owner", $_POST['owner']);
    $case->addChild("vehicle", $_POST['vehicle']);
    $case->addChild("violation", $_POST['violation']);
    $case->addChild("fine", $_POST['fine']);

    $photo = "";

    if (!empty($_FILES['photo']['name'])) {
        $photo = time() . "_" .
                 basename($_FILES['photo']['name']);

        if (move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "uploads/" . $photo
        )) {
            $case->addChild("photo", $photo);
        }
    }

    $case->addChild("status", "Pending");
    $xml->asXML($file);

    $message = "Traffic case created successfully!";
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$xml = simplexml_load_file($file);
?>

<!DOCTYPE html>
<html>
<head>
<title>Traffic Squad Management</title>
<style>
body {
    font-family: Arial;
    background: #eef2f5;
    text-align: center;
}
.container {
    background: white;
    width: 75%;
    margin: 30px auto;
    padding: 20px;
    border-radius: 10px;
}
input, select {
    padding: 10px;
    margin: 7px;
}
button {
    padding: 10px 20px;
    background: #1769aa;
    color: white;
    border: none;
}
table {
    margin: auto;
    border-collapse: collapse;
    width: 90%;
}
td, th {
    border: 1px solid gray;
    padding: 10px;
}
</style>
</head>
<body>

<div class="container">

<h1>Traffic Squad</h1>
<h3>Penalty Collection and Management System</h3>

<p><?php echo htmlspecialchars($message); ?></p>

<?php if (!isset($_SESSION['role'])) { ?>

<h2>User Registration</h2>

<form method="post">
<input name="username" placeholder="Username" required>
<input name="password" type="password"
placeholder="Password" required>
<input name="vehicle" placeholder="Vehicle Number" required>
<button name="register">Register</button>
</form>

<hr>

<h2>Login</h2>

<form method="post">
<input name="username" placeholder="Username" required>
<input name="password" type="password"
placeholder="Password" required>

<select name="role">
<option value="user">User</option>
<option value="police">Police</option>
<option value="admin">Admin</option>
</select>

<button name="login">Login</button>
</form>

<p>Demo Police Login: police / 123</p>
<p>Demo Admin Login: admin / 123</p>

<?php } else { ?>

<h2>Welcome <?php
echo htmlspecialchars($_SESSION['username']);
?></h2>

<?php if ($_SESSION['role'] == "user") { ?>

<h3>View Challan / Case Details</h3>

<table>
<tr>
<th>Vehicle</th>
<th>Violation</th>
<th>Fine</th>
<th>Status</th>
</tr>

<?php foreach ($xml->cases->case as $c) {
if ((string)$c->vehicle ==
    (string)$_SESSION['vehicle']) { ?>
<tr>
<td><?php echo htmlspecialchars((string)$c->vehicle); ?></td>
<td><?php echo htmlspecialchars((string)$c->violation); ?></td>
<td>Rs. <?php echo htmlspecialchars((string)$c->fine); ?></td>
<td><?php echo htmlspecialchars((string)$c->status); ?></td>
</tr>
<?php } } ?>
</table>

<?php } ?>

<?php if ($_SESSION['role'] == "police") { ?>

<h2>Create Traffic Case</h2>

<form method="post" enctype="multipart/form-data">
<input name="owner" placeholder="Vehicle Owner" required>
<input name="vehicle" placeholder="Vehicle Number" required>
<input name="violation" placeholder="Traffic Violation" required>
<input name="fine" type="number" placeholder="Fine Amount" required>
<br>
Upload Proof Photo:
<input type="file" name="photo" accept="image/*">
<br>
<button name="create">Create Case</button>
</form>

<h3>Traffic Rules</h3>
<p>Follow traffic signals and road safety rules.</p>
<p>Wear helmets and seat belts.</p>
<p>Avoid overspeeding.</p>

<h3>Traffic Map</h3>
<a href="https://www.google.com/maps"
target="_blank">Google Maps Traffic</a>

<?php } ?>

<?php if ($_SESSION['role'] == "admin") { ?>

<h2>Daily Traffic Report</h2>

<p>Total Cases: <?php echo count($xml->cases->case); ?></p>

<table>
<tr>
<th>Owner</th>
<th>Vehicle</th>
<th>Violation</th>
<th>Fine</th>
</tr>

<?php foreach ($xml->cases->case as $c) { ?>
<tr>
<td><?php echo htmlspecialchars((string)$c->owner); ?></td>
<td><?php echo htmlspecialchars((string)$c->vehicle); ?></td>
<td><?php echo htmlspecialchars((string)$c->violation); ?></td>
<td>Rs. <?php echo htmlspecialchars((string)$c->fine); ?></td>
</tr>
<?php } ?>
</table>

<?php } ?>

<br>
<a href="?logout=1">Logout</a>

<?php } ?>

</div>
</body>
</html>
