This file is where php files need to be held.

Index.php is the entrypoint for the php runtime

The following is a explanation for anyone confused

---
Here's how it generally works:

    Any PHP file you place in the api folder (e.g., api/example.php) will be accessible via a URL like https://your-vercel-project.vercel.app/api/example.
    The api/index.php file will act as the main entry point for all requests if you want centralized routing.
    
    There will be routes within vercel.json that link to all the php files for ease of connectivity

    I believe these will have to be switched out between projects
    --- 
    Anna's Main - http://roomify-main.vercel.app/api/example
    Roomify Test - https://roomifytest.vercel.app/api/example.
### Configuration Explanation

The following configuration code is used to deploy a PHP project on Vercel:

```json
{
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.3"
    }
  },
  "routes": [
    { "src": "/(.*)", "dest": "/api/index.php" }
  ]
}
```

#### Explanation:

1. **`functions` Key**:
   - Defines how serverless functions are configured for Vercel.
   - **`api/*.php`**: Specifies that all PHP files located in the `api` directory are considered serverless functions.
   - **`"runtime": "vercel-php@0.7.3"`**: Indicates the runtime environment used to execute PHP code. The `vercel-php` runtime (version `0.7.3`) is responsible for running the PHP functions.

2. **`routes` Key**:
   - Sets up routing rules for incoming web requests.
   - **`"src": "/(.*)"`**: A regular expression that matches all URL paths, meaning any URL request will be handled.
   - **`"dest": "/api/index.php"`**: Redirects all incoming requests to `api/index.php`. This allows `index.php` to handle every request, which is useful for centralized routing or API processing.

---

### Understanding Serverless Functions on Vercel

Serverless functions on Vercel are a way to run backend code without needing to manage or configure any servers. When a function is deployed:

- **Automatic Scaling**: Vercel automatically scales the function based on the number of incoming requests. More requests mean more instances are created to handle the load.
- **On-Demand Execution**: The function code only runs when triggered by an HTTP request. Once the request is processed, the function is terminated, saving resources.
- **Ease of Use**: You can write server-side code using popular programming languages (like PHP, Node.js, or Python) and deploy it easily without worrying about infrastructure.


