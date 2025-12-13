#!/bin/bash
# Test approval produksi via HTTP

PRODUKSI_ID=2
DOSPEM_ID=25  # Sesuaikan dengan dosen yang ada

# Get CSRF token from login
echo "=== Getting CSRF Token ==="
curl -s -c /tmp/cookies.txt http://localhost:8000/login | grep -oP 'name="csrf-token"[^>]*content="\K[^"]*' | head -1

# Login as dosen
echo "=== Logging in as Dosen ==="
curl -s -b /tmp/cookies.txt -c /tmp/cookies.txt \
  -X POST http://localhost:8000/login \
  -d "email=dosen@example.com&password=password&_token=token" \
  -w "\nStatus: %{http_code}\n"

# Submit approval
echo "=== Submitting Approval ==="
curl -s -b /tmp/cookies.txt \
  -X POST http://localhost:8000/dospem/produksi/$PRODUKSI_ID/produksi-akhir \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{"produksi_status":"disetujui","produksi_feedback":"Test approval"}' \
  -w "\nStatus: %{http_code}\n"
