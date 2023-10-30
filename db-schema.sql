CREATE TABLE twitter_posts (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `url` TEXT NOT NULL UNIQUE,
    `username` VARCHAR NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `date` DATE NOT NULL,
    `media_type `VARCHAR DEFAULT NULL,
    `media_file` TEXT DEFAULT 'NO_MEDIA',
    `extracted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);