document.addEventListener("DOMContentLoaded", () => {
    // --- Hero Section (Swiper) ---
    if (document.querySelector(".swiper")) {
        const swiper = new Swiper(".swiper", {
            loop: true,
            speed: 600,
            autoplay: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            effect: "slide",
        });
    }

    // --- FAQ Section ---
    const accheaders = document.querySelectorAll(".accordion-header");
    if (accheaders.length) {
        accheaders.forEach((accheader) => {
            accheader.addEventListener("click", () => {
                const item = accheader.parentElement;
                document.querySelectorAll(".accordion-item").forEach((i) => {
                    if (i !== item) i.classList.remove("active");
                });
                item.classList.toggle("active");
            });
        });
    }

    // --- Hostel List Section ---
    const filterButtons = document.querySelectorAll(".filter-btn");
    const hostelCards = document.querySelectorAll(".hostel-card");

    if (filterButtons.length && hostelCards.length) {
        filterButtons.forEach((button) => {
            button.addEventListener("click", () => {
                filterButtons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");

                const type = button.getAttribute("data-type");
                hostelCards.forEach((card) => {
                    card.style.display =
                        type === "all" || card.getAttribute("data-type") === type
                            ? "block"
                            : "none";
                });
            });
        });
    }

    // --- Testimonial Section ---
    const hprevBtn = document.querySelector(".slider-btn.prev");
    const hnextBtn = document.querySelector(".slider-btn.next");
    const htestimonials = document.querySelectorAll(".testimonial-item");

    if (hprevBtn && hnextBtn && htestimonials.length) {
        let current = 0;

        function showTestimonial(index) {
            htestimonials.forEach((item, i) => {
                item.classList.toggle("active", i === index);
            });
        }

        hprevBtn.addEventListener("click", () => {
            current = (current - 1 + htestimonials.length) % htestimonials.length;
            showTestimonial(current);
        });

        hnextBtn.addEventListener("click", () => {
            current = (current + 1) % htestimonials.length;
            showTestimonial(current);
        });

        showTestimonial(current);
    }
});
