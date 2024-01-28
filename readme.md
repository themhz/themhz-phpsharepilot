SharePilot
---
**It's a Free and Open Source PHP script that you can use to automate your posts in social media. 
You can install it on your server, schedule and automate your posting on social media like
Facebook, Twitter, Linkedin, Reddit or even add more social media channels.**


Table of Contents
---

1. Introduction
2. Installation
3. Usage
4. Contributing
5. Code of Conduct
6. License
7. Changelog

Introduction
---
*Share Pilot is a Social Media Post Management System (SMPMS). It enables you to share your posts across various channels on social media. With Share Pilot, you can schedule your posts for consistent and timely distribution across your social media platforms. Additionally, it allows you to maintain a history of your posts and manage their lifecycle effectively.*

*With Share Pilot, you can create multiple channels across your social media platforms. For instance, if you have several Facebook pages, Share Pilot allows you to set up separate schedules for posts on each channel. The system supports collaboration, enabling you to add multiple users to work on campaigns together.
You have the flexibility to add more social media channels or customize the script to meet your specific needs. Share Pilot is built on a simple PHP framework and utilizes an easy-to-understand ORM. It's compatible with PHP 8+ and MySQL, ensuring a broad range of usability. The installation process is straightforward, allowing you to integrate it with your server, whether you're using WordPress, Joomla, or any other framework.
To get started, all you need to do is provide the links that you wish to share and post on social media. Share Pilot takes care of the rest, streamlining your social media management.* 

Features
--
1. Manage your posts (create delete and update) your posts
2. Post them in multiple channels
3. Schedule the posts in any number or any date
4. Filter your schedule to post specific hours in the day
5. Manage more the one channel on each social media site. For example you can use multiple pages and post different content in different pages
6. Secure system to manage users entering the system.

Installation
---

*Step-by-step installation instructions, starting from the very basics. Assume the user knows nothing about your software.*

1. Download the script and unzip it to your server.
2. Type the domain that the script is hosted in your browser, like https://{my_domain_name}/sharepilot/install where {my_domain_name} is the domain name of your site and sharepilot is the name of the script. 
3. Enter the database credentials like where the db is hosted, the username and password and the name of the database and enter the username and password for the admin.
4. Click on the install button and if everything went ok, you will be redirected to the webpage of the successfull instalation. If not then see if there is a problem with the connection of the database. Feel free to contact me if you find any problem you cant solve alown.
5. If everything when well then delete the install folder.
6. Login to the script https://{my_domain_name}/sharepilot by providing the username and password you created.
7. Log in to you Cpanel or your provider and create a cron job. You need to give the cron job the path of the script to the worker.php with in the cron folder.
8. Make sure to set the cron job to run every minute. 
9. Then create your socials in the social tab...
## Usage
---

*Detailed instructions about how to use your software, possibly with examples. This section can be quite expansive and detailed for complex software.*

```
Example of how to use your software (if applicable)
```

## Contributing
---

*Explain how others can contribute to your project. Detail the process for submitting pull requests, reporting bugs, and requesting features.*

## Code of Conduct
---

*Outline the standards for respect and behavior in your project community.*

## License
---

*Include information about your license. If it's a standard license, you can simply say "This project is licensed under the [License-Name] - see the [LICENSE.md](LINK) file for details"*

## Changelog
---

*List of changes made in each version of your software*

---
Remember, it's crucial that your documentation is clear, organized, and easy to understand. It's one of the first resources users will turn to when they have questions or problems.

