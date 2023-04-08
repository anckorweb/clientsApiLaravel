<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::all();

        if(!$clients)
        {
            $data = [
                "status" => "error",
                "message" => "No data found"
            ];

            return response()->json($data, 404);
        }

        $data = [
            "status" => "success",
            "message" => "Clients retrieved successfully",
            "data" => [
                "clients" => $clients
            ]
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            "name"    => "required|string|max:255",
            "email"   => "required|email|max:255|unique:clients,email",
            "phone"   => "nullable|string|max:10",
            "address" => "nullable|string|max:100"
        ], [
            "name.required" => "El campo nombre es obligatorio.",
            "email.email" => "Debes introducir un email válido."
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

        $client          = New Client;
        $client->name    = $request->name;
        $client->email   = $request->email;
        $client->phone   = $request->phone;
        $client->address = $request->address;
        $client->save();

        $data = [
            "status" => "success",
            "message" => "Client created successfully",
            "data" => [
                "clients" => $client
            ]   
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified client.
     */
    public function show($client)
    {
        try
        {
            $client = Client::findOrFail($client);

            $data = [
                "status" => "success",
                "message" => "Correct request",
                "data" => [
                    "client" => $client
                ]
            ];

            return response()->json($data, 200);
        }

        catch (ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid client id"
            ];

            return response()->json($data, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $client)
    {
        try
        {
            $client = Client::findOrFail($client);
        }

        catch (ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid client id"
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), 
        [
            "name"    => "required|string|max:255",
            "email"   => "required|email|max:255|unique:clients,email,".$client->id,
            "phone"   => "nullable|string|max:10",
            "address" => "nullable|string|max:100"
        ], [
            "name.required" => "El campo nombre es obligatorio.",
            "email.email" => "Debes introducir un email válido."
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

        $client->name    = $request->name;
        $client->email   = $request->email;
        $client->phone   = $request->phone;
        $client->address = $request->address;
        $client->save();

        $data = [
            "status" => "success",
            "message" => "Client updated successfully",
            "data" => [
                "clients" => $client
            ]
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($client)
    {
        try
        {
            $client = Client::findOrFail($client);
        }
        
        catch(ModelNotFoundException $e)
        {
            $data = [
                "status" => "error",
                "message" => "Invalid client id"
            ];

            return response()->json($data, 404);
        }

        $client->delete();

        $data = [
            "status" => "success",
            "message" => "Client deleted successfully",
            "data" => [
                "clients" => $client
            ]   
        ];

        return response()->json($data);
    }
}
