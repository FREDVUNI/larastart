<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" v-show="success">The user details has been saved.</div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">users</h3>
                        <a class="btn btn-sm btn-light text-sm float-right" href="#" @click="addmodal">
							<i class="fas fa-user-plus fa-fw"></i> Add user
						</a>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-bordered">
							<thead>
								<tr>
								    <th>#</th>
									<th>Name</th>
									<th>Email</th>
									<th>Type</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type }}</td>
                                    <td>
                                        <a href="#" @click="updatemodal(user.id)" class="btn btn-sm btn-light">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" @click="deleteuser(user.id)" class="btn btn-sm btn-light">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
								<tr>
								    <th>#</th>
									<th>product</th>
									<th>Email</th>
									<th>Type</th>
									<th></th>
								</tr>
							</tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="usermodallabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modaluserlabel" v-text="modalshow?'Edit user':'Add user'"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="modalshow?updateuser(this.singleuser.id):submitForm()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" :class="{'is-invalid':form.errors.has('name')}" @keydown="form.errors.clear('name')" placeholder="Enter username" v-model="form.name">
                                <span class="invalid-feedback" v-show="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" :class="{'is-invalid':form.errors.has('email')}" @keydown="form.errors.clear('email')" placeholder="Enter email address" v-model="form.email">
                                <span class="invalid-feedback" v-show="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio <span class="text-muted">(optional)</span></label>
                                <textarea class="form-control" :class="{'is-invalid':form.errors.has('bio')}" @keydown="form.errors.clear('bio')" placeholder="Enter short user bio" v-model="form.bio" cols="5"></textarea>
                                <span class="invalid-feedback" v-show="form.errors.has('bio')" v-text="form.errors.get('bio')"></span>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" :class="{'is-invalid':form.errors.has('type')}" @keydown="form.errors.clear('type')" v-model="form.type">
                                    <option value="">Choose a user type</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Standard user</option>
                                    <option value="author">Author</option>
                                </select>
                                <span class="invalid-feedback" v-show="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                            </div>  
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" :class="{'is-invalid':form.errors.has('password')}" @keydown="form.errors.clear('password')" placeholder="Enter password" v-model="form.password">
                                <span class="invalid-feedback" v-show="form.errors.has('password')" v-text="form.errors.get('password')"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">close</button>
                            <button type="submit" class="btn btn-primary btn-sm" v-text="modalshow?'Update':'Create'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                modalshow:false,
                form:new Form({
                    id:"",
                    name:"",
                    email:"",
                    type:"",
                    bio:"",
                    password:"",
                }),
                success:false,
                users:[],
                singleuser:{},
            }
        },
        methods:{
            addmodal(){
                this.form.reset()
                this.modalshow = false,
                $("#usermodal").modal("show")
            },
            updatemodal(user){
                this.modalshow = true,
                $("#usermodal").modal("show")
                axios.get('api/user/'+ user)
                .then((resp)=>{
                    this.form.errors.clear()
                    this.singleuser = resp.data
                    this.form.name = resp.data.name
                    this.form.email = resp.data.email
                    this.form.type = resp.data.type
                    this.form.bio = resp.data.bio
                    // this.form.password = resp.data.password
                })
                .catch((err)=>{
                    console.log("something went wrong" + err);
                })
            },
            updateuser(id){
                this.modalshow = true

                let data = this.singleuser;
                
                data.name = this.form.name
                data.email = this.form.email
                data.type = this.form.type
                data.bio = this.form.bio
                // data.password = this.form.password
                
                axios.patch('api/user/'+id,data)
                .then((resp)=>{
                    this.getusers()
                    $("#usermodal").modal('hide')
                    this.success = true
                    //  console.log(resp.data);
                    this.$swal(
                        "Updated",
                        "The user has been updated.",
                        "success"
                    )
                })
                .catch((err)=>{
                    // console.log("something went wrong" + err);
                    this.form.errors.record(err.response.data)
                })
            },
            deleteuser(id){
                this.$swal({
                    title:"Are you sure?",
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton:true,
                    confirmButtonText:"Yes, delete it",
                    cancelButtonClass: '#f8f9fa',
                    confirmButtonColor: '#dc3545',
                })
                .then((result) =>{
                    if(result.value){
                        axios.delete('api/user/'+id)
                        .then((resp)=>{
                            this.$swal(
                            "Deleted",
                            "The user has been deleted.",
                            "success"
                        )
                        })
                        this.getusers()
                        .catch((error)=>{
                            console.log("Something went wrong" + error);
                        })
                    }
                })
            },
            getusers(){
                axios.get('api/user')
                .then((resp)=>{
                    // console.log(resp.data);
                    this.users = resp.data
                })
                .catch((error)=>{
                    console.log("something went wrong." + error);
                })
            },
            submitForm(){
                let data = new FormData();
                data.append("name",this.form.name)
                data.append("email",this.form.email)
                data.append("type",this.form.type)
                data.append("bio",this.form.bio)
                data.append("password",this.form.password)

                axios.post('api/user',data)
                .then((resp)=>{
                    this.getusers()
                    this.form.reset()
                    $("#usermodal").modal('hide')
                    console.log(resp.data);
                    this.success= true
                    this.$swal(
                        "Created",
                        "The user has been created.",
                        "success"
                    )
                })
                .catch((err)=>{
                    this.form.errors.record(err.response.data) 
                })
            }
        },
        mounted(){
            this.getusers()
        }

    }
</script>
<style>
.swal2-styled.swal2-cancel{
    color: #1F2D3D;
    background-color: #f8f9fa;
}
</style>
