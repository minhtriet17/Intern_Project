<template>
    <div class="login-container">
      <div class="login-box">
          <h2 class="text-center">Welcome Back</h2>
          <p class="text-center">Please login to continue</p>
          <form @submit.prevent="saveData">
              <div class="form-group">
                  <label>Email</label>
                  <input type="email" v-model="student.email" class="form-control" placeholder="Enter your email" required>
              </div>
  
              <div class="form-group">
                  <label>Password</label>
                  <input type="password" v-model="student.password" class="form-control" placeholder="Enter your password" required>
              </div>
  
              <button type="submit" class="btn btn-primary btn-block">Login</button>
              <p class="text-center mt-3"><a href="#">Forgot password?</a></p>
              <p class="text-center mt-3"><a href="/register">Register an account</a></p>
          </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { reactive, onMounted } from 'vue';
  import { useRouter } from 'vue-router'; 
  
  export default {
      name: 'Login',

      data() {
        return {
            user: {
            email: '',
            password: ''
            }
        };
        },
        methods: {
        async saveData() {
            try {
            const response = await axios.post('http://localhost/api/login', this.user);
            if (response.data.status) {
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('role', response.data.role);
                
                if (response.data.role === 'admin') {
                this.$router.push('/admin');
                } else {
                this.$router.push('/user');
                }
            } else {
                alert(response.data.message);
            }
            } catch (error) {
                alert('Login failed. Please check your credentials.');
            }
        }
        },
      setup() {
          const router = useRouter(); 
          const student = reactive({
              email: '',
              password: ''
          });
  
          const saveData = () => {
              axios.post("http://127.0.0.1:8000/api/login", student)
              .then(({ data }) => {
                  console.log(data);
                  alert("Login successfully");
                  router.push({ name: 'HomePage' }); 
                  // Lưu token & role vào localStorage
                    localStorage.setItem("token", data.token);
                    localStorage.setItem("role", data.user.role); 

                    // Chuyển hướng theo role
                    if (data.user.role === "admin") {
                        router.push({ name: "HomePage" }); // Chuyển đến trang Admin
                    } else {
                        router.push({ name: "User" }); // Chuyển đến trang User
                    }
              })
              .catch((error) => {
                  console.error(error);
                  alert("Wrong password or account, please try again");
              });
          };
  
          onMounted(() => {
              console.log("mounted() called...");
          });
  
          return { student, saveData };
      }
  };
  </script>
  
  <style lang="scss" scoped>
  .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #667eea, #764ba2);
  }
  
  .login-box {
      background: #fff;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      width: 380px;
      text-align: center;
  }
  
  h2 {
      color: #333;
      margin-bottom: 0.5rem;
  }
  
  p {
      color: #666;
      margin-bottom: 1.5rem;
  }
  
  .form-group {
      margin-bottom: 1.2rem;
      text-align: left;
  }
  
  .form-control {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
  }
  
  .btn-primary {
      width: 100%;
      padding: 12px;
      background-color: #5a67d8;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      color: white;
      cursor: pointer;
      transition: background 0.3s ease;
  
      &:hover {
          background-color: #434190;
      }
  }
  
  .text-center a {
      color: #5a67d8;
      text-decoration: none;
      transition: color 0.3s ease;
  
      &:hover {
          color: #434190;
      }
  }
  </style>
  