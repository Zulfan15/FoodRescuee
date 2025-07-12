#!/bin/bash

# Script untuk melakukan donasi makanan menggunakan curl
CSRF_TOKEN="tNPxuB6jUz2Z6c0KxRMq1PzqOtL1qOtUtBmmeJYW"
BASE_URL="http://127.0.0.1:8090"

echo "Starting donation submissions for user Zulfan..."

# Donasi 1: Vegetables
echo "1. Submitting Fresh Vegetables donation..."
curl -X POST "$BASE_URL/donations" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=$CSRF_TOKEN" \
  -d "title=Fresh Vegetables from Restaurant Kitchen" \
  -d "food_type=vegetables" \
  -d "description=Fresh mixed vegetables including carrots, broccoli, and bell peppers. These are surplus from our restaurant kitchen, still in excellent condition and perfect for cooking." \
  -d "quantity=5" \
  -d "unit=kg" \
  -d "expiry_date=2025-07-14T18:00" \
  -d "pickup_time_start=2025-07-13T09:00" \
  -d "pickup_time_end=2025-07-13T17:00" \
  -d "pickup_location=Jl. Veteran No. 12, Klojen, Malang, East Java" \
  -d "pickup_latitude=-7.9666" \
  -d "pickup_longitude=112.6326" \
  -d "is_perishable=1" \
  -d "special_instructions=Please bring insulated bags. Vegetables are stored in refrigerated area." \
  -w "\nHTTP Status: %{http_code}\n" \
  -s -o /dev/null

echo ""

# Donasi 2: Fruits
echo "2. Submitting Fresh Fruits donation..."
curl -X POST "$BASE_URL/donations" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=$CSRF_TOKEN" \
  -d "title=Seasonal Fresh Fruits - Apples & Oranges" \
  -d "food_type=fruits" \
  -d "description=Fresh seasonal fruits including red apples and sweet oranges. These are from our grocery store surplus, slightly overripe but still delicious and nutritious." \
  -d "quantity=20" \
  -d "unit=pieces" \
  -d "expiry_date=2025-07-15T20:00" \
  -d "pickup_time_start=2025-07-13T10:00" \
  -d "pickup_time_end=2025-07-13T16:00" \
  -d "pickup_location=Jl. Soekarno Hatta No. 45, Lowokwaru, Malang, East Java" \
  -d "pickup_latitude=-7.9553" \
  -d "pickup_longitude=112.6175" \
  -d "is_perishable=1" \
  -d "special_instructions=Fruits are in good condition, consume within 2-3 days for best quality." \
  -w "\nHTTP Status: %{http_code}\n" \
  -s -o /dev/null

echo ""

# Donasi 3: Baked Goods
echo "3. Submitting Baked Goods donation..."
curl -X POST "$BASE_URL/donations" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=$CSRF_TOKEN" \
  -d "title=Homemade Bread and Pastries" \
  -d "food_type=baked_goods" \
  -d "description=Fresh baked bread, croissants, and pastries from our bakery. Made this morning with high-quality ingredients. Perfect for breakfast or snacks." \
  -d "quantity=3" \
  -d "unit=boxes" \
  -d "expiry_date=2025-07-14T12:00" \
  -d "pickup_time_start=2025-07-13T08:00" \
  -d "pickup_time_end=2025-07-13T14:00" \
  -d "pickup_location=Jl. Ijen Boulevard No. 88, Klojen, Malang, East Java" \
  -d "pickup_latitude=-7.9757" \
  -d "pickup_longitude=112.6304" \
  -d "special_instructions=Each box contains assorted bread and pastries. Can be frozen if needed." \
  -w "\nHTTP Status: %{http_code}\n" \
  -s -o /dev/null

echo ""

echo "Donation submissions completed!"
