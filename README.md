# Sharpspring to Prophet Comparison Tool
---

## Comparison Tool
---

I put together a little tool to compare data across SS and Prophet. It's located on the developer iMac at
```
/Applictions/MAMP/htdocs/sharpspring-prophet/
```

Enter an email address and the script will make a call to both the SS and Prophet APIs and pull up any data associated with that email.  


## Ken's Code

Below is some information I compiled regarding the code Ken authored that handles the sync between SS and Prophet.  I also went back into Zapier and put together some notes on what those Zaps are doing, what scripts from Ken's code are being called, and what part of the zaps are automated by Zapier.  


## Hooks being run by Zapier / Cron Jobs
---

#### New SharpSpring Lead to Prophet

1. Every hour the zap runs this script "http://prophet-sharpspring.tizianiwhitmyre.com/get_new_sharpspring_contacts_prophet.php"

2. Zapier sends a text string to the script as a URL parameter named "verify".

3. The script looks for that url param. If it's present, and it matches the value created in Zapier then the script runs the function
```
get_sharpspring_create_queue_item()
```

4. That function is found in the file **sharpspring_functions.php** on line line 80.
  - This function connects to the mysql database found on the Media Temple server and pulls a row of data from the table **create_queue** where the entity_origin == Sharpspring.  
  - If a value is found the function returns the value from the **entity_id** number. The entity_id is the SharpSpring ID.  

5. The get_new_sharpspring_contacts_prophet.php script takes the value of the entity_id and passes it to the function
```
get_sharpspring_contact_by_ssid()
```

6. That function is found in the file **sharpspring_functions.php** on line line 100.
  - This function makes a connection to the SharpSpring API using credentials stored in the **congif.php** file.
  - The SS API returns an array of contact data for the account that has the SS ID / entity_id number.  

7. The data pulled from the SS API is returned to the script and Zapier takes it and creates a contact in Prophet using data pulled from the SS API.

8. After the contact is created Zapier makes three POST requests to these scripts:
- https://prophet-sharpspring.tizianiwhitmyre.com/update_prophet_guid_data_to_synced_sharpspring_contact.php

  This script syncs SS to Prophet data.

- https://prophet-sharpspring.tizianiwhitmyre.com/send_email_to_support.php

  This sends an email to Tiz Inc support.

- https://prophet-sharpspring.tizianiwhitmyre.com/clear_create_queue.php

  This clears the row in the create_queue mysql table.

#### New SharpSpring Lead to Prophet V2

This appears to be the same Zap as **New Sharpspring Lead to Prophet**. I'm not sure why it's there or why it's turned on.

#### Prophet New Contacts to SharpSpring

This Zap creates a new SharpSpring lead when a new contact is added in Prophet.  The Zap automates most of this and fires whenever a new Prophet contact is created. The only custom script this runs is
```
https://prophet-sharpspring.tizianiwhitmyre.com/update_custom_fields_and_company_sharpspring.php
```
This function uses the Prophet API to get account data from a Prophet account using the Prophet ID.  If then checks for a SharpSpring account and either updates the custom fields that relate to Prophet and / or company data.  

#### Function files

There are three files that contain all of the functions used in Ken's codebase.

##### prophet_functions.php

All of the functions relating to the Prophet API.

```
get_token_prophet()
```

Gets an authentication token from the Prophet API for use in other functions.

```
create_guid_prophet()
```

creates a guid field in prophet

```
get_custom_fields_prophet()
```

retrieves custom fields from the Prophet API.  Requires the token generated in get_prophet_token()

```
get_company_prophet()
```

gets company data from Prophet

```
get_contact_prophet()
```

uses the prophet ID to return a an array of contact fields from prophet API

```
get_updated_list_prophet()
```

retrieves a list of all of the prophet contacts that have been updated in the last 30 mins

```
save_to_prophet_updated_queue()
```

connects to the database on Media Temple and saves the ID of prophet accounts that have been updated that need to sync to SharpSpring.  

```
save_one_contact_to_prophet_updated_queue()
```

same as above but only handles one prophet account

```
get_prophet_queue_item()
```

retrieves an account number from the MT database table 'update_queue' if the entity origin == prophet

```
remove_prophet_queue_item()
```

Removes a row from the table 'update_queue' if the entity_origin == prophet


```
build_prophet_contact_array()
```

creates an array of all of the custom fields in a prophet account. I assume this array is passed to other functions for later use.

```
update_contact_prophet()
```

passes an array of SharpSpring data and uses the Prophet contact array created above to check and then update data in prophet.

---

##### sharpspring_functions.php

Functions related to the SharpSpring API


```
get_sharpspring_contact_by_email()
```

Retreives a sharpspring account from the API using the email address associated with the account. The email address is passed to the function as an argument.

```
get_sharpspring_queue_item()
```

retreives an item from the database table 'update_queue' if the entity_origin == sharpspring.  

```
get_sharpspring_contact_by_ssid()
```

Gets SS data from the API using the SS ID number...

```
get_sharpspring_updated_contact_list()
```

Connects to the SS API and pulls a list of any contacts updated in the last 30 mins

```
save_to_sharpspring_updated_queue()
```

Saves an SS ID number to the database table 'update_queue' from the array generated in get_sharpspring_updated_contact_list().

```
save_to_sharpspring_create_queue()
```

Adds an SS account number to the db table 'create_queue'.  

```
remove_sharpspring_queue_item()
```

removes an items from the db table 'update_queue'.

```
remove_sharpspring_create_queue_item()
```

removes an item from the db table 'create_queue'

```
update_sharpspring_record()
```

updates a sharpspring record with data from Prophet

```
create_sharpspring_record()
```

creates an SS lead from data pulled from Prophet

##### agnostic_functions.php

This file has just one function, diff_contacts, which shows the differences between the SS account and the Prophet account. I never actually tested this so I don't know if it works or not.
