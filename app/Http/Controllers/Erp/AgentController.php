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

    public function index()
    {
        // Eager load branch and parent for better performance
        $agents = Agent::with(['branch'])->get();
        return view('admin.agent.index', compact('agents'));
    }

    public function create()
    {
        $branches = Branch::all();
        $parentAgents = Agent::where('parent_agent_id', null)->get(); // List of main agents
        return view('admin.agent.form', compact('branches', 'parentAgents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|unique:agents,code',
            'email'           => 'required|email|unique:agents,email',
            'phone'           => 'nullable|string|max:20',
            'branch_id'       => 'required|exists:branches,id',
            'parent_agent_id' => 'nullable|exists:agents,id',
            'image_path'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('agents', 'public');
        }

        Agent::create($validated);

        return redirect()->route('agents.index')->with('success', 'Agent created successfully.');
    }

    public function edit(Agent $agent)
    {
        $branches = Branch::all();
        // Prevent an agent from being its own parent
        $parentAgents = Agent::where('id', '!=', $agent->id)->get(); 
        return view('admin.agent.form', compact('agent', 'branches', 'parentAgents'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|unique:agents,code,' . $agent->id,
            'email'           => 'required|email|unique:agents,email,' . $agent->id,
            'phone'           => 'nullable|string|max:20',
            'branch_id'       => 'required|exists:branches,id',
            'parent_agent_id' => 'nullable|exists:agents,id',
            'image_path'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            if ($agent->image_path) { Storage::disk('public')->delete($agent->image_path); }
            $validated['image_path'] = $request->file('image_path')->store('agents', 'public');
        }

        $agent->update($validated);

        return redirect()->route('agents.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        if ($agent->image_path) { Storage::disk('public')->delete($agent->image_path); }
        $agent->delete();
        return redirect()->route('agents.index')->with('success', 'Agent deleted.');
    }
    // public function index(Request $request): Response
    // {
    //     $search = $request->get('search');
    //     $branch = $request->get('branch');
    //     $perPage = $request->get('per_page', 10);

    //     $query = Agent::with(['branch', 'parentAgent'])
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('email', 'like', "%{$search}%")
    //                   ->orWhere('code', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($branch, function ($query, $branch) {
    //             $query->where('branch_id', $branch);
    //         })
    //         ->orderBy('created_at', 'desc');

    //     $agents = $query->paginate($perPage);
    //     $branches = Branch::all();
    //     $parentAgents = Agent::whereNull('parent_agent_id')->get();

    //     return Inertia::render('agents/index', [
    //         'agents' => $agents,
    //         'branches' => $branches,
    //         'parentAgents' => $parentAgents,
    //         'filters' => [
    //             'search' => $search,
    //             'branch' => $branch,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    // public function create(): Response
    // {
    //     $branches = Branch::all();
    //     $parentAgents = Agent::whereNull('parent_agent_id')->get();

    //     return Inertia::render('agents/create', [
    //         'branches' => $branches,
    //         'parentAgents' => $parentAgents,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:agents,email',
    //         'phone' => 'nullable|string|max:20',
    //         'code' => 'required|string|max:50|unique:agents,code',
    //         'branch_id' => 'required|exists:branches,id',
    //         'parent_agent_id' => 'nullable|exists:agents,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     Agent::create($validated);

    //     return redirect()->route('agents.index')
    //         ->with('success', 'Agent created successfully.');
    // }

    // public function show(Agent $agent): Response
    // {
    //     $agent->load(['branch', 'parentAgent', 'childAgents']);

    //     return Inertia::render('agents/show', [
    //         'agent' => $agent,
    //     ]);
    // }

    // public function edit(Agent $agent): Response
    // {
    //     $branches = Branch::all();
    //     $parentAgents = Agent::whereNull('parent_agent_id')
    //         ->where('id', '!=', $agent->id)
    //         ->get();

    //     return Inertia::render('agents/edit', [
    //         'agent' => $agent,
    //         'branches' => $branches,
    //         'parentAgents' => $parentAgents,
    //     ]);
    // }

    // public function update(Request $request, Agent $agent)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:agents,email,' . $agent->id,
    //         'phone' => 'nullable|string|max:20',
    //         'code' => 'required|string|max:50|unique:agents,code,' . $agent->id,
    //         'branch_id' => 'required|exists:branches,id',
    //         'parent_agent_id' => 'nullable|exists:agents,id',
    //         'image_path' => 'nullable|string|max:255',
    //     ]);

    //     $agent->update($validated);

    //     return redirect()->route('agents.index')
    //         ->with('success', 'Agent updated successfully.');
    // }

    // public function destroy(Agent $agent)
    // {
    //     $agent->delete();

    //     return redirect()->route('agents.index')
    //         ->with('success', 'Agent deleted successfully.');
    // }
}
