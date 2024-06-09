<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = DB::table('users')
            ->join('commandes', 'commandes.user_id', '=', 'users.id')
            ->join('products', 'commandes.product_id', '=', 'products.id')
            ->select('products.*', 'users.*', 'commandes.*')
            ->get();

        return response()->json($commandes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|min:1',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::table('commandes')->insert([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['message' => 'Commande created successfully'], 201);
    }

    public function destroy($id)
    {
        $deleted = DB::table('commandes')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Commande deleted successfully.']);
        } else {
            return response()->json(['message' => 'Commande not found.'], 404);
        }
    }
}
