<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->all());

        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Create a new student record
            $student = new Student();
            $student->name = $request->name;
            $student->age = $request->age;
            $student->status = $request->status;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                Storage::putFileAs('public/images', $image, $imageName);
                $student->image = $imageName;
            }

            $student->save();


            return Redirect::back()->with('success', 'Student created successfully.');
        } catch (\Exception $e) {

            Log::error('Error saving student: ' . $e->getMessage());


            return Redirect::back()->with('error', 'Failed to save student.');
        }
    }

    public function index()
    {

        $students = Student::all();

        $formattedStudents = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'age' => $student->age,
                'status' => $student->status,
                'image' => $student->image,

            ];
        });


        return Inertia::render('Index', [
            'students' => $formattedStudents,
        ]);
    }

    public function edit($id)
    {

        $student = Student::findOrFail($id);

        return inertia('Edit', ['student' => $student]);
    }

    public function update(Request $request, $id)
{
    try {
        \Log::info('Request Payload:', $request->all());
        \Log::info('Student ID:', ['id' => $id]);

        // Retrieve the student by ID
        $student = Student::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'status' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Log the validated data
        \Log::info('Validated data:', $validatedData);

        // Update student data
        $student->update([
            'name' => $validatedData['name'],
            'age' => $validatedData['age'],
            'status' => $validatedData['status'],
        ]);

        // Log the updated student data
        \Log::info('Updated student data:', $student->toArray());

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $student->image = $imageName;
            $student->save();
        }

        // Log a success message
        \Log::info('Student information updated successfully.');

        // Return Inertia response
        return Inertia::location(route('Index'))->with('success', 'Student information updated successfully.');
    } catch (\Exception $e) {
        // Log any errors
        \Log::error('Error updating student: ' . $e->getMessage());

        // Return error response
        return back()->withError('An error occurred while updating student information.')->withErrors($e->getMessage());
    }
}

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete student'], 500);
        }
    }






}
