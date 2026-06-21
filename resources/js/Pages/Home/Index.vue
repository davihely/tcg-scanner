<script setup>
import { Head } from '@inertiajs/vue3';
import useHomeLogic from './Index.js';
import './Index.css';

const props = defineProps({
    collections: {
        type: Array,
        required: true
    }
});

const { searchQuery, filteredCollections, isPokemonMenuOpen, togglePokemonMenu } = useHomeLogic(props);
</script>

<template>
    <Head title="Coleções Pokémon TCG" />

    <div class="tcg-layout">
        <header class="top-header">
            <div class="search-container">
                <input 
                    type="text" 
                    v-model="searchQuery"
                    placeholder="Pesquisar coleções..." 
                    class="search-input"
                />
            </div>
        </header>

        <nav class="sub-nav">
            <div class="nav-content">
                <div 
                    class="nav-item-container"
                    @mouseenter="togglePokemonMenu(true)"
                    @mouseleave="togglePokemonMenu(false)"
                >
                    <span class="nav-item active">Pokémon ▾</span>

                    <div v-show="isPokemonMenuOpen" class="mega-menu">
                        
                        <div class="mega-header">
                            <h2>Pokémon</h2>
                            <div class="shop-links">
                                <a href="#">Shop All English</a>
                                <a href="#">Shop All Japanese</a>
                            </div>
                        </div>

                        <div class="mega-body">
                            <div class="mega-lists">
                                <div class="lists-row">
                                    <div class="list-group">
                                        <h3>Latest English Sets</h3>
                                        <div class="list-columns">
                                            <ul>
                                                <li>ME05: Pitch Black</li>
                                                <li>ME04: Chaos Rising</li>
                                                <li>ME03: Perfect Order</li>
                                                <li>ME: Ascended Heroes</li>
                                                <li>ME02: Phantasmal Flames</li>
                                            </ul>
                                            <ul>
                                                <li>ME01: Mega Evolution</li>
                                                <li>SV: Black Bolt</li>
                                                <li>SV: White Flare</li>
                                                <li>SV10: Destined Rivals</li>
                                                <li>SV09: Journey Together</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="list-group">
                                        <h3>Latest Japanese Sets</h3>
                                        <div class="list-columns">
                                            <ul>
                                                <li>M5: Abyss Eye</li>
                                                <li>M4: Ninja Spinner</li>
                                                <li>M3: Nihil Zero</li>
                                                <li>M2a: High Class Pack</li>
                                                <li>M2: Inferno X</li>
                                            </ul>
                                            <ul>
                                                <li>m1L: Mega Brave</li>
                                                <li>m1S: Mega Symphonia</li>
                                                <li>SV11B: Black Bolt</li>
                                                <li>SV11W: White Flare</li>
                                                <li>SV10: The Glory of Team Rocket</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="lists-row bottom-links">
                                    <div class="list-group">
                                        <h3>Articles</h3>
                                        <div class="list-columns">
                                            <ul><li>All Articles</li></ul>
                                            <ul><li>How to Play</li></ul>
                                        </div>
                                    </div>
                                    
                                    <div class="list-group">
                                        <h3>More</h3>
                                        <div class="list-columns">
                                            <ul>
                                                <li>Mass Entry</li>
                                                <li>Gift Cards</li>
                                            </ul>
                                            <ul><li>Help</li></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mega-featured">
                                <div class="featured-image-placeholder">ME05: Pitch Black Banner</div>
                                
                                <h4>ME05: Pitch Black</h4>
                                <button class="btn-preorder">Preorder Now</button>

                                <div class="featured-actions">
                                    <button class="btn-outline">English Price Guide</button>
                                    <button class="btn-outline">Japanese Price Guide</button>
                                    <button class="btn-outline">Advanced Search</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <div class="content-header">
                <h1>Últimas Coleções de Pokémon</h1>
                <p>Navegue pelas expansões mais recentes do Pokémon TCG.</p>
            </div>

            <div class="sets-grid">
                <div v-for="set in filteredCollections" :key="set.id" class="set-card">
                    <div class="card-image-container">
                        <img v-if="set.logo" :src="set.logo + '.png'" :alt="set.name" />
                        <span v-else class="no-image-text">Logo Indisponível</span>
                    </div>

                    <div class="card-info">
                        <h3 class="set-title">{{ set.name }}</h3>
                        <button class="btn-shop">Ver Cartas</button>
                        <a href="#" class="price-guide-link">Detalhes da Coleção ({{ set.cardCount.total }})</a>
                    </div>
                </div>
            </div>

            <div v-if="filteredCollections.length === 0" class="empty-state">
                Nenhuma coleção encontrada para a sua pesquisa.
            </div>
        </main>
    </div>
</template>