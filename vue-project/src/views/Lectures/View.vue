<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Lectures</h4>
                <RouterLink to="/lectures/create" class="btn btn-primary float-end">Add Lecture</RouterLink>
            </div>
            <div class="card-body">
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
                    <tbody v-if="this.lectures.length > 0">
                        <tr v-for="(data, index) in this.lectures" :key="index">
                            <td>{{ data.id }}</td>
                            <td>{{ data.title }}</td>
                            <td>{{ data.description }}</td>
                            <td>{{ data.video_url }}</td>
                            <td>{{ data.category }}</td>
                            <td>{{ data.created_at }}</td>
                            <td>
                                <RouterLink :to="{path: '/lecture/'+data.id+'/edit'}" class="btn btn-success">Edit</RouterLink>
                                <button type="button" class="btn btn-danger">Delete</button>
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
</template>


<script>
    import axios from 'axios'

    export default {
        name: "lectures",
        data(){
            return {
                lectures: []
            }
        },
        mounted(){

            this.getLectures();
            //console.log('Component mounted.')
        },
        methods: {
            getLectures(){
                axios.get('http://localhost:8000/api/lectures')
                .then(res => {
                    this.lectures = res.data.data
                    console.log(this.lectures)
                })
                .catch(error => {
                    console.log(error)
                })
            }
        },
    }
</script>
  