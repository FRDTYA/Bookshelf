// hide and unhide password
const inputIcons = document.querySelectorAll(".input__icon");
const inputPasswords = document.querySelectorAll(".input__field");

inputIcons.forEach((inputIcon, index) => {
  inputIcon.addEventListener("click", () => {
    inputIcon.classList.toggle("ri-eye-off-line");
    inputIcon.classList.toggle("ri-eye-line");
    inputPasswords[index].type =
      inputPasswords[index].type === "password" ? "text" : "password";
  });
});


// change color
let btn = document.getElementById("btn");
let book = document.getElementById("book");
let logo = document.getElementById("logo");
let eye = document.getElementById("eye");
let eyes = document.getElementById("eyes");

function changeColor(color) {
  document.body.style.background = color;
  btn.style.background = color;
  book.style.color = color;
  logo.style.color = color;
  eye.style.color = color;
  eyes.style.color = color;

  // mark as active selected color
  document.querySelectorAll("span").forEach(function (item) {
    item.classList.remove("active");
  });
  event.target.classList.add("active");
}
