<template>
    <div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
          <router-link to="/" @click="reloadPage" class="navbar-brand">
            <img src="/src/assets/logo_plt.png" class="navbar-logo">
          </router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <router-link to="/" @click="reloadPage" class="nav-link active" aria-current="page">Trang chủ</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/course" class="nav-link">Bài giảng</router-link>
              </li>
              <li class="nav-item dropdown">
                <router-link class="nav-link dropdown-toggle" 
                          to="/courselist" 
                          id="navbarDropdown" 
                          role="button" 
                          data-bs-toggle="dropdown" 
                          aria-expanded="false">
                Khóa học
              </router-link>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="/courselist">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <router-link to="/" class="nav-link disabled">Liên hệ</router-link>
              </li>
              
            </ul>
          </div>
        </div>
      </nav>
        
      <div class="homepage">
            <!-- Banner Chào Mừng -->
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/src/assets/anh1.jpg" class="d-block w-100" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <h1>Chào mừng đến với <span class="highlight">PLT SOLUTION COURSES</span></h1>
                    <p>Nâng cao kỹ năng của bạn với các khóa học chất lượng hàng đầu.</p>
                    <router-link to="/course" class="btn btn-primary">Khám phá khóa học</router-link>
                  </div>
                </div>
                <div class="carousel-item">
                  <img src="/src/assets/anh2.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="/src/assets/anh3.jpg" class="d-block w-100" alt="...">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>


            <!-- Bài giảng -->
            <section class="courses-section">
            <h2>Danh sách các bài giảng</h2>
            <div v-if="isLoading" class="loading">Đang tải...</div>
            <div v-else class="course-list">
                <div v-for="(course, index) in visibleLectures" :key="course.id" class="course-card">
                <img :src="getThumbnailUrl(course.video_url)" :alt="course.title" class="course-img">
                <h3>{{ course.title }}</h3>
                <p>{{ course.description }}</p>
                <router-link :to="'/videodetail/' + course.id" class="btn btn-outline-primary">Xem chi tiết</router-link>
                </div>
            </div>
            <button v-if="lectures.length > 6 && !showAllLectures" @click="toggleShowAllLectures" class="btn btn-primary">Xem Thêm</button>
            </section>
      </div>

        <!-- Footer Section -->
      <footer class="text-center text-white">
        <div class="container p-4 pb-0">
          <section class="">
            <p class="d-flex justify-content-center align-items-center">
              <span class="me-3">Tạo tài khoản miễn phí:</span>
              <router-link to="/login" type="button" class="btn btn-outline-light btn-rounded">
                Đăng ký ngay!
              </router-link>
            </p>
          </section>
        </div>
        <div class="text-center p-3">
          © 2025 Copyright:
          <router-link class="text-white" to="/">PLT Solutions</router-link>
        </div>

      </footer>

    </div>
</template>
<script>
    import axios from 'axios'
    
    export default {
        name: "HomePage",
        data() {
            return {
                lectures: [],
                isLoading: true,
                showAllLectures: false,
            };
        },
        computed: {
            featuredCourses() {
                // Chọn 3 khóa học đầu tiên làm khóa học nổi bật
                return this.lectures.slice(0, 3);
            },
            visibleLectures() {
                return this.showAllLectures ? this.lectures : this.lectures.slice(0, 6);
            }
        },
        methods: {
            reloadPage(event) {
                event.preventDefault(); // Ngăn chặn điều hướng mặc định
                window.location.reload(); // Tải lại trang
            },

            getLectures() {
            axios.get('http://localhost:8000/api/lectures')
                .then(res => {
                this.lectures = res.data.data;
                this.isLoading = false;
                })
                .catch(error => {
                console.log(error);
                this.isLoading = false;
                });
            },
            extractYouTubeId(url) {
                const regExp = /(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|.*embed\/|.*youtu\.be\/|v=))([^&?\/]+)/;
                const match = url.match(regExp);
                return match && match[1] ? match[1] : null;
            },
            getThumbnailUrl(url) {
                const videoId = this.extractYouTubeId(url);
                return videoId ? `https://img.youtube.com/vi/${videoId}/hqdefault.jpg` : '/default-thumbnail.jpg';
            },
            toggleShowAllLectures() {
                this.showAllLectures = !this.showAllLectures;
            }
        },
        mounted() { 
            this.getLectures();
        }
    }
</script>
<style scoped>
/* Logo */
.navbar-logo {
  height: 100px; /* Adjust the height as needed */
  width: auto;
}

/*Nav item */
  .nav-item{
    margin-right: 15px;
    font-size: 19px;
  }
  .nav-item:hover{
    text-transform: uppercase;
  }
 .nav-item::after {
     content: '';
     display: block;
     width: 0px;
     height: 2px;
     background: #3883e6;
     transition: 0.4s
 }

 .nav-item:hover::after {
     width: 100%
 }

 .navbar-dark .navbar-nav .active>.nav-link,
 .navbar-dark .navbar-nav .nav-link.active,
 .navbar-dark .navbar-nav .nav-link.show,
 .navbar-dark .navbar-nav .show>.nav-link,
 .navbar-dark .navbar-nav .nav-link:focus,
 .navbar-dark .navbar-nav .nav-link:hover {
     color: #324ee9
 }

 .nav-link {
     padding: 5px 5px;
     transition: 0.2s
 }
/* Lectures */
.courses-section {
  text-align: center;
  padding: 60px 10%;
}

.courses-section h2 {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.course-list {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

.course-card {
  background: white;
  border-radius: 10px;
  padding: 15px;
  width: calc(33.33% - 20px); /* 3 cards per row */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
  display: flex;
  flex-direction: column;
}

.course-card:hover {
  transform: translateY(-5px);
}

.course-img {
  width: 100%;
  border-radius: 5px;
}

.course-card h3 {
  font-size: 1.2rem;
  margin: 10px 0;
  flex-grow: 1;
  max-height: 4rem; /* Adjust the height as needed */
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* Number of lines to show */
  -webkit-box-orient: vertical;
}

.course-card p {
  font-size: 1rem;
  margin: 10px 0;
  flex-grow: 1;
  max-height: 4rem; /* Adjust the height as needed */
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Number of lines to show */
  -webkit-box-orient: vertical;
}

.btn-primary {
  margin-top: 20px; /* Adjust the value as needed */
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .course-card {
    width: calc(50% - 20px); /* 2 cards per row */
  }
}

@media (max-width: 768px) {
  .course-card {
    width: calc(100% - 20px); /* 1 card per row */
  }
}

.carousel {
  max-width: 1300px; /* Adjust the width as needed */
  margin: 50px auto; /* Center the carousel and add margin */
  border: 1px solid #ddd; /* Optional: Add a border */
  border-radius: 10px; /* Optional: Add rounded corners */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow */
}

.carousel-inner img {
  border-radius: 10px; /* Optional: Add rounded corners to images */
}

.carousel-caption {
  position: absolute;
  bottom: 50px; /* Adjust position from bottom */
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
  padding: 15px;
  border-radius: 15px;
  color: white; /* Text color */
  text-align: center;
  width: 80%; /* Adjust width */
}

@media (max-width: 768px) {
  .carousel-caption {
    bottom: 20px; /* Adjust position from bottom for smaller screens */
    padding: 10px;
    width: 90%; /* Adjust width for smaller screens */
  }

  .carousel-caption h1 {
    font-size: 1.5rem; /* Adjust font size for smaller screens */
  }

  .carousel-caption p {
    font-size: 0.9rem; /* Adjust font size for smaller screens */
  }
}

@media (max-width: 480px) {
  .carousel-caption {
    bottom: 10px; /* Adjust position from bottom for very small screens */
    padding: 8px;
    width: 95%; /* Adjust width for very small screens */
  }

  .carousel-caption h1 {
    font-size: 1.2rem; /* Adjust font size for very small screens */
  }

  .carousel-caption p {
    font-size: 0.8rem; /* Adjust font size for very small screens */
  }
}

/* Footer Section */
footer{
  background-color:#3a3f5f;
}
</style>