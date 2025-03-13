# YouWallet

## API Reference

#### Register 

```http
  POST /auth/register
  Default ROLE => ROLE_user

   Response : 
      => SANCTUM_Token
      => Nom du utilisateur 
      => Role name
      => wallet et leur balance
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | **required_max:50**. |
| `last_name` | `string` | **required_max:50**. |
| `id_role` | `integer` | **required_numeric**. |
| `email` | `string` | **required_unique:users,email**. |
| `phone` | `string` | **sometimes_required_unique:users,phone_digits_between:10:20**. |
| `password` | `string` | **required_min:6**. |

#### Login

```http
  POST /auth/login
  Response : 
      => SANCTUM_Token
      => Nom du utilisateur 
      => Role name
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `string` | **required_email_exists:users,email**. |
| `password`      | `string` | **Required**. |


#### Logout
```http
  POST /auth/logout
  Response : 
      => message indiquant que l'utilisateur se déconnecte avec succès
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |



### Mes infosrmation en tant que utilisateur

```http
  POST /auth/infosUser
  Response : 
      => first_name,
      => last_name,
      => email,
      => phone,
      => gender,
      => image,
      => id_role,
      => email_verified_at,
      => password
      => created_at
      =>updated_at

      => wallet et leur balance
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |


### Stokage d'argent dans mon wallet
```http
  POST /auth/stokage
  Response : 
      => message indiquant que le stokage avec success
      => balance
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |
| `amount`      | `integer` | **required_decimal,max:10, 2_après_la_vergule**. |


### retrait de l'argent dans mon wallet
```http
  POST /auth/retrait
  Response : 
      => message indiquant que le retrait effectuer avec success
      => le reste d'argent dans le wallet
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |
| `amount`      | `integer` | **required__decimal,max:10,2_après_la_vergule,superieur_à:0,superieur_que:ballance_du_user**. |


### envoyer de l'argent
```http
  POST /auth/envoyer
  Response : 
      => message indiquant que l'envoye d'argent s'effectuer avec success
      => le reste d'argent dans le wallet
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |
| `recipient_id`      | `integer` | **required_exists:users**. |
| `amount`      | `integer` | **required__decimal,max:10,2_après_la_vergule,superieur_à:0,superieur_que:ballance_du_user**. |


### all transactions
```http
  POST /auth/allTransaction
  Response : 
      => array du transactions de l'utulisateur authentifié
      => sender_wallet_id
      => recipient_wallet_id
      => amount
      => type 
      => created_at
      => updated_at
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token`      | `sanctum` | **token_de_utulisateur_authentifié**. |