document.addEventListener("DOMContentLoaded", function () {
  if (localStorage.getItem("dark-mode") == "dark") {
    document.querySelector("html").classList.add("dark");
  }
  // document.querySelector("html").classList.add(localStorage.getItem("dark-mode"));
  document.querySelector("#btn-dark").addEventListener("click", function () {
    const darkMode = localStorage.getItem("dark-mode") === "dark" ? "light" : "dark";
    document.querySelector("html").classList = "";
    document.querySelector("html").classList.add(darkMode);
    localStorage.setItem("dark-mode", darkMode);
  });
  if (document.querySelector("#toast")) {
    setTimeout(() => {
      document.querySelector("#toast")?.remove();
    }, 3000);

    document.querySelector("#close-toast-btn").addEventListener("click", function () {
      this.closest("#toast").remove();
    });
  }
  document.querySelector("#mobile-btn")?.addEventListener("click", function (e) {
    document.querySelector("#mobile-search").classList.toggle("hidden");
    document.querySelector("#main-nav").classList.toggle("hidden");
  });
});
