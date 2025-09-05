<template>
    <!-- hero section -->
    <section class="hero-section" id="hero-section">
        <div class="hero-container swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="../../front/img/silder-1.jpg" alt="Hostel 1" />
                </div>
                <div class="swiper-slide">
                    <img src="../../front/img/slider-2.jpg" alt="Hostel 2" />
                </div>
                <div class="swiper-slide">
                    <img src="../../front/img/slider-3.jpg" alt="Hostel 3" />
                </div>
            </div>

            <!-- Hero Content -->
            <div class="hero-content">
                <span class="tag">HOSTEL</span>
                <h1>Find Your Perfect<br />Hostel or PG with Ease</h1>
                <p>
                    Trusted options near universities with verified listings,
                    secure bookings, and transparent pricing.
                </p>
                <div class="hero-btns">
                    <button class="btn-register-slider">Register Now</button>
                    <div class="nav-arrows">
                        <button class="swiper-button-prev">&#10094;</button>
                        <button class="swiper-button-next">&#10095;</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- support card section -->
    <section class="support-section" id="support-section">
        <div class="support-grid">
            <!-- Card 1 -->
            <div class="support-card">
                <div class="support-icon">
                    <img
                        src="../../front/img/support-img-1.png"
                        alt="Verified Listings"
                    />
                </div>
                <h3>Verified Listings</h3>
                <p>
                    Access fully verified hostels and PGs near your university
                    for a safe and reliable stay.
                </p>
                <button class="read-more-btn">Read More</button>
            </div>

            <!-- Card 2 -->
            <div class="support-card">
                <div class="support-icon">
                    <img
                        src="../../front/img/support-img-2.png"
                        alt="Flexible Stay"
                    />
                </div>
                <h3>Flexible Stay Options</h3>
                <p>
                    Choose flexible stay plans — daily, monthly, or yearly — to
                    suit your lifestyle and budget.
                </p>
                <button class="read-more-btn">Read More</button>
            </div>

            <!-- Card 3 -->
            <div class="support-card">
                <div class="support-icon">
                    <img
                        src="../../front/img/support-img-3.png"
                        alt="Secure & Trusted"
                    />
                </div>
                <h3>Secure & Trusted</h3>
                <p>
                    Security-checked accommodations with genuine reviews and
                    safety ratings.
                </p>
                <button class="read-more-btn">Read More</button>
            </div>

            <!-- Card 4 -->
            <div class="support-card">
                <div class="support-icon">
                    <img
                        src="../../front/img/support-img-4.png"
                        alt="Maintenance Support"
                    />
                </div>
                <h3>Maintenance Support</h3>
                <p>
                    Quick maintenance for plumbing, electrical, or hygiene
                    issues via our trusted partners.
                </p>
                <button class="read-more-btn">Read More</button>
            </div>
        </div>
    </section>

    <!-- faq section  -->
    <section class="faq-section">
        <!-- <div class="faq-left-container"></div> -->
        <div class="faq-container">
            <div class="faq-left">
                <div class="accordion">
                    <div class="accordion-item active">
                        <button class="accordion-header">
                            How to Register ?
                        </button>
                        <div class="accordion-body">
                            <p>
                                You can register by filling out the hostel
                                registration form available at the hostel office
                                or on the official website . Submit the form
                                along with the required documents for
                                Verification.
                            </p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">How we work ?</button>
                        <div class="accordion-body">
                            <p>
                                We work through verified processes and clear
                                communication with all users.
                            </p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">
                            Why HOSTEL is the best?
                        </button>
                        <div class="accordion-body">
                            <p>
                                Because we provide verified listings, great
                                support, and flexible stay options.
                            </p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">
                            Do we get the best support?
                        </button>
                        <div class="accordion-body">
                            <p>
                                Yes, with 24/7 maintenance and communication
                                support.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq-right">
                <span>ABOUT US</span>
                <h2>What Make Us The Best Hostel ?</h2>
                <p>
                    Welcome to our hostel — a perfect blend of comfort, safety,
                    and convenience. We understand that choosing the right place
                    to stay is essential for a happy and stress-free life, which
                    is why we focus on providing the best facilities and a
                    homely environment for all our residents.
                </p>

                <p>
                    We offer spacious, well-furnished rooms designed to ensure
                    maximum comfort, along with 24/7 security and CCTV
                    surveillance to keep you safe. Our healthy and hygienic
                    meals are prepared fresh every day, keeping your well-being
                    our top priority. To support your studies and work, we
                    provide high-speed Wi-Fi and a peaceful atmosphere that
                    helps you stay focused and productive.
                </p>

                <a
                    href="Admin Dashboard/"
                    class="btn-login-submit"
                    type="submit"
                    >Register Now</a
                >
            </div>
        </div>
    </section>

    <!-- hostel list section  -->
    <section class="hostel-list-section" id="hostel-list-section">
        <h2 class="section-title">Hostel Lists</h2>
        <div class="hostel-filters-container hide-Scrollbar">
            <div class="hostel-filters">
                <button class="filter-btn active" data-type="all">
                    All Hostel
                </button>
                <button class="filter-btn" data-type="boys">Boys Hostel</button>
                <button class="filter-btn" data-type="girls">
                    Girls Hostel
                </button>
                <button class="filter-btn" data-type="guest">
                    Guest Hostel
                </button>
            </div>
        </div>

        <div class="hostel-grid">
            <!-- Example Card -->
            <div class="hostel-card" data-type="boys">
                <img src="../../front/img/hostel.jpg" alt="Hostel Image" />
                <div class="hostel-info">
                    <span class="hostel-type">Boys Hostel</span>
                    <h3 class="hostel-name">Hostel Name</h3>
                </div>
            </div>

            <div class="hostel-card" data-type="girls">
                <img src="../../front/img/hostel 2.jpg" alt="Hostel Image" />
                <div class="hostel-info">
                    <span class="hostel-type">Girls Hostel</span>
                    <h3 class="hostel-name">Hostel Name</h3>
                </div>
            </div>

            <div class="hostel-card" data-type="guest">
                <img src="../../front/img/hostel 3.jpg" alt="Hostel Image" />
                <div class="hostel-info">
                    <span class="hostel-type">Guest Hostel</span>
                    <h3 class="hostel-name">Hostel Name</h3>
                </div>
            </div>

            <!-- Add more cards with appropriate `data-type` -->
        </div>
    </section>

    <!-- testimonial section  -->
    <section class="main-page-padding">
        <section class="testimonial-section">
            <!-- <div class="faq-left-container"></div> -->
            <div class="faq-container">
                <div class="faq-left-testimonial">
                    <div class="testimonial-slider">
                        <div class="testimonial-wrapper">
                            <div class="testimonial-item active">
                                <p>
                                    “I found a clean and safe PG near my college
                                    within minutes. The process was smooth, and
                                    the support team was very helpful
                                    throughout.”
                                </p>
                                <div class="testimonial-user">
                                    <img
                                        src="https://i.pravatar.cc/100?img=1"
                                        alt="Anjali Sharma"
                                    />
                                    <div>
                                        <small>Student at RNTU Bhopal</small>
                                        <h4>Anjali Sharma</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-item">
                                <p>
                                    “HOSTEL helped me compare PGs easily. The
                                    reviews were really helpful, and I made my
                                    decision within an hour.”
                                </p>
                                <div class="testimonial-user">
                                    <img
                                        src="https://i.pravatar.cc/100?img=2"
                                        alt="Rahul Mehra"
                                    />
                                    <div>
                                        <small>Student at Amity</small>
                                        <h4>Rahul Mehra</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more testimonials here -->
                        </div>
                        <div class="slider-controls">
                            <button class="slider-btn prev">&#8249;</button>
                            <button class="slider-btn next">&#8250;</button>
                        </div>
                    </div>
                </div>

                <div class="faq-right">
                    <span>TESTIMONIALS</span>
                    <h2>What they say about us?</h2>
                    <p>
                        I found a clean and safe PG near my college within
                        minutes. The process was smooth, and the support team
                        was very helpful throughout
                    </p>
                    <button>Discover More</button>
                </div>
            </div>
        </section>
    </section>
</template>

<script setup>
import { ref } from "vue";

const features = ref([
    {
        title: "Accessories",
        description: "Browse available hostel accessories.",
        link: "/accessories",
    },
    {
        title: "Register",
        description: "Register for hostel accommodation online.",
        link: "/register",
    },
    {
        title: "Contact",
        description: "Get in touch with hostel management.",
        link: "/contact",
    },
]);
</script>
