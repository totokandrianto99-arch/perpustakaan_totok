<?php

namespace App\Http\Controllers;

use App\Models\{Wishlist, Buku};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('buku')->latest()->get();
        return view('member.wishlist', compact('wishlists'));
    }

    public function toggle(Buku $buku)
    {
        $user     = Auth::user();
        $existing = Wishlist::where('id_user', $user->id_user)
                            ->where('id_buku', $buku->id_buku)
                            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create(['id_user' => $user->id_user, 'id_buku' => $buku->id_buku]);
            $added = true;
        }

        $count = $user->wishlists()->count();

        return response()->json(['added' => $added, 'count' => $count]);
    }

    public function remove(Buku $buku)
    {
        Wishlist::where('id_user', Auth::id())
                ->where('id_buku', $buku->id_buku)
                ->delete();

        return back()->with('success', "Buku \"{$buku->judul}\" dihapus dari wishlist.");
    }
}
