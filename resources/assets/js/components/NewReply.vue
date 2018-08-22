<template>

    <div>
        <div v-if="signedIn">
            <div class="form-group">
                        <textarea name="body"
                                  id="body"
                                  class="form-control"
                                  placeholder="Have something to say?"
                                  rows="5"
                                  required
                                  v-model="body"> </textarea>
            </div>

            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>

        </div>

        <p class="text-center " v-else>
            Please <a href="/login">SignIn</a> to be part of the
            discussion...</p>

    </div>

</template>

<script>
    export default {
        data(){
            return {
                body: '',
            }
        },
        computed: {
            signedIn(){
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                    .then(({ data }) => {
                        this.body = '';
                        flash('Your Reply has been posted');
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>