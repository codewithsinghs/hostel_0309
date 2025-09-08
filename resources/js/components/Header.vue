<!-- header section  -->
<!--
    <template> <header class="main-header">
        <nav class="navbar navbar-expand-lg nav-container">

            <div class="nav-container-left">
                <a href="index.html" class="">
                    <div class="navbar-brand register-header-logo">
                        <img src="../../front/img/main-logo.png" alt="RNTU Logo" />
                    </div>
                </a>
                <div class="search-bar">
                    <input type="text" placeholder="Type Something" />
                    <button>
                        <img src="https://img.icons8.com/ios-filled/20/000000/search--v1.png" alt="Search" />
                    </button>
                </div>
            </div>

            <button class="hamburger" type="button" data-bs-toggle="collapse" data-bs-target="#headercollape"
                aria-controls="headercollape" aria-expanded="false" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end " id="headercollape">
                <div class="nav-container-right">
                    <ul class="nav-links">
                        <li><a href="#hero-section" class="nav-link">Home</a></li>
                        <li><a href="#support-section" class="nav-link">Services</a></li>
                        <li><a href="#hostel-list-section" class="nav-link">Hostels</a></li>
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/accessories">Accessories</a></li>
            <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                    </ul>

                    <div class="nav-btns">
                        <button class="btn-custom btn-register"> <a href="{{ route('guest.register') }}">Registration</a></button>
                        <button class="btn-custom btn-register"><a href="#">Login</a> </button>
                    </div>
                </div>
            </div>

        </nav>

    </header> 
  </template>  
  -->

<template>
    <header class="main-header" :class="{ 'header-hidden': isHidden }">
        <nav class="navbar navbar-expand-lg nav-container p-4">
            <div class="nav-container-left">
                <router-link to="/" class="navbar-brand register-header-logo">
                    <img src="../../front/img/main-logo.png" alt="RNTU Logo" />
                </router-link>

                <div class="search-bar">
                    <input
                        type="text"
                        placeholder="Type Something"
                        v-model="searchQuery"
                    />
                    <button @click="search">
                        <img
                            src="https://img.icons8.com/ios-filled/20/000000/search--v1.png"
                            alt="Search"
                        />
                    </button>
                </div>
            </div>

            <!-- Hamburger -->
            <button
                class="hamburger"
                type="button"
                :class="{ active: isMenuOpen }"
                @click="toggleMenu"
            >
                <span></span><span></span><span></span>
            </button>

            <!-- Collapsible menu -->
            <div
                class="collapse navbar-collapse justify-content-end"
                :class="{ show: isMenuOpen }"
            >
                <div class="nav-container-right custom">
                    <ul class="nav-links">
                        <li>
                            <router-link
                                to="/"
                                class="nav-link"
                                @click="closeMenu"
                                >Home</router-link
                            >
                        </li>

                        <!-- Only on Home -->
                        <template v-if="isHome">
                            <li>
                                <a
                                    href="#support-section"
                                    class="nav-link"
                                    @click="closeMenu"
                                    >Services</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#hostel-list-section"
                                    class="nav-link"
                                    @click="closeMenu"
                                    >Hostels</a
                                >
                            </li>
                        </template>

                        <li>
                            <router-link
                                to="/about"
                                class="nav-link"
                                @click="closeMenu"
                                >About</router-link
                            >
                        </li>
                        <li>
                            <router-link
                                to="/contact"
                                class="nav-link"
                                @click="closeMenu"
                                >Contact</router-link
                            >
                        </li>
                    </ul>

                    <div class="nav-btns">
                        <button
                            class="btn-custom btn-register"
                            @click="closeMenu"
                        >
                            <router-link to="/guest/registration"
                                >Registration</router-link
                            >
                        </button>
                        <button
                            class="btn-custom btn-register"
                            @click="closeMenu"
                        >
                            <router-link to="/login">Login</router-link>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const isHome = computed(() => route.path === "/");

const searchQuery = ref("");
const search = () => {
    if (searchQuery.value.trim()) {
        alert(`Searching for: ${searchQuery.value}`);
    }
};

// Menu toggle
const isMenuOpen = ref(false);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
const closeMenu = () => {
    isMenuOpen.value = false;
    isHidden.value = false; // ✅ ensure header reappears
};

// Scroll hide/show header
const isHidden = ref(false);
let lastScrollY = window.scrollY;
let timeoutId;

const handleScroll = () => {
    // ✅ Don't hide header if menu is open
    if (isMenuOpen.value) return;

    if (window.scrollY > lastScrollY) {
        isHidden.value = true;
    } else {
        isHidden.value = false;
    }
    lastScrollY = window.scrollY;

    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        isHidden.value = false;
    }, 250);
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});
onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
/* Hamburger */
.hamburger {
    border: none;
    background: transparent;
    cursor: pointer;
    display: none;
}
.hamburger span {
    display: block;
    width: 25px;
    height: 3px;
    margin: 5px;
    background: #000;
    transition: all 0.3s;
}
.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}
.hamburger.active span:nth-child(2) {
    opacity: 0;
}
.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Show hamburger only below 991px */
@media (max-width: 991px) {
    .hamburger {
        display: block;
    }
}

/* Header hide animation */
.main-header {
    transition: top 0.3s;
    position: sticky;
    top: 0;
    z-index: 999;
}
.main-header.header-hidden {
    top: -100px;
}
</style>
