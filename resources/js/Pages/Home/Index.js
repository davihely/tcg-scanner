import { ref, computed } from 'vue';

export default function useHomeLogic(props) {
    const searchQuery = ref('');
    
    const isPokemonMenuOpen = ref(false);

    const filteredCollections = computed(() => {
        if (!searchQuery.value) return props.collections;
        return props.collections.filter(set => 
            set.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    });

    const togglePokemonMenu = (state) => {
        isPokemonMenuOpen.value = state;
    };

    return {
        searchQuery,
        filteredCollections,
        isPokemonMenuOpen,
        togglePokemonMenu
    };
}