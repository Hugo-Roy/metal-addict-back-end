# SHARE-O-METAL API

The purpose of this API is to provides datas needed by the front-end application of Share-o-metal.

The URL of this API is `54.162.156.51/Share-O-Metal/public/api`. No public domain has been created yet.

Endpoints have to be prefixed by this URL in any cases.

## Event endpoints

### event search :

Fetch a list of events from the API of Setlist.fm. 
The authorized method is `GET`.

#### endpoint

`/search/{id}` the band id is required to perform a research

#### query parameters

"cityName", "venueName", "countryId", "year" and "p" (for pagination). They can be empty in the url and no particular order is required.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/search/5?cityName=Paris&venueName=Le%20Zenith&countryId=1&year=2000&p=1

Click on the link above to see a Json response example.

### event show :

Fetch an event and its datas from the API of Setlist.fm.
The authorized method is `GET`.

#### endpoint

`/event/{setlistId}` the setlist id of the event is required.

#### query parameters

No query parameter is allowed.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/event/5bd6dfc0

Click on the link above to see a Json response example.

## Review endpoints

### review list :

Fetch a list of reviews. At the moment, it only works for the list of reviews needed for the home page.
The authorized method is `GET`.

#### endpoint

`/review`

#### query parameters

"order" ("ASC" or "DESC"), "limit". No particular order is required.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/review?limit=6&order=ASC

Click on the link above to see a Json response example.

### review show :

Fetch a review and its associated event, user, band and country.
The authorized method is `GET`.

#### endpoint

`/review/{id}`

#### query parameters

No query parameter is allowed.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/review/2

Click on the link above to see a Json response example.

### User endpoints

### user show :

Fetch a user and its associated datas.
The authorized method is `GET`.

#### endpoint

`/user/{id}`

#### query parameters

No query parameter is allowed.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/user/2

Click on the link above to see a Json response example.

### user update :

Fetch a user and its associated datas.
The authorized methods are `PUT` and `PATCH`.

#### endpoint

`/user/{id}`

#### query parameters

No query parameter is allowed in the URL. Although the request need a Json body such as :

```
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

http://54.162.156.51/Share-O-Metal/public/api/user/2

//TODO standardise the response with the front-end app.

### user login :

Fetch a user and its associated datas.
The authorized method is `POST`.

#### endpoint

`/login`

#### query parameters

No query parameter is allowed in the URL as the datas are sent in `POST`. Although the request need a Json body such as :

```
{
    "email": "josh@josh.com",
    "password": "josh",
}
```

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/login

#### response exemple

if connected
```
{
  "email": "josh@josh.com",
  "nickname": "Josh Homme",
  "roles": [
    "ROLE_USER"
  ]
}
```
else
```
{
  "error": "Invalid credentials."
}
```

### Band endpoints

### band list :

Fetch a list of all authorized bands for event research. 
The authorized method is `GET`.

#### endpoint

`/band`

#### query parameters

No query parameter is allowed in the URL.

#### request URL example

http://54.162.156.51/Share-O-Metal/public/api/band

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

http://54.162.156.51/Share-O-Metal/public/api/country

Click on the link above to see a Json response example.