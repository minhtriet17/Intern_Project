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
        <!-- Main content -->
        <main class="content">
                <div class="welcome-container">
                    <h1>Welcome to PLT SOLUTIONS COURSE</h1>
                    <p>{{ msg }}</p>
                    <div class="welcome-image">
                        <img src="/plt-main-logo.jpg" alt="Welcome Image" />
                    </div>
                    <div class="welcome-description">
                        <p>Explore our wide range of video courses and enhance your skills with PLT Solutions.</p>
                        <router-link to="/lectures" class="btn btn-primary">Explore Courses</router-link>
                    </div>
                </div>
          </main>
      </div>
    </div>
  </template>
  
  <script>

  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { library } from '@fortawesome/fontawesome-svg-core';
  import { faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faBook, faEnvelope  } from '@fortawesome/free-solid-svg-icons';
  import { useRouter } from 'vue-router';
  
  library.add(faGauge, faVideo, faUsers, faCog, faSignOutAlt, faChevronLeft, faChevronRight, faBook, faEnvelope);
  
  export default {
    name: 'Admin',
    components: {
      FontAwesomeIcon
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
        logout // 
      };
    },
    data() {
      return {
        msg: 'Welcome to PLT Solution App',
        isCollapsed: false,
        searchQuery: '',
        isMobile: window.innerWidth <= 768
      };
    },
    mounted() {
      window.addEventListener('resize', this.checkScreenSize);
      const role = sessionStorage.getItem("role") || localStorage.getItem("role");
      console.log("Kiểm tra role:", role); 
      if (role !== "admin") {
          // alert("Bạn không có quyền truy cập trang này!");
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
      logout() {
        console.log("Đang đăng xuất...");
        localStorage.clear();
        sessionStorage.clear();
        window.location.reload();
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
    font-size: 20px;
    transition: font-size 0.3s;
  }
  .logo {
    cursor: pointer; /* Hiển thị con trỏ khi rê chuột vào */
    transition: transform 0.2s;
    width: 250px;
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

  .welcome-container {
    text-align: center;
    padding: 50px 20px;
  }

  .welcome-image {
    margin: 20px 0;
    max-width: 100%;
    height: auto;
  }

  .welcome-image img {
    width: 100%; /* Điều chỉnh ảnh để nó chiếm toàn bộ chiều rộng của khối */
    max-width: 500px; /* Giới hạn kích thước tối đa của ảnh */
    height: auto;
    border-radius: 8px; /* Bo góc ảnh */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Hiệu ứng khi hover */
  }

  .welcome-image img:hover {
    transform: scale(1.05); /* Phóng to ảnh khi hover */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Thêm bóng đổ khi hover */
  }

  .welcome-description {
    margin-top: 20px;
    text-align: center;
  }

  .btn {
    padding: 10px 20px;
    text-decoration: none;
    color: white;
    background-color: #007bff;
    border-radius: 5px;
    transition: background-color 0.3s;
  }

  .btn:hover {
    background-color: #0056b3;
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
  