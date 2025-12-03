

-- 1. List all movies
SELECT * FROM Movie;

-- 2. List all halls
SELECT * FROM Hall;

-- 3. List all shows with movie and hall info
SELECT s.show_id, m.title, h.hall_name, s.show_date, s.show_time
FROM Show s
JOIN Movie m ON s.movie_id = m.movie_id
JOIN Hall h ON s.hall_id = h.hall_id;

-- 4. Movies running today
SELECT m.title, s.show_date, s.show_time
FROM Show s
JOIN Movie m ON s.movie_id = m.movie_id
WHERE s.show_date = CURDATE();

-- 5. Shows of a specific movie
SELECT s.show_id, s.show_date, s.show_time, h.hall_name
FROM Show s
JOIN Hall h ON s.hall_id = h.hall_id
WHERE s.movie_id =  :movie_id;

-- 6. All seats for a hall
SELECT seat_no
FROM Seat
WHERE hall_id = :hall_id;

-- 7. Available seats for a given show
SELECT seat_no
FROM Seat
WHERE hall_id = (SELECT hall_id FROM Show WHERE show_id = :show_id)
AND seat_no NOT IN (
    SELECT seat_no FROM Booking WHERE show_id = :show_id
);

-- 8. Booked seats for a show
SELECT seat_no
FROM Booking
WHERE show_id = :show_id;

-- 9. User booking history
SELECT b.booking_id, m.title, s.show_date, s.show_time, b.seat_no
FROM Booking b
JOIN Show s ON b.show_id = s.show_id
JOIN Movie m ON s.movie_id = m.movie_id
WHERE b.user_id = :user_id
ORDER BY s.show_date DESC;

-- 10. Total bookings per movie
SELECT m.title, COUNT(b.booking_id) AS total_bookings
FROM Booking b
JOIN Show s ON b.show_id = s.show_id
JOIN Movie m ON s.movie_id = m.movie_id
GROUP BY m.movie_id, m.title;

-- 11. Total bookings per show
SELECT s.show_id, m.title, COUNT(b.booking_id) AS seats_filled
FROM Booking b
JOIN Show s ON b.show_id = s.show_id
JOIN Movie m ON s.movie_id = m.movie_id
GROUP BY s.show_id;

-- 12. Movies with no bookings yet
SELECT m.movie_id, m.title
FROM Movie m
WHERE m.movie_id NOT IN (
    SELECT DISTINCT movie_id FROM Show
    WHERE show_id IN (SELECT show_id FROM Booking)
);


-- Add movie
INSERT INTO Movie (title, genre, duration)
VALUES (:title, :genre, :duration);

-- Add hall
INSERT INTO Hall (hall_name, capacity)
VALUES (:hall_name, :capacity);

-- Add show
INSERT INTO Show (movie_id, hall_id, show_date, show_time)
VALUES (:movie_id, :hall_id, :show_date, :show_time);

-- Add seat (usually done during setup)
INSERT INTO Seat (hall_id, seat_no)
VALUES (:hall_id, :seat_no);

-- Add booking
INSERT INTO Booking (user_id, show_id, seat_no, booking_time)
VALUES (:user_id, :show_id, :seat_no, NOW());

-- Add user
INSERT INTO User (username, email, password_hash)
VALUES (:username, :email, :password_hash);


-- Update movie info
UPDATE Movie
SET title = :title, genre = :genre, duration = :duration
WHERE movie_id = :movie_id;

-- Update showtime
UPDATE Show
SET show_date = :show_date, show_time = :show_time
WHERE show_id = :show_id;

-- Update hall details
UPDATE Hall
SET hall_name = :hall_name, capacity = :capacity
WHERE hall_id = :hall_id;

-- Update user info
UPDATE User
SET username = :username, email = :email
WHERE user_id = :user_id;


-- Delete a booking
DELETE FROM Booking
WHERE booking_id = :booking_id;

-- Delete a movie
DELETE FROM Movie
WHERE movie_id = :movie_id;

-- Delete a show
DELETE FROM Show
WHERE show_id = :show_id;

-- Delete user
DELETE FROM User
WHERE user_id = :user_id;
