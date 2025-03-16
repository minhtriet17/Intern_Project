<template>
  <div class="admin-home">
    <aside :class="['sidebar', { collapsed: isCollapsed }]">
      <router-link to="/admin" class="logo-link">
        <img src="/logo_plt.png" alt="Logo" class="logo" />
      </router-link>
      <div class="logo-container">
        <h2 v-if="!isCollapsed">PLT SOLUTION ADMIN</h2>
      </div>
      <div class="toggle-container" @click="toggleSidebar">
        <font-awesome-icon :icon="['fas', isCollapsed ? 'chevron-right' : 'chevron-left']" class="toggle-btn" />
      </div>
      <ul>
        <li>
          <router-link to="/lectures">
            <font-awesome-icon :icon="['fas', 'video']" />
            <span v-if="!isCollapsed">Quản lý Video</span>
          </router-link>
        </li>
        <li>
          <router-link to="/subjectmanager">
            <font-awesome-icon :icon="['fas', 'book']" />
            <span v-if="!isCollapsed">Quản lý Môn Học</span>
          </router-link>
        </li>
        <li>
          <router-link to="/usermanager">
            <font-awesome-icon :icon="['fas', 'users']" />
            <span v-if="!isCollapsed">Quản lý Người Dùng</span>
          </router-link>
        </li>
        <li>
          <router-link to="/contactmanager">
            <font-awesome-icon :icon="['fas', 'envelope']" />
            <span v-if="!isCollapsed">Quản lý liên hệ</span>
          </router-link>
        </li>
        <li @click="logout">
          <a href="#">
            <font-awesome-icon :icon="['fas', 'sign-out-alt']" />
            <span v-if="!isCollapsed">Đăng Xuất</span>
          </a>
        </li>
      </ul>
    </aside>
    <div class="main-container">
      <header class="header">
        <div class="search-box">
          <input type="text" placeholder="Tìm kiếm..." v-model="searchQuery" />
          <font-awesome-icon :icon="['fas', 'search']" class="search-icon" />
        </div>
      </header>

      <main class="content">
        <div class="container-fluid mt-2">
          <div class="card">
            <div class="card-header">
              <h4>Lectures</h4>
              <button @click="showModal = true" class="btn btn-primary float-end">Add Lecture</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Source</th>
                      <th>Category</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody v-if="this.filteredLectures.length > 0">
                    <tr v-for="(data, index) in this.filteredLectures" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ data.title }}</td>
                      <td>{{ data.description }}</td>
                      <td>{{ data.video_url }}</td>
                      <td>{{ data.category }}</td>
                      <td>{{ data.created_at }}</td>
                      <td>
                        <RouterLink :to="{ path: '/lectures/' + data.id + '/edit' }" class="btn btn-success mx-1 my-1">
                          Edit</RouterLink>
                        <button type="button" @click="deleteLecture(data.id)"
                          class="btn btn-danger mx-1 my-1">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="7" class="text-center">Loading...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <Modal :isVisible="showModal" @close="showModal = false">
      <lectureCreate @close="showModal = false" />
    </Modal>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faSearch, faBook, faEnvelope } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Modal from '/src/views/Lectures/Modal.vue';
import lectureCreate from '/src/views/Lectures/Create.vue';

library.add(faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faSearch, faBook, faEnvelope);

export default {
  name: 'LecturesView',
  components: {
    FontAwesomeIcon,
    Modal,
    lectureCreate
  },
  setup() {
    const router = useRouter();

    const logout = () => {
      console.log("Đang đăng xuất...");

      // Xóa toàn bộ token & role
      localStorage.removeItem("token");
      localStorage.removeItem("role");
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("role");

      // Kiểm tra nếu token còn tồn tại
      console.log("Token sau khi xóa:", localStorage.getItem("token"));
      console.log("Role sau khi xóa:", localStorage.getItem("role"));

      // Xóa toàn bộ localStorage để chắc chắn
      localStorage.clear();
      sessionStorage.clear();

      // Reload trang để Vue Router cập nhật
      window.location.reload();

      // Điều hướng về trang login
      router.push("/login");
    };
    return {
      logout
    };
  },
  watch: {
    searchQuery: function (val) {
      this.filteredLectures = this.lectures.filter(lecture =>
        lecture.title.toLowerCase().includes(val.toLowerCase()) ||
        lecture.description.toLowerCase().includes(val.toLowerCase()) ||
        lecture.category.toLowerCase().includes(val.toLowerCase()) ||
        lecture.created_at.toLowerCase().includes(val.toLowerCase())
      );
    }
  },
  data() {
    return {
      isCollapsed: false,
      searchQuery: null,
      isMobile: window.innerWidth <= 768,
      lectures: [],
      filteredLectures: [],
      showModal: false
    };
  },
  mounted() {
    this.getLectures();
    window.addEventListener('resize', this.checkScreenSize);
    const role = sessionStorage.getItem("role") || localStorage.getItem("role");
    if (role !== "admin") {
      this.$router.push("/login"); // Quay lại trang login nếu không phải admin
    }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.checkScreenSize);
  },
  methods: {
    toggleSidebar() {
      if (this.isMobile) {
        this.isCollapsed = !this.isCollapsed;
        document.querySelector(".sidebar").classList.toggle("show");
      } else {
        this.isCollapsed = !this.isCollapsed;
      }
    },
    checkScreenSize() {
      this.isMobile = window.innerWidth <= 768;
      if (this.isMobile) {
        this.isCollapsed = true;
      } else {
        this.isCollapsed = false;
      }
    },
    deleteLecture(lectureId) {
      if (confirm('Are you sure you want to delete this lecture?')) {
        axios.delete(`http://localhost:8000/api/lectures/${lectureId}/delete`).then(res => {
          alert(res.data.message);
          this.getLectures();
        }).catch(error => {
          if (error.response && error.response.status == 404) {
            alert(error.response.data.message);
          }
        });
      }
    },
    getLectures() {
      axios.get('http://localhost:8000/api/lectures')
        .then(res => {
          this.lectures = res.data.data;
          this.filteredLectures = this.lectures;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.admin-home {
  display: flex;
}

.sidebar {
  width: 250px;
  background: #333;
  color: white;
  padding: 20px;
  height: 100vh;
  transition: width 0.3s;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.sidebar.collapsed {
  width: 80px;
}

.toggle-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  padding: 10px 0;
  cursor: pointer;
  background: #444;
  border-radius: 5px;
  margin-bottom: 10px;
}

.toggle-btn {
  color: white;
  font-size: 20px;
}

.logo-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  margin-bottom: 20px;
}

.logo {
  cursor: pointer;
  /* Hiển thị con trỏ khi rê chuột vào */
  transition: transform 0.2s;
  width: 250px;
  height: auto;
}

.logo:hover {
  transform: scale(1.1);
  /* Hiệu ứng phóng to nhẹ khi hover */
}

.logo-link {
  display: flex;
  justify-content: center;
  align-items: center;
  text-decoration: none;
  /* Loại bỏ gạch chân */
}

.logo-container h2 {
  font-size: 20px;
  transition: font-size 0.3s;
}

.sidebar.collapsed .logo-container h2 {
  display: none;
  font-size: 14px;
  /* Kích thước nhỏ hơn khi sidebar thu gọn */
}

.sidebar ul {
  list-style: none;
  padding: 0;
  width: 100%;
}

.sidebar ul li {
  margin: 15px 0;
}

.sidebar ul li a {
  color: white;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
  transition: background 0.3s;
  white-space: nowrap;
}

.sidebar ul li a svg {
  font-size: 20px;
}

.sidebar ul li a span {
  transition: opacity 0.3s;
}

.sidebar.collapsed ul li a span {
  opacity: 0;
  width: 0;
  overflow: hidden;
}

.sidebar ul li a:hover {
  background: #555;
}

.content {
  flex-grow: 1;
  padding: 20px;
}

.table-responsive {
  overflow-x: auto;
}

.table-responsive .table {
  border: 1px solid #ddd;
}

.table-responsive .table th,
.table-responsive .table td {
  padding: 8px;
  text-align: left;
}

@media (max-width: 768px) {
  .sidebar {
    width: 80px;
  }

  .sidebar.collapsed {
    width: 60px;
  }

  .sidebar ul li a span {
    display: none;
  }

  .sidebar .logo-container h2 {
    display: none;
  }

  .toggle-container {
    position: absolute;
    top: 10px;
    right: 10px;
  }
}

@media (max-width: 480px) {
  .sidebar {
    position: fixed;
    left: -250px;
    height: 100%;
    transition: left 0.3s;
  }

  .sidebar.show {
    left: 0;
  }
}

.main-container {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  width: 100%;
}

/* Header */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #000000;
  padding: 15px 20px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

/* Thanh tìm kiếm */
.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box input {
  width: 250px;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 20px;
  outline: none;
  transition: width 0.3s;
}

.search-box input:focus {
  width: 300px;
}

.search-icon {
  position: absolute;
  right: 10px;
  color: #777;
}

/* Profile */
.profile {
  display: flex;
  align-items: center;
  gap: 10px;
}

.profile-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.username {
  font-weight: bold;
  color: #fff7f7;
}
</style>