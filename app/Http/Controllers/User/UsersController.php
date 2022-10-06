<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use wadeshuler\FlashMessages\Facades\FlashMessages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->btnEdit = config('adminlte.btn-edit');
        $this->btnDelete = config('adminlte.btn-delete');
        $this->btnDetails = config('adminlte.btn-view');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $heads = [
            'Name',
            'Email',
            'Member Since',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($users as $user){
            if($user->id == 1){
                $data[] = array(
                    $user->name,
                    $user->email,
                    Carbon::parse($user->created_at)->format('d F Y'),
                    '<nobr><a href="'.url('users/'.$user->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('users/'.$user->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
                );
            }else{
                $data[] = array(
                    $user->name,
                    $user->email,
                    Carbon::parse($user->created_at)->format('d F Y'),
                    '<nobr><a href="'.url('users/'.$user->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('users/'.$user->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('users/'.$user->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
                );
            }
        }
        
        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];

        return view('users.list', [
            'heads' => $heads,
            'config' => $config,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);

        $data = $request->all();

        $store = User::create([
            'name'  =>  $data['name'],
            'email' =>  $data['email'],
            'password'  =>  Hash::make($data['password']),
        ]);

        if($store){
            FlashMessages::addFlashMessage(
                'success',
                'User created successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to create user',
            );
        }

         return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        return view('users.edit', compact('user'));
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
        $this->validate($request, [            
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:password_confirmation',
        ]);

        $data['name'] = $request->name;

        if(!empty($request->email)){
            $user = User::find($id)->first();
            if($user->email != $request->email){
                $data['email'] = $request->email;
            }
        }

        if(!empty($request->password)){
            $data['password'] = Hash::make($request->password);
        }

        $user = User::find($id)->update($data);

        if($user){
            FlashMessages::addFlashMessage(
                'success',
                'User updated successfully',
            );
        }else{
            FlashMessages::addFlashMessage(
                'error',
                'Unable to update user',
            );
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        FlashMessages::addFlashMessage(
            'success',
            'User deleted successfully',
        );
        return redirect()->route('users');
    }
}
