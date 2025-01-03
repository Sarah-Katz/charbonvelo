let slideIndex = 1;

document.addEventListener("DOMContentLoaded", () => {
  showSlides(slideIndex);

  // Navigation boutons
  document.querySelector(".prev").addEventListener("click", () => changeSlide(-1));
  document.querySelector(".next").addEventListener("click", () => changeSlide(1));

  // Dots pour navigation
  document.querySelectorAll(".dot").forEach((dot, index) => {
    dot.addEventListener("click", () => goToSlide(index + 1));
  });
});

function changeSlide(n) {
  showSlides((slideIndex += n));
}

function goToSlide(n) {
  showSlides((slideIndex = n));
}

function showSlides(n) {
  const slides = document.querySelectorAll(".mySlides");
  const dots = document.querySelectorAll(".dot");

  // Ajuster l'index
  if (n > slides.length) slideIndex = 1;
  if (n < 1) slideIndex = slides.length;

  // Masquer tous les slides
  slides.forEach((slide) => slide.classList.add("hidden"));

  // Réinitialiser les dots
  dots.forEach((dot) => {
    dot.classList.remove("bg-primary");
    dot.classList.add("bg-secondary");
  });

  // Afficher le slide actuel et activer son dot
  slides[slideIndex - 1].classList.remove("hidden");
  dots[slideIndex - 1].classList.add("bg-primary");
  dots[slideIndex - 1].classList.remove("bg-secondary");
}
