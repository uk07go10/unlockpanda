function toggleMenu() {
    const mobileMenu = document.getElementById("mobile-responsive-menu");

    const mobileOpenIcon = document.getElementById("open-mobile-menu");
    const mobileCloseIcon = document.getElementById("close-mobile-menu");

    if (mobileMenu.style.display === "none") {
      mobileMenu.style.display = "flex";
      mobileOpenIcon.style.display = "none";
      mobileCloseIcon.style.display = "block";
    } else {
      mobileMenu.style.display = "none";
      mobileOpenIcon.style.display = "block";
      mobileCloseIcon.style.display = "none";
    }
  }