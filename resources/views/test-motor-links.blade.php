@extends('layouts.fann')

@section('title', 'Test Motor Detail Links')

@section('content')
<div class="content-header">
    <h1>Test Motor Detail Links</h1>
    <p>Testing if motor detail links work correctly</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-motorcycle me-2"></i>Available Motors for Testing</h5>
            </div>
            <div class="card-body">
                <!-- Motor 11 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Motor ID: 11 - Yamaha R25</h6>
                                <p class="mb-1"><strong>Plat:</strong> Z 123 EKA</p>
                                <p class="mb-1"><strong>CC:</strong> 250cc</p>
                                <p class="mb-1"><strong>Owner:</strong> User ID 13 (Eka)</p>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('pemilik.motor.detail', 11) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motor 18 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Motor ID: 18 - Honda Beat (Requested by User)</h6>
                                <p class="mb-1"><strong>Plat:</strong> Z B34T 1</p>
                                <p class="mb-1"><strong>CC:</strong> 125cc</p>
                                <p class="mb-1"><strong>Owner:</strong> User ID 13 (Eka)</p>
                                <p class="mb-1"><strong>Year:</strong> 2024</p>
                                <p class="mb-1"><strong>Color:</strong> Hitam</p>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('pemilik.motor.detail', 18) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail (User Request)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Fixed Components</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Dropdown Menu Link</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Route Definition</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Controller Method</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>View Template</span>
                        <span class="badge bg-success">✓</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h6>Route Info</h6>
            </div>
            <div class="card-body">
                <small>
                    <strong>Route:</strong> GET pemilik/motors/{id}<br>
                    <strong>Name:</strong> pemilik.motor.detail<br>
                    <strong>Controller:</strong> PemilikController@motorDetail<br>
                    <strong>View:</strong> pemilik.motor-detail
                </small>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info mt-4">
    <h6><i class="bi bi-info-circle me-2"></i>Testing Instructions:</h6>
    <ol>
        <li>Login as user "Eka" (eka@gmail.com) - Owner of motors</li>
        <li>Click "Lihat Detail" buttons above</li>
        <li>Should redirect to motor detail page</li>
        <li>URL should be: http://127.0.0.1:8000/pemilik/motors/18</li>
    </ol>
</div>
@endsection