CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nonce VARCHAR(255),
    nonce_expiry DATETIME,
    created DATETIME,
    modified DATETIME
);

INSERT INTO users (email, password, created, modified) VALUES ('test@example.com', 'secret-password', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);