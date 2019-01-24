# SoundConcepts

This is a small libary to handle the SoundConcepts API requests. It is currently in development, so use at your ownr risk! Actually there isn't much risk involved, it really is just not built out as far as it could be.

It requires the Nubersoft class library `rasclatt/nubersoft` and requires others besides that one.

###### Example 1) Create New User

```
<?php
use SoundConcepts\User\Controller as SoundConcepts; 

# These would be provide by SoundConcepts
$username = 'whateverusername';
$password = 'whateverpassword';
$subdomain = 'companysubdomain';

# Create instance, user your provided user, pass, and subdomain
$SoundConcepts	=	new SoundConcepts($username, $password, $subdomain);

# Create required attributes (more are available, reffer to API Docs)
$data['distributor_id'] = '123';
$data['email'] = 'test@example.com';
$data['password'] = 'pas857#@!#$sword';
$data['username'] = 'myusername';
$data['first_name'] = 'Jane';
$data['last_name'] = 'Doe';
$data['country'] = 'US';

# Create User
$success  = $SoundConcepts->createUser($data);

if($success == 1) {
  echo "User created!";
}
else {
  echo "Something went wrong...";
}
```

###### Example 2) Delete User

```
$success  = $SoundConcepts->deleteUser($distributor_id);
```

###### Example 3) Multiple SoundConcept Instances

```
<?php
use SoundConcepts\User\Controller as User;
use SoundConcepts\Credits\Controller as Credits;

# Create User instance
$User = new User(SC_USERNAME, SC_PASSWORD, SC_SUBDOMAIN);
# If you create a different instance of a SoundConcepts object,
# you don't need to add $username, $password, $subdomain
$Credits = new Credits();
```

###### Example 4) Credits Overview

```
<?php
use SoundConcepts\Credits\Controller as Credits;
# Asset id for the sample program
$asset_id = 123;
# Credit Quantity
$qty  = 1;
# Description (not required)
$description = 'Added for advancing to new rank';
# Set Id
$distributor_id = 1234;
# Create instance
$Credits = new Credits(SC_USERNAME, SC_PASSWORD, SC_SUBDOMAIN);
# Add some credits to an asset id
# The last parameter is for expiration date. See manual for format
$Credits->addCredits($distributor_id, $asset_id, $qty, $description);
# Fetch the user summary
$summary = $Credits->getCreditSummary($distributor_id);
# Output the array
print_r($summary);
```
# Global Credentials
An easy way to make sure your credentials are always loaded so you don't need to keep adding your credentials on class instantiation, you can create an observer that extends the `API/Model` and initialize that with the credentials on page-load. In a Wordpress implementation, that could be a function that loads inside the `init` hook.

```
<?php
class MyObserver extends \SoundConcepts\API\Model
{
  public  function __construct()
  {
      # Set your credentials
      $username = 'myprovidedusername';
      $password = 'myprovidedpassword';
      $subdomain = 'myprovideddomain';
      # Create a parent construct 
      return parent::__construct($username, $password, $subdomain);
  }
}
```
In Wordpress you'd do something like this in your functions:

```
function start_soundconcepts()
{
  new MyObserver();
}

add_action('init', 'start_soundconcepts');
```
