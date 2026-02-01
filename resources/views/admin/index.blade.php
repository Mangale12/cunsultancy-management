@extends('layout')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Overview</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-primary" id="addBtn">
            <i data-feather="plus"></i> Add New Record
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card p-3 bg-primary text-white">
            <h5>Total Clients</h5>
            <h2 id="clientCount">1,245</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 bg-success text-white">
            <h5>Active Consultations</h5>
            <h2>84</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 bg-warning text-dark">
            <h5>Pending Payments</h5>
            <h2>$4,200</h2>
        </div>
    </div>
</div>

<h3>Recent Appointments</h3>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#ID</th>
                <th>Consultant</th>
                <th>Client</th>
                <th>Service</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="dataTable">
            <tr>
                <td>#101</td>
                <td>Dr. Sarah Smith</td>
                <td>John Doe</td>
                <td>Business Strategy</td>
                <td><span class="badge bg-success">Completed</span></td>
                <td><button class="btn btn-sm btn-outline-secondary view-btn"><i data-feather="eye"></i></button></td>
            </tr>
            </tbody>
    </table>
</div>
@endsection
