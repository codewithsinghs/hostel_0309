<template>
    <div>
        <SiteHeader />

        <main>
            <router-view />
        </main>

        <SiteFooter />
    </div>
</template>

<script>
import SiteHeader from "../components/Header.vue";
import SiteFooter from "../components/Footer.vue";

export default {
    components: { SiteHeader, SiteFooter },
};
</script>
