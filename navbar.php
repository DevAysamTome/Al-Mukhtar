<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="style.css">
  
	<title>AL-MUKHTAR | </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/x-icon" href="Logo.ico">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
</head>
<body>
<header>
		<nav class="navbar">
            <div class="navbar-container">
              <a href="#" class="navbar-logo">AL-MUKHTAR</a>
              <ul class="navbar-menu">
                <?php
                // Database connection
                $conn = mysqli_connect("localhost", "root", "", "al-mukhtar");

                // Query to fetch menu items
                $query = "SELECT * FROM nav";

                // Execute the query
                $result = mysqli_query($conn, $query);

                // Loop through the menu items and generate the menu dynamically
                while ($row = mysqli_fetch_assoc($result)) {
                    $url = $row['nav_link'];
                    $label = $row['nav_name'];

                    echo "<li><a href='$url'>$label</a></li>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
                </ul>
              <div class="hamburger-menu">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
              </div>
            </div>
          </nav>
    </header>
            </body>
            <script src="nav.js"></script>

            </html>