-- Creation de la database
CREATE DATABASE photosphere;
USE photosphere;

-- Création des différentes entitées de la database
CREATE TABLE `user` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    role ENUM('basicUser','proUser','admin', 'moderator') DEFAULT 'basicUser',
    level VARCHAR(50) DEFAULT NULL,
    upload_count INT DEFAULT 0,
    sub_start DATETIME DEFAULT NULL,
    sub_end DATETIME DEFAULT NULL,
    profile_img VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME DEFAULT NULL
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

-- Insertion des données des différentes entités

INSERT INTO `user` (username, email, password, bio, role, level, upload_count, sub_start, sub_end, profile_img, created_at, last_login) 
VALUES 
(
    'alice_basic', 'alice@photosphere.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'Photographe amateur passionnée de paysages et couchers de soleil', 'basicUser', NULL, 2, NULL, NULL, NULL, '2024-03-15 10:30:00','2025-01-04 14:20:00'
),
(
    'emma_basic', 'emma@photosphere.com', '$2y$10$HZJ8fGhKlMnOpQrStUvWxO.yY8zCdEfGhIjKlMnOpQrStUvWxYzA', 'Amoureuse de la nature et des animaux', 
    'basicUser', NULL, 8, NULL, NULL, NULL, '2024-07-10 11:20:00','2024-12-28 09:15:00'
),
(
    'bob_pro', 'bob@photosphere.com', '$2y$10$3i1ELkVrBD3BLVKlqaB0zuKVr9lznwLkL.YjLi2YwQOc6HxB7V5Su', 'Photographe professionnel spécialisé en portrait et studio', 
    'proUser',  NULL, 15, '2024-06-01 09:00:00', '2025-06-01 09:00:00', NULL, '2023-05-20 14:15:00','2025-01-05 08:30:00'
),
(
    'david_pro', 'david@photosphere.com', '$2y$10$AbCdEfGhIjKlMnOpQrStUvWxYzAbCdEfGhIjKlMnOpQrStUvWxYz', 
    'Street photographer | Paris & Tokyo', 'proUser', NULL, 23, '2023-11-01 12:00:00', '2025-11-01 12:00:00', NULL, '2023-04-12 10:20:00','2025-01-05 07:00:00'
),

(
    'moderator_jr', 'mod.junior@photosphere.com', '$2y$10$JrModHashExample1234567890123456789012345678901234', 'Modérateur junior - Gestion des contenus signalés', 
    'moderator', 'junior', 0, NULL, NULL, NULL, '2024-08-01 09:00:00','2025-01-04 18:30:00'
),
(
    'moderator_lead', 'mod.lead@photosphere.com', '$2y$10$LeadModHashExample123456789012345678901234567890123', 'Lead modérateur - Responsable de l''équipe de modération', 
    'moderator', 'lead', 0, NULL, NULL, NULL, '2023-06-10 08:30:00','2025-01-05 10:15:00'
),
(
    'admin_super', 'admin@photosphere.com', '$2y$10$N0RWMKo5HmnKwJRr5vLvmO5K8nJVwVmZ3kH6vhJYLKqDJHvJ3Oqzu', 'Super-administrateur de la plateforme Photosphere', 
    'admin', 'Super Admin', 25, NULL, NULL, NULL, '2023-01-01 08:00:00','2025-01-05 07:30:00'
);


INSERT INTO Photo (title, description, img_link, img_size, dimensions, state, view_count, published_at, created_at, updated_at, user_id) VALUES
('Coucher de soleil sur la Méditerranée', 'Magnifique coucher de soleil capturé sur la côte d''Azur', 'uploads/2024/sunset_mediterranean_001.jpg', 2458624, '1920x1080', 'published', 1567, '2024-11-18 18:45:00', '2024-11-18 17:30:00', '2024-11-18 18:45:00', 1),
('Les yeux bleus', 'Portrait en gros plan avec focus sur le regard', 'uploads/2024/blue_eyes_002.jpg', 3825152, '2400x1800', 'published', 2876, '2024-11-22 15:20:00', '2024-11-22 14:00:00', '2024-11-22 15:20:00', 3),
('Architecture moderne', 'Immeuble futuriste à La Défense, Paris', 'uploads/2024/modern_architecture_003.jpg', 4194304, '3000x2000', 'published', 1234, '2024-11-28 11:30:00', '2024-11-28 10:15:00', '2024-11-28 11:30:00', 2),
('Papillon Monarque', 'Macro d''un papillon monarque sur une fleur', 'uploads/2024/monarch_butterfly_004.jpg', 3145728, '2560x1920', 'published', 2103, '2024-12-02 09:15:00', '2024-12-01 16:40:00', '2024-12-02 09:15:00', 4),
('Lever de soleil sur les Alpes', 'Vue panoramique depuis le Mont Blanc au lever du jour', 'uploads/2024/alps_sunrise_005.jpg', 5767168, '4096x2304', 'published', 4532, '2024-12-08 07:30:00', '2024-12-07 19:20:00', '2024-12-08 07:30:00', 1),
('Street art Belleville', 'Fresque colorée dans le 20ème arrondissement', 'uploads/2024/street_art_006.jpg', 2883584, '2048x1536', 'published', 1098, '2024-12-12 16:45:00', '2024-12-12 15:20:00', '2024-12-12 16:45:00', 3),
('Mon chat Garfield', 'Mon adorable chat roux qui fait sa toilette', 'uploads/2024/orange_cat_007.jpg', 1835008, '1600x1200', 'published', 3421, '2024-12-15 14:00:00', '2024-12-15 13:30:00', '2024-12-15 14:00:00', 4);



INSERT INTO comment (content, status, created_at, updated_at, user_id, photo_id) VALUES
('Superbes couleurs !', 'approved', '2024-11-18 19:30:00', '2024-11-18 19:30:00', 3, 1),
('Le regard est captivant', 'approved', '2024-11-22 16:00:00', '2024-11-22 16:00:00', 1, 2),
('Très belle architecture', 'approved', '2024-11-28 12:00:00', '2024-11-28 12:00:00', 2, 3),
('Quelle netteté ! Quel objectif ?', 'approved', '2024-12-02 10:30:00', '2024-12-02 10:30:00', 1, 4),
('Vue à couper le souffle ', 'approved', '2024-12-08 08:15:00', '2024-12-08 08:15:00', 3, 5),
('Trop mignon !', 'approved', '2024-12-15 15:00:00', '2024-12-15 15:00:00', 4, 6),
('Le N&B sublime ce portrait', 'approved', '2024-12-18 12:00:00', '2024-12-18 12:00:00', 2, 7),
('Ambiance mystique', 'approved', '2024-12-22 09:00:00', '2024-12-22 09:00:00', 3, 2);


INSERT INTO `like` (created_at, user_id, photo_id) VALUES
('2024-11-18 19:00:00', 2, 1),
('2024-11-18 20:15:00', 3, 1),
('2024-11-19 09:30:00', 4, 1),
('2024-11-22 15:45:00', 1, 2),
('2024-11-22 16:30:00', 4, 2),
('2024-12-12 18:30:00', 3, 6),
('2024-12-15 14:30:00', 1, 7),
('2024-12-15 16:45:00', 3, 7);


INSERT INTO tag (name) VALUES
('paysage'),
('nature'),
('urbain'),
('montagne'),
('mer'),
('animaux'),
('forêt');

INSERT INTO photo_tag (tag_id, photo_id) VALUES
(1, 1), (2, 1),
(4, 2),
(3, 3), (6, 3),
(5, 4), (2, 4),
(1, 5), (2, 5), (7, 5),
(3, 6);


INSERT INTO album (name, public, cover, published_at, updated_at, user_id) VALUES
('Mes Plus Beaux Paysages', TRUE, 'uploads/2024/sunset_mediterranean_001.jpg', '2024-11-20 10:00:00', '2024-12-22 09:30:00', 3),
('Monde Animalier', TRUE, 'uploads/2025/red_fox_012.jpg', '2024-12-16 12:00:00', '2025-01-03 11:30:00', 2),
('Collection Privée', FALSE, 'uploads/2024/foggy_forest_009.jpg', NULL, '2024-12-23 10:00:00', 1);

INSERT INTO photo_album (photo_id, album_id) VALUES
(1, 1),
(5, 1),
(3, 1),
(2, 2),
(6, 2),
(4, 2),
(7, 3);