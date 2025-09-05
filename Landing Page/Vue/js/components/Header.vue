<template>
    <!-- header section  -->
    <header class="main-header">
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

<script setup>
import { ref } from "vue";

const searchQuery = ref("");

const search = () => {
    if (searchQuery.value.trim()) {
        alert(`Searching for: ${searchQuery.value}`);
    }
};
</script>
