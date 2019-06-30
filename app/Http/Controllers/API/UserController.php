<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Validator;

class UserController extends Controller
{


    public function __construct(){
         $this->middleware('auth:api');
        //  $this->authorize('isAdmin'); //its take the all function work
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $this->authorize('isAdmin');
       if (\Gate::allows('isAdmin') ||  \Gate::allows('isAuthor')) {
        $data = User::orderBY('id','desc')->get();
        return request()->json(200 , $data );
    }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $data=Validator::make($request->all(),[
                'name' => 'required|string|min:6|max:191',
                'email' => 'required|string|email|max:180',
                'password' => 'required|string|min:6'
             ],[
                 "name.required"=>"NAME is needed",
                 "email.required"=>"email is needed",
                 "email.email"=>"email should be valid emalil"
             ])->validate();

           User::Create([
          'name' => $request['name'],
          'email' => $request['email'],
          'type' => $request['type'],
          'bio' => $request['bio'],
          'photo' => $request['photo'],
          'password' => Hash::make($request['password']),
             ]);

             $data = User::orderBY('id','desc')->get();
             return request()->json(200 , $data );

               
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         
    }

    public function profile()
    {
        // return Auth::user();   this is for normal controller 
        return auth("api")->user();        ///this is for api
    }
      
    public function updateprofile(Request $request)
    {
            $user = auth("api")->user(); 


            $data=Validator::make($request->all(),[
              'name' => 'required|string|min:6|max:191',
              'email' => 'required|string|email|max:180',
              'password' => 'sometimes|required|string|min:6'
           ],[
               "name.required"=>"NAME is needed",
               "email.required"=>"email is needed",
               "email.email"=>"email should be valid emalil"
           ])->validate();



                $currentphoto = $user->photo;
            if($request->photo != $currentphoto){

              
               $name =time().'.' .explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
               \Image::make($request->photo)->save(public_path('/img/profile/').$name);
                   $request->merge(['photo' => $name]);
               
                    $userPhoto =public_path('/img/profile/').$currentphoto;
                    if(file_exists($userPhoto)){
                           @unlink($userPhoto);
                   }
                 }

            if(!empty($request->password)){
                $request->merge(['password' => Hash::make($request['password'])]);
            }
                                   
             $user->update($request->all());
             return ['msg'=>'succ'];
             
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
           $user = User::find($id);
             $user->update($request->all());
              return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('isAdmin');
          $delete = User::find($id);
          $delet = $delete->delete();
          if($delet) {
          $data = User::orderBY('id','desc')->get();
          return request()->json(200 , $data );
          }
          else{
            return  request()->json(200 , 'this data id not delete');
          }
         
    }

    public function search(){
        if($search = \Request::get('q')){
            $user = User::where(function($query) use ($search){
                $query->where('name','LIKE','% search %')
                      ->orWhere('email','LIKE','% search %');
                     
            })->get();
        }
        return $user;
    }
}
