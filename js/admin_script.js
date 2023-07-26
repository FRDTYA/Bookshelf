let navbar = document.querySelector(".header .flex .navbar");
// let accountBox = document.querySelector('.header .account-box');

document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  profile.classList.remove("active");
};

let profile = document.querySelector(".header .flex .profile");

document.querySelector("#user-btn").onclick = () => {
  profile.classList.toggle("active");
  navbar.classList.remove("active");
};

// document.querySelector('#user-btn').onclick = () =>{
//     accountBox.classList.toggle('active');
//     navbar.classList.remove('active');
// }

window.onscroll = () => {
  navbar.classList.remove("active");
  profile.classList.remove("active");
};

document.querySelector("#close-update").onclick = () => {
  document.querySelector(".edit-product-form").style.display = "none";
  window.location.href = "admin_products.php";
};

let formattedPrice = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
}).format(price);
document.getElementById("price").textContent = formattedPrice;

function updatePaymentStatus(select) {
  var box = select.parentElement.parentElement;
  var status = select.value;

  if (status == "pending") {
    box.classList.add("pending");
    box.classList.remove("completed");
  } else if (status == "completed") {
    box.classList.add("completed");
    box.classList.remove("pending");
  } else {
    box.classList.remove("pending");
    box.classList.remove("completed");
  }

  box.classList.add("updated");
};

var swiper = new Swiper(".books-slider", {
  loop: true,
  centeredSlides: true,
  autoplay: {
    delay: 9500,
    disableOnInteraction: false,
  },
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 3,
    },
  },
});

// var swiper = new Swiper(".books-slider", {
//   loop: true,
//   centeredSlides: true,
//   autoplay: {
//       delay: 3000,
//       disableOnInteraction: false,
//   },
//   pagination: {
//       el: ".swiper-pagination",
//       clickable: true,
//   },
// });

