@extends('layouts.app')

@section('title', 'Edit Donation - FoodRescue')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Food Donation
                    </h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        Update your food donation details. Make sure all information is accurate.
                    </p>

                    <!-- Display success/error messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('donations.update', $donation) }}" enctype="multipart/form-data" id="donationForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Basic Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Donation Title</label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $donation->title) }}" 
                                       placeholder="e.g., Fresh Vegetables from Restaurant"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="food_type" class="form-label">Food Type</label>
                                <select class="form-select @error('food_type') is-invalid @enderror" 
                                        id="food_type" 
                                        name="food_type" 
                                        required>
                                    <option value="">Select type...</option>
                                    <option value="vegetables" {{ old('food_type', $donation->food_type) === 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                    <option value="fruits" {{ old('food_type', $donation->food_type) === 'fruits' ? 'selected' : '' }}>Fruits</option>
                                    <option value="meat" {{ old('food_type', $donation->food_type) === 'meat' ? 'selected' : '' }}>Meat</option>
                                    <option value="dairy" {{ old('food_type', $donation->food_type) === 'dairy' ? 'selected' : '' }}>Dairy</option>
                                    <option value="grains" {{ old('food_type', $donation->food_type) === 'grains' ? 'selected' : '' }}>Grains</option>
                                    <option value="prepared_food" {{ old('food_type', $donation->food_type) === 'prepared_food' ? 'selected' : '' }}>Prepared Food</option>
                                    <option value="baked_goods" {{ old('food_type', $donation->food_type) === 'baked_goods' ? 'selected' : '' }}>Baked Goods</option>
                                    <option value="beverages" {{ old('food_type', $donation->food_type) === 'beverages' ? 'selected' : '' }}>Beverages</option>
                                    <option value="snacks" {{ old('food_type', $donation->food_type) === 'snacks' ? 'selected' : '' }}>Snacks</option>
                                    <option value="other" {{ old('food_type', $donation->food_type) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('food_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Describe the food items, their condition, and any relevant details..."
                                      required>{{ old('description', $donation->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Quantity Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity', $donation->quantity) }}" 
                                       min="1"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <select class="form-select @error('unit') is-invalid @enderror" 
                                        id="unit" 
                                        name="unit" 
                                        required>
                                    <option value="">Select unit...</option>
                                    <option value="kg" {{ old('unit', $donation->unit) === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                    <option value="portions" {{ old('unit', $donation->unit) === 'portions' ? 'selected' : '' }}>Portions</option>
                                    <option value="boxes" {{ old('unit', $donation->unit) === 'boxes' ? 'selected' : '' }}>Boxes</option>
                                    <option value="bags" {{ old('unit', $donation->unit) === 'bags' ? 'selected' : '' }}>Bags</option>
                                    <option value="pieces" {{ old('unit', $donation->unit) === 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="liters" {{ old('unit', $donation->unit) === 'liters' ? 'selected' : '' }}>Liters</option>
                                    <option value="packs" {{ old('unit', $donation->unit) === 'packs' ? 'selected' : '' }}>Packs</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Time & Expiry Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Time & Expiry Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" 
                                       name="expiry_date" 
                                       value="{{ old('expiry_date', $donation->expiry_date ? $donation->expiry_date->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="pickup_time_start" class="form-label">Pickup Start Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('pickup_time_start') is-invalid @enderror" 
                                       id="pickup_time_start" 
                                       name="pickup_time_start" 
                                       value="{{ old('pickup_time_start', $donation->pickup_time_start ? $donation->pickup_time_start->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('pickup_time_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="pickup_time_end" class="form-label">Pickup End Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('pickup_time_end') is-invalid @enderror" 
                                       id="pickup_time_end" 
                                       name="pickup_time_end" 
                                       value="{{ old('pickup_time_end', $donation->pickup_time_end ? $donation->pickup_time_end->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('pickup_time_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Pickup Location</h5>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pickup_location" class="form-label">Pickup Address</label>
                            <textarea class="form-control @error('pickup_location') is-invalid @enderror" 
                                      id="pickup_location" 
                                      name="pickup_location" 
                                      rows="3" 
                                      placeholder="Enter the complete pickup address..."
                                      required>{{ old('pickup_location', $donation->pickup_location) }}</textarea>
                            @error('pickup_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_latitude" class="form-label">Latitude</label>
                                <input type="number" 
                                       class="form-control @error('pickup_latitude') is-invalid @enderror" 
                                       id="pickup_latitude" 
                                       name="pickup_latitude" 
                                       value="{{ old('pickup_latitude', (string)$donation->pickup_latitude) }}" 
                                       step="any" 
                                       readonly>
                                @error('pickup_latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pickup_longitude" class="form-label">Longitude</label>
                                <input type="number" 
                                       class="form-control @error('pickup_longitude') is-invalid @enderror" 
                                       id="pickup_longitude" 
                                       name="pickup_longitude" 
                                       value="{{ old('pickup_longitude', (string)$donation->pickup_longitude) }}" 
                                       step="any" 
                                       readonly>
                                @error('pickup_longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-warning w-100" id="getLocationBtn">
                                <i class="fas fa-map-marker-alt me-2"></i>Update Current Location for Pickup
                            </button>
                            <small class="text-muted">Click to update coordinates with your current location</small>
                        </div>

                        <!-- Current Images (if any) -->
                        @if($donation->images && count($donation->images) > 0)
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Current Images</h5>
                            </div>
                        </div>
                        <div class="row mb-4">
                            @foreach($donation->images as $image)
                            <div class="col-md-3 mb-2">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="img-fluid rounded" 
                                     alt="Donation image"
                                     style="height: 100px; object-fit: cover; width: 100%;">
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Additional Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2">Additional Information</h5>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Food Images (Optional)</label>
                            <input type="file" 
                                   class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*">
                            <small class="text-muted">Upload new images to replace existing ones (max 2MB each)</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_instructions" class="form-label">Special Instructions (Optional)</label>
                            <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                      id="special_instructions" 
                                      name="special_instructions" 
                                      rows="3" 
                                      placeholder="Any special handling instructions, dietary information, or notes for recipients...">{{ old('special_instructions', $donation->special_instructions) }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_perishable" 
                                   name="is_perishable" 
                                   {{ old('is_perishable', $donation->is_perishable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_perishable">
                                <strong>This food is perishable</strong>
                                <br><small class="text-muted">Check this if the food needs to be picked up quickly</small>
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            <i class="fas fa-save me-2"></i>Update Donation
                                        </button>
                                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-eye me-2"></i>View Donation
                                        </a>
                                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-list me-2"></i>Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('getLocationBtn');
    const latitudeInput = document.getElementById('pickup_latitude');
    const longitudeInput = document.getElementById('pickup_longitude');

    // Set minimum dates for datetime inputs (only for future dates)
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    
    // Only set minimum for future dates when creating new donations
    // For editing, allow current values even if they're in the past
    
    // Get location functionality
    getLocationBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting Location...';
            getLocationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitudeInput.value = position.coords.latitude;
                    longitudeInput.value = position.coords.longitude;
                    getLocationBtn.innerHTML = '<i class="fas fa-check me-2"></i>Location Updated';
                    getLocationBtn.classList.remove('btn-outline-warning');
                    getLocationBtn.classList.add('btn-success');
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                    getLocationBtn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i>Update Current Location for Pickup';
                    getLocationBtn.disabled = false;
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });

    // Form validation for pickup times
    document.getElementById('pickup_time_start').addEventListener('change', function() {
        const startTime = this.value;
        document.getElementById('pickup_time_end').min = startTime;
    });

    // Image preview
    document.getElementById('images').addEventListener('change', function() {
        const files = this.files;
        if (files.length > 5) {
            alert('You can only upload maximum 5 images');
            this.value = '';
        }
    });

    // Form submission debug
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        console.log('Form being submitted...');
        console.log('Form action:', this.action);
        console.log('Form method:', this.method);
        
        // Let the form submit normally
        return true;
    });
});
</script>
@endpush
