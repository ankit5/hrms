INTRODUCTION
------------

Adds a field to User showing who created the user and creates permissions
around that field.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/user_created_by_field

 * To submit bug reports and feature suggestions, or track changes:
   https://www.drupal.org/project/issues/user_created_by_field


REQUIREMENTS
------------

This module requires no modules outside of Drupal core.


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.


CONFIGURATION
-------------
 
 * Configure the user permissions in Administration » People » Permissions:

   - Edit User Created By field

     Roles assigned this permission can edit the field after it's populated at
     a user's creation. Reminder that Administrators can always edit this field.

   - View User Created By field

     Roles assigned this permission can see the field. Useful to share the
     information without allowing users to edit the field.

 * After install, you can output the field where desired. Recommended to show
   it at least on the People page found on Administration » People. To accomplish
   this, please edit the view found in Administration » Structure » Views and add
   the field to the People view.


FAQ
---

Q: Why don't I see the new field?

A: The field is part of the User entity. You will need to add it to a view/display.

Q: What happens when I unisntall the module?

A: The installed field and content of that field will be removed from your database.
   You can stop this by uncommenting out the code in user_created_by_field.install
   file before uninstalling.