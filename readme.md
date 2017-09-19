
# Api Task

## Installation
- Run composer install

```bash
composer install
```
- Copy the .env file an create an application key

```bash
cp .env.example .env && php artisan key:generate
```

- Should take installed and running in your system mongodb and redis.

- Config the .env file with yours own credentials of database

## Usage

- Run php buil-in server

```bash
php artisan serve
```
- If you want a seed data in the API, run the next command

```bash
php artisan db:seed
```
**Note:** To make requests to the API, you need sending in the headers the **API_KEY** parameter, present in .env file. 

- If you want retrieve tasks (by default be show the first five task created) 

GET   http://127.0.0.1:8000/api/tasks

### Filters

The tasks can be filter by due date, completed and uncompleted, date of creation, and date of update.   

Should be defined the parameter `where` like array three-dimensional, where:   
1st position: Property of task to filter   
2nd position: The filter operator (supported operators: `=`, `!=`, `IN`, `LIKE`, `>`, `>=`, `<`, `<=`)   
3rd position: The value to search

GET   http://127.0.0.1:8000/api/tasks?where[0][0][]=created_at&where[0][0][]=>=&where[0][0][]=2017-09-01

Retrieve task can be cumbersome, instance can use:

POST   http://127.0.0.1:8000/api/tasks/search   
```json
{
    "where":
    [
        [
            [
                "completed",
                "=",
                false
             ]
        ]
    ]
}
```

### Pagination

Two parameters are available: `limit` and `page`. `limit` will determine the number of
records per page and `page` will determine the current page.

GET   http://127.0.0.1:8000/api/tasks?limit=10&page=3

Will return books number 30-40.


- If you want create a task, can send the body as json or as form-data.

POST   http://127.0.0.1:8000/api/tasks 
```json
{
    "title": "Title task one",
    "due_date": "2017-09-21"
}
```

- To retrieve a task you must provide an ID

GET   http://127.0.0.1:8000/api/tasks/{id}

- If you want update a Task, can send the body as json or as x-www-form-urlencoded

 PUT   http://127.0.0.1:8000/api/tasks/{id}
 ```json
 {
     "title": "New title"
 }
 ```
 
- To remove a task you must provide an ID
 
 DELETE   http://127.0.0.1:8000/api/tasks/{id}
