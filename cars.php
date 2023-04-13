<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="slide.css">
<title>المختار | قسم السيارات </title>

<link rel="icon" type="image/x-icon" href="Logo.ico">

</head>
<body>
<?php include 'navbar.php' ?>
<br><br><br><br><br>
    <section class="cars">
    <h2 style="text-align:center">معرض المختار لتجارة  وتاجير السيارات</h2>

<div class="row">
 <!-- <div class="column">
    <img src="img/20150617_171610_d1450.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
  </div>
  <div class="column">
    <img src="img/20160205_170651_d1450.jpg" style="width:100%" onclick="openModal();currentSlide(2)" class="hover-shadow cursor">
  </div>
  <div class="column">
    <img src="img/IMG-20170826-WA0055_d1000.jpg" style="width:100%" onclick="openModal();currentSlide(3)" class="hover-shadow cursor">
  </div>
  <div class="column">
    <img src="img/MTR-Company_d1000.jpg" style="width:100%" onclick="openModal();currentSlide(4)" class="hover-shadow cursor">
  </div>
              -->
              <?php
                // Database connection
                $conn = mysqli_connect("localhost", "root", "", "al-mukhtar");

                // Query to fetch menu items
                $query = "SELECT * FROM cars";

                // Execute the query
                $result = mysqli_query($conn, $query);

                // Loop through the menu items and generate the menu dynamically
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $url = $row['car_img'];
                    $counter = 0;
                   

                    
                    echo "<div class='column'>
                    <img src='Al-MUKHTAR/upload/$url' style='width:100%;height:200px' onclick='openModal();currentSlide(".$counter++.")' class='hover-shadow cursor'>
                  </div>";
                }

                // Close database connection
                mysqli_close($conn);
                ?>
</div>

<div id="myModal" class="modal">
  <span class="close cursor" onclick="closeModal()">&times;</span>
  <div class="modal-content">

    <!-- <div class="mySlides">
      <div class="numbertext">1 / 4</div>
      <img src="img/20150617_171610_d1450.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">2 / 4</div>
      <img src="img/20160205_170651_d1450.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">3 / 4</div>
      <img src="img/IMG-20170826-WA0055_d1000.jpg" style="width:100%">
    </div>
    
    <div class="mySlides">
      <div class="numbertext">4 / 4</div>
      <img src="img/MTR-Company_d1000.jpg" style="width:100%">
    </div> -->
    <?php
                // Database connection
                $conn = mysqli_connect("localhost", "root", "", "al-mukhtar");

                // Query to fetch menu items
                $query = "SELECT * FROM modal";

                // Execute the query
                $result = mysqli_query($conn, $query);
                $counter = 1;

                  while ($row = mysqli_fetch_assoc($result) and $counter < 15) {
                    $id = $row['id'];
                    $url = $row['slide_src'];
                    $num = $row['num_slide'];
                

                // Loop through the menu items and generate the menu dynamically
                    echo "<div class='mySlides'>
                    <div class='numbertext'> ".$counter++."</div>
                    <img src='Al-MUKHTAR/upload/$url' style='width:100%'>
                  </div>";
                }

                // Close database connection
               
    
  echo "  <a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
    <a class='next' onclick='plusSlides(1)'>&#10095;</a>";
 mysqli_close($conn);
               ?>
    <div class="caption-container">
      <p id="caption"></p>
    </div>


    <div class="column">
      <img class="demo cursor" src="img/20150617_171610_d1450.jpg" style="width:100%" onclick="currentSlide(1)" alt="Nature and sunrise">
    </div>
    <div class="column">
      <img class="demo cursor" src="img/20160205_170651_d1450.jpg" style="width:100%" onclick="currentSlide(2)" alt="Snow">
    </div>
    <div class="column">
      <img class="demo cursor" src="img/IMG-20170826-WA0055_d1000.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">
    </div>
    <div class="column">
      <img class="demo cursor" src="img/MTR-Company_d1000.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">
    </div>
  </div>
</div>
    </section>

    
    <script >
      function openModal() {
    document.getElementById("myModal").style.display = "block";
  }
  
  function closeModal() {
    document.getElementById("myModal").style.display = "none";
  }
  
  var slideIndex = 1;
  showSlides(slideIndex);
  
  function plusSlides(n) {
    showSlides(slideIndex += n);
  }
  
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }
  
  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
  }
    </script>

</body>
</html>