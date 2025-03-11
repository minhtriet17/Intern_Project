<template>
    <div class="video-detail-page">

        <div class="page-video_detail">
            <iframe :src="getEmbedUrl(video.video_url)" title="Video Player" frameborder="0"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
            </iframe>
        </div>
        <h2>{{ video.title }}</h2>
        <span>Thời gian đăng tải: {{ formatDate(video.created_at) }}</span>
        <p>{{ video.description }}</p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'VideoDetailPage',
    data() {
        return {
            video: {}
        };
    },
    mounted() {
        this.getVideoDetails();
    },


    methods: {
        getVideoDetails() {
            // Lấy ID từ URL và gọi API
            axios.get(`http://localhost:8000/api/lectures/${this.$route.params.id}`)
                .then(res => {
                    this.video = res.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getEmbedUrl(url) {
            if (!url) return "";
            const videoId = this.extractYouTubeId(url);
            return videoId ? `https://www.youtube.com/embed/${videoId}` : url;
        },
        extractYouTubeId(url) {
            const regExp = /(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|.*embed\/|.*youtu\.be\/|v=))([^&?\/]+)/;
            const match = url.match(regExp);
            return match && match[1] ? match[1] : null;
        },
        formatDate(dateString) {
            if (!dateString) return "N/A";
            const date = new Date(dateString);
            return date.toLocaleDateString("vi-VN"); // Hiển thị theo định dạng dd/mm/yyyy
        }
    }
};
</script>

<style scoped>
.video-detail-page h2 {
    font-size: 20px;
    margin: 15px 20px;
}

.video-detail-page p {
    margin: 0 20px;
}

.video-detail-page span {
    margin: 0 20px;
}

.page-video_detail {
    width: 100vw;
    height: 70vh;
}
</style>