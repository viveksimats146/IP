<?php
session_start();

$file = "journal.xml";
$xml = simplexml_load_file($file);
$msg = "";

// REGISTRATION
if (isset($_POST['register'])) {
    $m = $xml->members->addChild("member");
    $m->addChild("name", $_POST['name']);
    $m->addChild("email", $_POST['email']);
    $m->addChild("password", $_POST['password']);
    $m->addChild("role", $_POST['role']);

    $xml->asXML($file);
    $msg = "Registration successful!";
}

// LOGIN
if (isset($_POST['login'])) {
    foreach ($xml->members->member as $m) {
        if ((string)$m->email == $_POST['email'] &&
            (string)$m->password == $_POST['password'] &&
            (string)$m->role == $_POST['role']) {
            $_SESSION['name'] = (string)$m->name;
            $_SESSION['role'] = (string)$m->role;
        }
    }

    $msg = isset($_SESSION['role']) ?
        "Login successful!" : "Invalid login!";
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
<title>IJSSE Journal</title>
<style>
body {
    font-family: Arial;
    background: #eef2f5;
    margin: 0;
    text-align: center;
}
header {
    background: #163b5c;
    color: white;
    padding: 25px;
}
nav {
    background: #24577c;
    padding: 15px;
}
nav a {
    color: white;
    margin: 10px;
    text-decoration: none;
}
section {
    background: white;
    width: 80%;
    margin: 20px auto;
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
}
</style>
</head>
<body>

<header>
<h1><?php echo htmlspecialchars((string)$xml->name); ?></h1>
<p>Print ISSN: <?php echo htmlspecialchars((string)$xml->printISSN); ?></p>
<p>Online ISSN: <?php echo htmlspecialchars((string)$xml->onlineISSN); ?></p>
</header>

<nav>
<a href="#home">Home</a>
<a href="#about">About Journal</a>
<a href="#ethics">Ethics</a>
<a href="#articles">Articles</a>
<a href="#authors">Authors</a>
<a href="#contact">Contact</a>
</nav>

<section id="home">
<h2>Welcome to IJSSE</h2>
<p>International Journal of Scientific and Social Excellence</p>
</section>

<section id="about">
<h2>About Journal</h2>
<p>IJSSE publishes scientific and social research articles.</p>
</section>

<section id="ethics">
<h2>Publication Ethics</h2>
<p>Authors must submit original research work.</p>
<p>Reviewers should provide fair and honest feedback.</p>
</section>

<section>
<h2>Editorial Board</h2>
<p>Editor Registration and Login</p>
<p>Editorial Committee and Editor Responsibilities</p>
</section>

<section>
<h2>Reviewers</h2>
<p>Reviewer Registration and Login</p>
<p>Article Review Information</p>
</section>

<section id="authors">
<h2>Author Registration and Login</h2>

<form method="post">
<input name="name" placeholder="Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<select name="role">
<option>Author</option>
<option>Reviewer</option>
<option>Editor</option>
</select>

<br>
<button name="register">Register</button>
</form>

<hr>

<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<select name="role">
<option>Author</option>
<option>Reviewer</option>
<option>Editor</option>
</select>

<button name="login">Login</button>
</form>

<p><?php echo htmlspecialchars($msg); ?></p>

<?php if (isset($_SESSION['role'])) { ?>
<h3>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?></h3>
<p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
<a href="?logout=1">Logout</a>
<?php } ?>
</section>

<section id="articles">
<h2>Articles</h2>
<table border="1" cellpadding="10" align="center">
<tr>
<th>Article Title</th>
<th>Author</th>
<th>Issue</th>
</tr>

<?php foreach ($xml->articles->article as $a) { ?>
<tr>
<td><?php echo htmlspecialchars((string)$a->title); ?></td>
<td><?php echo htmlspecialchars((string)$a->author); ?></td>
<td><?php echo htmlspecialchars((string)$a->issue); ?></td>
</tr>
<?php } ?>
</table>
</section>

<section>
<h2>Call for Papers</h2>
<p>Submit your research manuscript to IJSSE.</p>
<p>Author Guidelines and Publication Instructions</p>
</section>

<section id="contact">
<h2>Contact Us</h2>
<p>Email: editor@ijsse.example</p>
</section>

</body>
</html>
