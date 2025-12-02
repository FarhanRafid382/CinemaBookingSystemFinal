-- init.sql
CREATE DATABASE IF NOT EXISTS cinemalast;
USE cinemalast;

-- 1) users
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(30),
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_approved TINYINT(1) NOT NULL DEFAULT 0
);

-- 2) manager (single account)
CREATE TABLE manager (
  manager_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- 3) theaters
CREATE TABLE theaters (
  theater_id INT AUTO_INCREMENT PRIMARY KEY,
  theater_number VARCHAR(50) NOT NULL,
  location VARCHAR(255) NOT NULL
);

-- 4) movies
CREATE TABLE movies (
  movie_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  showtime DATETIME NOT NULL,
  release_date DATE,
  price_per_seat DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  total_seats INT NOT NULL DEFAULT 0,
  theater_id INT NOT NULL,
  genre VARCHAR(100),
  duration VARCHAR(50),
  FOREIGN KEY (theater_id) REFERENCES theaters(theater_id) ON DELETE CASCADE
);

-- 5) bookings
CREATE TABLE bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  movie_id INT NOT NULL,
  seats_booked INT NOT NULL,
  booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE
);

-- 6) pending_registrations
-- created to satisfy the 7-table requirement. Not used by default implementation.
CREATE TABLE pending_registrations (
  pending_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(30),
  email VARCHAR(150) NOT NULL,
  password VARCHAR(255) NOT NULL,
  reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 7) scores
CREATE TABLE scores (
  score_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  movie_id INT NOT NULL,
  score INT NOT NULL,
  score_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE CASCADE,
  UNIQUE KEY uq_user_movie (user_id, movie_id)
);

-- sample manager account (password stored in plain text as requested)
INSERT INTO manager (name, email, password) VALUES ('Cinema Manager','manager@cinema.com','managerpass');

-- sample theater and movie
INSERT INTO theaters (theater_number, location) VALUES ('Theater-1','Downtown');
INSERT INTO movies (title, showtime, release_date, price_per_seat, total_seats, theater_id, genre, duration)
VALUES ('Example Movie','2025-12-10 19:00:00','2025-12-01',150.00,100,1,'Action','2h');
