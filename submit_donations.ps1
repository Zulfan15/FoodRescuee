# PowerShell script untuk melakukan donasi makanan
$CSRF_TOKEN = "tNPxuB6jUz2Z6c0KxRMq1PzqOtL1qOtUtBmmeJYW"
$BASE_URL = "http://127.0.0.1:8090"

Write-Host "Starting donation submissions for user Zulfan..." -ForegroundColor Green

# Donasi 1: Vegetables
Write-Host "1. Submitting Fresh Vegetables donation..." -ForegroundColor Yellow

$body1 = @{
    "_token" = $CSRF_TOKEN
    "title" = "Fresh Vegetables from Restaurant Kitchen"
    "food_type" = "vegetables"
    "description" = "Fresh mixed vegetables including carrots, broccoli, and bell peppers. These are surplus from our restaurant kitchen, still in excellent condition and perfect for cooking."
    "quantity" = "5"
    "unit" = "kg"
    "expiry_date" = "2025-07-14T18:00"
    "pickup_time_start" = "2025-07-13T09:00"
    "pickup_time_end" = "2025-07-13T17:00"
    "pickup_location" = "Jl. Veteran No. 12, Klojen, Malang, East Java"
    "pickup_latitude" = "-7.9666"
    "pickup_longitude" = "112.6326"
    "is_perishable" = "1"
    "special_instructions" = "Please bring insulated bags. Vegetables are stored in refrigerated area."
}

try {
    $response1 = Invoke-WebRequest -Uri "$BASE_URL/donations" -Method POST -Body $body1 -UseBasicParsing
    Write-Host "Vegetables donation submitted successfully! Status: $($response1.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "Error submitting vegetables donation: $($_.Exception.Message)" -ForegroundColor Red
}

Start-Sleep -Seconds 2

# Donasi 2: Fruits
Write-Host "2. Submitting Fresh Fruits donation..." -ForegroundColor Yellow

$body2 = @{
    "_token" = $CSRF_TOKEN
    "title" = "Seasonal Fresh Fruits - Apples & Oranges"
    "food_type" = "fruits"
    "description" = "Fresh seasonal fruits including red apples and sweet oranges. These are from our grocery store surplus, slightly overripe but still delicious and nutritious."
    "quantity" = "20"
    "unit" = "pieces"
    "expiry_date" = "2025-07-15T20:00"
    "pickup_time_start" = "2025-07-13T10:00"
    "pickup_time_end" = "2025-07-13T16:00"
    "pickup_location" = "Jl. Soekarno Hatta No. 45, Lowokwaru, Malang, East Java"
    "pickup_latitude" = "-7.9553"
    "pickup_longitude" = "112.6175"
    "is_perishable" = "1"
    "special_instructions" = "Fruits are in good condition, consume within 2-3 days for best quality."
}

try {
    $response2 = Invoke-WebRequest -Uri "$BASE_URL/donations" -Method POST -Body $body2 -UseBasicParsing
    Write-Host "Fruits donation submitted successfully! Status: $($response2.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "Error submitting fruits donation: $($_.Exception.Message)" -ForegroundColor Red
}

Start-Sleep -Seconds 2

# Donasi 3: Baked Goods
Write-Host "3. Submitting Baked Goods donation..." -ForegroundColor Yellow

$body3 = @{
    "_token" = $CSRF_TOKEN
    "title" = "Homemade Bread and Pastries"
    "food_type" = "baked_goods"
    "description" = "Fresh baked bread, croissants, and pastries from our bakery. Made this morning with high-quality ingredients. Perfect for breakfast or snacks."
    "quantity" = "3"
    "unit" = "boxes"
    "expiry_date" = "2025-07-14T12:00"
    "pickup_time_start" = "2025-07-13T08:00"
    "pickup_time_end" = "2025-07-13T14:00"
    "pickup_location" = "Jl. Ijen Boulevard No. 88, Klojen, Malang, East Java"
    "pickup_latitude" = "-7.9757"
    "pickup_longitude" = "112.6304"
    "special_instructions" = "Each box contains assorted bread and pastries. Can be frozen if needed."
}

try {
    $response3 = Invoke-WebRequest -Uri "$BASE_URL/donations" -Method POST -Body $body3 -UseBasicParsing
    Write-Host "Baked goods donation submitted successfully! Status: $($response3.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "Error submitting baked goods donation: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nDonation submissions completed!" -ForegroundColor Green
