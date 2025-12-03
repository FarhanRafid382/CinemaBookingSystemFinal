-- sample manager account (password stored in plain text as requested)
INSERT INTO manager (name, email, password) VALUES
 ('Cinema Manager','manager@cinema.com','managerpass'),
('Manager Two','mgr2@cinema.com','pass2'),
('Manager Three','mgr3@cinema.com','pass3'),
('Manager Four','mgr4@cinema.com','pass4'),
('Manager Five','mgr5@cinema.com','pass5');


-- sample theater
INSERT INTO theaters (theater_number, location) VALUES 
('Theater-1','Downtown'),
('Theater-2','Uptown'),
('Theater-3','City Center'),
('Theater-4','North Square'),
('Theater-5','South Gate');

-- sample movies
INSERT INTO movies (title, showtime, release_date, price_per_seat, total_seats, theater_id, genre, duration) VALUES
('Dark Echo','2025-12-12 18:00:00','2025-12-05',180.00,120,2,'Thriller','1h45m'),
('Sky Frontier','2025-12-13 20:30:00','2025-11-29',200.00,150,3,'Sci-Fi','2h10m'),
('Laugh Out','2025-12-11 17:15:00','2025-11-20',130.00,90,4,'Comedy','1h40m'),
('Tidal Storm','2025-12-14 21:00:00','2025-12-02',220.00,160,5,'Action','2h05m'),
('Example Movie','2025-12-10 19:00:00','2025-12-01',150.00,100,1,'Action','2h');

-- sample users
INSERT INTO users (name, phone, email, password, is_approved) VALUES
('Alice Karim','01711111111','alice@mail.com','alice123',1),
('Bashir Khan','01822222222','bashir@mail.com','bashir123',1),
('Chloe Rahman','01933333333','chloe@mail.com','chloe123',0),
('David Islam','01644444444','david@mail.com','david123',1),
('Eva Sultana','01555555555','eva@mail.com','eva123',0);

-- sample bookings
INSERT INTO bookings (user_id, movie_id, seats_booked) VALUES
(1,1,2),
(2,2,3),
(3,3,1),
(4,4,4),
(5,5,2);

-- sample pending registrations
INSERT INTO pending_registrations (name, phone, email, password) VALUES
('Rafi Chowdhury','01766666666','rafi@mail.com','rafi123'),
('Sonia Talukdar','01977777777','sonia@mail.com','sonia123'),
('Tarek Azad','01888888888','tarek@mail.com','tarek123'),
('Mina Farzana','01599999999','mina@mail.com','mina123'),
('Hadi Alam','01610101010','hadi@mail.com','hadi123');

-- sample scores
INSERT INTO scores (user_id, movie_id, score) VALUES
(1,1,90),
(2,2,75),
(3,3,88),
(4,4,60),
(5,5,95);
