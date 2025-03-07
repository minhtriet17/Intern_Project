<template>
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Lectures</h4>
                <RouterLink to="/lectures/create" class="btn btn-primary float-end">Add Lecture</RouterLink>
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
                        <tbody v-if="this.lectures.length > 0">
                            <tr v-for="(data, index) in this.lectures" :key="index">
                                <td>{{ data.id }}</td>
                                <td>{{ data.title }}</td>
                                <td>{{ data.description }}</td>
                                <td>{{ data.video_url }}</td>
                                <td>{{ data.category }}</td>
                                <td>{{ data.created_at }}</td>
                                <td>
                                    <RouterLink :to="{path: '/lectures/'+data.id+'/edit'}" class="btn btn-success">Edit</RouterLink>
                                    <button type="button" @click="deleteLecture(data.id)" class="btn btn-danger mx-1">Delete</button>
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
            deleteLecture(lectureId){
                if(confirm("Are you sure you want to delete this lecture?")){
                    //console.log(lectureId);
                    axios.delete(`http://localhost:8000/api/lectures/${lectureId}/delete`).then(res => {
                        alert(res.data.message)
                        this.getLectures();
                    }).catch(function (error){
                        if (error.response) {
                            if(error.response.status == 404){ 
                                alert(error.response.data.message);
                            }
                        }
                    });
                }
                
            },
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

<style scoped>
.table-responsive {
    overflow-x: auto;
}
</style>


