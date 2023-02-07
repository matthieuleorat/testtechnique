## Installation

1. Build docker images
`docker-compose up --build -d`
2. Start Symfony server
`symfony server:start -d`
3. Run doctrine migration
`php bin/console doctrine:migration:migrate`
4. Generate key for jwt tokens
`php bin/console lexik:jwt:generate-keypair`
5. Create an admin and a member with the command
`php bin/console app:user:new <username>`

## Enpoints:
### Authentication
`POST https://127.0.0.1:8000/api/login_check`
```json
{
	"username":"admin",
	"password":"*****"
}
```

### Articles
#### List articles
Url: `GET https://127.0.0.1:8000/api/article/<page=1>`  

#### Create a new article
Url: `POST https://127.0.0.1:8000/api/article/create`  
Body
```json
{
	"title": "article 4"
}
```

#### Edit an article
Id in the query and in the payload must match  
Url: `PUT https://127.0.0.1:8000/api/article/edit/<articleId>`    
Body
```json
{
	"title": "article 4",
    "id": <articleId>
}
```

#### Delete an article
Id in the query and in the payload must match  
Url: `DELETE https://127.0.0.1:8000/api/article/delete/<articleId>`    
Body
```json
{
    "id": <articleId>
}
```

#### Edit an article
Id in the query and in the payload must match  
Url: `PUT https://127.0.0.1:8000/api/article/edit/<articleId>`    
Body
```json
{
    "title": "article 4",
    "id": <articleId>
}
```

#### Delete an article
Id in the query and in the payload must match  
Url: `DELETE https://127.0.0.1:8000/api/article/delete/<articleId>`    
Body
```json
{
    "id": <articleId>
}
```


## Comments
#### List comment of an article
Url: `GET hhttps://127.0.0.1:8000/api/comment/article/<articleId>`

#### List comment of a comment
Url: `GET hhttps://127.0.0.1:8000/api/comment/comment/<commentId>`

#### Create a new comment on an article
Url: `POST https://127.0.0.1:8000/api/comment/article/create`  
Body
```json
{
  "content": "content",
  "articleId": 1
}
```

#### Create a new comment on a comment
Id in the query and in the payload must match  
Url: `POST https://127.0.0.1:8000/api/comment/comment/create`  
Body
```json
{
  "content": "content",
  "commentId": 1
}
```

#### Edit ca comment
Id in the query and in the payload must match  
Url: `PUT https://127.0.0.1:8000/api/comment/edit/<commentId>`  
Body
```json
{
  "content": "comment modifié",
  "id": <commentId>
}
```

#### Delete a comment
Id in the query and in the payload must match  
Url: `DELETE https://127.0.0.1:8000/api/comment/delete/<commentId>`  
Body
```json
{
  "id": <commentId>
}
```

#### Approve ca comment
Id in the query and in the payload must match  
Url: `PUT https://127.0.0.1:8000/api/comment/approval/<commentId>`  
Body
```json
{
  "approval": false,
  "id": <commentId>
}
```

#### Rate a comment
Id in the query and in the payload must match  
Url: `POST https://127.0.0.1:8000/api/comment_rate/rate/commentId>`  
Body
```json
{
  "id": <commentId>,
  "rate": 1
}
```

## User and Authentication
- Command to create a user
- Command to reset a user password
- An entrypoint to login

## Article
- An admin can create an article
- An admin can update an article
- An admin can delete an article
- A member can list all article, with a pagination feature

## Comment
- An admin can approve a comment
- An admin can delete any comment
- A member can post a comment on an article
- A member can post a comment on another comment
- A member can delete its own comments
- A member can modify its own comments. If he try to modify a comment belonging to antoher member(or admin), system will show a 404 error. This way user cannot know if the id exist or not
- A member can list all comments of an article
- A member can list all comments of another comment
- When a new comment is created, its approval is set to null, An admin can either set it to true or false

## Comment Rate
- A member can not rate his own comment
- A member can modify the rate he set on a comment
- Rate must be between 1 and 5

## Social Authentication
Callback urls to allow are:
- https://127.0.0.1:8000/connect/facebook/check
- https://127.0.0.1:8000/connect/google/check

Api keys and secrets are not in the git repository, please configure them.

### Facebook
To login with facebook, please go there https://127.0.0.1:8000/connect/facebook

### Google
To login with google, please go there https://127.0.0.1:8000/connect/google

## CQRS
Command and queries are in each context (article, comment and commentRate) folder.  
Command and queries handler are the <object>Service class at the root of each context.
