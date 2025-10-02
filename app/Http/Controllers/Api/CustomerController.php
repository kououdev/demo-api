<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Traits\ApiResponse;

class CustomerController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10
        $customers = Customer::with('tickets')->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->paginatedResponse($customers, 'Customer list retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:customers,email',
        ]);

        $customer = Customer::create($validated);
        return $this->successResponse($customer, 'Customer created successfully', 201);
    }

    public function show(string $id)
    {
        $customer = Customer::with('tickets')->findOrFail($id);
        return $this->successResponse($customer, 'Customer retrieved successfully');
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|unique:customers,email,' . $id,
        ]);

        $customer->update($validated);
        return $this->successResponse($customer, 'Customer updated successfully');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return $this->successResponse(null, 'Customer deleted successfully', 204);
    }
}
