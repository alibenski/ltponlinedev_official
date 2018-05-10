<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\TORGAN;
use Auth;
use DB;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

//Enables BCrypt for password encyption
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a specific permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all users and pass it to the view
        // $users = User::paginate(50); 
        // Gets the query string from our form submission 
        $query = \Request::input('search');
        // Returns an array of users that have the query string located somewhere within 
        // our users name. Paginates them so we can break up lots of search results.
        $users = User::where('name', 'LIKE', '%' . $query . '%')->paginate(20);

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::get();
        $cat = DB::table('LTP_Cat')->pluck("Description","Cat")->all();
        $org = TORGAN::get(["Org Full Name","Org name"]);

        return view('users.create', ['roles'=>$roles])->withCat($cat)->withOrg($org);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name, email and password fields
        $this->validate($request, [
            'indexno' => 'required|unique:users',
            'nameFirst'=>'required|max:120',
            'nameLast'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create([ 
            'indexno' => $request->indexno,
            'email' => $request->email, 
            'nameFirst' => $request->nameFirst,
            'nameLast' => $request->nameLast,
            'name' => $request->nameFirst.' '.$request->nameLast,
            'password' => Hash::make($request->password),
            'must_change_password' => 1,
            'approved_account' => 1,
        ]); 

        $user->sddextr()->create([
            'INDEXNO' => $request->indexno,
            'INDEXNO_old' => $request->indexno,
            'FIRSTNAME' => $request->nameFirst,
            'LASTNAME' => $request->nameLast,
            'EMAIL' => $request->email,
            'SEX' => $request->gender,
            'DEPT' => $request->org,
            'PHONE' => $request->contact_num,
            'CAT' => $request->cat,
        ]);

        $roles = $request['roles']; //Retrieving the roles field
    //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();            
            $user->assignRole($role_r); //Assigning role to user
            }
        }        
    //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles

        return view('users.edit', compact('user', 'roles')); //pass user and roles data to view
    }

    public function passwordReset($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        return view('users.reset', compact('user'));
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'password'=>'required|min:6|confirmed'
        ]);
        $input = ([ 
            'password' => Hash::make($request->password),
        ]); //Retreive the name, email and password fields

        $user->fill($input)->save();
        return redirect()->route('users.index')
            ->with('flash_message',
             'User: '.$user->name.' password reset successful.');
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
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            // 'password'=>'required|min:6|confirmed'
        ]);
        $input = ([ 
            'email' => $request->email, 
            'name' => $request->name,
            // 'password' => Hash::make($request->password),
        ]); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        }        
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }
}
