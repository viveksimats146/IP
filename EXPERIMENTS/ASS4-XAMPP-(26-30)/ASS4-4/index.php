<?php
$file = "event.xml";
$xml = simplexml_load_file($file);
$msg = "";

if (isset($_POST['register'])) {
    $r = $xml->registrations->addChild("participant");
    $r->addChild("name", $_POST['name']);
    $r->addChild("email", $_POST['email']);

    $xml->asXML($file);
    $msg = "Registration successful!";
}

$xml = simplexml_load_file($file);
?>

<!DOCTYPE html>
<html>
<head>
<title>Event Management</title>
<style>
body {
    font-family: Arial;
    background: #edf2f7;
    text-align: center;
}
header {
    background: #17365d;
    color: white;
    padding: 25px;
}
section {
    background: white;
    padding: 20px;
    margin: 20px auto;
    width: 75%;
    border-radius: 10px;
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
<p>Venue: <?php echo htmlspecialchars((string)$xml->venue); ?></p>
<p>Date: <?php echo htmlspecialchars((string)$xml->date); ?></p>
</header>

<section>
<h2>Purpose of Event</h2>
<p><?php echo htmlspecialchars((string)$xml->purpose); ?></p>
<p>Target Audience:
<?php echo htmlspecialchars((string)$xml->audience); ?></p>
</section>

<section>
<h2>Speakers</h2>
<?php foreach ($xml->speakers->speaker as $s) { ?>
<p><?php echo htmlspecialchars((string)$s); ?></p>
<?php } ?>
</section>

<section>
<h2>Event Schedule</h2>
<?php foreach ($xml->schedule->session as $s) { ?>
<p><?php echo htmlspecialchars((string)$s); ?></p>
<?php } ?>
</section>

<section>
<h2>Event Registration</h2>
<form method="post">
<input name="name" placeholder="Your Name" required>
<br><br>
<input type="email" name="email" placeholder="Email" required>
<br><br>
<button name="register">Register Now</button>
</form>
<p><?php echo htmlspecialchars($msg); ?></p>
</section>

</body>
</html>
