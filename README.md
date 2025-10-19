# Backend Wizards — Stage 0: Profile Endpoint

This project implements a dynamic profile endpoint that returns user information along with a random cat fact from an external API.

## Requirements

- PHP 7.0 or higher

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone <https://github.com/Ukaoha/HNG-1>
   cd <your-repo-directory>
   ```

2. Ensure you have PHP installed:
   ```bash
   php --version
   ```

3. Start the development server:
   ```bash
   php -S localhost:8000 index.php
   ```

4. Test the endpoint:
   ```bash
   curl http://localhost:8000/me
   ```

## API Endpoints

### GET /me

Returns user profile information in JSON format.

**Response Format:**
```json
{
  "status": "success",
  "user": {
    "email": "<user-email>",
    "name": "<user-name>",
    "stack": "PHP"
  },
  "timestamp": "<current-UTC-time-in-ISO-8601>",
  "fact": "<random-cat-fact>"
}
```

**Example Response:**
```json
{
  "status": "success",
  "user": {
    "email": "ukaohachizoba6@gmail.com",
    "name": "Ukaoha Chizoba",
    "stack": "PHP"
  },
  "timestamp": "2025-10-16T13:18:30.000Z",
  "fact": "A cat’s nose pad is ridged with a unique pattern, just like the fingerprint of a human."
}
```

## Features

- **Dynamic Timestamp**: Updates on every request with current UTC time in ISO 8601 format.
- **Random Cat Facts**: Fetches a new cat fact from `https://catfact.ninja/fact` on each request.
- **Error Handling**: Graceful fallback if the cat facts API is unavailable.
- **JSON Response**: Always returns `Content-Type: application/json`.
- **HTTP Status**: Returns 200 OK on success.

## Testing

### Automatic Testing
The endpoint has been tested to ensure:
- Returns HTTP 200 status
- Response is valid JSON with correct schema
- Timestamp updates dynamically
- Cat fact is fetched on each request
- Content-Type header is set correctly

### Manual Testing
Use these commands to test:
```bash
# Test the endpoint
curl -s http://localhost:8000/me | jq

# Check HTTP status
curl -w "%{http_code}" -o /dev/null http://localhost:8000/me

# Test dynamic timestamp (run multiple times)
for i in {1..3}; do curl -s http://localhost:8000/me | jq '.timestamp'; sleep 1; done
```

## Implementation Details

- Built with pure PHP (no frameworks)
- Uses `file_get_contents()` for HTTP requests with timeout
- Cat facts fetched from external API with error handling
- ISO 8601 timestamp generation using `gmdate()`

## Environment Variables

No environment variables are required. All configuration is hardcoded for simplicity.

## Dependencies

- PHP 7.0+
- No external PHP libraries required

## Hosting

This application can be deployed to any PHP-supported hosting platform including:
- Railway
- Heroku
- AWS
- PXXL App
- DigitalOcean App Platform

*Note: Vercel is not suitable for this PHP-based application.*

## Submission Details

- **Name**: Ukaoha Chizoba
- **Email**: ukaohachizoba6@gmail.com
- **Stack**: PHP
- **GitHub Repo**: [Your Repo Link]

## What I Learned

This project taught me:
- Implementing RESTful APIs in PHP
- Making HTTP requests to external APIs
- Proper error handling and fallbacks
- Dynamic timestamp generation
- JSON response formatting
- CORS considerations for APIs
