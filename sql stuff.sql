
sql stuff

login:
            mysql --host 127.0.0.1 -u root
            select db:
            use workout_site

set up db

create table users (
    ID INT NOT NULL UNIQUE KEY AUTO_INCREMENT,
    Username VARCHAR(255),
    Email VARCHAR(255),
    delete_flag BOOL
);

insert into
    users (Username, email, delete_flag) ->
Values (
        'manu',
        'lindner-manuel@protonmail.com',
        FALSE
    );

create table Workouts (
    ID INT NOT NULL UNIQUE KEY AUTO_INCREMENT,
    Date DATE NOT NULL,
    User_ID int,
    PRIMARY KEY (ID),
    FOREIGN KEY (User_ID) REFERENCES users (ID)
);

create table Exercises (
    ID INT NOT NULL UNIQUE KEY AUTO_INCREMENT,
    Name varchar(255),
    Primary_Muscle varchar(255),
    Secondary_Muscle varchar(255),
    Tertiary_Muscle varchar(255),
    Compound BOOL
);

LOAD DATA LOCAL INFILE '/home/websiteadmin/Downloads/ex.csv' INTO
TABLE Exercises FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (Name, Primary_Muscle)
SET
    ID = NULL;

create table Sets (
    ID INT NOT NULL UNIQUE KEY AUTO_INCREMENT,
    PRIMARY KEY (ID),
    User_ID int,
    FOREIGN KEY (User_ID) REFERENCES users (ID),
    Exercise_ID int,
    FOREIGN KEY (Exercise_ID) REFERENCES Exercises(ID),
    Workout_ID int,
    FOREIGN KEY (Workout_ID) REFERENCES Workouts(ID),
    Reps int,
    Weight int,
    Volume int
);


queries show tables;

describe users;

select *
From Sets
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID

Select Workouts.Date, Workouts.ID, Sets.Reps, Sets.Weight, Sets.Volume, Exercises.Name
From
    Workouts
    INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
WHERE
    Workouts.User_ID = '1'
ORDER BY Workouts.ID DESC;

Select DISTINCT
    Exercise_ID.ID,
    Workouts.Date,
    Workouts.ID,
    Sets.Reps,
    Sets.Weight,
    Sets.Volume,
    Exercises.Name,
    Exercises.Primary_Muscle
From
    Workouts
    INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
WHERE
    Workouts.User_ID = '1'
    AND Exercises.Primary_Muscle = 'Chest'
ORDER BY Workouts.ID DESC;

Select Exercises.Name, Workouts.Date, Workouts.ID, Sets.Reps, Sets.Weight, Sets.Volume, Exercises.ID
From
    Workouts
    INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
WHERE
    Workouts.User_ID = '1'
    AND Exercises.ID = '1'
Order BY Volume DESC
LIMIT 1;

Select Exercises.Name, Workouts.Date, Workouts.ID, Sets.Reps, Sets.Weight, Sets.Volume, Exercises.ID
From
    Workouts
    INNER JOIN Sets ON Workouts.ID = Sets.Workout_ID
    INNER JOIN Exercises ON Sets.Exercise_ID = Exercises.ID
WHERE
    Workouts.User_ID = '1'
    AND Exercises.ID = '87'
Order BY Volume DESC
LIMIT 1;
