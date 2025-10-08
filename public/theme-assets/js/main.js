
function drawerHandler() {
  if ($(".mobile-drawer").hasClass("active")) {
    $(".mobile-drawer").removeClass("active");
  } else {
    $(".mobile-drawer").addClass("active");
  }
}
function openPriority(e, priority) {
  let i, blogItem, filterBtn;
  blogItem = document.querySelectorAll(".blog-content");
  for (i = 0; i < blogItem.length; i++) {
    blogItem[i].classList.remove("active");
  }
  filterBtn = document.querySelectorAll(".filter-btn");
  for (i = 0; i < filterBtn.length; i++) {
    filterBtn[i].className = filterBtn[i].className.replace(" active", "");
  }
  document.getElementById(priority).classList.add("active");
  e.currentTarget.className += " active";
}
function elisc_tm_cursor() {
  var myCursor = $(".mouse-cursor");
  if (myCursor.length) {
    if ($("body")) {
      const e = document.querySelector(".cursor-inner"),
        t = document.querySelector(".cursor-outer");
      let n,
        i = 0,
        o = !1;
      (window.onmousemove = function (s) {
        o ||
          (t.style.transform =
            "translate(" + s.clientX + "px, " + s.clientY + "px)"),
          (e.style.transform =
            "translate(" + s.clientX + "px, " + s.clientY + "px)"),
          (n = s.clientY),
          (i = s.clientX);
      }),
        $("body").on("mouseenter", "a, .cursor-pointer", function () {
          e.classList.add("cursor-hover"), t.classList.add("cursor-hover");
        }),
        $("body").on("mouseleave", "a, .cursor-pointer", function () {
          ($(this).is("a") && $(this).closest(".cursor-pointer").length) ||
            (e.classList.remove("cursor-hover"),
              t.classList.remove("cursor-hover"));
        }),
        (e.style.visibility = "visible"),
        (t.style.visibility = "visible");
    }
  }
}
elisc_tm_cursor();

function videoPopup() {
  const videoContainer = $(".popv-main-wrapper");
  if (videoContainer.hasClass("active")) {
    videoContainer.removeClass("active");
  } else {
    videoContainer.addClass("active");
  }
}
document.addEventListener("scroll", () => {
  const element = document.querySelector(".navigation-wrapper");
  if (element) {
    if (
      document.body.scrollTop > 100 ||
      document.documentElement.scrollTop > 100
    ) {
      element.classList.add("sticky");
    } else {
      element.classList.remove("sticky");
    }
  }
});
