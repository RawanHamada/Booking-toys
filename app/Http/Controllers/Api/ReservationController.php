<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'num_of_seats' => 'required|numeric|integer|max:1',
            'date' => 'nullable|date',
            'time' => 'required|string',
            'cafe_id' => 'required|exists:cafes,id'
        ]);

        $errors = $validator->errors();
        $user = Auth::guard('sanctum')->user();

        if(!$user){
            return response()->json([
                'message_en' => 'عذراً, سجل الدخول',

                'message_ar' => 'Sorry, log in',

                'code' => 400,
                'status' => false,
            ], 400);
        }
        if( $validator->fails() ) {

            return response()->json([
                'message_en' => $errors,

                'message_ar' => 'خطأ.',

                'code' => 400,
                'status' => false,
            ], 400);

        }

        // Create a new reservation
        $reservation = new Reservation();

        $reservation->user_id = Auth::guard('sanctum')->user()->id;
        // Assuming authentication is required
        $reservation->cafe_id = $request->input('cafe_id');
        $reservation->num__of_seats = $request->input('num_of_seats');

        if ($request->has('date') && $request->has('time')) {

            $reservation->date = $request->input('date');
            $reservation->time = $request->input('time');
            // Save the reservation
            $reservation->save();

            return response()->json([
                'message_en' => 'Reservation created successfully',

                'message_ar' => 'تم إنشاء الحجز بنجاح',

                'code' => 201,
                'status' => true,
            ], 200);
        }
        $reservation->date = Carbon::now();

        // Save the reservation
        $reservation->save();

        // Return a response
        return response()->json([
            'message_en' => 'Reservation created successfully',

            'message_ar' => 'تم إنشاء الحجز بنجاح',

            'code' => 201,
            'status' => true,
        ], 200);    }

    public function update(Request $request, $id)
    {
        // Find the reservation
        $reservation = Reservation::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // Other validation rules
        ]);

        // Update the reservation
        $reservation->start_date = $request->input('start_date');
        $reservation->end_date = $request->input('end_date');
        // Update other attributes

        // Save the reservation
        $reservation->save();

        // Return a response
        return response()->json(['message' => 'Reservation updated successfully'], 200);
    }

    // public function delete($id)
    // {
    //     // Find the reservation
    //     $reservation = Reservation::
    // }
}
