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
                    <h2>Quản Lý Liên Hệ</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Tin Nhắn</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="contact in contacts" :key="contact.id">
                            <td>{{ contact.id }}</td>
                            <td>{{ contact.name }}</td>
                            <td>{{ contact.email }}</td>
                            <td>{{ contact.number }}</td>
                            <td>{{ contact.message }}</td>
                            <td>{{ contact.is_read ? "Đã đọc" : "Chưa đọc" }}</td>
                            <td>
                            <button v-if="!contact.is_read" @click="markAsRead(contact.id)">Đánh dấu đã đọc</button>
                            <button @click="deleteContact(contact.id)">Xóa</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
            </main>
      </div>
    </div>
  </template>
  
  <script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faVideo, faUsers, faBook, faSignOutAlt, faChevronLeft, faChevronRight, faEnvelope } from '@fortawesome/free-solid-svg-icons';

library.add(faVideo, faUsers, faBook, faSignOutAlt, faChevronLeft, faChevronRight, faEnvelope);

export default {
  name: "ContactManager",
  components: { FontAwesomeIcon },
  setup() {
    const router = useRouter();
    const isCollapsed = ref(false);
    const searchQuery = ref("");
    const contacts = ref([]);

    const fetchContacts = async () => {
        try {
            const token = sessionStorage.getItem("token");
            const response = await axios.get("http://127.0.0.1:8000/api/contacts", {
            headers: { Authorization: `Bearer ${token}` }
            });
            console.log("Dữ liệu API trả về:", response.data); // In dữ liệu để kiểm tra
            contacts.value = response.data;
        } catch (error) {
            console.error("Lỗi khi lấy danh sách liên hệ:", error);
        }
    };

    const deleteContact = async (id) => {
      if (confirm("Bạn có chắc chắn muốn xóa?")) {
        try {
          const token = sessionStorage.getItem("token");
          await axios.delete(`http://127.0.0.1:8000/api/contacts/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          fetchContacts();
        } catch (error) {
          console.error("Lỗi khi xóa liên hệ:", error);
        }
      }
    };

    const markAsRead = async (id) => {
        if (!confirm("Bạn có chắc chắn muốn đánh dấu tin nhắn này là đã đọc?")) return;
        try {
            const token = sessionStorage.getItem("token");
            const response = await axios.put(
            `http://127.0.0.1:8000/api/contacts/${id}/mark-as-read`, 
            {}, 
            { headers: { Authorization: `Bearer ${token}` } }
            );

            if (response.status === 200) {
            const contact = contacts.value.find((c) => c.id === id);
            if (contact) contact.is_read = true; // Cập nhật trạng thái trực tiếp mà không cần fetch lại toàn bộ danh sách
            }
        } catch (error) {
            console.error("Lỗi khi đánh dấu đã đọc:", error);
        }
    };

    const logout = () => {
      localStorage.clear();
      sessionStorage.clear();
      router.push("/login");
    };

    const toggleSidebar = () => {
      isCollapsed.value = !isCollapsed.value;
    };

    onMounted(fetchContacts);
    return { isCollapsed, searchQuery, contacts, deleteContact, markAsRead, logout, toggleSidebar };
  }
};
</script>
  
  <style scoped>
    .admin-home {
    display: flex;
    flex-direction: row;
    height: 100vh;
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
  table {
    width: 100%;
    overflow-x: auto;
    display: block; /* Đảm bảo bảng có thể cuộn ngang */
  }

  th, td {
    font-size: 14px; /* Giảm kích thước font */
    padding: 5px; /* Giảm padding */
  }

  td {
    word-wrap: break-word; /* Chia từ nếu quá dài */
  }

  /* Các cột có thể được điều chỉnh để có chiều rộng tối ưu */
  th:nth-child(1), td:nth-child(1) {
    width: 50px; /* Cột ID */
  }

  th:nth-child(2), td:nth-child(2) {
    width: 150px; /* Cột Họ Tên */
  }

  th:nth-child(3), td:nth-child(3) {
    width: 200px; /* Cột Email */
  }

  th:nth-child(4), td:nth-child(4) {
    width: 120px; /* Cột SĐT */
  }

  th:nth-child(5), td:nth-child(5) {
    width: 200px; /* Cột Tin Nhắn */
  }

  th:nth-child(6), td:nth-child(6) {
    width: 100px; /* Cột Trạng Thái */
  }

  th:nth-child(7), td:nth-child(7) {
    width: 150px; /* Cột Hành Động */
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

  /* Điều chỉnh thanh tìm kiếm */
  .search-box input {
    width: 200px; /* Thu nhỏ thanh tìm kiếm */
  }

  th, td {
    padding: 10px; /* Tăng khoảng cách cho dễ đọc */
  }

  /* Cải thiện trải nghiệm xem bảng trên di động */
  table {
    display: block;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  /* Trên màn hình rất nhỏ, ẩn một số cột không quá quan trọng */
  th:nth-child(5), td:nth-child(5), /* Tin Nhắn */ 
  th:nth-child(6), td:nth-child(6) { /* Trạng Thái */
    display: none; /* Ẩn các cột Tin Nhắn và Trạng Thái */
  }

  /* Thu gọn thêm cho các cột còn lại */
  th, td {
    font-size: 12px;
  }

  td {
    word-wrap: break-word;
  }
  
}

/* Khi sidebar mở rộng trên desktop */
@media (min-width: 768px) {
  .sidebar.collapsed {
    width: 80px;
  }

  .sidebar ul li a span {
    display: inline-block;
  }
  
  .sidebar .logo-container h2 {
    display: block;
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
  width: 200px;
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
    
table {
    width: 100%;
    border-collapse: collapse;
    display: block;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  button {
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }

  </style>
  