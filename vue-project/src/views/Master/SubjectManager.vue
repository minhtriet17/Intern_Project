<template>
    <div class="admin-home">
        <aside :class="['sidebar', { collapsed: isCollapsed }]">
        <router-link to="/admin" class="logo-link">
          <img src="/logo.svg" alt="Logo" class="logo" />
        </router-link>
        
        <div class="logo-container">
          <h2 v-if="!isCollapsed">PLT SOLUTION</h2>
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
        <!-- Main content -->
        <main class="content">
          <div class="subject-manager">
            <h1>Quản lý Môn Học</h1>
            <button @click="showAddSubjectModal = true" class="btn btn-primary">Thêm Môn Học</button>
            
            <table class="subject-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tên Môn Học</th>
                  <th>Mô Tả</th>
                  <th>Hành Động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="subject in subjects" :key="subject.id">
                  <td>{{ subject.id }}</td>
                  <td>{{ subject.name }}</td>
                  <td>{{ subject.description }}</td>
                  <td>
                    <button @click="editSubject(subject)" class="btn btn-warning">Sửa</button>
                    <button @click="deleteSubject(subject.id)" class="btn btn-danger">Xóa</button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <div v-if="showAddSubjectModal" class="modal">
                <div class="modal-content">
                    <h2>Thêm Môn Học</h2>
                    <button @click="showAddSubjectModal = false" class="close-btn">&times;</button>
                    <label>Tên Môn Học:</label>
                    <input v-model="newSubject.name" type="text" />
                    <label>Mô Tả:</label>
                    <input v-model="newSubject.description" type="text" />
                    <button @click="newSubject.id ? updateSubject() : addSubject()" class="btn btn-success">
                    {{ newSubject.id ? "Cập Nhật" : "Thêm" }}
                    </button>
                    <button @click="showAddSubjectModal = false" class="btn btn-secondary">Hủy</button>
                </div>
            </div>
          </div>
        </main>
        </div>
    </div>
</template>
  
<script>
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faBook, faSearch  } from '@fortawesome/free-solid-svg-icons';

library.add(faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faSearch, faBook);

export default {
  name: 'SubjectManager',
  components: {
    FontAwesomeIcon
  },
  data() {
    return {
      msg: 'Welcome to PLT Solution App',
      isCollapsed: false,
      searchQuery: '',
      subjects: [],
      isMobile: window.innerWidth <= 768,
      showAddSubjectModal: false,
      newSubject: { name: '', description: '' }
    };
  },
  mounted() {
    this.fetchSubjects();
    window.addEventListener('resize', this.checkScreenSize);
    const role = sessionStorage.getItem("role") || localStorage.getItem("role");
    if (role !== "admin") {
        alert("Bạn không có quyền truy cập trang này!");
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
    async fetchSubjects() {
      try {
        const token = sessionStorage.getItem('token');
        const response = await axios.get('http://127.0.0.1:8000/api/subjects', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.subjects = response.data;
      } catch (error) {
        console.error("Lỗi khi lấy danh sách môn học:", error);
      }
    },
    async addSubject() {
      try {
        const token = sessionStorage.getItem('token');
        await axios.post('http://127.0.0.1:8000/api/subjects', this.newSubject, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.fetchSubjects();
        this.showAddSubjectModal = false;
      } catch (error) {
        console.error("Lỗi khi thêm môn học:", error);
      }
    },
    async deleteSubject(id) {
        if (confirm("Bạn có chắc chắn muốn xóa môn học này không?")) {
            try {
                const token = sessionStorage.getItem('token');
                await axios.delete(`http://127.0.0.1:8000/api/subjects/${id}`, {
                    headers: {
                    'Authorization': `Bearer ${token}`
                    }
                });
                this.fetchSubjects();
            } catch (error) {
            console.error("Lỗi khi xóa môn học:", error);
            }
        }
    },
    async updateSubject() {
      try {
        const token = sessionStorage.getItem('token');
        await axios.put(`http://127.0.0.1:8000/api/subjects/${this.newSubject.id}`, this.newSubject, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.fetchSubjects();
        this.showAddSubjectModal = false;
      } catch (error) {
        console.error("Lỗi khi cập nhật môn học:", error);
      }
    },
    editSubject(subject) {
      this.newSubject = { ...subject };
      this.showAddSubjectModal = true;
    },
    logout() {
      sessionStorage.removeItem("role");
      localStorage.removeItem("role");
      sessionStorage.removeItem('token');
      this.$router.push("/login");
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
  .logo-container h2 {
    font-size: 30px;
    transition: font-size 0.3s;
  }
  .logo {
    cursor: pointer; /* Hiển thị con trỏ khi rê chuột vào */
    transition: transform 0.2s;
    width: 40px;
    height: auto;
  }
  .logo:hover {
    transform: scale(1.1); /* Hiệu ứng phóng to nhẹ khi hover */
  }
  .logo-link {
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none; /* Loại bỏ gạch chân */
  }
  .sidebar.collapsed .logo-container h2 {
    display: none;
    font-size: 14px; /* Kích thước nhỏ hơn khi sidebar thu gọn */
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

/* SubjectManager Table */
.subject-manager {
  padding: 20px;
}
.subject-table {
  width: 100%;
  border-collapse: collapse;
}
.subject-table th, .subject-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
.subject-table th {
  background-color: #f4f4f4;
}
.btn {
  padding: 5px 10px;
  margin: 5px;
  cursor: pointer;
}
.btn-primary { background-color: blue; color: white; }
.btn-warning { background-color: orange; color: white; }
.btn-danger { background-color: red; color: white; }
.btn-success { background-color: green; color: white; }
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 300px;
}

.modal-content .h2 {
  font-size: 16px;
}
.close-btn {
  position: absolute;
  right: 10px;
  top: 10px;
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
}
  </style>
  