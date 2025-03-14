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
                <router-link to="/" class="nav-link disabled">Liên hệ</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/login" tabindex="-1" aria-disabled="true">Đăng xuất</router-link>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <!-- Danh sách khóa học -->
    <div class="container mt-4">
      <h2 class="text-center">Danh Sách Khóa Học</h2>
      <div v-if="courses.length > 0" class="row">
        <div v-for="course in courses" :key="course.id" class="col-md-4 mb-4">
          <div class="card">
            <img v-if="course.image" :src="course.image" class="card-img-top" alt="Hình ảnh khóa học">
            <div class="card-body">
              <h5 class="card-title">{{ course.name }}</h5>
              <p class="card-text">{{ course.description }}</p>
              <router-link to="/course" class="btn btn-primary">Xem chi tiết</router-link>
            </div>
          </div>
        </div>
      </div>
      <p v-else class="text-center">Không có khóa học nào.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'CoursesListPage',
    data() {
        return {
            courses: [] // Danh sách khóa học
        };
    },
    methods: {
        async fetchCourses() {
            try {
                const response = await axios.get('http://localhost:8000/api/subjects'); // Gọi API từ backend
                this.courses = response.data;
            } catch (error) {
                console.error("Lỗi khi tải danh sách khóa học:", error);
            }
        },
    },
        mounted() {
            this.fetchCourses(); // Gọi hàm khi trang được tải
        }
    }
</script>

<style>
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
 /* Cards */
.card {
  border-radius: 10px;
  overflow: hidden;
}
.card img {
  height: 200px;
  object-fit: cover;
}
</style>