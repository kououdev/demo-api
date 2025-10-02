<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Traits\ApiResponse;

class TicketController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10
        $tickets = Ticket::with('customer')->orderBy('created_at', 'desc')->paginate($perPage);
        return $this->paginatedResponse($tickets, 'Ticket list retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required',
            'description' => 'required',
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket = Ticket::create($validated);
        return $this->successResponse($ticket, 'Ticket created successfully', 201);
    }

    public function show(string $id)
    {
        $ticket = Ticket::with('customer')->findOrFail($id);
        return $this->successResponse($ticket, 'Ticket retrieved successfully');
    }

    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'subject' => 'sometimes|required',
            'description' => 'sometimes|required',
            'status' => 'sometimes|required|in:open,in_progress,closed',
        ]);

        $ticket->update($validated);
        return $this->successResponse($ticket, 'Ticket updated successfully');
    }

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return $this->successResponse(null, 'Ticket deleted successfully', 204);
    }
}
