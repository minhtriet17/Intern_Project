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
          <li>
            <router-link to="/login">
              <font-awesome-icon :icon="['fas', 'sign-out-alt']" />
              <span v-if="!isCollapsed">Đăng Xuất</span>
            </router-link>
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
                <div class="user-manager">
                    <h1>Quản lý Người Dùng</h1>
                    <button @click="showAddUserModal = true" class="btn btn-primary">Thêm Người Dùng</button>
                    
                    <table class="user-table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai Trò</th>
                        <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                                <span>{{ user.role }}</span>
                        </td>
                        
                        <td>
                            <button @click="editUser(user)" class="btn btn-warning">Sửa</button>
                            <button @click="deleteUser(user.id)" class="btn btn-danger">Xóa</button>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                    
                    <div v-if="showAddUserModal" class="modal">
                    <div class="modal-content">
                        <h2>Thêm Người Dùng</h2>
                        <label>Tên:</label>
                        <input v-model="newUser.name" type="text" />
                        <label>Email:</label>
                        <input v-model="newUser.email" type="email" />
                        <label>Mật Khẩu:</label>
                        <input v-model="newUser.password" type="password" />
                        <label>Vai Trò:</label>
                        <select v-model="newUser.role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        </select>
                        <button @click="newUser.id ? updateUser() : addUser()" class="btn btn-success">
                            {{ newUser.id ? "Cập Nhật" : "Thêm" }}
                        </button>
                        <button @click="showAddUserModal = false" class="btn btn-secondary">Hủy</button>
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
  import { faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faSearch, faBook } from '@fortawesome/free-solid-svg-icons';
  
  library.add(faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faSearch, faBook);
  
  export default {
    name: 'UserManager',
    components: {
      FontAwesomeIcon
    },
    data() {
      return {
        msg: 'Welcome to PLT Solution App',
        isCollapsed: false,
        searchQuery: '',
        isMobile: window.innerWidth <= 768,
        users: [],
        showAddUserModal: false,
        newUser: {
            name: '',
            email: '',
            password: '',
            role: 'user'
        }
      };
    },
    mounted() {
        
      window.addEventListener('resize', this.checkScreenSize);
      const role = sessionStorage.getItem("role") || localStorage.getItem("role");
      if (role !== "admin") {
          alert("Bạn không có quyền truy cập trang này!");
          this.$router.push("/login"); // Quay lại trang login nếu không phải admin
      } else {
        this.fetchUsers(); //Gọi API lấy danh sách người dùng
      }
    },
    beforeDestroy() {
      window.removeEventListener('resize', this.checkScreenSize);
    },
    methods: {
        async fetchUsers() {
            try {
                const token = sessionStorage.getItem('token'); 
                const response = await axios.get('http://127.0.0.1:8000/api/users', {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.users = response.data;
            } catch (error) {
                console.error("Lỗi khi lấy danh sách người dùng:", error);
                alert("Không thể tải danh sách người dùng!");
            }
        },
        async addUser() {
            try {
                await axios.post('http://127.0.0.1:8000/api/users', this.newUser);
                this.fetchUsers();
                this.showAddUserModal = false;
            } catch (error) {
                console.error("Lỗi khi thêm người dùng:", error.response?.data || error.message);
                alert("Có lỗi xảy ra khi thêm người dùng!");
            }
        },
        async deleteUser(id) {
          try {
              await axios.delete(`http://127.0.0.1:8000/api/users/${id}`, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
              });
              alert("Xóa thành công!");
              this.fetchUsers();
          } catch (error) {
              console.error("Lỗi xóa người dùng:", error.response);
              alert("Xóa thất bại: " + error.response.data.message);
          }
        },
        async updateUser() {
            try {
                await axios.put(`http://127.0.0.1:8000/api/users/${this.newUser.id}`, this.newUser);
                this.fetchUsers();
                this.showAddUserModal = false;
            } catch (error) {
                console.error("Lỗi khi cập nhật người dùng:", error.response?.data || error.message);
                alert("Có lỗi xảy ra khi cập nhật người dùng!");
            }
        },
        async updateUserRole(user) {
            await axios.put(`http://127.0.0.1:8000/api/users/${user.id}`, { role: user.role });
            this.fetchUsers();
        },
        editUser(user) {
            this.newUser = { ...user };  // Copy dữ liệu, tránh tham chiếu trực tiếp
            this.showAddUserModal = true;
        },
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

/* UserManager Table */
.user-manager {
  padding: 20px;
}
.user-table {
  width: 100%;
  border-collapse: collapse;
}
.user-table th, .user-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
.user-table th {
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
  </style>
  