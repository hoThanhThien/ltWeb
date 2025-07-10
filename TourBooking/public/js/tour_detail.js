document.addEventListener("DOMContentLoaded", () => {
  const previews = document.querySelectorAll(".gallery-preview img");
  const mainImg = document.querySelector(".main-image");

  previews.forEach((img) => {
    img.addEventListener("click", () => {
      mainImg.src = img.src;
      previews.forEach((i) => i.classList.remove("active"));
      img.classList.add("active");
    });
  });

  
  const tabs = document.querySelectorAll(".tab-list li");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");

      const id = this.getAttribute("onclick").match(/'(.*?)'/)[1];
      contents.forEach((c) => (c.style.display = "none"));
      document.getElementById(id).style.display = "block";
    });
  });
});
