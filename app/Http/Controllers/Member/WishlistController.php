<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id'
        ]);

        // Cek apakah sudah ada di wishlist
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah ada di daftar favorit Anda'
            ], 422);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan ke favorit'
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus dari favorit'
        ]);
    }

    public function index()
    {
        $wishlists = Wishlist::with(['buku', 'buku.pengarang', 'buku.kategori'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('member.wishlist.index', compact('wishlists'));
    }

    public function check($buku_id)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('buku_id', $buku_id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}