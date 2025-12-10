<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Bustype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BustypeController extends Controller
{
    /**
     * Display all bus types.
     */
    public function index()
    {
        return response()->json([
            'status' => 200,
            'types' => Bustype::orderBy('type', 'asc')->get()
        ]);
    }

    /**
     * Store a newly created bus type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:100|unique:bustypes,type',
        ]);

        $bustype = Bustype::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Bustype added successfully',
            'data' => $bustype
        ], 201);
    }

    /**
     * Display a single bus type.
     */
    public function show($id)
    {
        $bustype = Bustype::find($id);

        if (!$bustype) {
            return response()->json([
                'status' => 404,
                'message' => 'Bus type not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'bustype' => $bustype,
        ]);
    }

    /**
     * Update bus type.
     */
    public function update(Request $request, $id)
    {
        $bustype = Bustype::find($id);

        if (!$bustype) {
            return response()->json([
                'status' => 404,
                'message' => 'Bus type not found',
            ], 404);
        }

        $validated = $request->validate([
            'type' => 'required|string|max:100|unique:bustypes,type,' . $id,
        ]);

        $bustype->update($validated);

        return response()->json([
            'status'   => 200,
            'message'  => 'Bustype updated successfully',
            'data'     => $bustype
        ]);
    }

    /**
     * Delete bus type.
     */
    public function destroy($id)
    {
        $bustype = Bustype::find($id);

        if (!$bustype) {
            return response()->json([
                'status' => 404,
                'message' => 'Bus type not found',
            ], 404);
        }

        $bustype->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Bus type deleted successfully',
        ]);
    }
}
