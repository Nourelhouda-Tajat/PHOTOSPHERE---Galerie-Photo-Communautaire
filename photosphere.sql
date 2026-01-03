CREATE DATABASE photosphere;
USE photosphere;

CREATE TABLE `user` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    role ENUM('basicUser','proUser','admin', 'moderator') DEFAULT 'basicUser',
    level VARCHAR(50),
    upload_count INT DEFAULT 0,
    sub_start DATETIME,
    sub_end DATETIME,
    profile_img VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
);

CREATE TABLE Photo (
    id_img INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200),
    img_link VARCHAR(255) NOT NULL,
    img_size INT,
    dimensions VARCHAR(50),
    state VARCHAR(50) DEFAULT 'draft',
    view_count INT DEFAULT 0,
    published_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE comment (
    id_comment INT AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(50) NOT NULL,
    status VARCHAR(25) DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    photo_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (photo_id) REFERENCES Photo(id_img)
);

CREATE TABLE `like` (
    id_like INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    photo_id INT NOT NULL,
    UNIQUE KEY unique_like (user_id, photo_id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (photo_id) REFERENCES Photo(id_img)
);

CREATE TABLE tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE photo_tag (
    tag_id INT NOT NULL,
    photo_id INT NOT NULL,
    PRIMARY KEY (tag_id, photo_id),
    FOREIGN KEY (tag_id) REFERENCES tag(id),
    FOREIGN KEY (photo_id) REFERENCES Photo(id_img)
);

CREATE TABLE album (
    id_album INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    public BOOLEAN DEFAULT FALSE,
    cover VARCHAR(255),
    published_at DATETIME,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE photo_album (
    photo_id INT NOT NULL,
    album_id INT NOT NULL,
    PRIMARY KEY (photo_id, album_id),
    FOREIGN KEY (photo_id) REFERENCES Photo(id_img),
    FOREIGN KEY (album_id) REFERENCES album(id_album) 
);

