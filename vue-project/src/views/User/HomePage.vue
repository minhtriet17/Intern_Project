<template>
    <div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
          <router-link to="/" @click="reloadPage" class="navbar-brand">PLT Solutions</router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <router-link to="/" @click="reloadPage" class="nav-link active" aria-current="page">Trang chủ</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/course" class="nav-link">Bài giảng</router-link>
              </li>
              <li class="nav-item dropdown">
                <router-link class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <router-link class="nav-link disabled" to="/login" tabindex="-1" aria-disabled="true">Đăng xuất</router-link>
              </li>
            </ul>
            <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
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

            <!-- Giới thiệu -->
            <section class="about-section">
            <div class="about-content">
                <h2>Tại sao chọn PLT SOLUTION COURSES?</h2>
                <p>Chúng tôi cung cấp các khóa học trực tuyến chất lượng cao, giúp bạn phát triển kỹ năng và đạt được thành công.</p>
                <ul>
                <li>✅ Học từ các chuyên gia</li>
                <li>✅ Nội dung cập nhật liên tục</li>
                <li>✅ Linh hoạt thời gian học</li>
                </ul>
            </div>
            <img src="/src/assets/faq.jpg" alt="E-learning" class="about-image">
            </section>

            <!-- Khóa học nổi bật -->
            <section class="courses-section">
            <h2>Khóa học nổi bật</h2>
            <div v-if="isLoading" class="loading">Đang tải...</div>
            <div v-else class="course-list">
                <div v-for="course in featuredCourses" :key="course.id" class="course-card">
                <img :src="getThumbnailUrl(course.video_url)" :alt="course.title" class="course-img">
                <h3>{{ course.title }}</h3>
                <p>{{ course.description }}</p>
                <router-link :to="'/videodetail/' + course.id" class="course-button">Xem chi tiết</router-link>
                </div>
            </div>
            </section>
        </div>
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
            };
        },
        computed: {
            featuredCourses() {
                // Chọn 3 khóa học đầu tiên làm khóa học nổi bật
                return this.lectures.slice(0, 3);
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
            }
        },
        mounted() {
            this.getLectures();
        }
    }
</script>
<style  scoped>


/* About Section */
.about-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 60px 10%;
  background-color: #fefefe;
}

.about-content {
  max-width: 50%;
}

.about-content h2 {
  font-size: 2rem;
  color: #333;
}

.about-content p {
  font-size: 1.1rem;
  margin: 10px 0;
}

.about-content ul {
  padding: 0;
  list-style: none;
}

.about-content li {
  font-size: 1rem;
  margin: 5px 0;
}

.about-image {
  width: 40%;
  border-radius: 10px;
}

/* Featured Courses */
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
  width: 280px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
}

.course-card:hover {
  transform: translateY(-5px);
}

.course-img {
  width: 100%;
  border-radius: 5px;
}

.course-button {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background: #333;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-size: 0.9rem;
}

.course-button:hover {
  background: #555;
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

</style>