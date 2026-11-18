
<?php
session_start();
$file = "bus.xml";
$xml = simplexml_load_file($file);
$msg = "";

// USER REGISTRATION
if (isset($_POST['register'])) {
    $u = $xml->users->addChild("user");
    $u->addChild("name", $_POST['name']);
    $u->addChild("password", $_POST['password']);
    $u->addChild("email", $_POST['email']);
    $u->addChild("wallet", "0");

    $xml->asXML($file);
    $msg = "Registration successful!";
}

// LOGIN
if (isset($_POST['login'])) {
    $role = $_POST['role'];
    $name = $_POST['name'];
    $pass = $_POST['password'];

    if ($role == "user") {
        foreach ($xml->users->user as $u) {
            if ((string)$u->name == $name &&
                (string)$u->password == $pass) {
                $_SESSION['user'] = $name;
                $_SESSION['role'] = "user";
            }
        }
    } elseif (
        ($role == "admin" && $name == "admin" && $pass == "123") ||
        ($role == "conductor" && $name == "conductor" && $pass == "123")
    ) {
        $_SESSION['user'] = $name;
        $_SESSION['role'] = $role;
    }

    $msg = isset($_SESSION['role']) ?
        "Login successful!" : "Invalid login!";
}

// WALLET ADD MONEY
if (isset($_POST['wallet'])) {
    foreach ($xml->users->user as $u) {
        if ((string)$u->name == $_SESSION['user']) {
            $u->wallet = (float)$u->wallet +
                         (float)$_POST['amount'];
        }
    }
    $xml->asXML($file);
    $msg = "Money added successfully!";
}

// APPLY BUS PASS
if (isset($_POST['apply'])) {
    $p = $xml->passes->addChild("pass");
    $p->addChild("name", $_SESSION['user']);
    $p->addChild("type", $_POST['type']);
    $p->addChild("route", $_POST['route']);
    $p->addChild("validity", $_POST['validity']);
    $p->addChild("status", "Pending");

    $xml->asXML($file);
    $msg = "Bus pass application submitted!";
}

// APPROVE PASS
if (isset($_POST['approve'])) {
    foreach ($xml->passes->pass as $p) {
        if ((string)$p->name == $_POST['owner']) {
            $p->status = "Approved";
        }
    }
    $xml->asXML($file);
    $msg = "Pass approved!";
}

// BUY TICKET
if (isset($_POST['ticket'])) {
    $t = $xml->tickets->addChild("ticket");
    $t->addChild("name", $_SESSION['user']);
    $t->addChild("route", $_POST['route']);
    $t->addChild("fare", $_POST['fare']);
    $xml->asXML($file);
    $msg = "Bus ticket booked!";
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
<title>Online Bus Pass</title>
<style>
body {
    font-family: Arial;
    background: #edf2f7;
    text-align: center;
}
.box {
    background: white;
    padding: 20px;
    margin: 25px auto;
    width: 80%;
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
    border: 0;
}
table {
    margin: auto;
    border-collapse: collapse;
}
td, th {
    border: 1px solid gray;
    padding: 10px;
}
</style>
</head>
<body>

<div class="box">
<h1>Online Bus Pass Management</h1>
<p><?php echo htmlspecialchars($msg); ?></p>

<?php if (!isset($_SESSION['role'])) { ?>

<h2>User Registration</h2>
<form method="post">
<input name="name" placeholder="Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button name="register">Register</button>
</form>

<h2>Login</h2>
<form method="post">
<input name="name" placeholder="Username" required>
<input name="password" type="password" placeholder="Password" required>
<select name="role">
<option value="user">User</option>
<option value="admin">Admin</option>
<option value="conductor">Conductor</option>
</select>
<button name="login">Login</button>
</form>

<p>Demo Admin: admin / 123</p>
<p>Demo Conductor: conductor / 123</p>

<?php } else { ?>

<h2>Welcome <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

<?php if ($_SESSION['role'] == "user") { ?>

<h3>Add Money to Wallet</h3>
<form method="post">
<input type="number" name="amount" placeholder="Amount" required>
<button name="wallet">Add Money</button>
</form>

<h3>Apply Bus Pass</h3>
<form method="post">
<select name="type">
<option>Student Pass</option>
<option>Route Pass</option>
</select>
<input name="route" placeholder="Route" required>
<select name="validity">
<option>1 Month</option>
<option>3 Months</option>
<option>6 Months</option>
</select>
<button name="apply">Apply Pass</button>
</form>

<h3>Buy Bus E-Ticket</h3>
<form method="post">
<input name="route" placeholder="Route" required>
<input type="number" name="fare" placeholder="Fare" required>
<button name="ticket">Buy Ticket</button>
</form>

<h3>My Bus Passes</h3>
<table>
<tr><th>Type</th><th>Route</th><th>Validity</th><th>Status</th></tr>
<?php foreach ($xml->passes->pass as $p) {
if ((string)$p->name == $_SESSION['user']) { ?>
<tr>
<td><?php echo htmlspecialchars((string)$p->type); ?></td>
<td><?php echo htmlspecialchars((string)$p->route); ?></td>
<td><?php echo htmlspecialchars((string)$p->validity); ?></td>
<td><?php echo htmlspecialchars((string)$p->status); ?></td>
</tr>
<?php } } ?>
</table>

<?php } ?>

<?php if ($_SESSION['role'] == "admin") { ?>

<h3>Approve Bus Pass</h3>
<table>
<tr><th>User</th><th>Type</th><th>Route</th><th>Status</th><th>Action</th></tr>
<?php foreach ($xml->passes->pass as $p) { ?>
<tr>
<td><?php echo htmlspecialchars((string)$p->name); ?></td>
<td><?php echo htmlspecialchars((string)$p->type); ?></td>
<td><?php echo htmlspecialchars((string)$p->route); ?></td>
<td><?php echo htmlspecialchars((string)$p->status); ?></td>
<td>
<form method="post">
<input type="hidden" name="owner"
value="<?php echo htmlspecialchars((string)$p->name); ?>">
<button name="approve">Approve</button>
</form>
</td>
</tr>
<?php } ?>
</table>

<?php } ?>

<?php if ($_SESSION['role'] == "conductor") { ?>

<h3>Conductor Dashboard</h3>
<p>Verify the passenger's bus pass and ticket.</p>
<form method="post">
<input name="passid" placeholder="Enter Pass ID">
<button type="button"
onclick="alert('Demo: Pass verification feature')">
Verify Pass
</button>
</form>

<?php } ?>

<br><br>
<a href="?logout=1">Logout</a>

<?php } ?>
</div>
</body>
</html>
