const mysql = require('mysql2');

// Create a connection to the RDS database
const connection = mysql.createConnection({
  host: 'roomify-user-db.cby488i6k81t.us-east-1.rds.amazonaws.com', // RDS endpoint
  user: 'samiller1109', // Master username
  password: 'fKxrA-2huA-9tpdq', // Master password
  database: 'roomify-user-db', // Database name
  port: 3306  // default MySQL port
});

// Connect to the database
connection.connect(error => {
  if (error) {
    console.error('Error connecting to the database:', error);
    return;
  }
  console.log('Connected to the MySQL database.');
});
