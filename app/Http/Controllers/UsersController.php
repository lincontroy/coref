<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loans;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
    }

    public function applyloan(){
        return view('apply');
    }

    public function applyloanpost(Request $request){
        if($request->loanType==''){
            return back()->withErrors(['tel' => 'Loan type cannot be blank']);
        }
        $loans=new Loans();
        $loans->loanType=$request->loanType;

        if($request->loanType=='Emergency mkopo'){
            $loans->amount="2500";
            $loans->fee="499";
        }else if($request->loanType=='Car mkopo'){
            $loans->amount="500,000";
            $loans->fee="4,999";
        }else if($request->loanType=='Education mkopo'){
            $loans->amount="25,000";
            $loans->fee="999";
        }else if($request->loanType=='Rental mkopo'){
            $loans->amount="20,000";
            $loans->fee="899";
        }else if($request->loanType=='Business mkopo'){
            $loans->amount="50,000";
            $loans->fee="1499";
        }else if($request->loanType=='Personal loan'){
            $loans->amount="10,000";
            $loans->fee="799";
        }else{
            return back()->withErrors(['error' => 'Unidentified Loan']);
        }
        
        //save this data to loans table and initialze

        $loans->user=Auth::user()->id;
        $user=Auth::user();
        
        if($loans->save()){

                $request->session()->put('loans', $loans);
                $request->session()->put('user', $user);

                return redirect()->route('loan');

        }else{
            return back()->withErrors(['error' => 'Loan could not be created']);
        }
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('register');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function login(){
        return view('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Redirect to the login page after logout
        return redirect('/login');
    }

    public function loginpost(Request $request){

        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('dashboard');
        }

        // Authentication failed...
        return back()->withErrors(['tel' => 'Invalid phone number or password']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user=new User();

        $user->name=$request->fname;
        $user->email=$request->email;
        $user->phone=$request->tel;

        $user->nationaId =$request->nationalId;
        // $user->loanType =$request->loantype;
        $user->password=Hash::make($request->password);

        //create Loan item

        

        
        

        if ((User::where('email', $user->email)->exists()) || (User::where('phone', $user->phone)->exists())  ) {
            return redirect()->route('register')->with('error', 'User with that email or phone exists');
        } else {

            // User does not exist, proceed with creating the user
            $user->save();


            $credentials = [
                'phone' => $request->tel, // Map 'tel' from the request to 'phone' in the database
                'password' => $request->password,
            ];

            // dd($credentials);

            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return redirect()->route('dashboard');
            }

        

            // $loans=new Loans();

            // $loans->user=$user->id;
            // $loans->loanType=$request->loantype;

            // $loans->amount="5,500";

            // $loans->status=$request->status;

            // if($loans->save()){
            //     $request->session()->put('loans', $loans);
            //     $request->session()->put('user', $user);

            //     return redirect()->route('loan');
            // }


            
            // Handle successful user creation
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Users $users)
    {
        //
    }

    public function loan(Request $request){

        return view ('loan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users $users)
    {
        //
    }
}
