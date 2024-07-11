<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('assets/images'), $imageName);

        $menu = Menu::create([
            'nama' => $request->name,
            'deskripsi' => $request->description,
            'image' => $imageName,
            'harga' => $request->price,
        ]);

        return response()->json(['message' => 'Menu created successfully', 'menu' => $menu], 201);
    }

    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'sometimes|required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $menu->update($validatedData);
        return response()->json(['message' => 'Menu updated successfully', 'menu' => $menu], 200);
    }

    public function delete(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully'], 200);
    }
}
