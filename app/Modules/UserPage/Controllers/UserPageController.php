<?php

namespace App\Modules\UserPage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\UserPage\Models\UserPage;

class UserPageController extends Controller
{
    // Display the list of user pages
    public function index()
    {
        $active_menu = 'userpage_list'; // Thêm biến này để xác định menu hiện tại
        $userpage = UserPage::paginate(10); // Thay đổi all() thành paginate()
        return view('UserPage::userpage.index', compact('userpage', 'active_menu'));
    }

    // Show the form for creating a new user page
    public function create()
    {
        $active_menu = 'userpage_add'; // Cập nhật biến này
        return view('UserPage::userpage.create', compact('active_menu'));
    }

    // Store a new user page
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:user_pages',
            'summary' => 'required',
            'items' => 'required',
        ]);

        UserPage::create($validatedData);

        return redirect()->route('admin.userpage.index')->with('success', 'User page created successfully.');
    }

    // Show a specific user page
    public function show(UserPage $userpage)
    {
        $active_menu = 'userpage_show'; // Cập nhật biến này
        return view('UserPage::userpage.show', compact('userpage', 'active_menu'));
    }

    // Show the form for editing an existing user page
    public function edit(UserPage $userpage)
    {
        $active_menu = 'userpage_edit'; // Cập nhật biến này
        return view('UserPage::userpage.edit', compact('userpage', 'active_menu'));
    }

    // Update a user page
    public function update(Request $request, UserPage $userpage)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:user_pages,slug,' . $userpage->id,
            'summary' => 'required',
            'items' => 'required',
        ]);

        $userpage->update($validatedData);

        return redirect()->route('admin.userpage.index')->with('success', 'User page updated successfully.');
    }

    // Delete a user page
    public function destroy(UserPage $userpage)
    {
        $userpage->delete();
        return redirect()->route('admin.userpage.index')->with('success', 'User page deleted successfully.');
    }
}
