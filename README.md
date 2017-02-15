Create an upload directory and place the files above the webroot. 

Create a template for the plugin tag, something like downloads/index.html. 

In the File Upload Preferences, change the URL of Upload Directory to this url. in this case the url would be http://domain.com/downloads/.

    {exp:fm_protected_files:protect  
        allowed_member_groups="1|2"  
        directory_id="9"  
        filename="{segment_2}"
    }
    
If the allowed_member_groups param is not used, all member groups will be allowed access, with the exception of guests.