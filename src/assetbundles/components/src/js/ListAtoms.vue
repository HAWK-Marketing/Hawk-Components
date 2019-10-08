<template>
    <div>
        Atoms

        <div class="atoms" v-for="atom in atoms">
            {{atom.name}}

            <div class="variants" v-for="(variant, key) in atom.variants">
                {{variant.title}}

                <iframe :src="getUrl(atom.key, key)"></iframe>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        data: function() {
            return {
                atoms: window.components.atoms
            }
        },
        methods: {
            getUrl(component, variant) {
                return '/admin/components/render?component=' + component + '&variant=' + variant + '&type=atoms'
            }
        }
    }
</script>