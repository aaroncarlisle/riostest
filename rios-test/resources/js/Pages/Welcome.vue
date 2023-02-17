<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="TODO - the app" />

     <div>
       Hello.
       <!-- this needs to be just a field and and add  -->
       <!--ToDoItem :edit="true" :item="editItem" /-->
       <div>
         <input v-model="itemText" /> <button v-on:click="addItem">+</button>
       </div>
       <ol>
         <li v-for="item in items">
           <ToDoItem :item="item" v-on:refresh="getItems" />
         </li>
       </ol>
    </div>
</template>

<script>
import axios from 'axios'
import ToDoItem from '@/components/ToDoItem.vue'

export default {
  components: {
    ToDoItem
  },
  data () {
    return {
      items: [],
      /*
      editItem: {
        itemText: ''
      }
      */
      itemText: ''
    }
  },
  mounted () {
    this.getItems()
  },
  methods: {
    getItems: function () {
      axios.get('/api/item').then(res=> {
        console.log(res)
        this.items = res.data
      })
    },
    addItem: function () {
      axios.post('/api/item', {
        item_text: this.itemText,
        complete: false
      }).then(res => {
        this.itemText = ''
        this.getItems()
      })
    }
  }
}
</script>
