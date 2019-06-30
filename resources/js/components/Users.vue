<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAuthororAdmin()">
      <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users table</h3>

                <div class="card-tools">
                  <button class="btn btn-success" @click=newmodal>Add New<i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Register At</th>
                    <th>Modify</th>
                  </tr>

                  <tr v-for="data in userdatas" :key="data.id">
                    <td>{{data.id}}</td>
                    <td>{{data.name}}</td>
                    <td>{{data.email}}</td>
                    <td>{{data.type|uptext}} </td>
                    <td><span class="tag tag-success">{{data.created_at|myDate}}</span></td>
                    <td>
                      <button >
                        <i class="fa fa-edit" @click="editform(data)"></i>
                      </button>
                       <button @click="deleteuser(data.id)">
                        <i class="fa fa-trash"></i>
                       </button>
                    </td>
                  </tr>
                 
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
      </div>
       <div v-if="!$gate.isAuthororAdmin()">
       <notfound></notfound>
        </div>
    
         <div class="container">
      <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
         <div class="modal-content">
         <div class="modal-header">
        <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">Add New User</h5>
         <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Update</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  @submit.prevent="editmode ? updateuser() : createUser() ">
      <div class="modal-body">
       
        <div class="form-group">
        
      <input v-model="form.name" type="text" name="name"  placeholder="enter your name"
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
        </div>

        <div class="form-group">
      <input v-model="form.email" type="email" name="email" placeholder="enter your email"
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
        </div>

         <div class="form-group">
      <input v-model="form.password" type="password" name="password" placeholder="enter your password"
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
      <has-error :form="form" field="password"></has-error>
        </div>

        <div class="form-group">
      <select v-model="form.type" type="text" id="type"
        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
        <option value="">select User role</option>
        <option value="admin">Admin</option>
        <option value="standard User">standard User</option>
        <option value="author">Author</option>
      </select>
      <has-error :form="form" field="type"></has-error>
        </div>

         <div class="form-group">
      <textarea v-model="form.bio" type="text" name="bio" placeholder="enter your bio"
        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
      <has-error :form="form" field="bio"></has-error>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
      </div>
      </form>
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
                 editmode: false,
                 form:new Form({
                   id:'',
                   name : '',
                   email :'',
                   password : '',
                   type :'',
                   bio : '',
                   photo :''
                    }),
                    userdatas:{},
                    error:[],
                    editrec:{}

               }

             },
             methods:{
               updateuser(id){
                this.$Progress.start();
                this.form.put("api/user/" +this.form.id)
                .then(responce => {
                      
                       $('#addUserModal').modal('hide');
                           Swal.fire(
                              'update!',
                              'Your data has been update.',
                              'success'
                            )
                            this.$Progress.finish();
                            Fire.$emit('addStudent');    
                })
                .catch(()=>{
                  this.$Progress.fail();
                }) 

                  
               },
               createUser(){
                
               this.$Progress.start();
              
                this.form.post('api/user')
               Fire.$emit('addStudent');
               this.form.reset();

              $('#addUserModal').modal('hide')

              Toast.fire({
               type: 'success',
                  title: 'User created in successfully'
                 })
                 
             this.$Progress.finish();
             
          },
               editform(data){
                     this.editmode = true;
                    $("#addUserModal").modal('show');
                      this.form.fill(data);
                  
               },
               newmodal(){
                   this.editmode = false;
                   this.form.reset();
                   $("#addUserModal").modal('show');
               },
               deleteuser(id){
                 Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                        })

                        

                        .then((result) => {
                          if (result.value) {
                            axios.delete("api/user/" + id)
                        .then(responce=>this.userdatas =responce.data)
                       .catch(error => this.error = error.responce.data.errors)
                            Swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            )
                          }
                        })
                  

               },    
               dataload(){
                     if(this.$gate.isAuthororAdmin()){
                               axios.get('api/user')
                          .then((responce) =>this.userdatas = responce.data)
                          .catch(error =>this.error = error.responce.data.errors)
                     }
                  
                       
                   }
             },
        created() {

                    Fire.$on('search',()=>{
                         let queery=this.$parent.search;
                         axios.get('api/search?q=' + queery)
                         then((data)=>{
                           this.userdatas = data.data
                         })
                         .catch((error)=>{

                         })
                       })
                      this.dataload();

                     Fire.$on('addStudent',() => {
                         
                          this.dataload();
                       });
                       
                       
                      
                         
                  
        }
    }
</script>
