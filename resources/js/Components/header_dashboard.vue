<template>

<header class="bg-sky-200 py-4 px-2">
    <Logo></Logo>

    <nav class="flex flex-col max-w-md mx-auto">
        <router-link to="/" class="nav-link"> Home </router-link>
        <router-link to="/notes/crear" class="nav-link"> Crear nota </router-link>
        <form class="nav-link" @submit="logout">
            <input type="submit" value="Logout">
        </form>
    </nav>
    

</header>

</template>

<script>

import Logo from '../Components/logo.vue';
import axios from 'axios';
import { mapGetters } from 'vuex';

export default{
    computed:{
        ...mapGetters(['getUser']),
    },
    name: "header_dashboard",
    components : {
        Logo,
    },
    methods: {
        async logout(){
            try {
                const response = await axios.post(`/api/logout`);
                localStorage.removeItem('token');

                this.$router.push('/login');
            } catch (error) {
                console.error('Error fetching notes:', error);
            }
        }
    }
}
</script>