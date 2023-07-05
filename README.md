## Project overview

Twitty is a twitter-clone application and this is the back-end side of the project, this project offers a seamless social media experience like you used to in twitter app.

# Hi, I'm Anas! 👋

I'm a full stack web developer passionate about
creating interactive applications and experiences on
the web.

## Getting Started

To get started with twitty, follow these steps:

#### create a link storage file in the public folder

double check that a file named storage was created in the public folder

```
php artisan storage:link
```

#### Create a database: Create a database for your project and make a note of the database name.

Configure the environment: Duplicate the .env.example file and rename it to .env. Open the .env file and update the following line with your database information:

```bash
  DB_DATABASE=your_database_name

```

Add two enviroments variable

```
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
```

Modify the BROADCAST_DRIVER env variable to pusher

```
BROADCAST_DRIVER=pusher
```

Create your own Pusher account and create a new Channel and Add the credentials to your .env file

```
PUSHER_APP_ID=your_pusher_credentials
PUSHER_APP_KEY=your_pusher_credentials
PUSHER_APP_SECRET=your_pusher_credentials
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=your_pusher_credentials
```

Install Laravel dependencies: Open a terminal in the project directory and run the following command to install the Laravel dependencies:

```bash
  composer install
```

create the APP KEY

```bash
 php artisan key:generate
```

Migrate the database: Run the database migrations to create the necessary tables in your database.

```bash
 php artisan migrate
```

Start the development server: Run the following command to start the development server:

```bash
 php artisan serve
```

**_ YOU ARE READY TO GO _**

## Key Features

-   Full authentication and authorization layer
-   Restrict the data receieved by clients by laverages the API resources in Laravel
-   Add tweets with optional photo(up to 4 photos) or on vedio
-   Like, retweet, retweet with quote and commonet on your own tweets or others' tweets
-   Every user have it's own profile where his own tweets and interactions place in
-   Users also have the ability to add or delete their own profile picture, their cover, name, bio and location
-   Users can follow each others
-   Search for users with their name or username
-   Users can communicate through chatting feature
-   Users will notify when others like, retweet, follow, or comment on their tweets or if somebody send a message to him
-   Real-time updates for notifications and new messages inbox through a webSocket connection using Pusher service
-   Also users can bookmark their favourite tweets and they have access to it anytime
-   And most importantly the app have a dark and light mode as I know the feelings of having only the light mode
-   AND MORE

## 🛠 Skills

-   HTML, CSS(tailwindcss), vue.js, nuxt.js 3, Laravel, MYSQL

## 🔗 Links

**_my linkedin profile_**
[linkedin](https://www.linkedin.com/in/anas-elnahef-10074021b/)

## Screenshots

-   #### To view all individual screenshot open the 'screenshots' folder in the root folder

#### Home page Dark and Light mode

[🖼️ home.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/home.png) [🖼️ homeDark.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/homeDark.png)

#### Profile Preview

[🖼️Home.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/profile.png)

#### Single Tweet Preview

[🖼️ tweet.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/tweet_preview.png) [🖼️ single_tweet.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/single_tweet.png)

#### Notifications

[🖼️ notifications.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/notifications.png)

#### Chat inbox

[🖼️ chat.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/chat.png)

#### Chat inbox

[🖼️ bookmarks.png](https://github.com/anas322/Streetwear-E-Commerce-Laravel/blob/main/screenshots/bookmarks.png)

##### Note: This is just a bunch of screenshot of the applications to highlight the top level overview of the applications and the applications itself has alot of other features

## License

[MIT](https://choosealicense.com/licenses/mit/)
