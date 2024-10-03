<template>        
    <main class="h-screen flex items-center">
        <router-view></router-view>
        <div class="max-w-md mx-auto w-full">
            <div class="bg-sky-300 p-6">
            <h1 class="logo text-center">AppToDo</h1>
            <form @submit.prevent="submitLogin" class="form">
                <div class="campo">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" v-model="email" placeholder="Email">
                </div>
                <div class="campo">
                    <label class="label" for="password">Password</label>
                    <input class="input" type="password" v-model="password" placeholder="Password">
                </div>
                <inputError v-for="item in respuestaErrores" :message="item[0]"></inputError>
                <div class="flex justify-between">
                    <input class="submit" value="Login" type="submit">
                    <router-link to="/register" class="mt-2 underline"> Registrarse </router-link>
                </div>
            </form>
            </div>
        </div>
    </main>
</template>

<script>

import axios from 'axios';
import inputError from '../Components/InputError.vue'

export default {
    components: {
        inputError
    }, 
  name: "Login",
  data(){
    return {
        email: '',
        password: '',
        respuestaErrores : '',
    }
  },
  methods: {
    async submitLogin(){

        try {
            const response = await axios.post('api/login', {
                email: this.email,
                password: this.password
            })

            localStorage.setItem('token', response.data.personal_token);

            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.personal_token}`;

            this.$store.commit('setUser', response.data.user )

            this.$router.push('/');

        } catch (error) {
            this.respuestaErrores = error.response.data.errors;
        }
        
    }
  }


};
</script>