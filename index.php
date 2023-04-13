<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>AL-MUKHTAR</title>
	<link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="1.css">

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
	
	<main>
		<div class="slideshow-container">
            <div class="slideshow">
            <!--
              <div class="slide fade">
                <img src="./img/20150617_171610_d1450.jpg" style="width : 100%" alt="Car 1">
              </div>
              <div class="slide fade">
                <img src="./img/20160205_170651_d1450.jpg" style="width : 100%" alt="Car 2">
              </div>
              <div class="slide fade">
                <img src="./img/IMG-20170826-WA0055_d1000.jpg" style="width : 100%" alt="Car 3">
              </div>
            -->  
              <!-- Add more slides as needed -->
              <?php
                // Database connection
                $conn = mysqli_connect("localhost", "root", "", "al-mukhtar");

                // Query to fetch menu items
                $query = "SELECT * FROM slideshow";

                // Execute the query
                $result = mysqli_query($conn, $query);

                // Loop through the menu items and generate the menu dynamically
                while ($row = mysqli_fetch_assoc($result)) {
                    $url = $row['img_link'];
                    

                    echo "<div class='slide fade'>
                    <img src='Al-MUKHTAR/upload/$url' style='width : 100%' />
                  </div>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
            </div>
          
            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>
          </div>
          
    
		</section>
		<section id="about">
            <div class="about-text">
                <h2>من نحن ؟</h2>
			<p>تقدم شركة المختار خدمة تأجير السيارات للسائحين والمواطنين والشركات بحضور المصداقية والأمانة والخدمة المميزة والسيارات الحديثة والأسعار المنافسة. تعمل شركة المختار في بيئة تنافسية قوية وتحديات تجارية وإقتصادية لخدمة جمهور طالبي السيارات السياحية

                جاءت فكرة تأسيس الشركة في العام 2008 وتأسست في نفس العام وباشرت أعمالها في العام 2011 بخمس سيارات وفي العام 2012 استملكت شركة المختار شركة الإمارة لتوسيع وزيادة نشاطها التجاري وخدمة أوسع طبقة من المواطنين
                
                تتميز شركة المختار بتقديم أرقى مستويات الخدمة بحضور الأمانة والمصداقية والإخلاص في أداء العمل وبتأجير أحدث أنواع السيارات وبأسعار منافسة
                </p>
			<a href="#" class="button">قراءة المزيد</a>
            </div>
			<div class="about-img">
                <img src="./img/المختار لـتاجير السيارات.png">    
            </div>

		</section>
    <section>
    <div class="section">
  <h2>شركات المختار</h2>
  <div class="companies-container">
    <div class="company">
      <a href="https://www.mukhtar.ps/">
        <img src="img/Logo_d1450.png" alt="المختار لتجارة وتأجير السيارات" >
        <h3>المختار لتجارة وتأجير السيارات</h3>
      </a>
    </div>
    <div class="company">
      <a href="http://coupons.ps/">
        <img src="img/CouponIcon_d200.png" alt="كوبونات فلسطين" wid>
        <h3>كوبونات فلسطين</h3>
      </a>
    </div>
    <div class="company">
      <a href="http://akarat.ps/">
        <img src="img/image_6.jpg" alt="عقارات فلسطين" >
        <h3>عقارات فلسطين</h3>
      </a>
    </div>
  </div>
</div>
    </section>
	</main>
	<footer>
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <h3>من نحن ؟</h3>
              <p>تقدم شركة المختار خدمة تأجير السيارات للسائحين والمواطنين والشركات بحضور المصداقية والأمانة والخدمة المميزة والسيارات الحديثة والأسعار المنافسة</p>
            </div>
            <div class="col-md-4">
              <h3>الخدمات</h3>
              <ul>
                <li><a href="#">تجارة السيارات</a></li>
                <li><a href="#">تاجير السيارات</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <h3>تواصل معنا</h3>
              <ul>
                <li><i class="fas fa-map-marker-alt"></i> شارع عسكر - نابلس - فلسطين                </li>
                <li><i class="fas fa-phone"></i> +970 598 966944</li>
                <li><i class="fas fa-envelope"></i> mukhtar.motor@gmail.com</li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
      
	<script src="main.js"></script>
    <script src="nav.js"></script>
    <script src="footer.js"></script>


</body>
</html>
