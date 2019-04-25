

FileUploader - BlueMega

Its a picture manager tool able to generate proportional half picture, stocked in job with notification when generated
- weight of picture is stocked in Kb in DB
- jobs are queued in db : use > php artisan queue:listen


Requirement

    install seeds and migration to use test account : 'email' => 'test@bluemega.com', 'password' => 'test',
    need php exif module
    change user mail in file '.env' > mail configuration : mailtrap.io : MAIL_USERNAME=20a64c814884b4 MAIL_PASSWORD=2972337ce51d0b

