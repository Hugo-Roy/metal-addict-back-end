# SHARE-O-METAL API

The purpose of this API is to provides datas needed by the front-end application of Share-o-metal.

The URL of this API is `3.80.87.102/Share-O-Metal/public/api`. No public domain has been created yet.

Endpoints have to be prefixed by this URL in any cases.

Once you get the JWT token, send it to every request headers for authorization (Authorization : Bearer {token}).

## Event endpoints

### event search :

Fetch a list of events from the API of Setlist.fm. 
The authorized method is `GET`.

#### endpoint

`/search/{id}` the band id is required to perform a research

#### query parameters

"cityName", "venueName", "countryId", "year" and "p" (for pagination). They can be empty in the url and no particular order is required.

#### request URL example

http:// 3.80.87.102/Share-O-Metal/public/api/search/5?cityName=Paris&venueName=Le%20Zenith&countryId=1&year=2000&p=1

Click on the link above to see a Json response example.

### event show :

Fetch an event and its datas from the API of Setlist.fm.
The authorized method is `GET`.

#### endpoint

`/event/{setlistId}` the setlist id of the event is required.

#### query parameters

No query parameter is allowed.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/event/5bd6dfc0

Click on the link above to see a Json response example.

### event add :

Basically, this endpoint will specify that a user has been to an event. If nobody has been to this event yet, it will be created in or database and associated to the given user. Otherwise it will only be associated with the user.
The authorized method is `POST`.

#### endpoint

`/event/{setlistId}` the setlist id of the event is required.

#### query parameters

No query parameter is allowed.

#### response example

The response is a basic status 201 "created".

## Review endpoints

### review list :

Fetch a list of reviews. At the moment, it only works for the list of reviews needed for the home page and a for a given event.
The authorized method is `GET`.

#### endpoint

`/review`

#### query parameters

"order" ("ASC" or "DESC"), "limit", "setlistId". No particular order is required. The limit parameter will always be accepted apart for a given event (if you send a setlistId).

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/review?limit=6&order=ASC

Click on the link above to see a Json response example.

### review show :

Fetch a review and its associated event, user, band and country.
The authorized method is `GET`.

#### endpoint

`/review/{id}`

#### query parameters

No query parameter is allowed.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/review/2

Click on the link above to see a Json response example.

### review add :

Add a review for an event. You must send a valid JWT token.
The authorized method is `POST`.

#### endpoint

`/review`

#### query parameters

No query parameter is allowed. You have to send the field of the review in a Json body such as :

```json
{
	"title": "Awesome review",
	"content": "lorem..."
}
```

#### response example

```json
{
  "id": 202,
  "title": "Awesome review",
  "content": "lorem de ouf...",
  "createdAt": "2021-01-26T10:29:11+01:00",
  "user": {
    "id": 1,
    "nickname": "Lemmy Killmister"
  },
  "event": {
    "id": 51,
    "setlistId": "43cd0f37",
    "venue": "Cabaret Sauvage",
    "city": "Ville-Lumière",
    "date": "2014-12-19T00:00:00+01:00",
    "band": {
      "id": 4,
      "name": "Meshuggah",
      "image": null
    },
    "country": {
      "id": 1,
      "name": "France",
      "countryCode": "FR"
    }
  }
}
```
### review update :

Modify a review for an event. You must send a valid JWT token.
The authorized methods are `PUT` and `PATCH`.

It works almost the same as review add. Just send the field you want to update with your valid JWT token.

### User endpoints

### user show :

Fetch a user and its associated datas.
The authorized method is `GET`.

#### endpoint

`/user/{id}`

#### query parameters

No query parameter is allowed.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/user/2

Click on the link above to see a Json response example.

### user update :

Fetch a user and its associated datas.
The authorized methods are `PUT` and `PATCH`.

#### endpoint

`/user/{id}`

#### query parameters

No query parameter is allowed in the URL. Although the request need a Json body such as :

```json
{
  "email": "josh@josh.com",
  "biography": "Lorem...",
  "nickname": "My new nickname",
  "oldPassword": "josh",
  "newPassword": "homme"
}
```

No parameter is required in the Json body. Note that if you want to modify the user password, both "oldPassword" and "newPassword" is needed. Changing the avatar is not supported yet.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/user/2

//TODO standardise the response with the front-end app.

### user login :

Fetch a user and its associated datas.
The authorized method is `POST`.

#### endpoint

`/login`

#### query parameters

No query parameter is allowed in the URL as the datas are sent in `POST`. Although the request need a Json body such as :

```json
{
    "email": "josh@josh.com",
    "password": "josh",
}
```

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/login

The response is a JWT token, decode it to get the user id, username, nickname, avatar and biography.

### Band endpoints

### band list :

Fetch a list of all authorized bands for event research. 
The authorized method is `GET`.

#### endpoint

`/band`

#### query parameters

No query parameter is allowed in the URL.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/band

Click on the link above to see a Json response example.

### Country endpoints

### country list :

Fetch a list of all authorized countries for event research. 
The authorized method is `GET`.

#### endpoint

`/country`

#### query parameters

No query parameter is allowed in the URL.

#### request URL example

http://3.80.87.102/Share-O-Metal/public/api/country

Click on the link above to see a Json response example.

### Picture endpoints

### picture add :

Add a picture associated with a user and an event.
The authorized method is `POST`.

#### endpoint

`/picture/{setlistId}`

#### query parameters

No query parameter is allowed in the URL. 

#### request

You have to set "Content-Type : multipart/form-data" in the headers with a valid JWT token.
The key name set in the headers of the uploaded file is 'picture'.
The response is the name of the created picture in the database.

### picture delete :

Delete a picture. The user must be the owner of the picture to delete it.
The authorized method is `DELETE`.

#### endpoint

`/picture/{id}`

#### query parameters

No query parameter is allowed in the URL. 
