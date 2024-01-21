<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['user' => $user, 'message' => 'User created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
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

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'string|min:6',
        ]);

        $user->update([
            'name' => $request->input('name', $user->name),
            'email' => $request->input('email', $user->email),
            'password' => $request->has('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        return response()->json(['user' => $user, 'message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function dashboard() {
        try {
            $userEmail = User::all()->pluck('email');
            echo $userEmail;
            $result = DB::table('products')
                ->select(DB::raw("'All Products' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Horticultural Product' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('products')
                        ->where('category_id', 1);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Agriculture Product' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('products')
                        ->where('category_id', 2);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Aquaculture Product' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('products')
                        ->where('category_id', 3);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Product Category' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('categories');
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Supplier' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers');
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Jan' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 1);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Feb' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 2);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Mar' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 3);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Apr' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 4);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'May' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 5);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Jun' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 6);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Jul' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 7);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Aug' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 8);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Sep' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 9);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Oct' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 10);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Nov' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 11);
                })
                ->unionAll(function ($query) {
                    $query->select(DB::raw("'Dec' AS kolom"), DB::raw('COUNT(*) AS VALUE'))
                        ->from('suppliers')
                        ->whereMonth('created_at', 12);
                })
                ->get();

                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            } catch(\Exception $error) {
                return response()->json([
                    'status' => 'error',
                    'message' => $error->getMessage()
                ]);
            }
        }
}
