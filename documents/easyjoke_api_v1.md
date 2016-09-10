FORMAT: 1A

# EasyJoke

# Authentication [/auth]
Authentication

## Register user [POST /auth/signup]
Register a new user

+ Request (application/json)
    + Body

            {
                "name": "User Name",
                "login": "user",
                "email": "user@comapany.com",
                "password": "xx15Ab",
                "password_confirmation": "xx15Ab"
            }

+ Response 200 (application/json)
    + Body

            {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "login": [
                        "The login has already been taken."
                    ],
                    "email": [
                        "The email field is required."
                    ]
                },
                "status_code": 422
            }

## Login user [POST /auth/login]
Login of a user in the system with 'email' and 'password'.

+ Request (application/json)
    + Body

            {
                "email": "user@comapany.com",
                "password": "xx15Ab"
            }

+ Response 200 (application/json)
    + Body

            {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "email": [
                        "The email field is required."
                    ],
                    "password": [
                        "The password field is required."
                    ]
                },
                "status_code": 422
            }

## Password recovery [POST /auth/recovery]
Recover password of an account

+ Request (application/json)
    + Body

            {
                "email": "user@comapany.com"
            }

+ Response 200 (application/json)
    + Body

            {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9....."
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "email": [
                        "The email field is required."
                    ]
                },
                "status_code": 422
            }

+ Response 404 (application/json)
    + Body

            {
                "message": "Not Found",
                "errors": {
                    "email": [
                        "The email field is required."
                    ]
                },
                "status_code": 422
            }

## Reset password [POST /auth/recovery]
Reset password of an account

+ Request (application/json)
    + Body

            {
                "email": "user@comapany.com"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success"
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": [
                    [
                        "The token field is required."
                    ],
                    [
                        "The email field is required."
                    ],
                    [
                        "The password field is required."
                    ]
                ],
                "status_code": 422
            }

+ Response 500 (application/json)
    + Body

            {
                "message": "It is not possible to reset the password",
                "status_code": 500
            }

# Categories [/categories]

## List categories [GET /categories]
List all categories

+ Response 200 (application/json)
    + Body

            {
                "data": [
                    {
                        "id": 1,
                        "name": "Category One"
                    },
                    {
                        "id": 2,
                        "name": "Category Two"
                    }
                ],
                "meta": {
                    "pagination": {
                        "total": 1,
                        "count": 1,
                        "per_page": 15,
                        "current_page": 1,
                        "total_pages": 1,
                        "links": []
                    }
                }
            }

## Create category [POST /categories]
Create a new category

+ Request (application/json)

    + Attributes
        + name: Category Name (string, required) - The category name
    + Body

            {
                "name": "Category Name"
            }

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "name": "Category Name"
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "name": [
                        "The name field is required."
                    ]
                },
                "status_code": 422
            }

## Show category [GET /categories/:id]
Show a specified category

+ Parameters
    + id: (integer, required) - The category id.

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "name": "Category One"
                }
            }

+ Response 404 (application/json)
    + Body

            {
                "message": "Category not found",
                "status_code": 404
            }

## Update category [PUT /categories/:id]
Update a specified category

+ Parameters
    + id: (integer, required) - The category id.

+ Request (application/json)

    + Attributes
        + name: Update Category Name (string, required) - The category name
    + Body

            {
                "name": "Update Category Name"
            }

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "name": "Update Category Name"
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "name": [
                        "The name field is required."
                    ]
                },
                "status_code": 422
            }

+ Response 404 (application/json)
    + Body

            {
                "message": "Category not found",
                "status_code": 404
            }

## Delete category [DELETE /categories/:id]
Remove a specified category.

+ Parameters
    + id: (integer, required) - The category id.

+ Response 200 (application/json)
    + Body

            ""

+ Response 400 (application/json)
    + Body

            {
                "message": "Bad Request",
                "status_code": 400
            }

## List all jokes [GET /categories]
List all the company's jokes

+ Response 200 (application/json)
    + Body

            {
                "data": [
                    {
                        "id": 1,
                        "title": "Joke One",
                        "content": "Content of the joke one"
                    },
                    {
                        "id": 2,
                        "title": "Joke Two",
                        "content": "Content of the joke two"
                    }
                ],
                "meta": {
                    "pagination": {
                        "total": 2,
                        "count": 2,
                        "per_page": 15,
                        "current_page": 1,
                        "total_pages": 1,
                        "links": []
                    }
                }
            }

# Jokes [/jokes]

## List jokes [GET /jokes]
List all jokes

+ Response 200 (application/json)
    + Body

            {
                "data": [
                    {
                        "id": 1,
                        "title": "Joke One",
                        "content": "Content of the joke one"
                    },
                    {
                        "id": 2,
                        "title": "Joke Two",
                        "content": "Content of the joke two"
                    }
                ],
                "meta": {
                    "pagination": {
                        "total": 2,
                        "count": 2,
                        "per_page": 15,
                        "current_page": 1,
                        "total_pages": 1,
                        "links": []
                    }
                }
            }

## Create joke [POST /jokes]
Create a new joke

+ Request (application/json)

    + Attributes
        + title: Happy Joke (string, required) - The joke title
        + content: Seriously, this is my happy face (string, required) - The joke content
    + Body

            {
                "title": "Joke title",
                "content": "Joke content"
            }

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "title": "Happy Joke",
                    "content": "Seriously, this is my happy face"
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "title": [
                        "The title field is required."
                    ],
                    "content": [
                        "The content field is required."
                    ]
                },
                "status_code": 422
            }

## Show joke [GET /jokes/:id]
Show a specified joke

+ Parameters
    + id: (integer, required) - The joke id.

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "title": "Happy Joke",
                    "content": "Seriously, this is my happy face"
                }
            }

+ Response 400 (application/json)
    + Body

            {
                "message": "Joke not found",
                "status_code": 400
            }

## Update joke [PUT /jokes/:id]
Update a specified joke

+ Parameters
    + id: (integer, required) - The joke id.

+ Request (application/json)

    + Attributes
        + title: Update Happy Joke (string, required) - The joke title
        + content: Seriously, this is my happy face (string, required) - The joke content
    + Body

            {
                "title": "Joke title",
                "content": "Joke content"
            }

+ Response 200 (application/json)
    + Body

            {
                "data": {
                    "id": 1,
                    "title": "Update Happy Joke",
                    "content": "Seriously, this is my happy face"
                }
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "422 Unprocessable Entity",
                "errors": {
                    "title": [
                        "The title field is required."
                    ],
                    "content": [
                        "The content field is required."
                    ]
                },
                "status_code": 422
            }

+ Response 400 (application/json)
    + Body

            {
                "message": "Joke not found",
                "status_code": 400
            }

## Delete joke [DELETE /jokes/:id]
Remove a specified joke.

+ Parameters
    + id: (integer, required) - The joke id.

+ Response 200 (application/json)
    + Body

            ""

+ Response 400 (application/json)
    + Body

            {
                "message": "Bad Request",
                "status_code": 400
            }