<template>
    <div class="container">
      <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
         <div class="modal-content">
         <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  @submit.prevent="createUser">
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
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
      </form>
    </div>
  </div>
</div>  
</div>
</template>

<script>
    export default {
        props:['editrec'],
        data(){
            return{
               form:new Form({
                   name : '',
                   email :'',
                   password : '',
                   type :'',
                   bio : '',
                   photo :''
               })
            }

        },
        methods:{
          createUser(){
            this.$Progress.start();
              
              this.form.post('api/user')
              .then((data) =>this.$emit('addStudent',data))
              .catch(err=>console.log(this.err))
               this.form.reset();

              $('#addUserModal').modal('hide')

              Toast.fire({
               type: 'success',
                  title: 'User created in successfully'
                 })
                 
             this.$Progress.finish();
             
          }
        },
        created() {
           
        }
    }
</script>
