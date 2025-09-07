// header section javascript
function toggleMenu() {
    const menu = document.getElementById("mobileMenu");
    menu.classList.toggle("show");
}

// document.addEventListener("DOMContentLoaded", () => {
//     // Login Popup functionality
//     const loginBtn = document.getElementById("btn-login");
//     const loginPopup = document.getElementById("loginPopup");
//     const closePopupBtn = document.getElementById("closePopup");
//     const tabBtns = document.querySelectorAll(".tab-btn");
//     const formPanels = document.querySelectorAll(".xfw-form-panel");
//     const formWrapper = document.querySelector(".xfw-form-wrapper");

//     // Note: The login button is not in the provided HTML, so this might not work
//     // if you don't have an element with id="btn-login" to open the popup.
//     if (loginBtn) {
//         loginBtn.addEventListener("click", () => {
//             loginPopup.style.display = "flex";
//         });
//     }

//     closePopupBtn.addEventListener("click", () => {
//         loginPopup.style.display = "none";
//     });

//     tabBtns.forEach((btn) => {
//         btn.addEventListener("click", () => {
//             // Remove 'active' from all buttons and add to the clicked one
//             tabBtns.forEach((b) => b.classList.remove("active"));
//             btn.classList.add("active");

//             const targetTab = btn.dataset.tab;

//             // Remove 'xfw-active-form' from all panels
//             formPanels.forEach((panel) => {
//                 panel.classList.remove("xfw-active-form");
//             });

//             // Add 'xfw-active-form' to the target panel
//             if (targetTab === "student") {
//                 document
//                     .querySelector(".xfw-student-form")
//                     .classList.add("xfw-active-form");
//             } else if (targetTab === "staff") {
//                 document
//                     .querySelector(".xfw-staff-form")
//                     .classList.add("xfw-active-form");
//             }

//             // Slide the form wrapper container
//             if (targetTab === "staff") {
//                 formWrapper.style.transform = "translateX(-50%)";
//             } else {
//                 formWrapper.style.transform = "translateX(0)";
//             }
//         });
//     });

//     // Initialize by ensuring the student form is visible by default
//     document
//         .querySelector(".xfw-student-form")
//         .classList.add("xfw-active-form");
//     formWrapper.style.transform = "translateX(0)";
// });



    let lastScrollY = window.scrollY;
    let timeoutId = null;
    const header = document.querySelector('.main-header');

    window.addEventListener('scroll', () => {
        if (window.scrollY > lastScrollY) {
            header.classList.add('header-hidden'); // hide
        } else {
            header.classList.remove('header-hidden'); // show
        }
        lastScrollY = window.scrollY;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            header.classList.remove('header-hidden'); // reappear when stop
        }, 250);
    });

