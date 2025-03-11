<template>
    <div>
        <nav class="navbar">
            <div class="logo-container">
                <router-link to="#" class="logo-link" @click="reloadPage">
                <img src="/logo.svg" alt="Logo" class="logo-img" />
                </router-link>
            </div>
            <ul class="nav-links">
                <li><router-link to="/" @click="reloadPage">Trang chủ</router-link></li>
                <li><router-link to="/course">Khóa học</router-link></li>
                <li><router-link to="/login">Đăng xuất</router-link></li>
            </ul>
        </nav>
        <div class="homepage">
            <!-- Banner Chào Mừng -->
            <header class="hero">
            <div class="hero-content">
                <h1>Chào mừng đến với <span class="highlight">PLT SOLUTION COURSES</span></h1>
                <p>Nâng cao kỹ năng của bạn với các khóa học chất lượng hàng đầu.</p>
                <router-link to="/course" class="cta-button">Khám phá khóa học</router-link>
            </div>
            </header>

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

    /* Hero Banner */
.hero {
  background: url('/banner.jpg') center/cover no-repeat;
  color: white;
  text-align: center;
  padding: 100px 20px;
}

.hero-content h1 {
  font-size: 3rem;
  font-weight: bold;
  color: black;
}

.hero-content .highlight {
  color: #ffcc00;
}

.hero-content p {
  font-size: 1.2rem;
  margin-top: 10px;
}

.cta-button {
  display: inline-block;
  margin-top: 15px;
  padding: 12px 20px;
  background-color: #ffcc00;
  color: #333;
  font-weight: bold;
  text-decoration: none;
  border-radius: 5px;
  transition: 0.3s;
}

.cta-button:hover {
  background-color: #e6b800;
}

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
</style>