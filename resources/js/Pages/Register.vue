<template>        
    <main class="h-screen flex items-center">
        <router-view></router-view>
        <div class="max-w-md mx-auto w-full">
            <div class="bg-sky-300 p-6">
            <h1 class="logo text-center">AppToDo</h1>
            <form @submit.prevent="submitLogin" class="form">
                <h1 class="font-bold">Crea tu cuenta nueva!</h1>
                <div class="campo">
                    <label class="label" for="name">Nombre</label>
                    <input class="input" id="name" type="text" v-model="name" placeholder="nombre">
                </div>
                <div class="campo">
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" type="email" v-model="email" placeholder="Email">
                </div>
                <div class="campo">
                    <label class="label" for="password">Password</label>
                    <input class="input" type="password" v-model="password" placeholder="Password">
                </div>
                <div class="campo">
                    <label class="label" for="password2">Confirma la password</label>
                    <input class="input" id="password2" type="password" v-model="password_confirmation" placeholder="Confirmar password">
                </div>
                <inputError v-for="item in respuestaErrores" :message="item[0]"></inputError>
                <input class="submit" value="Registrarse" type="submit">
                <router-link to="/" class="mt-2 underline"> Ya tienes cuenta? Click aqui </router-link>
            </form>
            </div>
        </div>
    </main>
</template>

<script>

import axios from 'axios';
import inputError from '../Components/InputError.vue';
import { mapMutations } from 'vuex';


export default {
    computed: {
        ...mapMutations(['setUser'])
    },
    name: "Login",
    components: {
        inputError,
    },    
    data(){
        return {
            email: '',
            password: '',
            respuesta : '',
            password_confirmation: '',
            respuestaErrores : [],
            name: '',
        }
    },
    methods: {
        async submitLogin(){

            try {
                const response = await axios.post('/api/register', {
                    email: this.email,
                    password: this.password,
                    name: this.name,
                    password_confirmation: this.password_confirmation,
                })

                localStorage.setItem('token', response.data.personal_token);

                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.personal_token}`;

                this.$store.commit('setUser', response.data.user )

                this.$router.push('/');

            } catch (error) {
                this.respuesta = error.response.data.message;
                this.respuestaErrores = error.response.data.errors;
                console.log(this.respuesta);
            }
            
        }
   }


};
</script>