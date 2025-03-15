<template>

  <div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
          <router-link to="/" class="navbar-brand">PLT Solutions</router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <router-link to="/" class="nav-link active" aria-current="page">Trang chủ</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/course" class="nav-link">Bài giảng</router-link>
              </li>
              <li class="nav-item dropdown">
                <router-link class="nav-link dropdown-toggle disabled" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Khóa học
                </router-link>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/login" tabindex="-1" aria-disabled="true">Đăng xuất</router-link>
              </li>
            </ul>
          </div>
        </div>
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

    <!-- Search Input -->
    <div class="search-box">
      <input type="text" v-model="searchQuery" placeholder="Search lectures..." class="form-control" />
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
      searchQuery: '', // Add searchQuery data property
      isLoading: true
    };
  },
  computed: {
    filteredLectures() {
      let filtered = this.lectures;
      if (this.selectedCategory) {
        filtered = filtered.filter(lecture => lecture.category === this.selectedCategory);
      }
      if (this.searchQuery) {
        filtered = filtered.filter(lecture => 
          lecture.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          lecture.description.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      return filtered;
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

.search-box {
  margin: 20px 0;
  position: relative;
  display: flex;
  justify-content: center;
}

.search-box input {
  width: 100%;
  max-width: 500px; /* Limit the maximum width */
  padding: 10px 20px 10px 40px; /* Add padding for the search icon */
  border-radius: 25px; /* Make the input rounded */
  border: 1px solid #ccc;
  transition: border-color 0.3s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
}

.search-box input:focus {
  border-color: #007bff; /* Change border color on focus */
  outline: none;
}

.search-box .search-icon {
  position: absolute;
  left: 15px; /* Position the icon inside the input */
  top: 50%;
  transform: translateY(-50%);
  color: #777;
  pointer-events: none; /* Prevent the icon from blocking input clicks */
  font-size: 18px; /* Adjust the icon size */
}

</style>