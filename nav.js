const hamburgerMenu = document.querySelector(".hamburger-menu");
    const navbarMenu = document.querySelector(".navbar-menu");
    
    hamburgerMenu.addEventListener("click", () => {
      hamburgerMenu.classList.toggle("active");
      navbarMenu.classList.toggle("active");
    });
    
    // Add hover effect to navbar links
    const navLinks = document.querySelectorAll(".navbar-menu a");
    
    navLinks.forEach((link) => {
      link.addEventListener("mouseover", () => {
        link.style.color = "#fff";
        link.style.backgroundColor = "#000";
        link.style.transition = "all 0.2s ease-out";
      });
    
      link.addEventListener("mouseout", () => {
        link.style.color = "#000";
        link.style.backgroundColor = "#fff";
        link.style.transition = "all 0.2s ease-out";
      });
    });
    
    // Add hover effect to logo
    const logo = document.querySelector(".logo");
    
    logo.addEventListener("mouseover", () => {
      logo.style.color = "#fff";
      logo.style.backgroundColor = "#000";
      logo.style.transition = "all 0.2s ease-out";
    });
    
    logo.addEventListener("mouseout", () => {
      logo.style.color = "#000";
      logo.style.backgroundColor = "#fff";
      logo.style.transition = "all 0.2s ease-out";
    });