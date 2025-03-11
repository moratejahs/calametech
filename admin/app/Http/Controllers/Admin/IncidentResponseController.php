<?php

namespace App\Http\Controllers\Admin;

use App\Models\SOS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncidentResponseController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'id' => 'required', // Ensure the ID exists in the SOS table
            'status' => 'required',
            'type' => 'required',
            'address' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validate image format & size
        ]);

        // Retrieve the SOS record
        $sos = SOS::findOrFail($validated['id']);

        // Handle file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sos_images', 'public'); // Store in 'storage/app/public/sos_images'
            $sos->image_path = $imagePath; // Update model property
        }

        // Update SOS record
        $sos->status = $validated['status'];
        $sos->address = $validated['address'];
        $sos->type = $validated['type'];
        $sos->save(); // Save updates

        return redirect()->route('admin.admin-projects')->with('message', 'Incident response submitted successfully');
    }

}
