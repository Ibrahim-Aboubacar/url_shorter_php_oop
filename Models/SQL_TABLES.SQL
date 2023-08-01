CREATE TABLE
    users (
        id INT NOT NULL AUTO_INCREMENT,
        email VARCHAR(250),
        username VARCHAR(250),
        password VARCHAR(250),
        PRIMARY KEY(id)
    );

CREATE TABLE
    links (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(250),
        original_link TEXT,
        state BOOLEAN DEFAULT TRUE,
        visite int DEFAULT 0,
        user INT NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY (user) REFERENCES users(id)
    );