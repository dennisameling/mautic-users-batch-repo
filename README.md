# mautic-users-batch-repo

To reproduce a bug regarding consistent behavior in the batch edit API for users.

## How to test

1. Clone this repo
2. Make sure you update `$this->apiUrl` in `index.php`, line 30, with your Mautic URL.
3. Run `php index.php`. **Try multiple times**. This was my output after running multiple times:

```
dennis@mautic-users-batch-repo-web:/var/www/html$ php index.php
Successfully created user with ID 215 and username API_9STdqrKb
Successfully created user with ID 216 and username API_t3BkHRX1
Successfully created user with ID 217 and username API_UyzMXT6L
Successfully updated 3 users
Successfully deleted 3 users

dennis@mautic-users-batch-repo-web:/var/www/html$ php index.php
Successfully created user with ID 218 and username API_W5smdejC
Successfully created user with ID 219 and username API_xkgQYW2F
Successfully created user with ID 220 and username API_4plzg5Xs
Error in edit action - 400: username: Username is already in use. Please choose another., email: Email is already in use. Please choose another.
Error in edit action - 400: username: Username is already in use. Please choose another., email: Email is already in use. Please choose another.
Error in edit action - 400: username: Username is already in use. Please choose another., email: Email is already in use. Please choose another.

dennis@mautic-users-batch-repo-web:/var/www/html$ php index.php
Successfully created user with ID 239 and username API_7SEjbTzZ
Successfully created user with ID 240 and username API_slbf40QD
Successfully created user with ID 241 and username API_y2zULNGV
Successfully updated 3 users
Successfully deleted 3 users

dennis@mautic-users-batch-repo-web:/var/www/html$ php index.php
Successfully created user with ID 242 and username API_Xena0Jw7
Successfully created user with ID 243 and username API_Vwv8AIyZ
Successfully created user with ID 244 and username API_2VSnFgsp
Error in edit action - 400: username: Username is already in use. Please choose another., email: Email is already in use. Please choose another.
Error in edit action - 400: username: Username is already in use. Please choose another., email: Email is already in use. Please choose another.
```
