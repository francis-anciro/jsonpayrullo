# Requirements

- **Database:** <a href="#">Download Database here</a>
- **React Vite Frontend:** <a href="#">Download front end here</a>

---

# Setup

1. Download the ZIP file.
2. Extract the files.
3. Move the folder into `xampp/htdocs/`.
4. Start **Apache** and **MySQL** in XAMPP.

---

# Note

- Check the port number shown in your Vite URL.  
  Example:
  
- If the port is not `5173`, update this line in app/public/index.php to match port number

Change:
```php
header("Access-Control-Allow-Origin: http://localhost:<--YOUR PORT NUMBER HERE-->");
