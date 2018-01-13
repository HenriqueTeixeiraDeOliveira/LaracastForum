<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>

<script>
    import Reply from "./Reply.vue";

    export default {
        components: {Reply},
        props: ['data'],

        data() {
             return {
                 items: this.data
             }
        },

        methods: {
            remove(index) {
                this.items.splice(index,1);

                this.$emit('removed');

                flash('Reply was deleted!');
            }
        }
    }

</script>