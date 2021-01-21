## Band entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| name                     | no                  | string              |
| musicbrainzId (unique)            | no                  | string              |
| image                    | yes                  | string              |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |


## Event entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| setlistId (unique)                    | no                  | string              |
| bandId            | no                  | int              |
| countryId                    | no                  | int              |
| venue                    | no                  | string              |
| city                    | no                  | string              |
| date                    | no                  | dateTime              |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |


## User entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| email (unique)                     | no                  | string              |
| password            | no                  | string (encoded)             |
| nickname                    | no                  | string              |
| biography                    | no                  | string              |
| avatar                    | yes                  | string              |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |


## Review entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| title            | no                  | string             |
| content                    | no                  | string              |
| userId                    | no                  | int              |
| eventId                    | no                  | int              |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |


## Country entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| countryCode            | no                  | string             |
| name                    | no                  | string              |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |


## Picture entity :

| field name               | nullable            | type                |
|--------------------------|---------------------|---------------------|
| id                     | no                  | int              |
| path                     | no                  | string              |
| userId            | no                  | int             |
| eventId            | no                  | int             |
| createdAt                | no                  | dateTime            |
| updatedAt                | yes                  | dateTime            |

