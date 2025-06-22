## tabulation-ws
Tabulation WebSocket Server

### Requirements
- **PHP 8.0** or newer (make sure **php.exe** is in your [system PATH](https://stackoverflow.com/questions/31291317/php-is-not-recognized-as-an-internal-or-external-command-in-command-prompt))
- [**Composer**](https://getcomposer.org/)
  - _The following PHP extensions must be enabled in your **php.ini**:_
    - `zip`
  - _**How to enable PHP extensions?**_
    1. Open your **php.ini** file in a text editor.
       (Example location for XAMPP: `C:\xampp\php\php.ini`)

    2. Search for the following lines and remove the semicolon (**;**) at the beginning of each to enable them:
       ```angular2html
       extension=zip
       ```
    3. Save the **php.ini** file after making the changes.
---

### Development Setup (Port: 8079)
1. Clone the repository.
2. Open the project in a terminal and install the dependencies using Composer:
   ```
   composer install
   ```
3. Start the server in `--dev` mode:
   ```
   php index.php --dev
   ```
   This runs the WebSocket server on **port 8079**.

   _**Note:** Update your WebSocket URL in the `app/config/websocket.php` of the tabulation software to use port **8079** while in development mode._

---

### Production Setup (Port: 8080)
#### [TabulationWS Windows Service](service/windows)
- To run the WebSocket server as a Windows service,
follow the installation guide for:
[**service/windows**](service/windows)

---

### Integration with Tabulation Software
Any Tabulation Software fork that includes a pre-committed `app/config/websocket.example.php` file is supported.
If the file is present in the repository, it means the software is ready to integrate with this WebSocket server.

To connect the Tabulation Software with the WebSocket server:
1. Copy the example config file:
   
   `[tabulation-software]/app/config/websocket.example.php` to
  
   `[tabulation-software]/app/config/websocket.php`

2. Open the newly created **websocket.php** and update the WebSocket URL to match the local IP address of the machine where the WebSocket server is running. 
   - _use port **8079** when running in development mode (**--dev**)_
   - _otherwise, use port **8080**_

3. Open your browser and go to the dashboard using the IP address of the machine where the Tabulation Software is running:

   `http://<local-ip>/[tabulation-software]/app/dashboard`

   Example:

   `http://192.168.0.2/missiriga/app/dashboard`

