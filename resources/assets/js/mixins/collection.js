export default {
    data(){

        return{
            items:[]
        }

    },

    methods:{
        remove(item) {
            this.items.splice(item, 1);
            this.$emit('removed');
            flash("Reply was deleted!")
        },
        add(item) {
            this.items.push(item);
            this.$emit("added");
        }
    }
}