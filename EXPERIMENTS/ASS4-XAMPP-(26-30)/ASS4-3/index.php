<?php
$xml = simplexml_load_file("artist.xml");
$message = "";

if (isset($_POST['contact'])) {
    $message = "Thank you! Your message has been received.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ArtistryHub</title>
<style>
body {
    font-family: Arial;
    margin: 0;
    background: #f5f0eb;
    text-align: center;
}
header {
    background: #30243b;
    color: white;
    padding: 25px;
}
section {
    background: white;
    margin: 20px auto;
    padding: 20px;
    width: 80%;
    border-radius: 10px;
}
h2 { color: #704b80; }
</style>
</head>
<body>

<header>
<h1>ArtistryHub</h1>
<h3><?php echo htmlspecialchars((string)$xml->name); ?></h3>
<p>Explore the world of creativity and art</p>
</header>

<section>
<h2>About the Artist</h2>
<p><?php echo htmlspecialchars((string)$xml->bio); ?></p>
</section>

<section>
<h2>Portfolio</h2>
<?php foreach ($xml->artworks->artwork as $a) { ?>
<h3><?php echo htmlspecialchars((string)$a->title); ?></h3>
<p>Year: <?php echo htmlspecialchars((string)$a->year); ?></p>
<p><?php echo htmlspecialchars((string)$a->description); ?></p>
<hr>
<?php } ?>
</section>

<section>
<h2>Discography / Bibliography</h2>
<p>Emily Harris - Creative Art Collection</p>
<p>Year: 2025</p>
</section>

<section>
<h2>Media Gallery</h2>
<p>Art exhibitions and artist interviews</p>
</section>

<section>
<h2>News and Updates</h2>
<?php foreach ($xml->news->item as $n) { ?>
<p><?php echo htmlspecialchars((string)$n); ?></p>
<?php } ?>
</section>

<section>
<h2>Contact Artist</h2>
<form method="post">
<input name="name" placeholder="Your Name" required>
<br><br>
<input type="email" name="email" placeholder="Email" required>
<br><br>
<textarea name="message" placeholder="Your Message" required></textarea>
<br><br>
<button name="contact">Send Message</button>
</form>
<p><?php echo htmlspecialchars($message); ?></p>
</section>

</body>
</html>
