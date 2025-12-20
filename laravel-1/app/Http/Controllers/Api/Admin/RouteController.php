<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route as RouteModel; // ✅ alias to avoid conflict

class RouteController extends Controller
{
    /**
     * Display all routes (with pagination).
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $routes = RouteModel::orderBy('route_code', 'asc')
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $routes,
        ]);
    }
    public function routesfetch()
    {
        return response()->json([
            'status' => 200,
            'data' => RouteModel::orderBy('route_code')->get(['id', 'route_code']),
        ]);
    }

    /**
     * Store a newly created route.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_code'     => 'required|string|max:100',
            'start_location' => 'required|string|max:100',
            'end_location'   => 'required|string|max:100',
            'distance'       => 'nullable|string|max:50',
            'duration'       => 'nullable|string|max:50',
        ]);

        $route = RouteModel::create($validated);

        return response()->json([
            'status'  => 201,
            'message' => 'Route added successfully',
            'data'    => $route,
        ], 201);
    }

    /**
     * Display a single route.
     */
    public function show($id)
    {
        $route = RouteModel::find($id);

        if (!$route) {
            return response()->json([
                'status'  => 404,
                'message' => 'Route not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data'   => $route,
        ]);
    }

    /**
     * Update a route.
     */
    public function update(Request $request, $id)
    {
        $route = RouteModel::find($id);

        if (!$route) {
            return response()->json([
                'status'  => 404,
                'message' => 'Route not found',
            ], 404);
        }

        $validated = $request->validate([
            'route_code'     => 'required|string|max:100',
            'start_location' => 'required|string|max:100',
            'end_location'   => 'required|string|max:100',
            'distance'       => 'nullable|string|max:50',
            'duration'       => 'nullable|string|max:50',
        ]);

        $route->update($validated);

        return response()->json([
            'status'  => 200,
            'message' => 'Route updated successfully',
            'data'    => $route,
        ]);
    }

    /**
     * Delete a route.
     */
    public function destroy($id)
    {
        $route = RouteModel::find($id);

        if (!$route) {
            return response()->json([
                'status'  => 404,
                'message' => 'Route not found',
            ], 404);
        }

        $route->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Route deleted successfully',
        ]);
    }
}
