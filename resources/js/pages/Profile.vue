<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header text-white" style="background-image:url('./images/background.jpg')">
                        <h3 class="widget-user-username">Fred admin</h3>
                        <h4 class="widget-user-desc">web developer</h4>
                    </div>
                    <div class="widget-user-image" v-if="imgPreview">
                        <img :src="imgPreview" alt="profile" class="profile-user-img img-fluid img-circle">
                    </div>
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div>
                    <div class="tab-content container">
                    <div class="active tab-pane" id="activity">

                    </div>
                    <div class="tab-pane" id="settings">
                         <div class="alert alert-success" v-show="success">The user details has been saved.</div>
                        <form class="mt-3" method="post" @submit.prevent="updateForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" :class="{'is-invalid':form.errors.has('name')}" @keydown="form.errors.clear('name')" placeholder="Enter name" v-model="form.name">
                                <span class="text-danger" v-show="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" :class="{'is-invalid':form.errors.has('email')}" @keydown="form.errors.clear('email')" placeholder="Enter email address" v-model="form.email">
                                <span class="text-danger" v-show="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Experience</label>
                                <textarea class="form-control" placeholder="Experience" v-model="form.experience" @keydown="form.errors.clear('experience')"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Profile Picture</label>
                                <br>
                                <input type="file" @change="previewImg($event)" ref="photo" :class="{'is-invalid':form.errors.has('photo')}" accept="image/*">
                                <span class="text-danger" v-show="form.errors.has('photo')" v-text="form.errors.get('photo')"></span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="text-muted">(you can leave it)</span></label>
                                <input type="password" class="form-control" :class="{'is-invalid':form.errors.has('password')}" @keydown="form.errors.clear('password')" placeholder="Enter password" v-model="form.password">
                                <span class="text-danger" v-show="form.errors.has('password')" v-text="form.errors.get('password')"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn btn-sm">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                form:new Form({
                    name:"",
                    email:"",
                    experience:"",
                    // password:""
                }),
                photo:"",
                image:"",
                imgPlaceholder:this.photo,
                user:{},
                success:false
            }
        },
        methods:{
            previewImg(event){
                let image = event.target.files[0]
                this.photo = image
                // console.log(this.photo)
                let reader = new FileReader();
                
                if(image['size'] < 2111775){
                    reader.onloadend = (image) => {
                        this.photo = reader.result
                    }
                    reader.readAsDataURL(image);
                    reader.onload = event => {
                        this.imgPreview = event.target.result
                    }
                }else{
                    this.$swal({
                        type:"error",
                        title:"Ooops...",
                        text:"The image must no be more than 2MBs.",
                    })
                }
                // const file = event.target.files[0]
                // this.photo = URL.createObjectURL(file)

            },
            updateForm(){
                // let formData = new FormData()
                // formData.append("name",this.form.name)
                // formData.append("email",this.form.email)
                // formData.append("bio",this.form.experience)
                // formData.append("photo",this.$refs.photo.files[0])

                let data = this.user
                data.name =this.form.name
                data.email =this.form.email
                data.bio =this.form.experience 
                // data.password =this.form.password
                data.photo = this.photo
                
                axios.post("api/profile",data)
                .then((resp)=>{
                    this.user()
                    $("#usermodal").modal('hide')

                    // console.log(resp.data);
                    this.success= true
                    this.$swal(
                        "Updated",
                        "The user has been updated.",
                        "success"
                    )
                })
                .catch((err)=>{
                    this.form.errors.record(err.response.data)
                    console.log("something went wrong " + err);
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.imgPreview
        },
        created(){
            axios.get("api/profile")
            .then((resp)=>{
                this.user = resp.data
                this.form.name = resp.data.name
                this.form.email = resp.data.email
                this.form.experience = resp.data.bio
                var image = resp.data.photo

                if(image != ""){
                    this.photo =  "./images/uploads/profile/"+image
                }else{
                    this.photo = this.photo
                }
            })
            .catch((error)=>{
                console.log("something went wrong" + error);
            })
        },
        computed:{
            imgPreview(){
                return this.photo == "" ? this.imgPlaceholder:this.photo
            }
        }
    }
</script>
<style scoped>
.widget-user-header{
    background-position:center center;
    background-size:cover;
    height:200px;
}
.profile-user-img {
    border: 5px solid steelblue;
    margin: 0 auto;
    padding: 3px;
    width: 100px;
}
</style>
