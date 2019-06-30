export default class Gate{


    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'admin';
    }

    
      iaAuthor(){
        return this.user.type === 'author';
    }
    isAuthororAdmin(){
        if(this.user.type === 'author' || this.user.type === 'admin'){
            return true;
        }
       
    }
}