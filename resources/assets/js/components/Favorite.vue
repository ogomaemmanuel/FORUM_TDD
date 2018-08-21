<template>

    <button type="submit" :class="classes" @click="toggle">

        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props:["reply"],
        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited
            }
        },
        methods:{
            toggle(){
                 this.active? this.destroy():this.create()
            },

            destroy(){
                axios.delete(this.endPoint)
                this.active=false;
                this.count --;
            },

            create(){
                axios.post(this.endPoint)
                this.active=true;
                this.count ++;
            }
        },


        computed: {
            classes(){
                return ['btn',this.active ? 'btn-primary':'btn-default']
            },

            endPoint(){
                return "/replies/"+this.reply.id+"/favorites"
            }
        }

    }
</script>