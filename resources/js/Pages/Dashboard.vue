<template>
    <Header></Header>
  <Main>
      <router-view></router-view>
      <PopUp :index="activeIndex" v-if="isVisibileEdit" @close-modal="closeModal"></PopUp>
    
    <h2 class="letra-xl text-center mt-4">Notas</h2>

    <div class="flex flex-col">
        <label for="filtro">Filtrar por</label>
        <select @change="filtrar" v-model="filtro"  id="filtro">
            <option value="" disabled selected >--Selecciona un filtro--</option>
            <option value="sort_by_date=desc">Fecha de creacion ascendente </option>
            <option value="sort_by_date=asc">Fecha de creacion descendente </option>
        </select>
    </div>
    <ul class="contenedor-notas">
        <li v-for="(item, index) in notes" :key="item.id" class="my-4 ">
            <div @click="toggleInfo(index)" class="py-2 px-4 bg-sky-400 hover:bg-sky-500"> <h2 class="letra-lg text-center font-bold">{{ item.title }}</h2> </div>
            <div v-if="isActive(index)" class="bg-yellow-100 p-2">
                <div class="flex justify-between">
                    <span class="letra-xs pl-4 pr-2 py-1 bg-orange-300"><span class="font-bold">tag:</span> {{ item.tag }}</span>
                    <span class="letra-xs pl-4 pr-2 py-1 bg-orange-300"><span class="font-bold">Creado:</span> {{ formatear_fecha(item.created_at) }}</span>
                </div>
                <p class="my-4">{{ item.description }}</p>
                <div class="flex justify-between">
                    <button @click="showEdit(index)" class="button bg-orange-400 rounded-lg">Editar</button>
                    <button @click="eliminar_note" class="button bg-red-700">Eliminar</button>
                </div>
            </div>
        </li>
    </ul>
  </Main>
  
</template>
<script>

import Logo from '../Components/logo.vue';
import Header from '../Components/header_dashboard.vue';
import Main from '../Components/mainContainer.vue';
import PopUp from '../Components/popUpEdit.vue';

export default {
    name: "App",
    components: {
        Logo,
        Header,
        Main,
        PopUp
    },
    data(){
        return{
            notes : '',
            activeIndex: null,
            isVisibileEdit: false,
            filtro: '',
        }
    },
    async created(){
        this.getNotes();
    },
    methods: {
        formatear_fecha(rawDate){
            const date = new Date(rawDate);

            // Opciones de formateo
            const options = {
                year: 'numeric',
                month: 'short', // 'short' para abreviar el nombre del mes
                day: 'numeric',
            };

            // Formatear la fecha
            const formattedDate = date.toLocaleDateString('es-ES', options) + ' ' + 
                                date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            return formattedDate;
        },
        async filtrar(){
            try {
                const response = await axios.get(`/api/notes?${this.filtro}`);
                this.notes = response.data.notes; // Asigna las notas a la variable local
                this.$store.commit('setNotes', response.data.notes );
            } catch (error) {
                console.error('Error fetching notes:', error);
                // Aquí puedes manejar errores, como mostrar un mensaje al usuario
            }
        },
        async eliminar_note(){
            try {
                const response = await axios.delete(`/api/notes/${this.notes[this.activeIndex].id}`);
                this.$store.commit('deleteNote', this.activeIndex );

            } catch (error) {
                console.error('Error fetching notes:', error);
                // Aquí puedes manejar errores, como mostrar un mensaje al usuario
            }
        },
        closeModal(){
            this.isVisibileEdit = false;
        },
        isActive(index){
            return this.activeIndex === index;
        },
        toggleInfo(index){
            this.activeIndex = this.activeIndex === index ? null : index;
        },
        async getNotes() {
            try {
                const response = await axios.get('api/notes/');
                this.notes = response.data.notes; // Asigna las notas a la variable local
                this.$store.commit('setNotes', response.data.notes );
            } catch (error) {
                console.error('Error fetching notes:', error);
                // Aquí puedes manejar errores, como mostrar un mensaje al usuario
            }
        },
        showEdit(index){
            this.isVisibileEdit = true;
        }
    }
}
</script>