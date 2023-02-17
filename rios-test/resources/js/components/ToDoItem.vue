<template>
  <div>
    <div>
      <span>
        <input v-model="item.item_text" v-on:change="updateItem"/>
      </span>
      <input class="checkbox" type="checkbox" v-model="item.complete" v-on:change="updateItem"/>
      <!--button v-on:click="moveItemUp">⇡</button>
        <button v-on:click="moveItemDown">⇣</button-->
        <button v-on:click="makeChild">→</button>
        <button v-on:click="deleteItem">-</button>
        <button v-on:click="toggleCreate">+</button>
    </div>
    <div v-if="showCreate">
         <input v-model="itemText" v-on:keyup.enter="addChild"/>
         <button v-on:click="addChild">+</button>
    </div>
    <slot>
    </slot>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: [
    'item'
  ],
  data () {
    return {
      showCreate: false,
      itemText: ''
    }
  },
  /* this might not be necessary */
  watch: {
    item: {
      handler: function () {
        /* save or delete here */
        /* on delete, emit onDelete so refresh can happen */
      },
      deep: true
    }
  },
  methods: {
    /* run this on text change or completed change */
    updateItem: function () {
      axios.put('/api/item/' + this.item.id, this.item);
    },
    moveItemUp: function () {
      axios.patch('/api/item/swap/' + this.item.prev + '/' + this.item.id)
      this.$emit('refresh')
    },
    moveItemDown: function () {
      axios.patch('/api/item/swap/' + this.item.id + '/' + this.item.next)
      this.$emit('refresh')
    },
    deleteItem: function() {
      let delcheck = window.confirm('Are you sure you want to delete?')
      if (delcheck) {
        axios.delete('/api/item/' + this.item.id);
        this.$emit('refresh');
      }
    },
    makeChild: function () {
      axios.patch('/api/item/makechild/' + this.item.prev + '/' + this.item.id)
      this.$emit('refresh')
    },
    toggleCreate: function () {
      this.showCreate = !this.showCreate
    },
    addChild: function () {
      axios.post('/api/item', {
        item_text: this.itemText,
        parent: this.item.id,
        complete: false
      }).then(res => {
        this.itemText = ''
        this.$emit('refresh')
      })
    }
  }
}
</script>
