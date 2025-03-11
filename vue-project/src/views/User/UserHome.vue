<template>

  <div>
    <!-- Navbar -->
    <nav class="navbar">
      <div class="logo-container">
        <router-link to="#" class="logo-link" @click="reloadPage">
          <img src="/logo.svg" alt="Logo" class="logo-img" />
        </router-link>
      </div>
      <ul class="nav-links">
        <li><router-link to="/">Trang chủ</router-link></li>
        <li><router-link to="/about">Khóa học</router-link></li>
        <li><router-link to="/login">Đăng xuất</router-link></li>
      </ul>
    </nav>
    <h1 class="title_homepage">List Videos</h1>

    <div class="category-filter">
      <button v-for="category in categories" :key="category" @click="filterByCategory(category)"
        :class="selectedCategory === category ? 'btn-info' : 'btn-outline-secondary'">
        {{ category }}
      </button>
      <button @click="resetFilter()" class="btn btn-sm btn-outline-dark mx-1" v-if="selectedCategory">
        Tất cả
      </button>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <div class="table-bordered">
          <div v-if="filteredLectures.length > 0">
            <div class="hpage-total_videos">
              <ul v-for="(data, index) in filteredLectures" :key="index" class="lecture-item">
                <li class="frame-total_videos">
                  <div class="video-wrapper">
                    <router-link :to="'/videodetail/' + data.id">
                      <img :src="getThumbnailUrl(data.video_url)" alt="Video Thumbnail" class="video-thumbnail">
                    </router-link>
                  </div>
                </li>
                <li class="frame-total_videos desc_videos">
                  <router-link :to="'/videodetail/' + data.id" class="link_pagevideos">
                    <h4><strong>{{ data.title }}</strong></h4>
                  </router-link>
                </li>
                <li class="frame-total_videos desc_videos">
                  <p>{{ data.description }}</p>
                </li>
              </ul>
            </div>
          </div>
          <div v-else>
            <p class="text-center">{{ isLoading ? 'Loading...' : 'Không tìm thấy video' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import '@/assets/homeview_videos.css'

export default {
  name: "Lectures",
  data() {
    return {
      lectures: [],
      categories: [],
      selectedCategory: null,
      isLoading: true
    };
  },
  computed: {
    filteredLectures() {
      if (!this.selectedCategory) {
        return this.lectures;
      }
      return this.lectures.filter(lecture => lecture.category === this.selectedCategory);
    }
  },
  mounted() {
    this.getLectures();
  },
  methods: {
    filterByCategory(category) {
      this.selectedCategory = category;
    },
    resetFilter() {
      this.selectedCategory = null;
    },
    getLectures() {
      this.isLoading = true;
      axios.get('http://localhost:8000/api/lectures')
        .then(res => {
          this.lectures = res.data.data;
          // Lấy danh sách các category duy nhất
          this.categories = [...new Set(this.lectures.map(lecture => lecture.category))];
          this.isLoading = false;
        })
        .catch(error => {
          console.log(error);
          this.isLoading = false;
        });
    },
    reloadPage(event) {
      event.preventDefault(); // Ngăn chặn điều hướng mặc định
      window.location.reload(); // Tải lại trang
    },
    extractYouTubeId(url) {
      const regExp = /(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|.*embed\/|.*youtu\.be\/|v=))([^&?\/]+)/;
      const match = url.match(regExp);
      return match && match[1] ? match[1] : null;
    },
    getThumbnailUrl(url) {
      const videoId = this.extractYouTubeId(url);
      return videoId ? `https://img.youtube.com/vi/${videoId}/hqdefault.jpg` : '';
    }
  }
};
</script>

<style scoped>

/* NAVBAR */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #333;
  padding: 10px 20px;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 15px;
  margin: 0;
  padding: 0;
}

.nav-links li {
  display: inline;
}

.nav-links a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.nav-links a:hover {
  color: #ffcc00;
}

.logo-container {
  display: flex;
  align-items: center;
}

.logo-link {
  display: inline-block;
}

.logo-img {
  width: 35px; 
  height: auto;
}

/* COURSES PAGE */
.video-thumbnail {
  width: 100%;
  height: auto;
  cursor: pointer;
  border-radius: 8px;
  transition: transform 0.3s ease;
}

.video-thumbnail:hover {
  transform: scale(1.05);
}

.link_pagevideos {
  text-decoration: none;
  color: inherit;
  font-weight: bold;
}

.link_pagevideos:hover {
  text-decoration: none;
  color: inherit;
}

.category-filter {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  margin: 20px;

}

.category-filter button {
  background-color: rgb(236, 227, 227);
  border-radius: 5px;
  border: 0px;
}

.category-filter button:hover {
  /* transform: scale(1.05); */
  background-color: rgb(167, 163, 163);
  opacity: 0.7;
  color: white;
}
</style>