<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const videoElement = ref(null);
const canvasElement = ref(null);
const photoBase64 = ref(null);
const isProcessing = ref(false);
const serverResponse = ref(null);

const startCamera = async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });
        videoElement.value.srcObject = stream;
    } catch (error) {
        console.error("Erro ao acessar a câmera: ", error);
        alert("Não foi possível acessar a câmera. Verifique as permissões do navegador.");
    }
};

const takePhoto = () => {
    const video = videoElement.value;
    const canvas = canvasElement.value;
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    photoBase64.value = canvas.toDataURL('image/jpeg', 0.8);
};

const sendToLaravel = async () => {
    isProcessing.value = true;
    serverResponse.value = null;

    try {
        const response = await axios.post('/process-card', {
            image: photoBase64.value
        });
        
        serverResponse.value = response.data;
    } catch (error) {
        console.error("Erro ao enviar a imagem:", error);
        alert("Ocorreu um erro ao comunicar com o servidor.");
    } finally {
        isProcessing.value = false;
    }
};

onMounted(() => {
    startCamera();
});
</script>
<template>
    <Head title="Scanner de Cartas" />

    <div class="scanner-layout">
        
        <div class="card">
            <div class="card-header">
                <h1 style="margin:0; font-size: 1.25rem;">Scanner de Cartas</h1>
                <p style="margin:0; font-size: 0.875rem; color:#9ca3af;">Posicione a carta e capture a imagem</p>
            </div>

            <div class="card-body">
                <div style="background-color: black; border-radius: 0.75rem; overflow: hidden; aspect-ratio: 3/4; display: flex; align-items: center; justify-content: center;">
                    <video ref="videoElement" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                </div>

                <button @click="takePhoto" class="btn btn-blue">
                    Capturar Imagem
                </button>

                <canvas ref="canvasElement" style="display: none;"></canvas>
            </div>
        </div>

        <div v-if="photoBase64" class="card">
            <div class="card-header">
                <h2 style="margin:0; font-size: 1.25rem;">Revisão da Captura</h2>
                <p style="margin:0; font-size: 0.875rem; color:#9ca3af;">Verifique se os dados estão legíveis</p>
            </div>

            <div class="card-body">
                <div style="background-color: black; border: 1px solid #4b5563; border-radius: 0.75rem; overflow: hidden; display: flex; justify-content: center;">
                    <img :src="photoBase64" style="max-height: 60vh; width: auto; object-fit: contain;" alt="Preview da carta" />
                </div>
                
                <button @click="sendToLaravel" :disabled="isProcessing" class="btn btn-green">
                    {{ isProcessing ? 'Processando dados...' : 'Analisar Carta' }}
                </button>

                <div v-if="serverResponse" style="background-color: #374151; padding: 1rem; border-radius: 0.75rem; border-left: 4px solid #10b981; color: #34d399;">
                    <p style="margin:0; font-weight: bold;">{{ serverResponse.message }}</p>
                    <p style="margin:0; font-size: 0.875rem; margin-top: 0.5rem;">Tamanho: {{ serverResponse.tamanho_bytes }} bytes</p>
                </div>
            </div>
        </div>
        
        <div v-else style="display: flex; align-items: center; justify-content: center; border: 2px dashed #374151; border-radius: 1rem; color: #6b7280; padding: 2rem;">
            <p>A imagem capturada aparecerá aqui.</p>
        </div>

    </div>
</template>