# Poetry Desktop App - API Specification

## API Overview

Base URL: `/api/v1`

### Authentication
All requests must include a valid CSRF token in the `X-CSRF-TOKEN` header.

## Endpoints

### Poems

#### List Poems
```http
GET /poems

Response 200 (application/json)
{
    "data": [
        {
            "id": 1,
            "title": "Sample Poem",
            "content": "Poem content...",
            "position_x": 100,
            "position_y": 200,
            "window_position_x": 150,
            "window_position_y": 250,
            "window_width": 400,
            "window_height": 300,
            "created_at": "2024-01-01T12:00:00Z",
            "updated_at": "2024-01-01T12:00:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 10,
        "per_page": 15
    }
}
```

#### Create Poem
```http
POST /poems

Request Body:
{
    "title": "New Poem",
    "content": "Poem content...",
    "position_x": 100,
    "position_y": 200
}

Response 201 (application/json)
{
    "data": {
        "id": 2,
        "title": "New Poem",
        "content": "Poem content...",
        "position_x": 100,
        "position_y": 200,
        "created_at": "2024-01-01T12:00:00Z",
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

#### Get Poem
```http
GET /poems/{id}

Response 200 (application/json)
{
    "data": {
        "id": 1,
        "title": "Sample Poem",
        "content": "Poem content...",
        "position_x": 100,
        "position_y": 200,
        "window_position_x": 150,
        "window_position_y": 250,
        "window_width": 400,
        "window_height": 300,
        "created_at": "2024-01-01T12:00:00Z",
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

#### Update Poem
```http
PATCH /poems/{id}

Request Body:
{
    "title": "Updated Title",
    "content": "Updated content..."
}

Response 200 (application/json)
{
    "data": {
        "id": 1,
        "title": "Updated Title",
        "content": "Updated content...",
        "position_x": 100,
        "position_y": 200,
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

#### Update Poem Position
```http
PATCH /poems/{id}/position

Request Body:
{
    "position_x": 150,
    "position_y": 250
}

Response 200 (application/json)
{
    "data": {
        "id": 1,
        "position_x": 150,
        "position_y": 250,
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

#### Update Window State
```http
PATCH /poems/{id}/window

Request Body:
{
    "window_position_x": 200,
    "window_position_y": 300,
    "window_width": 450,
    "window_height": 350
}

Response 200 (application/json)
{
    "data": {
        "id": 1,
        "window_position_x": 200,
        "window_position_y": 300,
        "window_width": 450,
        "window_height": 350,
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

#### Delete Poem
```http
DELETE /poems/{id}

Response 204 No Content
```

## Error Responses

### 400 Bad Request
```json
{
    "error": {
        "code": "validation_error",
        "message": "The given data was invalid.",
        "details": {
            "title": ["The title field is required."],
            "content": ["The content field is required."]
        }
    }
}
```

### 404 Not Found
```json
{
    "error": {
        "code": "resource_not_found",
        "message": "The requested poem was not found."
    }
}
```

### 422 Unprocessable Entity
```json
{
    "error": {
        "code": "validation_error",
        "message": "The given data was invalid.",
        "details": {
            "position_x": ["The position x must be an integer."]
        }
    }
}
```

### 500 Server Error
```json
{
    "error": {
        "code": "server_error",
        "message": "An unexpected error occurred."
    }
}
```

## Rate Limiting

- Rate limit: 60 requests per minute
- Rate limit header: `X-RateLimit-Limit`
- Remaining requests: `X-RateLimit-Remaining`
- Rate limit reset: `X-RateLimit-Reset`

## Versioning

API versioning is handled through the URL path:
- Current version: `/api/v1`
- Future versions: `/api/v2`, etc.

## Security

### Authentication
- CSRF protection required
- Session-based authentication

### Headers
```http
X-CSRF-TOKEN: <token>
Accept: application/json
Content-Type: application/json
```

## Pagination

List endpoints support pagination through query parameters:
```http
GET /poems?page=2&per_page=15
```

Response includes metadata:
```json
{
    "data": [...],
    "meta": {
        "current_page": 2,
        "from": 16,
        "to": 30,
        "total": 50,
        "per_page": 15,
        "last_page": 4
    }
}
```
