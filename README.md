# Yii2 Comments System with Nested Comments and Captcha

This is a simple comment system built with the Yii2 framework. It allows users to:
- Add articles with comments.
- Add nested (reply) comments to existing comments.
- All comments are displayed under the corresponding article.
- The system uses **AJAX** to submit comments without reloading the page.
- **Captcha** is used to protect the comment form from spam.

## Features

- **Articles**: You can add new articles.
- **Comments**: Users can add comments to articles.
- **Nested Comments**: Users can reply to existing comments, creating a nested comment structure.
- **Captcha**: The system includes captcha validation for each comment form to prevent spam.
- **AJAX Submission**: Comments are submitted via AJAX, allowing dynamic updates without page reload.

## Requirements

- PHP >= 7.4
- Yii2 framework
- MySQL (or another supported database)
- Composer

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/yii2-comments-system.git
    cd yii2-comments-system
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Configure the database:

    Open the file `config/db.php` and set the `dsn`, `username`, and `password` for your database connection.

    ```php
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yourdbname',
        'username' => 'yourusername',
        'password' => 'yourpassword',
        'charset' => 'utf8',
    ];
    ```

4. Apply database migrations:

    Run the following command to apply the migrations and create the necessary tables for articles and comments:

    ```bash
    php yii migrate
    ```

5. Run the application:

    You can run the application using the built-in PHP server:

    ```bash
    php yii serve
    ```

    The application will be available at `http://localhost:8080`.

## Database Schema

The system uses two tables: `article` and `comment`.

- `article`: Stores article information (title, content, created_at).
- `comment`: Stores the comments with support for nested comments (via `parent_id`), as well as the article relation.

### Article Table Structure:

| Field      | Type         | Description            |
|------------|--------------|------------------------|
| id         | INT          | Primary key            |
| title      | VARCHAR(255) | Title of the article    |
| content    | TEXT         | Content of the article  |
| created_at | TIMESTAMP    | Creation time          |

### Comment Table Structure:

| Field      | Type         | Description                             |
|------------|--------------|-----------------------------------------|
| id         | INT          | Primary key                             |
| article_id | INT          | Foreign key linking to the `article`    |
| parent_id  | INT          | Reference to parent comment (NULL if it's a root comment) |
| name       | VARCHAR(255) | Name of the commenter                   |
| email      | VARCHAR(255) | Email of the commenter                  |
| text       | TEXT         | Content of the comment                  |
| created_at | TIMESTAMP    | Creation time                           |

## Usage

1. **Add Articles**: On the homepage, use the form to add new articles.
2. **Add Comments**: Under each article, you can add comments. If there are no comments yet, you will be able to add the first comment.
3. **Reply to Comments**: Each comment has a reply form, allowing users to add nested comments.
4. **AJAX Updates**: When you add a comment or reply, the system dynamically updates the page without reloading it.

## Customization

### Captcha Configuration

The system uses the default Yii2 captcha. If you need to customize or disable the captcha for development purposes, modify the action in the `SiteController`:

```php
public function actions()
{
    return [
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,  // Disable captcha in test environments
        ],
    ];
}
