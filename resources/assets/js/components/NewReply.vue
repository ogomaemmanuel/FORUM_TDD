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
                                      v-model="body"></textarea>
            </div>
            <button type="submit"
                    @click="addReply"
                    class="btn btn-default">Post
            </button>

        </div>
        <!--</form>-->
        <!--@else-->
        <!--<p class="text-center">Please <a href="{{route('login')}}"> Sign in </a> to participate in this discussion</p>-->
        <!--@endif-->
    </div>
</template>

<script>
    export default {
        props: ["endpoint"],
        data() {
            return {
                body: '',

            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, {body: this.body}).then(({data}) => {
                    this.body = '';

                    flash("Your Reply has been posted.")

                    this.$emit("created", data);
                })
            }
        }

    }
</script>