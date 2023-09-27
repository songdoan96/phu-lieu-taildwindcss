document.addEventListener("DOMContentLoaded", function () {
  if (localStorage.getItem("dark-mode") == "dark") {
    document.querySelector("html").classList.add("dark");
  }
  // document.querySelector("html").classList.add(localStorage.getItem("dark-mode"));
  document.querySelector("#btn-dark")?.addEventListener("click", function () {
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

  // ----------------------------------
  // Show Order
  const btnShowOrder = document.querySelectorAll(".btn-show-order");
  btnShowOrder.forEach((el) => {
    el.addEventListener("click", async function (e) {
      const id = e.target.dataset.id;
      if (el.classList.contains("show-on")) {
        document
          .querySelectorAll(`tr[parent-id='${id}']`)
          .forEach((child) => child.classList.add("hidden"));
        el.classList.replace("show-on", "show-off");
        el.querySelector(".img-show").classList.remove("hidden");
        el.querySelector(".img-hidden").classList.add("hidden");
      } else {
        document
          .querySelectorAll(`tr[parent-id='${id}']`)
          .forEach((child) => child.classList.remove("hidden"));
        el.classList.add("show-on");
        el.querySelector(".img-show").classList.add("hidden");
        el.querySelector(".img-hidden").classList.remove("hidden");
      }
      return;
    });
  });

  // Delete item
  const btnDeleteItem = document.querySelectorAll(".btn-delete-item");
  btnDeleteItem.forEach((el) => {
    el.addEventListener("click", function () {
      document.querySelector("#modal-delete").classList.replace("hidden", "flex");
      document.querySelector("#form-delete #delete-id").value = this.dataset.id;
      const isHetPage = `<?= isset($_GET['het']) ?>`;
      if (isHetPage) {
        const parentId = el.closest("tr").getAttribute("parent-id");
        const inputEl = document.createElement("input");
        inputEl.type = "hidden";
        inputEl.name = "redo-sold-out";
        inputEl.value = parentId;
        document.querySelector("#form-delete").append(inputEl);
      }
    });
  });
  document.querySelector("#btn-close-modal")?.addEventListener("click", function () {
    document.querySelector("#modal-delete").classList.replace("flex", "hidden");
  });
});
