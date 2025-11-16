<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index($user_id)
    {
        return Wishlist::with('product')
            ->where('user_id', $user_id)
            ->get();
    }


    public function store(Request $request)
    {
        $item = Wishlist::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json($item, 201);
    }

    public function destroy($id)
    {
        Wishlist::findOrFail($id)->delete();
        return response()->json(['message' => 'Removed'], 200);
    }
}
