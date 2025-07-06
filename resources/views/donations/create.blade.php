@extends('layouts.app')

@section('title', 'Create Donation - FoodRescue')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Create Food Donation
                    </h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        Share your surplus food with those in need. Fill out the form below to post your donation.
                        Your donation will be reviewed by our admin team before being published.
                    </p>

                    <form method="POST" action="/direct-donations-store" enctype="multipart/form-data" id="donationForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2">Basic Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Donation Title</label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
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
                                    <option value="vegetables" {{ old('food_type') === 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                    <option value="fruits" {{ old('food_type') === 'fruits' ? 'selected' : '' }}>Fruits</option>
                                    <option value="meat" {{ old('food_type') === 'meat' ? 'selected' : '' }}>Meat</option>
                                    <option value="dairy" {{ old('food_type') === 'dairy' ? 'selected' : '' }}>Dairy</option>
                                    <option value="grains" {{ old('food_type') === 'grains' ? 'selected' : '' }}>Grains</option>
                                    <option value="prepared_food" {{ old('food_type') === 'prepared_food' ? 'selected' : '' }}>Prepared Food</option>
                                    <option value="baked_goods" {{ old('food_type') === 'baked_goods' ? 'selected' : '' }}>Baked Goods</option>
                                    <option value="beverages" {{ old('food_type') === 'beverages' ? 'selected' : '' }}>Beverages</option>
                                    <option value="snacks" {{ old('food_type') === 'snacks' ? 'selected' : '' }}>Snacks</option>
                                    <option value="other" {{ old('food_type') === 'other' ? 'selected' : '' }}>Other</option>
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
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2">Quantity Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity') }}" 
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
                                    <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                    <option value="portions" {{ old('unit') === 'portions' ? 'selected' : '' }}>Portions</option>
                                    <option value="boxes" {{ old('unit') === 'boxes' ? 'selected' : '' }}>Boxes</option>
                                    <option value="bags" {{ old('unit') === 'bags' ? 'selected' : '' }}>Bags</option>
                                    <option value="pieces" {{ old('unit') === 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="liters" {{ old('unit') === 'liters' ? 'selected' : '' }}>Liters</option>
                                    <option value="packs" {{ old('unit') === 'packs' ? 'selected' : '' }}>Packs</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Time & Expiry Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2">Time & Expiry Information</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" 
                                       name="expiry_date" 
                                       value="{{ old('expiry_date') }}" 
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
                                       value="{{ old('pickup_time_start') }}" 
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
                                       value="{{ old('pickup_time_end') }}" 
                                       required>
                                @error('pickup_time_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2">Pickup Location</h5>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pickup_location" class="form-label">Pickup Address</label>
                            <textarea class="form-control @error('pickup_location') is-invalid @enderror" 
                                      id="pickup_location" 
                                      name="pickup_location" 
                                      rows="3" 
                                      placeholder="Enter the complete pickup address..."
                                      required>{{ old('pickup_location') }}</textarea>
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
                                       value="{{ old('pickup_latitude') }}" 
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
                                       value="{{ old('pickup_longitude') }}" 
                                       step="any" 
                                       readonly>
                                @error('pickup_longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary w-100" id="getLocationBtn">
                                <i class="fas fa-map-marker-alt me-2"></i>Get Current Location for Pickup
                            </button>
                            <small class="text-muted">Click to automatically fill in your current coordinates</small>
                        </div>

                        <!-- Additional Information -->
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2">Additional Information</h5>
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
                            <small class="text-muted">You can upload multiple images (max 2MB each)</small>
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
                                      placeholder="Any special handling instructions, dietary information, or notes for recipients...">{{ old('special_instructions') }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_perishable" 
                                   name="is_perishable" 
                                   {{ old('is_perishable') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_perishable">
                                <strong>This food is perishable</strong>
                                <br><small class="text-muted">Check this if the food needs to be picked up quickly</small>
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms_agreement" required>
                                        <label class="form-check-label" for="terms_agreement">
                                            I confirm that the food is safe for consumption and I agree to the 
                                            <a href="#" class="text-primary">Terms and Conditions</a>
                                        </label>
                                    </div>
                                    
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check me-2"></i>Submit Donation
                                        </button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-times me-2"></i>Cancel
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

    // Set minimum dates for datetime inputs
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    
    document.getElementById('expiry_date').min = minDateTime;
    document.getElementById('pickup_time_start').min = minDateTime;
    document.getElementById('pickup_time_end').min = minDateTime;

    // Get location functionality
    getLocationBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting Location...';
            getLocationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitudeInput.value = position.coords.latitude;
                    longitudeInput.value = position.coords.longitude;
                    getLocationBtn.innerHTML = '<i class="fas fa-check me-2"></i>Location Obtained';
                    getLocationBtn.classList.remove('btn-outline-primary');
                    getLocationBtn.classList.add('btn-success');
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                    getLocationBtn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i>Get Current Location for Pickup';
                    getLocationBtn.disabled = false;
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });

    // Form validation
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
});
</script>
@endpush
