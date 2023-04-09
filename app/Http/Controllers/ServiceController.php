<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = Service::all();

        if(!$services)
        {
            $data = [
                "status" => "error",
                "message" => "No data found"
            ];

            return response()->json($data, 404);
        }

        $data = [
            "status" => "success",
            "message" => "Services retrieved successfully",
            "data" => [
                "services" => $services
            ]
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            "name"       => "required|string|max:150",
            "descrption" => "nullable|string",
            "price"      => "required|integer",
        ], [
            "name.required" => "El campo nombre es obligatorio.",
            "price.integer" => "Debes introducir un número."
        ]);

        if($validator->fails())
        {
            $data = [
                "status" => "error",
                "message" => "Validation error",
                "validation" => $validator->errors()
            ];

            return response()->json($data, 422);
        }

        $service              = New Service;
        $service->name        = $request->name;
        $service->description = $request->description;
        $service->price       = $request->price;
        $service->save();

        $data = [
            "status" => "success",
            "message" => "Service created successfully",
            "data" => [
                "service" => $service
            ]   
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified service.
     */
    public function show($service)
    {
        try
        {
            $service = Service::findOrFail($service);

            $data = [
                "status" => "success",
                "message" => "Correct request",
                "data" => [
                    "service" => $service
                ]
            ];

            return response()->json($data, 200);
        }

        catch (ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid service id"
            ];

            return response()->json($data, 404);
        }
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $service)
    {
        try
        {
            $service = Service::findOrFail($service);
        }

        catch (ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid service id"
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), 
        [
            "name"       => "required|string|max:150",
            "descrption" => "nullable|string",
            "price"      => "required|integer",
        ], [
            "name.required" => "El campo nombre es obligatorio.",
            "price.integer" => "Debes introducir un número."
        ]);

        if($validator->fails())
        {
            $data = [
                "status" => "error",
                "message" => "Validation error",
                "validation" => $validator->errors()
            ];

            return response()->json($data, 422);
        }

        $service->name        = $request->name;
        $service->description = $request->description;
        $service->price       = $request->price;
        $service->save();

        $data = [
            "status" => "success",
            "message" => "Service updated successfully",
            "data" => [
                "service" => $service
            ]
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($service)
    {
        try
        {
            $service = Service::findOrFail($service);
        }
        
        catch(ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid service id"
            ];

            return response()->json($data, 404);
        }

        $service->delete();

        $data = [
            "status" => "success",
            "message" => "Service deleted successfully",
            "data" => [
                "service" => $service
            ]   
        ];

        return response()->json($data);
    }
}
