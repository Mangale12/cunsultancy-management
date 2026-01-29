<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Branch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AgentController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        $branch = $request->get('branch');
        $perPage = $request->get('per_page', 10);

        $query = Agent::with(['branch', 'parentAgent'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($branch, function ($query, $branch) {
                $query->where('branch_id', $branch);
            })
            ->orderBy('created_at', 'desc');

        $agents = $query->paginate($perPage);
        $branches = Branch::all();
        $parentAgents = Agent::whereNull('parent_agent_id')->get();

        return Inertia::render('agents/index', [
            'agents' => $agents,
            'branches' => $branches,
            'parentAgents' => $parentAgents,
            'filters' => [
                'search' => $search,
                'branch' => $branch,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create(): Response
    {
        $branches = Branch::all();
        $parentAgents = Agent::whereNull('parent_agent_id')->get();

        return Inertia::render('agents/create', [
            'branches' => $branches,
            'parentAgents' => $parentAgents,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'phone' => 'nullable|string|max:20',
            'code' => 'required|string|max:50|unique:agents,code',
            'branch_id' => 'required|exists:branches,id',
            'parent_agent_id' => 'nullable|exists:agents,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        Agent::create($validated);

        return redirect()->route('agents.index')
            ->with('success', 'Agent created successfully.');
    }

    public function show(Agent $agent): Response
    {
        $agent->load(['branch', 'parentAgent', 'childAgents']);

        return Inertia::render('agents/show', [
            'agent' => $agent,
        ]);
    }

    public function edit(Agent $agent): Response
    {
        $branches = Branch::all();
        $parentAgents = Agent::whereNull('parent_agent_id')
            ->where('id', '!=', $agent->id)
            ->get();

        return Inertia::render('agents/edit', [
            'agent' => $agent,
            'branches' => $branches,
            'parentAgents' => $parentAgents,
        ]);
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'phone' => 'nullable|string|max:20',
            'code' => 'required|string|max:50|unique:agents,code,' . $agent->id,
            'branch_id' => 'required|exists:branches,id',
            'parent_agent_id' => 'nullable|exists:agents,id',
            'image_path' => 'nullable|string|max:255',
        ]);

        $agent->update($validated);

        return redirect()->route('agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
