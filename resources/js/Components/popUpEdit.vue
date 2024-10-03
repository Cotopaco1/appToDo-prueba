<script>

import { mapGetters } from 'vuex';
import axios from 'axios';

export default{
    computed:{
        ...mapGetters(['getItem'])
    },

    data(){
        return {
            title : '',
            description : '',
            tag : '',
            note : '',
            respuesta : '',
            id : '',
        }
    },
    created(){
        this.obtener_nota();
    },
    methods: {
        async editar_nota(){
            try {
                const response = await axios.patch(`/api/notes/${this.id}`, {
                    title : this.title,
                    description: this.description,
                    tag: this.tag,
                });
                this.$store.commit('updateNote', response.data.note );
                this.closeModal();
            } catch (error) {
                console.error('Error fetching notes:', error);
                // Aquí puedes manejar errores, como mostrar un mensaje al usuario
            }
        },
        obtener_nota(){
            this.note = this.getItem(this.index);
            
            this.title = this.note.title;
            this.description = this.note.description;
            this.tag = this.note.tag;
            this.id = this.note.id;
        },
        closeModal(){
            this.$emit('close-modal');
        }
    },
    props : {
        index: {
            required: true,
            type : Number
        }
    }

}
</script>

<template>
    <div class="fixed inset-0 bg-slate-400/70 " @click="closeModal">
        <div class="h-full w-full flex items-center justify-center">
            <form @click.stop class="form p-4 bg-sky-200 max-w-md mx-auto w-full" @submit.prevent="editar_nota()" >
                <h2 class="letra-xl text-center mt-4 uppercase">Editar nota</h2>
            <div class="campo">
                <label class="label" for="title">Titulo</label>
                <input class="input" id="title" type="text" v-model="title" placeholder="Titulo">
            </div>
            <div class="campo">
                <label class="label" for="description">Description</label>
                <textarea id="description" v-model="description" placeholder="Description"></textarea>
            </div>
            <div class="campo">
                <label class="label" for="tag">Tag/Categoria</label>
                <input id="tag" v-model="tag" placeholder="Tag"></input>
            </div>
            <p v-if="respuesta">{{ respuesta }}</p>
            <div class="flex justify-between">
                <button class="button bg-red-700 font-bold" @click="closeModal">Cancelar</button>
                <input class="submit" value="Editar" type="submit">
            </div>
        </form>
        </div>
    </div>
</template>